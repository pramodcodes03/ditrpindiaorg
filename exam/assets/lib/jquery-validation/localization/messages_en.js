/*
 * Translated default messages for the jQuery validation plugin.
 * Locale: JA (Japanese; 日本語)
 */
(function ($) {
	$.extend($.validator.messages, {
		required: "This field is required",
		remote: "Please fix this field.",
		email: "Enter valid email",
		name: "Enter full name",
		username: "Enter user name",
		url: "Please enter a valid URL.",
		date: "Please enter a valid date.",
		dateISO: "Please enter a valid date (ISO).",
		number: "Please enter a valid number.",
		digits: "Please enter a valid number.",
		creditcard: "Please enter a valid credit card number.",
		equalTo: "Please re-enter the same value.",
		accept: "Please enter a value that contains a valid extension.",
		maxlength: $.format("{0} Please input the characters."),
		minlength: $.format("{0} Please enter at least characters."),
		rangelength: $.format("Please enter a value between {0} characters from {1} characters."),
		range: $.format("Please enter a value between {0} and {1}."),
		max: $.format("Please enter a value of {0} below."),
		min: $.format("Please enter a value of {0} or more.")
	});
}(jQuery));