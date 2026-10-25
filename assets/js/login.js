/* Login page: validates the two fields before the form is submitted. */

document.addEventListener("DOMContentLoaded", function () {
    var form = document.getElementById("loginForm");

    if (!form) {
        return;
    }

    var checks = [
        function () { return validateEmail("email"); },
        // Login only checks that a password was typed. The length rule
        // belongs on registration; applying it here would block any
        // existing account whose password predates that rule.
        function () { return validateRequired("password", "Password"); }
    ];

    bindLiveValidation("email", checks[0]);
    bindLiveValidation("password", checks[1]);

    form.addEventListener("submit", function (event) {
        if (!runAll(checks)) {
            event.preventDefault();
        }
    });
});

/**
 * Re-checks a field as the user types, but only once they have left it
 * the first time, so the form does not shout at an empty untouched box.
 */
function bindLiveValidation(fieldId, check) {
    var input = document.getElementById(fieldId);

    if (!input) {
        return;
    }

    var touched = false;

    input.addEventListener("blur", function () {
        touched = true;
        check();
    });

    input.addEventListener("input", function () {
        if (touched) {
            check();
        }
    });
}
