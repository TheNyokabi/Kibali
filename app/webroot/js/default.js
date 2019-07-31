$(document).ready(function(){
	$( ".datepicker" ).datepicker({
		//defaultDate: +7,
		showOtherMonths:true,
		autoSize: true,
		dateFormat: 'yy-mm-dd'// 'dd-mm-yy'
	});

	/**
	 * @deprecated
	 */
	$(".button-prompt-remove").on("click", function(e) {
		var r = confirm( "Are you sure you want to delete this record?" );
		if ( r == true ) {
			return true;
		} else {
			e.preventDefault();
			return false;
		}
	});

	$(".print-index").on("click", function(e) {
		$("#container").find(".widget.box.widget-closed").children(".widget-header").find(".widget-collapse").trigger("click");
		setTimeout('window.print();', 500);
	});

	$('#notification-dropdown').one('shown.bs.dropdown', function(e) {
		$.ajax({
			type: "GET",
			url: "/notifications/setNotificationsAsSeen"
		});
	});
	
	// Eramba.Ajax.attachEvents();
});

/**
* Best used before redirect.
*/
function setPseudoNProgress() {
	NProgress.set(0.4);
	setInterval(function(){NProgress.inc();}, 300);
}

function removeParent(ele) {
	$(ele).parent().remove();
}

function setHelp(ele, content, options) {
	$(ele).fadeTo(300, 0.5);
	$(ele).popover({
		container: "body",
		title: "Help",
		content: content,
		placement: "top",
		trigger: "hover"
	});
}
function hideHelp(ele) {
	$(ele).fadeTo(250, 1);
	$(ele).popover("destroy");
}

function whichTransitionEvent() {
	var t,
		el = document.createElement("fakeelement");

	var transitions = {
		"transition"      : "transitionend",
		"OTransition"     : "oTransitionEnd",
		"MozTransition"   : "transitionend",
		"WebkitTransition": "webkitTransitionEnd"
	}

	for (t in transitions){
		if (el.style[t] !== undefined){
			return transitions[t];
		}
	}
}