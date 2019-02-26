
function validate(element, type, minLength = 1, maxLength, required = true) {
	$element = $(element);

	if (required && !$element.val().length) {
		return 'Input required';
	}
}