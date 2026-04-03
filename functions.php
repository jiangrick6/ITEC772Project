<?php
/**
 * Format a date for display
 * @param string $date Date string in YYYY-MM-DD format
 * @return string Formatted date string
 */
function formatDate($date) {
    if (empty($date) || $date == '0000-00-00') {
        return 'N/A';
    }
    return date('M j, Y', strtotime($date));
}

/**
 * Calculate age from date of birth
 * @param string $dob Date of birth in YYYY-MM-DD format
 * @return int Age in years
 */
function calculateAge($dob) {
    if (empty($dob) || $dob == '0000-00-00') {
        return 0;
    }
    $birthDate = new DateTime($dob);
    $today = new DateTime('today');
    return $birthDate->diff($today)->y;
}


// Get all teams for dropdown
function getTeams() {
    global $mysqli;
    $result = $mysqli->query("SELECT team_id, team_name, city FROM team ORDER BY team_name");
    $teams = [];
    while ($row = $result->fetch_assoc()) {
        $teams[] = $row;
    }
    return $teams;
}

// Get all positions for dropdown
function getPositions() {
    global $mysqli;
    $result = $mysqli->query("SELECT position_id, position_name FROM position ORDER BY position_name");
    $positions = [];
    while ($row = $result->fetch_assoc()) {
        $positions[] = $row;
    }
    return $positions;
}

// Get all divisions for dropdown
function getDivisions() {
    global $mysqli;
    $result = $mysqli->query("SELECT division_id, division_name FROM division ORDER BY division_name");
    $divisions = [];
    while ($row = $result->fetch_assoc()) {
        $divisions[] = $row;
    }
    return $divisions;
}

// Get all stadiums for dropdown
function getStadiums() {
    global $mysqli;
    $result = $mysqli->query("SELECT stadium_id, stadium_name FROM stadium ORDER BY stadium_name");
    $stadiums = [];
    while ($row = $result->fetch_assoc()) {
        $stadiums[] = $row;
    }
    return $stadiums;
}

// Get current page for navigation highlighting
function getCurrentPage() {
    return basename($_SERVER['PHP_SELF']);
}
?>