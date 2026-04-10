<?php
session_start();

if (!isset($_SESSION['valid']) || !$_SESSION['valid']) {
    header("Location: login.php");
    exit();
}

include_once("connection.php");

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function getCount($mysqli, $table) {
    $result = mysqli_query($mysqli, "SELECT COUNT(*) AS count FROM `$table`");
    if (!$result) {
        return 0;
    }

    $row = mysqli_fetch_assoc($result);
    return (int)($row['count'] ?? 0);
}

$teamsCount = getCount($mysqli, 'team');
$playersCount = getCount($mysqli, 'player');
$coachesCount = getCount($mysqli, 'coach');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NFL Database Dashboard</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="page-wrap">

        <div class="topbar card">
            <div class="welcome-block">
                <p class="eyebrow">NFL Database Management System</p>
                <h2>Welcome, <?php echo e($_SESSION['username']); ?></h2>
                <p class="subtitle">Manage your teams, players, coaches, and reports from one clean dashboard.</p>
            </div>

            <div class="topbar-actions">
                <a class="btn btn-secondary" href="reports.php">View Reports</a>
                <a class="btn btn-danger" href="logout.php">Logout</a>
            </div>
        </div>

        <div class="card hero">
            <div class="hero-copy">
                <p class="eyebrow">Dashboard</p>
                <h1 class="dashboard-title">NFL Database Dashboard</h1>
                <p class="hero-text">
                    A central place to manage your NFL data with quick access to teams, players, coaches, and reports.
                </p>
            </div>
        </div>

        <div class="stats-grid">
            <div class="card stat-card">
                <div>
                    <div class="stat-number"><?php echo e($teamsCount); ?></div>
                    <div class="stat-label">Teams</div>
                    <p class="stat-copy">View and manage team records, divisions, and stadiums.</p>
                </div>
                <a class="btn btn-secondary" href="teams.php">Manage Teams</a>
            </div>

            <div class="card stat-card">
                <div>
                    <div class="stat-number"><?php echo e($playersCount); ?></div>
                    <div class="stat-label">Players</div>
                    <p class="stat-copy">Track player details, positions, jersey numbers, and teams.</p>
                </div>
                <a class="btn btn-secondary" href="players.php">Manage Players</a>
            </div>

            <div class="card stat-card">
                <div>
                    <div class="stat-number"><?php echo e($coachesCount); ?></div>
                    <div class="stat-label">Coaches</div>
                    <p class="stat-copy">Keep coaching staff records organized and easy to update.</p>
                </div>
                <a class="btn btn-secondary" href="coaches.php">Manage Coaches</a>
            </div>
        </div>

        <div class="card">
            <h2>Quick Actions</h2>
            <div class="quick-actions">
                <a class="action-link" href="add_team.php">
                    <strong>Add Team</strong>
                    <span>Create a new team record with division and stadium info.</span>
                </a>

                <a class="action-link" href="add_player.php">
                    <strong>Add Player</strong>
                    <span>Add a player with position, team, and physical details.</span>
                </a>

                <a class="action-link" href="add_coach.php">
                    <strong>Add Coach</strong>
                    <span>Create a coach profile with experience and birth date.</span>
                </a>

                <a class="action-link" href="reports.php">
                    <strong>Run Reports</strong>
                    <span>Open the reports page to view database summaries and queries.</span>
                </a>
            </div>
        </div>

        <div class="card">
            <h2>CRUD Shortcuts</h2>
            <div class="report-actions">
                <a class="btn btn-primary" href="teams.php">View Teams</a>
                <a class="btn btn-secondary" href="add_team.php">Add Team</a>
                <a class="btn btn-primary" href="players.php">View Players</a>
                <a class="btn btn-secondary" href="add_player.php">Add Player</a>
                <a class="btn btn-primary" href="coaches.php">View Coaches</a>
                <a class="btn btn-secondary" href="add_coach.php">Add Coach</a>
            </div>
        </div>

        <div class="mini-footer">
            Built for your NFL database project
        </div>
    </div>
</body>
</html>
