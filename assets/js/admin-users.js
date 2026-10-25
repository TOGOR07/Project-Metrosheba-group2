/*
 * Admin dashboard: lists customers over AJAX and edits or deletes them
 * in place. Nothing here navigates away, so a delete can no longer be
 * triggered by following a plain link.
 */

var allUsers = [];
var editingId = null;

document.addEventListener("DOMContentLoaded", function () {
    loadUsers();

    var search = document.getElementById("userSearch");

    if (search) {
        search.addEventListener("input", renderUsers);
    }

    var body = document.getElementById("userBody");

    if (body) {
        body.addEventListener("click", function (event) {
            var editButton = event.target.closest("button[data-edit-id]");

            if (editButton) {
                openEditor(editButton.dataset.editId);
                return;
            }

            var deleteButton = event.target.closest("button[data-delete-id]");

            if (deleteButton) {
                deleteUser(deleteButton.dataset.deleteId, deleteButton);
            }
        });
    }

    var form = document.getElementById("editForm");

    if (form) {
        form.addEventListener("submit", saveUser);
    }

    var cancel = document.getElementById("btnCancelEdit");

    if (cancel) {
        cancel.addEventListener("click", closeEditor);
    }
});

function loadUsers() {
    var body = document.getElementById("userBody");
    showTableMessage(body, 7, "Loading customers...");

    ajaxRequest("GET", "../controllers/admin_users_api.php", null, function (response, ok) {
        if (!ok) {
            showTableMessage(body, 7, response.message || "Could not load customers.");
            return;
        }

        allUsers = response.users || [];
        updateStats(response.stats);
        renderUsers();
    });
}

function updateStats(stats) {
    if (!stats) {
        return;
    }

    setText("statTickets", stats.total_tickets);
    setText("statProfit", formatTaka(stats.total_profit));
    setText("statCustomers", allUsers.length);
}

function setText(id, value) {
    var el = document.getElementById(id);

    if (el) {
        el.textContent = value;
    }
}

function getVisibleUsers() {
    var term = (document.getElementById("userSearch").value || "").trim().toLowerCase();

    if (term === "") {
        return allUsers;
    }

    return allUsers.filter(function (user) {
        return (user.name + " " + user.email + " " + user.mobile_number + " " + user.nid)
            .toLowerCase()
            .indexOf(term) !== -1;
    });
}

function renderUsers() {
    var body = document.getElementById("userBody");
    var rows = getVisibleUsers();

    if (rows.length === 0) {
        showTableMessage(body, 7, allUsers.length === 0
            ? "No customers registered yet."
            : "No customer matches your search.");
        setText("resultCount", "0 of " + allUsers.length);
        return;
    }

    body.innerHTML = "";

    rows.forEach(function (user) {
        body.appendChild(buildUserRow(user));
    });

    setText("resultCount", rows.length + " of " + allUsers.length);
}

function buildUserRow(user) {
    var row = document.createElement("tr");
    row.dataset.userId = user.id;

    row.appendChild(createCell("td", "#" + user.id));
    row.appendChild(createCell("td", user.name));
    row.appendChild(createCell("td", user.email));
    row.appendChild(createCell("td", user.mobile_number));
    row.appendChild(createCell("td", user.nid));
    row.appendChild(createCell("td", user.gender));

    var actions = document.createElement("td");
    var wrapper = document.createElement("div");
    wrapper.className = "row-actions";

    var edit = createCell("button", "Edit", "btn btn-primary btn-small");
    edit.type = "button";
    edit.dataset.editId = user.id;

    var remove = createCell("button", "Delete", "btn btn-danger btn-small");
    remove.type = "button";
    remove.dataset.deleteId = user.id;

    wrapper.appendChild(edit);
    wrapper.appendChild(remove);
    actions.appendChild(wrapper);
    row.appendChild(actions);

    return row;
}

/* ---------- edit ---------- */

function findUser(id) {
    for (var i = 0; i < allUsers.length; i++) {
        if (String(allUsers[i].id) === String(id)) {
            return allUsers[i];
        }
    }

    return null;
}

function openEditor(id) {
    var user = findUser(id);

    if (!user) {
        return;
    }

    editingId = user.id;

    document.getElementById("edit_id").value = user.id;
    document.getElementById("name").value = user.name;
    document.getElementById("email").value = user.email;
    document.getElementById("mobile_number").value = user.mobile_number;
    document.getElementById("nid").value = user.nid;
    document.getElementById("gender").value = user.gender;

    ["name", "email", "mobile_number", "nid", "gender"].forEach(clearFieldError);
    clearMessage(document.getElementById("editMessage"));

    var panel = document.getElementById("editPanel");
    panel.style.display = "block";
    panel.scrollIntoView({ behavior: "smooth", block: "nearest" });
}

function closeEditor() {
    editingId = null;
    document.getElementById("editPanel").style.display = "none";
}

function validateEditForm() {
    return runAll([
        function () { return validateFullName("name"); },
        function () { return validateEmail("email"); },
        function () { return validateMobile("mobile_number"); },
        function () { return validateNid("nid"); },
        function () { return validateSelect("gender", "gender"); }
    ]);
}

function saveUser(event) {
    event.preventDefault();

    var messageBox = document.getElementById("editMessage");
    clearMessage(messageBox);

    if (!validateEditForm()) {
        return;
    }

    var button = document.getElementById("btnSaveUser");
    var reset = setBusy(button, "Saving...");

    var payload = encodeParams({
        action: "update",
        id: editingId,
        name: fieldValue("name"),
        email: fieldValue("email"),
        mobile_number: fieldValue("mobile_number"),
        nid: fieldValue("nid"),
        gender: fieldValue("gender")
    });

    ajaxRequest("POST", "../controllers/admin_users_api.php", payload, function (response, ok) {
        reset();

        if (!ok) {
            showMessage(messageBox, response.message || "Update failed.", false);

            if (response.errors) {
                for (var field in response.errors) {
                    if (Object.prototype.hasOwnProperty.call(response.errors, field)) {
                        setFieldError(field, response.errors[field]);
                    }
                }
            }

            return;
        }

        // Swap the updated record into the list we already hold.
        for (var i = 0; i < allUsers.length; i++) {
            if (String(allUsers[i].id) === String(response.user.id)) {
                allUsers[i] = response.user;
                break;
            }
        }

        renderUsers();
        closeEditor();
        showMessage(document.getElementById("pageMessage"), response.message, true);
    });
}

/* ---------- delete ---------- */

function deleteUser(id, button) {
    var user = findUser(id);
    var label = user ? user.name : "this customer";

    if (!window.confirm("Delete " + label + "? This cannot be undone.")) {
        return;
    }

    var reset = setBusy(button, "...");

    ajaxRequest(
        "POST",
        "../controllers/admin_users_api.php",
        encodeParams({ action: "delete", id: id }),
        function (response, ok) {
            reset();

            if (!ok) {
                showMessage(document.getElementById("pageMessage"),
                    response.message || "Delete failed.", false);
                return;
            }

            allUsers = allUsers.filter(function (item) {
                return String(item.id) !== String(id);
            });

            if (String(editingId) === String(id)) {
                closeEditor();
            }

            updateStats(response.stats);
            renderUsers();
            showMessage(document.getElementById("pageMessage"), response.message, true);
        }
    );
}
