<?php
// Single source of truth for the MRT Line-6 stops and the fare rule.
// controllers/stations_api.php hands this same list to the browser so the
// price a passenger is shown is the price the server charges.

$stations = [
    "Uttara North", "Uttara Center", "Uttara South",
    "Pallabi", "Mirpur 11", "Mirpur 10",
    "Kazipara", "Shewrapara", "Agargaon",
    "Bijoy Sarani", "Farmgate", "Karwan Bazar",
    "Shahbag", "Dhaka University", "Motijheel"
];

define("FARE_PER_STATION", 10);
define("FARE_MINIMUM", 20);
define("MAX_TICKETS_PER_PURCHASE", 10);

// Returns the per-ticket fare, or 0 when either stop is unknown or the
// two stops are the same.
function calculateFare($fromStation, $toStation)
{
    global $stations;

    $fromIndex = array_search($fromStation, $stations, true);
    $toIndex = array_search($toStation, $stations, true);

    if ($fromIndex === false || $toIndex === false || $fromIndex === $toIndex) {
        return 0;
    }

    $stationsPassed = abs($toIndex - $fromIndex);
    $fare = $stationsPassed * FARE_PER_STATION;

    return $fare < FARE_MINIMUM ? FARE_MINIMUM : $fare;
}

function isValidStation($station)
{
    global $stations;
    return in_array($station, $stations, true);
}
?>
