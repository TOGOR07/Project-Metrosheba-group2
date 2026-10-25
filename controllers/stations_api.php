<?php
require_once("api_bootstrap.php");
require_once("../models/stations.php");

// The browser builds its dropdowns and its live price preview from this,
// so the station order and fare rule only ever live in one place.
jsonReply(true, [
    "stations" => $stations,
    "fare" => [
        "per_station" => FARE_PER_STATION,
        "minimum" => FARE_MINIMUM,
        "max_quantity" => MAX_TICKETS_PER_PURCHASE
    ]
]);
?>
