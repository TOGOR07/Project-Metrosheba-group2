/*
 * MetroSheba - shared browser helpers.
 *
 * Every page talks to the PHP endpoints through ajaxRequest(), so the
 * loading state, the JSON parsing and the error handling are written
 * once instead of in each page.
 */

/**
 * Sends an AJAX request with XMLHttpRequest and hands the decoded JSON
 * back through a callback.
 *
 * @param {string}  method   "GET" or "POST"
 * @param {string}  url      endpoint to call
 * @param {*}       data     FormData / URL-encoded string, or null
 * @param {Function} onDone  called as onDone(response, ok)
 */
function ajaxRequest(method, url, data, onDone) {
    var xhr = new XMLHttpRequest();
    xhr.open(method, url, true);

    // A FormData body sets its own multipart boundary, so only the
    // url-encoded case needs the header spelled out.
    if (typeof data === "string") {
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    }

    xhr.onreadystatechange = function () {
        if (xhr.readyState !== 4) {
            return;
        }

        var response;

        try {
            response = JSON.parse(xhr.responseText);
        } catch (e) {
            onDone({
                success: false,
                message: "The server sent an unreadable response."
            }, false);
            return;
        }

        onDone(response, xhr.status >= 200 && xhr.status < 300 && response.success === true);
    };

    xhr.onerror = function () {
        onDone({ success: false, message: "Network error. Please check your connection." }, false);
    };

    xhr.send(data);
}

/** Builds an application/x-www-form-urlencoded body from a plain object. */
function encodeParams(params) {
    var pairs = [];

    for (var key in params) {
        if (Object.prototype.hasOwnProperty.call(params, key)) {
            pairs.push(encodeURIComponent(key) + "=" + encodeURIComponent(params[key]));
        }
    }

    return pairs.join("&");
}

/**
 * Creates an element and fills it with TEXT, never HTML, so a station
 * or user name coming out of the database cannot inject markup.
 */
function createCell(tag, text, className) {
    var el = document.createElement(tag);
    el.textContent = text === null || text === undefined ? "" : String(text);

    if (className) {
        el.className = className;
    }

    return el;
}

/** Replaces a table body with a single full-width message row. */
function showTableMessage(tbody, columns, message) {
    tbody.innerHTML = "";

    var row = document.createElement("tr");
    var cell = createCell("td", message, "table-empty");
    cell.colSpan = columns;

    row.appendChild(cell);
    tbody.appendChild(row);
}

/** Shows a success/error banner inside a container element. */
function showMessage(container, message, isSuccess) {
    if (!container) {
        return;
    }

    container.textContent = message;
    container.className = "message-box " + (isSuccess ? "success-box" : "error-box");
    container.style.display = message ? "block" : "none";
}

function clearMessage(container) {
    if (container) {
        container.textContent = "";
        container.style.display = "none";
    }
}

/** Puts a button into a "working..." state and gives back a reset function. */
function setBusy(button, busyText) {
    if (!button) {
        return function () {};
    }

    var originalText = button.textContent;

    button.disabled = true;
    button.textContent = busyText || "Please wait...";

    return function reset() {
        button.disabled = false;
        button.textContent = originalText;
    };
}

/** 2026-01-05 14:30:00 -> "05 Jan 2026, 02:30 PM" */
function formatDateTime(value) {
    if (!value) {
        return "-";
    }

    // Safari refuses "YYYY-MM-DD HH:MM:SS", so the space becomes a "T".
    var parsed = new Date(String(value).replace(" ", "T"));

    if (isNaN(parsed.getTime())) {
        return value;
    }

    var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                  "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    var hours = parsed.getHours();
    var ampm = hours >= 12 ? "PM" : "AM";
    var hour12 = hours % 12 === 0 ? 12 : hours % 12;

    return pad(parsed.getDate()) + " " + months[parsed.getMonth()] + " " + parsed.getFullYear() +
           ", " + pad(hour12) + ":" + pad(parsed.getMinutes()) + " " + ampm;
}

function pad(number) {
    return number < 10 ? "0" + number : String(number);
}

function formatTaka(amount) {
    return Number(amount || 0).toLocaleString("en-US") + " BDT";
}
