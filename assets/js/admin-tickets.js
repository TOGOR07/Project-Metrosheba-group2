/*
 * Admin ticket sales page: AJAX stats and table, server-side search,
 * client-side sorting, a CSV export built in the browser, and a small
 * bar chart of the busiest routes drawn with plain DOM nodes.
 */

var ticketRows = [];
var searchTimer = null;
var ticketSort = { column: "id", ascending: false };

document.addEventListener("DOMContentLoaded", function () {
    loadTickets("");

    var search = document.getElementById("ticketSearch");

    if (search) {
        // Waiting out the typing keeps one request per pause instead of
        // one per keystroke.
        search.addEventListener("input", function () {
            window.clearTimeout(searchTimer);
            searchTimer = window.setTimeout(function () {
                loadTickets(search.value.trim());
            }, 300);
        });
    }

    var head = document.getElementById("ticketHead");

    if (head) {
        head.addEventListener("click", function (event) {
            var header = event.target.closest("th.sortable");

            if (header) {
                if (ticketSort.column === header.dataset.column) {
                    ticketSort.ascending = !ticketSort.ascending;
                } else {
                    ticketSort.column = header.dataset.column;
                    ticketSort.ascending = true;
                }

                renderTable();
            }
        });
    }

    var exportBtn = document.getElementById("btnExport");

    if (exportBtn) {
        exportBtn.addEventListener("click", exportCsv);
    }

    var refreshBtn = document.getElementById("btnRefresh");

    if (refreshBtn) {
        refreshBtn.addEventListener("click", function () {
            var term = document.getElementById("ticketSearch");
            loadTickets(term ? term.value.trim() : "");
        });
    }
});

function loadTickets(search) {
    var body = document.getElementById("ticketBody");
    showTableMessage(body, 9, "Loading tickets...");

    var url = "../controllers/ticketinfo_api.php?search=" + encodeURIComponent(search);

    ajaxRequest("GET", url, null, function (response, ok) {
        if (!ok) {
            showTableMessage(body, 9, response.message || "Could not load ticket data.");
            return;
        }

        ticketRows = response.tickets || [];

        updateStats(response.stats);
        renderRoutes(response.routes || []);
        renderTable();
    });
}

function updateStats(stats) {
    if (!stats) {
        return;
    }

    setText("totalCustomers", stats.total_customers);
    setText("totalTickets", stats.total_tickets);
    setText("totalRevenue", formatTaka(stats.total_revenue));
}

function setText(id, value) {
    var el = document.getElementById(id);

    if (el) {
        el.textContent = value;
    }
}

function sortedRows() {
    var rows = ticketRows.slice();
    var column = ticketSort.column;
    var direction = ticketSort.ascending ? 1 : -1;

    rows.sort(function (a, b) {
        var left = a[column];
        var right = b[column];

        if (!isNaN(parseFloat(left)) && !isNaN(parseFloat(right))) {
            return (parseFloat(left) - parseFloat(right)) * direction;
        }

        return String(left).localeCompare(String(right)) * direction;
    });

    return rows;
}

function renderTable() {
    var body = document.getElementById("ticketBody");
    var rows = sortedRows();

    markSortedHeader();
    setText("resultCount", rows.length + " ticket order" + (rows.length === 1 ? "" : "s"));

    if (rows.length === 0) {
        showTableMessage(body, 9, "No ticket sales found.");
        return;
    }

    body.innerHTML = "";

    rows.forEach(function (ticket) {
        var row = document.createElement("tr");

        row.appendChild(createCell("td", "#" + ticket.id));
        row.appendChild(createCell("td", ticket.username));
        row.appendChild(createCell("td", ticket.from_station));
        row.appendChild(createCell("td", ticket.to_station));
        row.appendChild(createCell("td", ticket.quantity));
        row.appendChild(createCell("td", formatTaka(ticket.per_price)));
        row.appendChild(createCell("td", formatTaka(ticket.total_price)));
        row.appendChild(createCell("td", formatDateTime(ticket.purchase_date)));

        var statusCell = document.createElement("td");
        statusCell.appendChild(createCell(
            "span",
            ticket.status === "active" ? "Active" : "Cancelled",
            "badge " + (ticket.status === "active" ? "badge-active" : "badge-cancelled")
        ));
        row.appendChild(statusCell);

        body.appendChild(row);
    });
}

function markSortedHeader() {
    var headers = document.querySelectorAll("#ticketHead th.sortable");

    for (var i = 0; i < headers.length; i++) {
        headers[i].classList.remove("sort-asc", "sort-desc");

        if (headers[i].dataset.column === ticketSort.column) {
            headers[i].classList.add(ticketSort.ascending ? "sort-asc" : "sort-desc");
        }
    }
}

/** Horizontal bars sized against the busiest route. */
function renderRoutes(routes) {
    var list = document.getElementById("routeChart");

    if (!list) {
        return;
    }

    list.innerHTML = "";

    if (routes.length === 0) {
        list.appendChild(createCell("li", "No route data yet.", "table-empty"));
        return;
    }

    var busiest = Number(routes[0].sold) || 1;

    routes.forEach(function (route) {
        var item = document.createElement("li");

        var meta = document.createElement("div");
        meta.className = "route-meta";
        meta.appendChild(createCell("span", route.from_station + " → " + route.to_station));
        meta.appendChild(createCell("strong", route.sold + " sold"));

        var track = document.createElement("div");
        track.className = "route-bar-track";

        var fill = document.createElement("div");
        fill.className = "route-bar-fill";
        fill.style.width = Math.round((Number(route.sold) / busiest) * 100) + "%";

        track.appendChild(fill);
        item.appendChild(meta);
        item.appendChild(track);
        list.appendChild(item);
    });
}

/* ---------- CSV export ---------- */

function exportCsv() {
    if (ticketRows.length === 0) {
        window.alert("There is nothing to export.");
        return;
    }

    var headers = ["ID", "Passenger", "From", "To", "Quantity",
                   "Per Price", "Total Price", "Purchase Date", "Status"];

    var lines = [headers.map(csvCell).join(",")];

    sortedRows().forEach(function (ticket) {
        lines.push([
            ticket.id,
            ticket.username,
            ticket.from_station,
            ticket.to_station,
            ticket.quantity,
            ticket.per_price,
            ticket.total_price,
            ticket.purchase_date,
            ticket.status
        ].map(csvCell).join(","));
    });

    // The BOM keeps Excel from mangling the Bangla station names.
    var blob = new Blob(["﻿" + lines.join("\r\n")], { type: "text/csv;charset=utf-8;" });
    var url = URL.createObjectURL(blob);

    var link = document.createElement("a");
    link.href = url;
    link.download = "metrosheba-ticket-sales.csv";

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    URL.revokeObjectURL(url);
}

function csvCell(value) {
    var text = value === null || value === undefined ? "" : String(value);
    return '"' + text.replace(/"/g, '""') + '"';
}
