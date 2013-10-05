/**
* Content Management System
* Installation script
*
* @author Chavjoh
* @license Creative Commons Attribution-ShareAlike 3.0 Unported
*/

// Disable function
jQuery.fn.extend({
	disable: function(state) {
		return this.each(function() {
			this.disabled = state;
		});
	}
});

function notEmpty(element) {
	var value = element.val();
	var ret = {};

	return {
		status: !(value.length == 0)
	};
}

function validateAdminKey(element) {
	return {
		status: /^[a-z0-9-_]+$/.test(element.val()),
		msg: 'The admin key accept only alphabets and numeric value without space.'
	};
}

$(function() {

	var statePermission = false;
	var stateRequirement = false;
	var stateDatabase = false;

	$.fn.wizard.logging = true;

	var wizard = $("#wizard").wizard({
		showCancel: false
	});

	wizard.cards["welcome"].on("selected", function(card) {
		$('#nextButton').disable(!stateRequirement);
	});

	wizard.cards["permission"].on("selected", function(card) {
		$('#nextButton').disable(!statePermission);
	});

	wizard.cards["information"].on("selected", function(card) {
		$('#nextButton').disable(false);
	});

	wizard.cards["database"].on("selected", function(card) {
		$('#nextButton').disable(!stateDatabase);
	});

	$('#checkDatabase').click(function() {
		// Temporarily disable check button
		$(this).disable(true);

		// Get data from fields
		var db_name = $('#database-name').val();
		var db_user = $('#database-user').val();
		var db_password = $('#database-password').val();
		var db_host = $('#database-host').val();
		var db_port = $('#database-port').val();

		// Check data with install script
		$.ajax('install.php?type=DATABASE&name='+db_name+'&user='+db_user+'&password='+db_password+'&host='+db_host+'&port='+db_port+'')
			.done(function(data) {
				if (data == "SUCCESS") {
					// Show next button
					$('#nextButton').disable(false);

					// Hide check button
					$('#checkDatabase').hide();

					// Disable fields
					$('#database-name').disable(true);
					$('#database-prefix').disable(true);
					$('#database-user').disable(true);
					$('#database-password').disable(true);
					$('#database-host').disable(true);
					$('#database-port').disable(true);

					// Change information state (used for navigation)
					stateDatabase = true;

					// Show victory message
					$('#stateDatabase').html('<div class="alert alert-success"> Database connexion OK ! </div>');
				}
				else {
					$('#stateDatabase').html('<div class="alert alert-error"> ' + data + ' </div>');
				}
			})
			.fail(function() {
				$('#stateDatabase').html('<div class="alert alert-error"> Ajax request cannot be made. </div>');
			});

		return false;
	});

	$('#checkRequirement').click(function() {
		// Temporarily disable check button
		$(this).disable(true);

		// Check data with install script
		$.ajax("install.php?type=REQUIREMENT")
			.done(function(data) {
				if (data == "SUCCESS") {
					// Show next button
					$('#nextButton').disable(false);

					// Hide check button
					$('#checkRequirement').hide();

					// Change information state (used for navigation)
					stateRequirement = true;

					// Show victory message
					$('#stateRequirement').html('<div class="alert alert-success"> Requirements OK ! </div>');
				}
				else {
					$('#stateRequirement').html('<div class="alert alert-error"> ' + data + ' </div>');
				}
			})
			.fail(function() {
				$('#stateRequirement').html('<div class="alert alert-error"> Ajax request cannot be made. </div>');
			});

		return false;
	});

	$('#checkPermission').click(function() {
		// Temporarily disable check button
		$(this).disable(true);

		// Check data with install script
		$.ajax("install.php?type=CHMOD")
			.done(function(data) {
				if (data == "SUCCESS") {
					// Show next button
					$('#nextButton').disable(false);

					// Hide check button
					$('#checkPermission').hide();

					// Change information state (used for navigation)
					statePermission = true;

					// Show victory message
					$('#statePermission').html('<div class="alert alert-success"> Permissions OK ! </div>');
				}
				else {
					$('#statePermission').html('<div class="alert alert-error"> ' + data + ' </div>');
				}
			})
			.fail(function() {
				$('#statePermission').html('<div class="alert alert-error"> Ajax request cannot be made. </div>');
			});

		return false;
	});

	wizard.on("submit", function(wizard) {
		var submitData = {
			'information-name':			$('#information-name').val(),
			'information-description':	$('#information-description').val(),
			'information-keywords':		$('#information-keywords').val(),

			'database-name':			$('#database-name').val(),
			'database-prefix':			$('#database-prefix').val(),
			'database-user':			$('#database-user').val(),
			'database-password':		$('#database-password').val(),
			'database-host':			$('#database-host').val(),
			'database-port':			$('#database-port').val(),

			'administration-key':		$('#administration-key').val(),
			'administration-user':		$('#administration-user').val(),
			'administration-password':	$('#administration-password').val()
		};

		// Update link to admin interface
		$('#adminLink').attr('href', '../' + $('#administration-key').val() + '/');

		// Submit installation
		$.ajax({
			type: "GET",
			url: "install.php?type=INSTALL",
			data: submitData,
			async: false
		})
			.done(function(data) {
				if (data == 'SUCCESS') {
					wizard.submitSuccess();
				}
				else {
					wizard.submitError();
					$('.wizard-error').append(data);
				}
			})
			.fail(function() {
				wizard.submitError();
			})
			.always(function() {
				wizard.hideButtons();
				wizard._submitting = false;
				wizard.updateProgressBar(0);
			});
	});

	wizard.el.find(".wizard-success .im-done").click(function() {
		wizard.reset().close();
	});

	$("#open-wizard").click(function() {
		wizard.show();
	});

	wizard.show();
});