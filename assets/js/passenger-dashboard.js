/*
 * Passenger dashboard: builds the station dropdowns and the route map
 * from data fetched over AJAX, previews the fare live, and buys the
 * ticket without ever reloading the page.
 */

var stations = [];
var fareRules = { per_station: 10, minimum: 20, max_quantity: 10 };

document.addEventListener("DOMContentLoaded", function () {
    loadStations();

    var form = document.getElementById("ticketForm");

    if (form) {
        form.addEventListener("submit", handlePurchase);
    }

    ["fromStation", "toStation", "quantity"].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) {
            el.addEventListener("change", updatePricePreview);
            el.addEventListener("input", updatePricePreview);
        }
    });
});

/** Asks PHP for the station list so the page and the server agree. */
function loadStations() {
    ajaxRequest("GET", "../controllers/stations_api.php", null, function (response, ok) {
        if (!ok) {
            showMessage(
                document.getElementById("formMessage"),
                response.message || "Could not load the station list.",
                false
            );
            return;
        }

        stations = response.stations;
        fareRules = response.fare;

        buildStationOptions();
        buildRouteMap();
        updatePricePreview();
    });
}

function buildStationOptions() {
    var fromSelect = document.getElementById("fromStation");
    var toSelect = document.getElementById("toStation");

    stations.forEach(function (station) {
        fromSelect.add(new Option(station, station));
        toSelect.add(new Option(station, station));
    });

    var quantity = document.getElementById("quantity");

    if (quantity) {
        quantity.max = fareRules.max_quantity;
    }
}

function buildRouteMap() {
    var mapList = document.getElementById("mapList");
    mapList.innerHTML = "";

    stations.forEach(function (station) {
        var item = createCell("li", station);
        item.dataset.station = station;
        mapList.appendChild(item);
    });
}

/**
 * Highlights every stop between the two chosen stations, so the
 * passenger can see the leg they are paying for.
 */
function highlightRoute(fromIndex, toIndex) {
    var items = document.querySelectorAll("#mapList li");
    var start = Math.min(fromIndex, toIndex);
    var end = Math.max(fromIndex, toIndex);

    for (var i = 0; i < items.length; i++) {
        var onRoute = fromIndex !== -1 && toIndex !== -1 && i >= start && i <= end;
        items[i].classList.toggle("leg-station", onRoute);
    }
}

/** Mirrors calculateFare() in models/stations.php. */
function calculateFare(fromStation, toStation) {
    var fromIndex = stations.indexOf(fromStation);
    var toIndex = stations.indexOf(toStation);

    if (fromIndex === -1 || toIndex === -1 || fromIndex === toIndex) {
        return 0;
    }

    var fare = Math.abs(toIndex - fromIndex) * fareRules.per_station;

    return fare < fareRules.minimum ? fareRules.minimum : fare;
}

function updatePricePreview() {
    var fromStation = document.getElementById("fromStation").value;
    var toStation = document.getElementById("toStation").value;
    var quantity = parseInt(document.getElementById("quantity").value, 10);

    if (isNaN(quantity) || quantity < 1) {
        quantity = 0;
    }

    var perPrice = calculateFare(fromStation, toStation);

    document.getElementById("perPrice").value = formatTaka(perPrice);
    document.getElementById("totalPrice").value = formatTaka(perPrice * quantity);

    highlightRoute(stations.indexOf(fromStation), stations.indexOf(toStation));
}

/* ---------- validation ---------- */

function validateTicketForm() {
    var fromStation = document.getElementById("fromStation").value;
    var toStation = document.getElementById("toStation").value;
    var quantityValue = document.getElementById("quantity").value;
    var quantity = parseInt(quantityValue, 10);

    var valid = true;

    if (fromStation === "" || toStation === "") {
        setFieldError("toStation", "Please select both stations.");
        valid = false;
    } else if (fromStation === toStation) {
        setFieldError("toStation", "From and To station cannot be the same.");
        valid = false;
    } else {
        clearFieldError("toStation");
    }

    if (quantityValue === "" || isNaN(quantity) || quantity < 1 || quantity > fareRules.max_quantity) {
        setFieldError("quantity", "Quantity must be between 1 and " + fareRules.max_quantity + ".");
        valid = false;
    } else {
        clearFieldError("quantity");
    }

    return valid;
}

/* ---------- purchase ---------- */

function handlePurchase(event) {
    event.preventDefault();

    var messageBox = document.getElementById("formMessage");
    clearMessage(messageBox);

    if (!validateTicketForm()) {
        return;
    }

    var button = document.getElementById("btnConfirm");
    var reset = setBusy(button, "Purchasing...");

    var payload = encodeParams({
        from_station: document.getElementById("fromStation").value,
        to_station: document.getElementById("toStation").value,
        quantity: document.getElementById("quantity").value
    });

    ajaxRequest("POST", "../controllers/purchase_ticket_api.php", payload, function (response, ok) {
        reset();

        if (!ok) {
            showMessage(messageBox, response.message || "Purchase failed.", false);

            if (response.errors) {
                for (var field in response.errors) {
                    if (Object.prototype.hasOwnProperty.call(response.errors, field)) {
                        setFieldError(field === "station" ? "toStation" : field, response.errors[field]);
                    }
                }
            }

            return;
        }

        showMessage(messageBox, response.message, true);
        renderReceipt(response.ticket);
        updateSummary(response.summary);
        resetForm();
    });
}

function resetForm() {
    document.getElementById("fromStation").value = "";
    document.getElementById("toStation").value = "";
    document.getElementById("quantity").value = 1;
    updatePricePreview();
}

function updateSummary(summary) {
    if (!summary) {
        return;
    }

    setText("statOrders", summary.total_orders);
    setText("statTickets", summary.total_tickets);
    setText("statSpent", formatTaka(summary.total_spent));
}

function setText(id, value) {
    var el = document.getElementById(id);

    if (el) {
        el.textContent = value;
    }
}

/** Builds the receipt out of DOM nodes rather than an HTML string. */
function renderReceipt(ticket) {
    var holder = document.getElementById("receiptArea");
    holder.innerHTML = "";

    var box = document.createElement("div");
    box.className = "receipt";
    box.appendChild(createCell("h4", "Ticket #" + ticket.id + " confirmed"));

    var list = document.createElement("dl");

    var rows = [
        ["Passenger", ticket.username],
        ["From", ticket.from_station],
        ["To", ticket.to_station],
        ["Quantity", ticket.quantity],
        ["Per ticket", formatTaka(ticket.per_price)],
        ["Total paid", formatTaka(ticket.total_price)],
        ["Purchased", formatDateTime(ticket.purchase_date)]
    ];

    rows.forEach(function (row) {
        list.appendChild(createCell("dt", row[0]));
        list.appendChild(createCell("dd", row[1]));
    });

    box.appendChild(list);

    var printBtn = createCell("button", "Print Ticket", "btn btn-dark btn-small");
    printBtn.type = "button";
    printBtn.addEventListener("click", function () {
        window.print();
    });

    box.appendChild(printBtn);
    holder.appendChild(box);

    box.scrollIntoView({ behavior: "smooth", block: "nearest" });
}
