/*
 * MetroSheba - client-side form validation.
 *
 * These rules mirror the PHP checks in controllers/. The browser copy
 * gives instant feedback; the PHP copy is the one that actually
 * protects the database, because anything here can be bypassed.
 */

/* ---------- primitive checks ---------- */

function isAllDigits(text) {
    if (text === "") {
        return false;
    }

    for (var i = 0; i < text.length; i++) {
        if (text[i] < "0" || text[i] > "9") {
            return false;
        }
    }

    return true;
}

function hasDigit(text) {
    for (var i = 0; i < text.length; i++) {
        if (text[i] >= "0" && text[i] <= "9") {
            return true;
        }
    }

    return false;
}

function getWords(text) {
    var parts = text.split(" ");
    var words = [];

    for (var i = 0; i < parts.length; i++) {
        var word = parts[i].trim();
        if (word !== "") {
            words.push(word);
        }
    }

    return words;
}

/* ---------- error display ---------- */

/**
 * Writes a message under a field and marks the input itself, so the
 * error is visible both as text and as a red border.
 */
function setFieldError(fieldId, message) {
    var box = document.getElementById("err_" + fieldId);
    var input = document.getElementById(fieldId);

    if (box) {
        box.textContent = message;
    }

    if (input) {
        if (message) {
            input.classList.add("is-invalid");
        } else {
            input.classList.remove("is-invalid");
        }
    }

    return message === "";
}

function clearFieldError(fieldId) {
    return setFieldError(fieldId, "");
}

function fieldValue(fieldId) {
    var input = document.getElementById(fieldId);
    return input ? input.value.trim() : "";
}

/* ---------- reusable field rules ---------- */

function validateFullName(fieldId) {
    var name = fieldValue(fieldId);

    if (name === "") {
        return setFieldError(fieldId, "Name is required.");
    }

    if (hasDigit(name)) {
        return setFieldError(fieldId, "Name cannot contain numbers.");
    }

    var words = getWords(name);

    if (words.length !== 2) {
        return setFieldError(fieldId, "Name must be exactly 2 words.");
    }

    if (words[0].length < 2 || words[1].length < 2) {
        return setFieldError(fieldId, "Each word must be at least 2 characters.");
    }

    return clearFieldError(fieldId);
}

function validateEmail(fieldId) {
    var email = fieldValue(fieldId);

    if (email === "") {
        return setFieldError(fieldId, "Email is required.");
    }

    if (email.indexOf(" ") !== -1) {
        return setFieldError(fieldId, "Email cannot contain spaces.");
    }

    var atPos = email.indexOf("@");

    if (atPos <= 0 || atPos !== email.lastIndexOf("@")) {
        return setFieldError(fieldId, "Email must contain one @, not at the start.");
    }

    var dotPos = email.lastIndexOf(".");

    if (dotPos === -1) {
        return setFieldError(fieldId, "Email must contain a dot after the @.");
    }

    if (dotPos < atPos + 2) {
        return setFieldError(fieldId, "The dot must come after the @.");
    }

    if (dotPos === email.length - 1) {
        return setFieldError(fieldId, "Email cannot end with a dot.");
    }

    return clearFieldError(fieldId);
}

function validateMobile(fieldId) {
    var mobile = fieldValue(fieldId);

    if (mobile === "") {
        return setFieldError(fieldId, "Mobile number is required.");
    }

    if (!isAllDigits(mobile)) {
        return setFieldError(fieldId, "Mobile must contain digits only.");
    }

    if (mobile.length !== 11) {
        return setFieldError(fieldId, "Mobile must be exactly 11 digits.");
    }

    if (mobile.substring(0, 2) !== "01") {
        return setFieldError(fieldId, "Mobile must start with 01.");
    }

    return clearFieldError(fieldId);
}

function validateRequired(fieldId, label) {
    if (fieldValue(fieldId) === "") {
        return setFieldError(fieldId, label + " is required.");
    }

    return clearFieldError(fieldId);
}

function validateNid(fieldId) {
    var nid = fieldValue(fieldId);

    if (nid === "") {
        return setFieldError(fieldId, "NID is required.");
    }

    if (!isAllDigits(nid)) {
        return setFieldError(fieldId, "NID must contain digits only.");
    }

    if (nid.length < 10 || nid.length > 17) {
        return setFieldError(fieldId, "NID must be between 10 and 17 digits.");
    }

    return clearFieldError(fieldId);
}

/** Date of birth must be a real past date and at least 12 years ago. */
function validateDob(fieldId) {
    var dob = fieldValue(fieldId);

    if (dob === "") {
        return setFieldError(fieldId, "Date of Birth is required.");
    }

    var parsed = new Date(dob);

    if (isNaN(parsed.getTime())) {
        return setFieldError(fieldId, "Enter a valid date.");
    }

    var today = new Date();

    if (parsed > today) {
        return setFieldError(fieldId, "Date of Birth cannot be in the future.");
    }

    var twelveYearsAgo = new Date(today.getFullYear() - 12, today.getMonth(), today.getDate());

    if (parsed > twelveYearsAgo) {
        return setFieldError(fieldId, "You must be at least 12 years old.");
    }

    return clearFieldError(fieldId);
}

function validatePassword(fieldId, optional) {
    var password = document.getElementById(fieldId) ? document.getElementById(fieldId).value : "";

    // On the edit-profile form a blank password means "keep the old one".
    if (password === "" && optional) {
        return clearFieldError(fieldId);
    }

    if (password === "") {
        return setFieldError(fieldId, "Password is required.");
    }

    if (password.length < 8) {
        return setFieldError(fieldId, "Password must be at least 8 characters.");
    }

    return clearFieldError(fieldId);
}

function validateOtp(fieldId) {
    var otp = fieldValue(fieldId);

    if (otp === "") {
        return setFieldError(fieldId, "OTP is required.");
    }

    if (!isAllDigits(otp)) {
        return setFieldError(fieldId, "OTP must contain digits only.");
    }

    if (otp.length !== 6) {
        return setFieldError(fieldId, "OTP must be 6 digits.");
    }

    return clearFieldError(fieldId);
}

function validateSelect(fieldId, label) {
    if (fieldValue(fieldId) === "") {
        return setFieldError(fieldId, "Please select a " + label + ".");
    }

    return clearFieldError(fieldId);
}

/** Checked radio group (used for gender on the edit-profile form). */
function validateRadioGroup(groupName, fieldId, label) {
    var chosen = document.querySelector('input[name="' + groupName + '"]:checked');

    if (!chosen) {
        return setFieldError(fieldId, "Please select a " + label + ".");
    }

    return clearFieldError(fieldId);
}

/** Runs every rule and returns true only if all passed. */
function runAll(checks) {
    var allValid = true;

    for (var i = 0; i < checks.length; i++) {
        if (!checks[i]()) {
            allValid = false;
        }
    }

    return allValid;
}
