var CustomValidator = function() {

	"use strict";

	var inProgress = false;

	var cusotomValidator = function($form) {
		if (inProgress) {
			return;
		}

		inProgress = true;

		var url = '/custom_validator/customValidator/getValidation/' + $form.data('model');
		var data = $form.serialize();

		App.blockUI($('.modal-content'));

		$.ajax({
			'url': url,
			'type': 'POST',
			'data': data,
			'dataType': 'json',
		}).done(function(data) {
			App.unblockUI($('.modal-content'));

			inProgress = false;

			if (data == false || typeof data === 'string') {
				return;	
			}

			applyFields(data);
		});
	}

	var applyFields = function(data) {
		for (var key in data) {
			var $elem = $('[data-field-name="' + key + '"]');

			resetField($elem);

			if (data[key] == 'optional') {
				optionalField($elem);
			}
			else if (data[key] == 'disabled') {
				disabledField($elem);
			}
			else if (data[key] == 'mandatory') {
				mandatoryField($elem);
			}
		};
	}

	var resetField = function($input) {
		var $parent = $input.closest('.form-group');

		$input.attr('disabled', false);
	}

	var optionalField = function($input) {
		var $parent = $input.closest('.form-group');

		$parent.find('.help-block-prefix').html('OPTIONAL: ');
	}

	var disabledField = function($input) {
		var $parent = $input.closest('.form-group');

		$input.attr('disabled', true);
		$parent.find('.help-block-prefix').html('');
	}

	var mandatoryField = function($input) {
		var $parent = $input.closest('.form-group');

		$parent.find('.help-block-prefix').html('MANDATORY: ');
	}
	
	var initTriggers = function() {
		$('body').on('change', 'select.custom-validator-trigger', function() {
			cusotomValidator($(this).closest('form'));
		});
		$('body').on('change', 'input.custom-validator-trigger', function() {
			cusotomValidator($(this).closest('form'));
		});
		$('body').on('change', 'textarea.custom-validator-trigger', function() {
			cusotomValidator($(this).closest('form'));
		});

		$('.custom-validator-form').each(function() {
			cusotomValidator($(this));
		});
	}

	return {
		init: function () {
			initTriggers()
		},
	};
}();