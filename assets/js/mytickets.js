/*
 * "My Tickets": loads the passenger's own tickets over AJAX, then does
 * the searching and sorting in the browser so filtering feels instant.
 */

var allTickets = [];
var sortState = { column: "id", ascending: false };

document.addEventListener("DOMContentLoaded", function () {
    loadTickets();

    var search = document.getElementById("ticketSearch");

    if (search) {
        search.addEventListener("input", renderTickets);
    }

    var statusFilter = document.getElementById("statusFilter");

    if (statusFilter) {
        statusFilter.addEventListener("change", renderTickets);
    }

    // One listener on the header row covers every sortable column.
    var head = document.getElementById("ticketHead");

    if (head) {
        head.addEventListener("click", function (event) {
            var header = event.target.closest("th.sortable");

            if (header) {
                toggleSort(header.dataset.column);
            }
        });
    }

    // One listener on the table body also covers rows added later,
    // which a listener per button could not do.
    var body = document.getElementById("ticketBody");

    if (body) {
        body.addEventListener("click", function (event) {
            var button = event.target.closest("button[data-cancel-id]");

            if (button) {
                cancelTicket(button.dataset.cancelId, button);
            }
        });
    }
});

function loadTickets() {
    var body = document.getElementById("ticketBody");
    showTableMessage(body, 9, "Loading your tickets...");

    ajaxRequest("GET", "../controllers/mytickets_api.php", null, function (response, ok) {
        if (!ok) {
            showTableMessage(body, 9, response.message || "Could not load your tickets.");
            return;
        }

        allTickets = response.tickets || [];
        updateSummary(response.summary);
        renderTickets();
    });
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

function toggleSort(column) {
    if (sortState.column === column) {
        sortState.ascending = !sortState.ascending;
    } else {
        sortState.column = column;
        sortState.ascending = true;
    }

    renderTickets();
}

function getVisibleTickets() {
    var term = (document.getElementById("ticketSearch").value || "").trim().toLowerCase();
    var status = document.getElementById("statusFilter").value;

    var rows = allTickets.filter(function (ticket) {
        if (status !== "all" && ticket.status !== status) {
            return false;
        }

        if (term === "") {
            return true;
        }

        return (ticket.from_station + " " + ticket.to_station + " #" + ticket.id)
            .toLowerCase()
            .indexOf(term) !== -1;
    });

    var column = sortState.column;
    var direction = sortState.ascending ? 1 : -1;

    rows.sort(function (a, b) {
        var left = a[column];
        var right = b[column];

        // Ids and money sort as numbers; stations and dates as text.
        if (!isNaN(parseFloat(left)) && !isNaN(parseFloat(right))) {
            return (parseFloat(left) - parseFloat(right)) * direction;
        }

        return String(left).localeCompare(String(right)) * direction;
    });

    return rows;
}

function renderTickets() {
    var body = document.getElementById("ticketBody");
    var rows = getVisibleTickets();

    markSortedHeader();

    if (rows.length === 0) {
        showTableMessage(body, 9, allTickets.length === 0
            ? "You have not bought any tickets yet."
            : "No tickets match your search.");
        setText("resultCount", "0 of " + allTickets.length);
        return;
    }

    body.innerHTML = "";

    rows.forEach(function (ticket) {
        body.appendChild(buildTicketRow(ticket));
    });

    setText("resultCount", rows.length + " of " + allTickets.length);
}

function buildTicketRow(ticket) {
    var row = document.createElement("tr");

    row.appendChild(createCell("td", "#" + ticket.id));
    row.appendChild(createCell("td", ticket.from_station));
    row.appendChild(createCell("td", ticket.to_station));
    row.appendChild(createCell("td", ticket.quantity));
    row.appendChild(createCell("td", formatTaka(ticket.per_price)));
    row.appendChild(createCell("td", formatTaka(ticket.total_price)));
    row.appendChild(createCell("td", formatDateTime(ticket.purchase_date)));

    var statusCell = document.createElement("td");
    var badge = createCell(
        "span",
        ticket.status === "active" ? "Active" : "Cancelled",
        "badge " + (ticket.status === "active" ? "badge-active" : "badge-cancelled")
    );
    statusCell.appendChild(badge);
    row.appendChild(statusCell);

    var actionCell = document.createElement("td");

    if (ticket.status === "active") {
        var button = createCell("button", "Cancel", "btn btn-danger btn-small");
        button.type = "button";
        button.dataset.cancelId = ticket.id;
        actionCell.appendChild(button);
    } else {
        actionCell.appendChild(createCell("span", "-"));
    }

    row.appendChild(actionCell);

    return row;
}

function markSortedHeader() {
    var headers = document.querySelectorAll("#ticketHead th.sortable");

    for (var i = 0; i < headers.length; i++) {
        headers[i].classList.remove("sort-asc", "sort-desc");

        if (headers[i].dataset.column === sortState.column) {
            headers[i].classList.add(sortState.ascending ? "sort-asc" : "sort-desc");
        }
    }
}

function cancelTicket(ticketId, button) {
    if (!window.confirm("Cancel ticket #" + ticketId + "? This cannot be undone.")) {
        return;
    }

    var messageBox = document.getElementById("pageMessage");
    var reset = setBusy(button, "...");

    ajaxRequest(
        "POST",
        "../controllers/mytickets_api.php",
        encodeParams({ action: "cancel", ticket_id: ticketId }),
        function (response, ok) {
            reset();

            if (!ok) {
                showMessage(messageBox, response.message || "Could not cancel the ticket.", false);
                return;
            }

            // Update the row we already hold instead of refetching.
            allTickets.forEach(function (ticket) {
                if (String(ticket.id) === String(ticketId)) {
                    ticket.status = "cancelled";
                }
            });

            updateSummary(response.summary);
            renderTickets();
            showMessage(messageBox, response.message, true);
        }
    );
}
