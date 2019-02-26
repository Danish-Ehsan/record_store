<?php

function validate( $type, $value, $valueTwo = false, $maxLength = false, $minLength = false, $required = false) {
	if ($required && strlen($value) < 1) {
            return 'This field is required.';
	} else if ($maxLength && strlen($value) > $maxLength) {
            return "This field can only contain $maxLength characters.";
	} else if ($required && $minLength && strlen($value) < $minLength) {
            return "This field needs at least $minLength characters.";
	} else if (!$required && strlen($value) > 0 && $minLength && strlen($value) < $minLength) {
            return "This field needs at least ' + $minLength + ' characters.";
	}

	if ($type == 'name') {
            $result = preg_match('/^[[:alpha:]]+$/', $value);
            if (!$result) { return 'This field can only include letters.'; }
	}
        
        if ($type == 'number') {
            $result = preg_match('/^[[:digit:]]+$/', $value);
            if (!$result) { return 'This field can only include numbers.'; }
	}
        
        if ($type == 'price') {
            $result = preg_match('/^[[:digit:]]*\.?[[:digit:]]{2}$/', $value);
            if (!$result) { return 'Invalid price. Make sure to not include any special characters.'; }
	}

	if ($type == 'email') {
            $result = preg_match('/\S+@\S+\.\S+/', $value);
            if (!$result) { return 'Invalid email address.'; }
	}

	if ($type == 'password') {
            if ($value != $valueTwo) { return 'Password fields don\'t match.'; }
	}

	return true;
}