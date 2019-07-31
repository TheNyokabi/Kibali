(function(Eramba, $, undefined) {
	'use strict';

	/**
	 * Private properties
	 */
	//var name = 'test';

	/**
	 * Private method
	 */
	/*var getName = function() {
		return 'name: ' + name;
	};*/

	/**
	 * Public methods and properties
	 */
	/*Eramba.title = 'title';
	Eramba.sayHello = function() {
		return "Hello World!";
	};*/

	var Settings = {
		key: "value"
	};

	Eramba.debug = 0;

	Eramba.locale = {
		errorTitle: "Something went wrong",
		code: "Code",
		message: "Message",
		requestUrl: "Request URL",
		error403: "Your session probably expired and you have been logged out of the application.",
		errorHuman: "Error occured and the request failed.<br />Enable debug mode if you want more information.<br />If this problem persist, contact the support.",
		tryAgain: 'Please try again or <span>reload the page</span>.'
	};

	Eramba.init = function(options) {
		setupBasics();
		
		Eramba.Ajax.UI.setup();
		// Eramba.Modal.init();

		// return this;
	};

	function setupBasics() {
		bootbox.setDefaults({
			className: "scale"
		});
	}

	Eramba.Ajax = (function() {
		function __Ajax() {

			var _this = this;
			this.currentIndex = null;

			this.init = function() {
				$(document).ajaxError(errorHandler);
				loaderProgress();
				headerViewportHandler();

				return this;
			}

			this.blockEle = function(ele) {
				App.blockUI(ele);
			};

			this.unblockEle = function(ele) {
				App.unblockUI(ele);
			};

			var headerViewportHandler = function() {
				$(document).ready(function(){
					var headerHeight = $(".header").outerHeight()-5;
					// console.log(headerHeight);
					$(window).on("scroll", function(e) {
						if ($(this).scrollTop() > headerHeight && !$("body").hasClass("header-not-in-viewport")) {
							$("body").addClass("header-not-in-viewport");
						}
						else if ($(this).scrollTop() <= headerHeight && $("body").hasClass("header-not-in-viewport")) {
							$("body").removeClass("header-not-in-viewport");
						}
					});
				});
			};

			var errorHandler = function(e, xhr, ajaxSettings, thrownError) {
				var message;
				console.log(e);
				console.log(xhr);
				console.log(thrownError);

				var tryAgain = true;
				if (Eramba.debug == 1) {
					message = Eramba.locale.code + ": <strong>" + xhr.status + "</strong><br />";
					message += Eramba.locale.message + ": <strong>" + getErrorMessage(xhr) + "</strong><br />";
					message += Eramba.locale.requestUrl + ": <strong>" + ajaxSettings.url + "</strong><br /><br />";
				}
				else {
					if (xhr.status == "403") {
						message = '<span style="font-weight:600">' + xhr.responseText + '</span>';

						tryAgain = false;
					}
					else {
						message = Eramba.locale.errorHuman;
					}
				}

				if (tryAgain) {
					message += "<br /><br />";
					message += '<span class="ajax-modal-try-again">' + Eramba.locale.tryAgain + '</span>';

					message += '<script type="text/javascript">jQuery(function($) {$(".ajax-modal-try-again > span").on("click", function(e){e.preventDefault();window.location.reload();})});</script>';
					message += '<style type="text/css">.ajax-modal-try-again > span{color:#4d7496;font-weight:600;text-decoration:underline;cursor:pointer;}</style>';
				}

				bootbox.alert({
					title: Eramba.locale.errorTitle,
					message: message
				});

				if (typeof _this.onError == "function") {
					_this.onError();
				}

				return false;
			};

			var loaderProgress = function() {
				$(document)
					.ajaxStart(function() {
						NProgress.start();
					})
					.ajaxSend(function() {
						NProgress.set(0.4);
					})
					.ajaxSuccess(function() {
						NProgress.inc();
					})
					.ajaxError(function() {
						NProgress.inc();
					})
					.ajaxComplete(function() {
						NProgress.inc();
					})
					.ajaxStop(function() {
						NProgress.done();
					});
			};

			var getErrorMessage = function(xhr) {
				var statusErrorMap = {
					'400' : "Server understood the request, but request content was invalid.",
					'401' : "Unauthorized access.",
					'403' : "Forbidden resource can't be accessed.",
					'404' : "Requested page not found.",
					'500' : "Internal server error.",
					'503' : "Service unavailable."
				};

				if (xhr.status && typeof statusErrorMap[xhr.status] != "undefined") {
					return statusErrorMap[xhr.status];
				}
				else {
					return "Unknown Error. " + xhr.statusText;
				}
			};

			return this.init();
		}

		return new __Ajax();
	}());

	var __ActionHandler = (function() {
		function __Queue() {

			var _this = this;
			this.data = [];
			this.revertData = null;

			this.init = function() {
				this.data = [];
				this.revertData = null;

				return this;
			}

			this.afterChange = function(action) {
				if (typeof action == "undefined") {
					var action = false;
				}

				if (this.notEmpty()) {
					//skip pushState for modal widgets
					if (typeof action == "string" && action.indexOf("/ajax/modalSidebarWidget") == -1) {
						// history.replaceState({}, '', this.getLast());
					}
				}
			};

			this.add = function(action) {
				console.log("Added to queue: " + action);
				this.data.push(action);
				this.revertData = "pop";

				this.afterChange(action);
			};

			this.pop = function() {
				if (this.isEmpty()) {
					return false;
				}
				console.log("Popped from queue: " + this.getLast());

				this.revertData = ["add", this.getLast()];
				var popped = this.data.pop();

				this.afterChange();
			};

			this.revert = function() {
				if (this.revertData == null) {
					return false;
				}

				console.log("Reverting queue");
				if (typeof this.revertData == "string") {
					return this[this.revertData]();
				}

				if (typeof this.revertData == "object") {
					return this[this.revertData[0]](this.revertData[1]);
				}
			};

			this.reset = function() {
				this.data = [];
				this.revertData = null;

				// history.replaceState({}, '', Eramba.Ajax.currentIndex);
			};

			this.isEmpty = function() {
				if (!this.data.length) {
					return true;
				}

				return false;
			};

			this.notEmpty = function() {
				if (this.data.length) {
					return true;
				}

				return false;
			};

			this.getLast = function() {
				if (this.isEmpty()) {
					return false;
				}

				return this.data[this.data.length-1];
			};

			return this.init();
		}

		return function() {
			var _this = this;

			this.init = function() {
				this.Queue = new __Queue();

				return this;
			}

			return this.init();
		}
	}());

	Eramba.Ajax.UI = (function() {
		function __AjaxUI() {

			/**
			 * In non-strict mode, 'this' is bound to the global scope when it isn't bound to anything else.
			 * In strict mode it is 'undefined'. That makes it an error to use it outside of a method.
			 */
			var _this = this;
			var ActionHandler = new __ActionHandler();

			this.triggerIndexReload = false;
				this.objectInAction = {};

			this.initAjaxCheck = false;
			this.initPushState = false;
			this._ajaxAction = null;
			this.currentAction = null;
			this.bootbox = null;
			this.limit = null;
			this.success = null;
			this.modal = null;
			this.attachmentsDropzone = null;

			/**
			 * Init call
			 */
			this.init = function() {
				Eramba.Ajax.onError = function() {
					unblockModal();
				};

				return this; /*this refer to Eramba.Ajax*/
			};


			/**
			 * Setup ajax ui base functionality when page loads.
			 */
			this.setup = function() {
				this.attachEvents();

				if (typeof this.initPushState == "object") {
					this.requestHandler(this.initPushState.url, this.initPushState.action);
					this.initPushState = false;
				}
			};

			/**
			 * Set limit count of comments displayed in a page.
			 */
			this.setPageLimit = function(limit) {
				if (limit) {
					this.limit = limit;
				}
			};

			/**
			 * Attach ajax action events for ajax ui interaction.
			 */
			this.attachEvents = function() {
				var _this = this;

				$("[data-ajax-action]").off("click.Eramba").on("click.Eramba", function(e) {
					e.preventDefault();

					// hold data for an item being edited for later use
					if ($(this).data("ajax-action") == "edit"/* && ActionHandler.Queue.isEmpty()*/) {
						var bindCurrentPos = null;
						if (ActionHandler.Queue.isEmpty()) {
							bindCurrentPos = "index";
						}
						else {
							bindCurrentPos = ActionHandler.Queue.getLast();
						}

						// console.log(bindCurrentPos);
						_this.objectInAction[bindCurrentPos] = $(this).closest("[data-action-list-menu]").eq(0).data();
						console.log(_this.objectInAction);
					}

					_this.requestHandler($(this).attr("href"), $(this).data("ajax-action"));

					return true;

					/*if ($.active !== 0) {
						return false;
					}

					var href = $(this).attr("href");

					_this._ajaxAction = $(this).data("ajax-action");
					_this.currentAction = _this._ajaxAction;

					if (_this.currentAction == "cancel" || _this.currentAction == "submit") {
						ActionHandler.Queue.pop();
					}
					else {
						ActionHandler.Queue.add(href);
					}

					_this.requestAction(href);*/
				});

				$( ".datepicker" ).datepicker({
					/*onSelect: function(date) {
			            alert(date);
			        },*/
					//defaultDate: +7,
					showOtherMonths:true,
					autoSize: true,
					dateFormat: 'yy-mm-dd'//,// 'dd-mm-yy',
					/*onSelect: function (date) {
						console.log(date);
						console.log(this);
						$(this).text(this.value);
					}*/
				});

				App.init();
				Plugins.init();
			};

			this.requestHandler = function(href, action) {
				if ($.active !== 0) {
					return false;
				}

				_this._ajaxAction = action;
				_this.currentAction = _this._ajaxAction;

				if (_this.currentAction == "cancel" || _this.currentAction == "submit") {
					ActionHandler.Queue.pop();
					_this.triggerIndexReload = true;
				}
				else {
					ActionHandler.Queue.add(href);
				}

				_this.requestAction(href);
			};

			/**
			 * Makes ajax action request for triggered ajax events.
			 */
			this.requestAction = function(href) {
				var formData = {};
				var ajaxMethod = $.get;
				if (this.currentAction == "quick-create") {
					// formData = $("#eramba-modal .widget-form form").serializeArray();
					formData = $("#eramba-modal .widget-form").not(".widget-popup-sidebar").find('form').serializeArray();

					// formData.push({name: "_eramba_ajax", value: "store_session"});
					var quickHref = "/ajax/custom?_eramba_ajax=store_session";

					blockModal();
					$.post(quickHref, formData)
						.done(function(data) {
							// unblockModal();
							// _this.handleAction(data);
							_this.makeGet(href, {});
						})
						.fail(function() {
							console.log("Fail");
						});
				}
				else {
				
					if (this.currentAction == "cancel" && !ActionHandler.Queue.isEmpty()) {
						formData.redirectAjax = ActionHandler.Queue.getLast();
					}
					if (this.currentAction == "add") {
						if (this._ajaxAction != "quick-create") {
							formData.ignoreSessionData = true;
						}
					}

					blockModal();
					return _this.makeGet(href, formData);
				}
				// $.get(href, formData)
				// 	.done(function(data) {
				// 		unblockModal();
				// 		_this.handleAction(data);
				// 	})
				// 	.fail(function() {
				// 		console.log("Fail");
				// 		ActionHandler.Queue.revert();
				// 	});
			};

			this.makeGet = function(href, formData) {
				return $.get(href, formData)
					.done(function(data) {
						unblockModal();

						var finalHref = href;
						if (!$.isEmptyObject(formData.redirectAjax)) {
							finalHref = formData.redirectAjax;
						}

						var status = _this.handleAction(data, finalHref);
						if (status == true) {

						}
					})
					.fail(function() {
						console.log("Fail");
						ActionHandler.Queue.revert();
					});
			};

			/**
			 * Handles completed ajax request for triggered event.
			 */
			this.handleAction = function(data, href) {
				var _this = this;
				//console.log(this.currentAction);
				// if ((this.currentAction == "cancel" || this.currentAction == "submit")) {
					if (ActionHandler.Queue.isEmpty()) {
						this.modal.hide();
						return false;
					}
					/*else {
						this.currentAction == "add";
						console.log(this.currentAction);
					}*/
				// }
				
				if (this.modal == null) {
					this.modal = new Eramba.Modal(data);
					this.modal.obj.on('shown.bs.modal.Ajax', function(e) {
						
						_this.modalShownHandler();

					});
					this.modal.obj.on('hidden.bs.modal.Ajax', function(e) {
						_this.modalHiddenHandler();
					});
				}
				else {
					this.insertModalData(data);
				}

				_this.scrollBackToObject(href);

				return true;
			};

			/**
			 * If modal is visible then we insert data directly into it.
			 */
			this.insertModalData = function(data) {
				this.modal.html(data);
				this.modalShownHandler();
			};

			/**
			 * Initialize modal functionality when opened or new content inserted.
			 */
			this.modalShownHandler = function(action) {
				// console.log("Modal.shown");
				if (typeof action !== "undefined") {
					this.currentAction = action;
				}

				if (!this.modal.shown) {
					this.modal.show();
					return true;
				}

				// console.log(this.currentAction);

				this.attachEvents();
				this.attachModalEvents(false);
				FormComponents.init();
				Plugins.init();

				
			};

			/**
			 * Trigger functionality when modal closes.
			 */
			this.modalHiddenHandler = function() {
				// console.log("Modal.hidden");
				this.removeDropzone();
				ActionHandler.Queue.reset();
				this.reloadIndex();
			};

			/**
			 * Removes current dropzone.
			 */
			this.removeDropzone = function() {
				if (this.attachmentsDropzone != null) {
					this.attachmentsDropzone.destroy();
					this.attachmentsDropzone = null;
				}
			};

			/**
			 * Binds ajax events located in a modal.
			 */
			this.attachModalEvents = function(setFocus) {
				if (typeof setFocus == "undefined") {
					var setFocus = true;
				}

				if (setFocus) {
					focusModalForm();
				}

				var action = this.currentAction;

				if (["edit", "add", "delete", "quick-create", "cancel", "submit"].indexOf(action) != -1) {
					this.onSubmitForm();
				}

				this.onSubmitComment();
				this.loadMoreComments();

				this.onSubmitNotification();
				this.notificationAjaxActions();
			};

			/**
			 * Attach an event for loading comments.
			 */
			this.loadMoreComments = function() {
				var _this = this;

				$("[data-ajax-action=load-comments]").off("click.Eramba").on("click.Eramba", function(e) {
					e.preventDefault();

					_this.limit = _this.limit + 5;
					blockModal();
					$.ajax({
						type: "POST",
						url: $(this).prop("href"),
						data: {limit: _this.limit}
					}).done(function(data) {
						$("#comments-content").html(data);
						unblockModal();
						_this.modalShownHandler('load-comments');
					});
				});
			};

			/**
			 * Initializes dropzone into current runtime.
			 */
			this.dropzoneInit = function(model, foreign_key) {
				var _this = this;

				if ($("#attachments-content-files").length && this.attachmentsDropzone == null) {
					Dropzone.options.attachmentsDropzone = {
						dictDefaultMessage: "<i class='icon icon-cloud-upload'></i>Drop files here or click to upload.",
						init: function() {
							this.on("queuecomplete", function() {
								$.ajax({
									url: "/attachments/getList/" + model + "/" + foreign_key
								}).done(function(html) {
									var ele = $(html);
									$("#attachments-content-files").html(ele);
								});

								_this.triggerIndexReload = true;
							});
						}
					};

					this.attachmentsDropzone = new Dropzone("#attachments-dropzone");
				}
			};

			/**
			 * Add/edit/delete form submission handling.
			 */
			this.onSubmitForm = function() {
				$(".modal-body form:not(#comment-form, #attachments-dropzone, #notifications-widget-form)")
				.off("submit.Eramba")
				.on("submit.Eramba", function(e) {
					e.preventDefault();
					blockModal();

					var formData = $(this).serializeArray();
					var formAction = $(this).prop("action");

					$.ajax({
						type: "POST",
						url: formAction,
						data: formData
					}).done(function(data) {
						unblockModal();

						_this.removeDropzone();
						_this.insertModalData(data, formAction);
						if (_this.success === true) {
							_this.success = null;
							_this.triggerIndexReload = true;
							
							ActionHandler.Queue.pop();
							if (ActionHandler.Queue.isEmpty()) {
								_this.modal.hide();
								return false;
							}

							if (ActionHandler.Queue.notEmpty()) {
								var action = ActionHandler.Queue.getLast();

								_this.currentAction = "add";
								console.log(action);
								_this.requestAction(action);
							}
						}
						else {
							
						}
					});
				});
			};

			/**
			 * Event to handle comment submission.
			 */
			this.onSubmitComment = function() {
				var _this = this;

				$("#comment-form").off("submit.Eramba").on("submit.Eramba", function(e) {
					e.preventDefault();
					blockModal();

					var formData = $(this).serializeArray();
					formData.push({name: "limit", value: _this.limit});

					$.ajax({
						type: "POST",
						url: $(this).prop("action"),
						data: formData
					}).done(function(html) {
						_this.triggerIndexReload = true;

						var ele = $(html);
						$("#comments-content").html(ele);
						ele.find(".transition-obj.masked").focus();
						ele.find(".transition-obj.masked").removeClass("masked");

						unblockModal();
						if (_this.modal.shown) {
							_this.modalShownHandler('comment-add');
						}
					});
				});
			};

			this.onSubmitNotification = function() {
				var _this = this;

				$("#notifications-widget-form").off("submit.Eramba").on("submit.Eramba", function(e) {
					e.preventDefault();
					blockModal();

					var formData = $(this).serializeArray();

					$.ajax({
						type: "POST",
						url: $(this).prop("action"),
						data: formData
					}).done(function(html) {
						_this.triggerIndexReload = true;

						$("#notifications-widget-content").html(html);

						unblockModal();
						if (_this.modal.shown) {
							_this.modalShownHandler('notification-add');
						}
					});
				});
			};

			this.notificationAjaxActions = function() {
				var _this = this;

				$("[data-ajax-action=notification-action]").off("click.Eramba").on("click.Eramba", function(e) {
					e.preventDefault();

					blockModal();
					$.ajax({
						type: "GET",
						url: $(this).prop("href")
					}).done(function(data) {
						$("#notifications-widget-content").html(data);
						unblockModal();
						_this.modalShownHandler('load-comments');
					});
				});
			};

			/**
			 * Callback triggers before pagination request starts.
			 */
			this.paginationBefore = function() {
				blockModal();
			};

			/**
			 * Callback triggers after pagination request completes.
			 */
			this.paginationComplete = function() {
				unblockModal();
				this.modalShownHandler("pagination");
			};

			/**
			 * Update current index data.
			 */
			this.reloadIndex = function() {
				var _this = this,
					mainContent = $("#content");

				if (this.triggerIndexReload == true) {
					this.triggerIndexReload = false;

					App.blockUI(mainContent);
					$.ajax({
						type: "GET",
						url: Eramba.Ajax.currentIndex
					}).done(function(data) {
						$("#main-content").html(data);
						App.init();
						Plugins.init();
						FormComponents.init();
						_this.attachEvents();
						mainContent.trigger("Eramba.reloadIndex");
						App.unblockUI(mainContent);

						_this.scrollBackToObject("index");
					});
				}
			};

			this.scrollBackToObject = function(contentType) {

				// we scroll the page to the last used object
				if (!$.isEmptyObject(_this.objectInAction[contentType])) {
					var indexAction = _this.objectInAction[contentType];
					var $targetAction = $("[data-action-list-menu=1][data-action-list-id="+indexAction.actionListId+"][data-action-list-model="+indexAction.actionListModel+"]");

					if ($targetAction.length) {
						if ($targetAction.parent().is(":visible")) {
							var _top = $targetAction.parent().offset().top;

							var $contentEle = $('html, body');
							if (contentType != "index") {
								$contentEle = $("#eramba-modal");
								_top = _top + $contentEle.scrollTop();
							}

							if (typeof _top != "undefined") {
								console.log("Scrolling to " +contentType+ " object " + _top + "px");
								setTimeout(function() {
									$contentEle.animate({
										scrollTop: _top - 20 //tolerance
									}, 850);
								}, 300);
							}
						}

						_this.objectInAction[contentType] = null;
					}

					if (contentType == "index") {
						_this.objectInAction = {};
					}
				}
			};

			/**
			 * Focus first input in a modal form.
			 */
			var focusModalForm = function() {
				var ele = $(".modal-body").find('input:visible, textarea:visible').filter(function() {
					return ($(this).is("input") ? $(this).val() == "" : $(this).text() == "");
				});

				if (ele.length) {
					ele.eq(0).focus();
				}
			};

			var blockModal = function() {
				App.blockUI($(".modal-content"));
			};

			var unblockModal = function() {
				App.unblockUI($(".modal-content"));
			};

			return this.init(); /*initialize the init()*/
		}

		return new __AjaxUI(); /*creating a new object of Ajax rather than a funtion*/
	}());

	Eramba.Modal = function(data) {
		// function __Modal(data) {
			this.obj = null;
			this.shown = false;
			this.template = '<div class="modal modal-ajax-ui fade scale" id="eramba-modal">' +
				'<div class="modal-dialog modal-responsive">' +
					'<div class="modal-content">' +
						/*'<div class="modal-header">' +
							'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
							'<h4 class="modal-title">Modal title</h4>' +
						'</div>' +*/
						'<div class="modal-body"></div>' +
						/*'<div class="modal-footer">' +
							'<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
							'<button type="button" class="btn btn-primary" data-dismiss="modal">Save changes</button>' +
						'</div>' +*/
					'</div>' +
				'</div>' +
			'</div>';

			this.init = function(data) {
				var _this = this;

				var el = $(this.template);
					el.find(".modal-body").html(data);

				//trigger callback on first modal load
				$("#content").trigger("Eramba.Modal.loadHtml");

				this.obj = el.modal({
					keyboard: false,
					show: false,
					backdrop: "static"
				})
				.on('show.bs.modal', function (e) {
					$("body").addClass("modal-open");
					_this.shown = true;
				})
				.on('hide.bs.modal', function (e) {
					$("body").removeClass("modal-open");
					_this.shown = false;
				});

				this.show();

				return this;
			};

			this.html = function(data) {
				this.obj.find(".modal-body .bs-tooltip").tooltip("destroy");
				this.obj.find(".modal-body .bs-popover").popover("destroy");

				this.obj.find(".modal-body").html(data);

				$("#content").trigger("Eramba.Modal.loadHtml");
			};

			this.destroy = function() {
				this.hide();
			};

			this._destroy = function() {
				this.obj.data("bs.modal", null);
				this.obj.remove();
				this.delete;
			};

			this.show = function(data) {
				this.obj.modal('show');
				// Eramba.Ajax.UI.modal.setSize('modal-responsive');
			};

			this.hide = function() {
				this.obj.modal('hide');
			};

			this.setSize = function(size) {
				var dialog = this.obj.children(".modal-dialog").eq(0);
				dialog.removeClass(function(index, className){
					var array = className.split(" "),
						index = array.indexOf("modal-dialog"),
						c = "";

					array.splice(index, 1);
					c = array.join(" ");
					return c;
				}).addClass(size);
			};

			return this.init(data);
		// }

		// return new __Modal(data);
	};

	/**
	 * Check to evaluate whether 'Eramba' exists in the global namespace - if not, assign window.Eramba an object literal
	 */
}(window.Eramba = window.Eramba || {}, jQuery));