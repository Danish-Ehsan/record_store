
function validate( type, value, valueTwo, maxLength, minLength, required ) {
	if (required && value.length < 1) {
		return 'This field is required.';
	} else if (maxLength && value.length > maxLength) {
		return 'This field can only contain ' + maxLength + ' characters.';
	} else if (required && minLength && value.length < minLength) {
		return 'This field needs at least ' + minLength + ' characters.';
	} else if (!required && value.length > 0 && minLength && value.length < minLength) {
		return 'This field needs at least ' + minLength + ' characters.';
	}

	var result;
	if (type == 'name') {
		result = /[a-z]/.test(value);
		if (!result) return 'This field can only include letters.';
	}

	if (type == 'email') {
		result = /\S+@\S+\.\S+/.test(value);
		if (!result) return 'Invalid email address.';
	}

	if (type == 'password') {
		if (value != valueTwo) return 'Password fields don\'t match.';
	}

	return true;
}