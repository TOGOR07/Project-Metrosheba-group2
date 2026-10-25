<?php
// Shared setup for every AJAX endpoint: one session, one JSON content
// type, and one shape for replies so the JavaScript side can rely on
// { success: bool, message: string, ... } everywhere.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json; charset=UTF-8");

function jsonReply($success, $payload = [], $message = "")
{
    echo json_encode(array_merge(
        ["success" => $success, "message" => $message],
        $payload
    ));
    exit();
}

function jsonFail($message, $httpCode = 400)
{
    http_response_code($httpCode);
    jsonReply(false, [], $message);
}

// Endpoints call this first; it returns the logged-in user's name.
function requireLogin($role = null)
{
    if (empty($_SESSION["username"]) || empty($_SESSION["role"])) {
        jsonFail("Your session has expired. Please log in again.", 401);
    }

    if ($role !== null && $_SESSION["role"] !== $role) {
        jsonFail("You are not allowed to perform this action.", 403);
    }

    return $_SESSION["username"];
}

function requirePost()
{
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        jsonFail("This endpoint only accepts POST requests.", 405);
    }
}
?>
