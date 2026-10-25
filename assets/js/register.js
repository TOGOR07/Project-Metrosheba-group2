/* Registration page: full field validation plus the AJAX "Send OTP"
   call that asks PHP for a one-time code without reloading the page. */

document.addEventListener("DOMContentLoaded", function () {
    var form = document.getElementById("registerForm");
    var btnSendOtp = document.getElementById("btnSendOtp");
    var otpMsg = document.getElementById("otpMsg");

    if (!form) {
        return;
    }

    var checks = [
        function () { return validateFullName("name"); },
        function () { return validateEmail("email"); },
        function () { return validateMobile("mobile"); },
        function () { return validateDob("dob"); },
        function () { return validateNid("nid"); },
        function () { return validateSelect("gender", "gender"); },
        function () { return validatePassword("password", false); },
        function () { return validateOtp("otp"); }
    ];

    var fieldIds = ["name", "email", "mobile", "dob", "nid", "gender", "password", "otp"];

    fieldIds.forEach(function (id, index) {
        bindLiveValidation(id, checks[index]);
    });

    form.addEventListener("submit", function (event) {
        if (!runAll(checks)) {
            event.preventDefault();
        }
    });

    if (btnSendOtp) {
        btnSendOtp.addEventListener("click", function () {
            requestOtp(btnSendOtp, otpMsg);
        });
    }
});

function requestOtp(button, messageBox) {
    messageBox.textContent = "";
    messageBox.className = "field-ok";

    // No point asking the server for a code for a malformed number.
    if (!validateMobile("mobile")) {
        return;
    }

    clearFieldError("otp");

    var reset = setBusy(button, "Sending...");

    ajaxRequest(
        "POST",
        "../controllers/registration_otp_api.php",
        encodeParams({ mobile: fieldValue("mobile") }),
        function (response, ok) {
            reset();

            if (ok) {
                messageBox.textContent = response.message;
                messageBox.className = "field-ok";
                return;
            }

            setFieldError("otp", response.message || "Could not send the OTP.");
        }
    );
}

function bindLiveValidation(fieldId, check) {
    var input = document.getElementById(fieldId);

    if (!input) {
        return;
    }

    var touched = false;
    var eventName = input.tagName === "SELECT" || input.type === "date" ? "change" : "input";

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
