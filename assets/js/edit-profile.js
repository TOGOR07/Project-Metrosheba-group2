/* Edit-profile form: same rules as registration, except the password
   is optional because leaving it blank keeps the current one. */

document.addEventListener("DOMContentLoaded", function () {
    var form = document.getElementById("editProfileForm");

    if (!form) {
        return;
    }

    var checks = [
        function () { return validateFullName("name"); },
        function () { return validateEmail("email"); },
        function () { return validateMobile("mobile"); },
        function () { return validateDob("dob"); },
        function () { return validateNid("nid"); },
        function () { return validateRadioGroup("gender", "gender", "gender"); },
        function () { return validatePassword("password", true); }
    ];

    ["name", "email", "mobile", "dob", "nid", "password"].forEach(function (id, index) {
        bindLiveValidation(id, checks[index < 5 ? index : 6]);
    });

    var radios = document.querySelectorAll('input[name="gender"]');

    for (var i = 0; i < radios.length; i++) {
        radios[i].addEventListener("change", checks[5]);
    }

    form.addEventListener("submit", function (event) {
        if (!runAll(checks)) {
            event.preventDefault();
        }
    });
});

function bindLiveValidation(fieldId, check) {
    var input = document.getElementById(fieldId);

    if (!input) {
        return;
    }

    var touched = false;
    var eventName = input.type === "date" ? "change" : "input";

    input.addEventListener("blur", function () {
        touched = true;
        check();
    });

    input.addEventListener(eventName, function () {
        if (touched) {
            check();
        }
    });
}
