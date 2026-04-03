<?php
session_start();

if (!isset($_SESSION['valid']) || !$_SESSION['valid']) {
    header("Location: login.php");
    exit();
}

include_once("connection.php");
include_once("functions.php");

function tableExists($conn, $tableName) {
    $tableName = mysqli_real_escape_string($conn, $tableName);
    $result = mysqli_query($conn, "SHOW TABLES LIKE '$tableName'");
    return ($result && mysqli_num_rows($result) > 0);
}

function requireTables($conn, $tables) {
    $missing = [];
    foreach ($tables as $table) {
        if (!tableExists($conn, $table)) {
            $missing[] = $table;
        }
    }
    return $missing;
}

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$run = isset($_GET['run']) ? $_GET['run'] : '';

function shouldRun($section) {
    global $run;
    return $run === 'all' || $run === $section;
}
?>

<html>
<head>
    <title>NFL Reports</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="page-wrap">
        <div class="report-header">
            <div>
                <h2>NFL Database Reports</h2>
                <p>Run one section at a time, or choose "Run All Reports" for full output.</p>
            </div>
            <div class="report-actions">
                <a href="index.php" class="btn btn-secondary">Home</a>
                <form method="get" style="margin:0;">
                    <input type="hidden" name="run" value="all">
                    <input type="submit" value="Run All Reports">
                </form>
            </div>
        </div>

    <!-- 1. Team Standings -->
    <h3>1. Team Standings</h3>
    <p>Will calculate wins/losses/ties per team based on games and results.</p>
    <form method="get" style="margin-bottom:10px;">
        <input type="hidden" name="run" value="team">
        <input type="submit" value="Run Team Standings">
    </form>
    <table width='100%' border=1>
        <tr bgcolor='#CCCCCC'>
            <td>Team</td>
            <td>Division</td>
            <td>Wins</td>
            <td>Losses</td>
            <td>Ties</td>
            <td>Win %</td>
        </tr>
        <?php
        if (!shouldRun('team')) {
            echo "<tr><td colspan='6' style='color:blue;text-align:center;'>Press the 'Run Team Standings' button above to execute this query.</td></tr>";
        } else {
            $missing = requireTables($mysqli, ['team', 'division', 'game', 'gameresult']);
            if (!empty($missing)) {
                echo "<tr><td colspan='6' style='color:red;text-align:center;'>Missing table(s): " . h(implode(', ', $missing)) . "</td></tr>";
            } else {
                $result = mysqli_query($mysqli, "
                    SELECT t.team_name, d.division_name,
                           COUNT(CASE WHEN gr.home_score > gr.away_score AND g.home_team_id = t.team_id THEN 1 END) +
                           COUNT(CASE WHEN gr.away_score > gr.home_score AND g.away_team_id = t.team_id THEN 1 END) AS wins,
                           COUNT(CASE WHEN gr.home_score < gr.away_score AND g.home_team_id = t.team_id THEN 1 END) +
                           COUNT(CASE WHEN gr.away_score < gr.home_score AND g.away_team_id = t.team_id THEN 1 END) AS losses,
                           COUNT(CASE WHEN gr.home_score = gr.away_score AND (g.home_team_id = t.team_id OR g.away_team_id = t.team_id) THEN 1 END) AS ties
                    FROM team t
                    JOIN division d ON t.division_id = d.division_id
                    LEFT JOIN game g ON (g.home_team_id = t.team_id OR g.away_team_id = t.team_id)
                    LEFT JOIN gameresult gr ON gr.game_id = g.game_id
                    GROUP BY t.team_id, t.team_name, d.division_name
                    ORDER BY wins DESC, losses ASC
                ");
                while ($res = mysqli_fetch_array($result)) {
                    $total_games = $res['wins'] + $res['losses'] + $res['ties'];
                    $win_pct = $total_games > 0 ? round(($res['wins'] / $total_games) * 100, 1) : 0;
                    echo "<tr>";
                    echo "<td>" . h($res['team_name']) . "</td>";
                    echo "<td>" . h($res['division_name']) . "</td>";
                    echo "<td>" . h($res['wins']) . "</td>";
                    echo "<td>" . h($res['losses']) . "</td>";
                    echo "<td>" . h($res['ties']) . "</td>";
                    echo "<td>" . h($win_pct) . "%</td>";
                    echo "</tr>";
                }
            }
        }
        ?>
    </table>

    <!-- 2. Specific Players -->
    <h3>2. Quarterbacks on the Cardinals</h3>
    <p>Shows only quarterbacks on one team instead of every player.</p>
    <form method="get" style="margin-bottom:10px;">
        <input type="hidden" name="run" value="qb">
        <input type="submit" value="Run Quarterbacks Query">
    </form>
    <table width='100%' border=1>
        <tr bgcolor='#CCCCCC'>
            <td>Player</td>
            <td>Team</td>
            <td>Position</td>
            <td>Jersey #</td>
            <td>College</td>
        </tr>
        <?php
        if (!shouldRun('qb')) {
            echo "<tr><td colspan='5' style='color:blue;text-align:center;'>Press the 'Run Quarterbacks Query' button above to execute this query.</td></tr>";
        } else {
            $missing = requireTables($mysqli, ['player', 'team', 'position']);
            if (!empty($missing)) {
                echo "<tr><td colspan='5' style='color:red;text-align:center;'>Missing table(s): " . h(implode(', ', $missing)) . "</td></tr>";
            } else {
                $result = mysqli_query($mysqli, "
                    SELECT p.first_name, p.last_name, t.team_name, pos.position_name, p.jersey_number, p.college
                    FROM player p
                    JOIN position pos ON p.position_id = pos.position_id
                    JOIN team t ON p.current_team_abbreviation = t.abbreviation
                    WHERE pos.position_name = 'Quarterback'
                      AND t.team_name = 'Cardinals'
                    ORDER BY p.last_name, p.first_name
                ");
                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>" . h($row['first_name'] . ' ' . $row['last_name']) . "</td>";
                    echo "<td>" . h($row['team_name']) . "</td>";
                    echo "<td>" . h($row['position_name']) . "</td>";
                    echo "<td>" . h($row['jersey_number']) . "</td>";
                    echo "<td>" . h($row['college']) . "</td>";
                    echo "</tr>";
                }
            }
        }
        ?>
    </table>

    <!-- 3. Head Coaches -->
    <h3>3. Head Coaches</h3>
    <p>Shows the head coach assigned to each team.</p>
    <form method="get" style="margin-bottom:10px;">
        <input type="hidden" name="run" value="coaches">
        <input type="submit" value="Run Coaches Report">
    </form>
    <table width='100%' border=1>
        <tr bgcolor='#CCCCCC'>
            <td>Team</td>
            <td>Coach</td>
            <td>Role</td>
            <td>Years Experience</td>
        </tr>
        <?php
        if (!shouldRun('coaches')) {
            echo "<tr><td colspan='4' style='color:blue;text-align:center;'>Press the 'Run Coaches Report' button above to execute this query.</td></tr>";
        } else {
            $missing = requireTables($mysqli, ['coachassignment', 'coach', 'team']);
            if (!empty($missing)) {
                echo "<tr><td colspan='4' style='color:red;text-align:center;'>Missing table(s): " . h(implode(', ', $missing)) . "</td></tr>";
            } else {
                $result = mysqli_query($mysqli, "
                    SELECT t.team_name, c.first_name, c.last_name, ca.role, c.years_experience
                    FROM coachassignment ca
                    JOIN coach c ON ca.coach_id = c.coach_id
                    JOIN team t ON ca.team_id = t.team_id
                    WHERE ca.role = 'Head Coach'
                    ORDER BY t.team_name
                ");
                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>" . h($row['team_name']) . "</td>";
                    echo "<td>" . h($row['first_name'] . ' ' . $row['last_name']) . "</td>";
                    echo "<td>" . h($row['role']) . "</td>";
                    echo "<td>" . h($row['years_experience']) . "</td>";
                    echo "</tr>";
                }
            }
        }
        ?>
    </table>

    <!-- 4. Recent Injuries -->
    <h3>4. Recent Injuries</h3>
    <p>Shows the most recent injuries with player names and return dates.</p>
    <form method="get" style="margin-bottom:10px;">
        <input type="hidden" name="run" value="injuries">
        <input type="submit" value="Run Injury Report">
    </form>
    <table width='100%' border=1>
        <tr bgcolor='#CCCCCC'>
            <td>Player</td>
            <td>Injury Type</td>
            <td>Injury Date</td>
            <td>Return Date</td>
            <td>Games Missed</td>
        </tr>
        <?php
        if (!shouldRun('injuries')) {
            echo "<tr><td colspan='5' style='color:blue;text-align:center;'>Press the 'Run Injury Report' button above to execute this query.</td></tr>";
        } else {
            $missing = requireTables($mysqli, ['injury', 'player']);
            if (!empty($missing)) {
                echo "<tr><td colspan='5' style='color:red;text-align:center;'>Missing table(s): " . h(implode(', ', $missing)) . "</td></tr>";
            } else {
                $result = mysqli_query($mysqli, "
                    SELECT p.first_name, p.last_name, i.injury_type, i.injury_date, i.return_date, i.games_missed
                    FROM injury i
                    JOIN player p ON i.player_id = p.player_id
                    ORDER BY i.injury_date DESC
                    LIMIT 10
                ");
                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>" . h($row['first_name'] . ' ' . $row['last_name']) . "</td>";
                    echo "<td>" . h($row['injury_type']) . "</td>";
                    echo "<td>" . h($row['injury_date']) . "</td>";
                    echo "<td>" . h($row['return_date']) . "</td>";
                    echo "<td>" . h($row['games_missed']) . "</td>";
                    echo "</tr>";
                }
            }
        }
        ?>
    </table>

    <!-- 5. Specific Contracts -->
    <h3>5. Top Cardinals Contracts</h3>
    <p>Shows only the highest-value contracts for one team instead of every contract.</p>
    <form method="get" style="margin-bottom:10px;">
        <input type="hidden" name="run" value="contracts">
        <input type="submit" value="Run Contracts Report">
    </form>
    <table width='100%' border=1>
        <tr bgcolor='#CCCCCC'>
            <td>Team</td>
            <td>Player</td>
            <td>Start Date</td>
            <td>End Date</td>
            <td>Total Value</td>
            <td>Guaranteed Money</td>
        </tr>
        <?php
        if (!shouldRun('contracts')) {
            echo "<tr><td colspan='6' style='color:blue;text-align:center;'>Press the 'Run Contracts Report' button above to execute this query.</td></tr>";
        } else {
            $missing = requireTables($mysqli, ['contract', 'player', 'team']);
            if (!empty($missing)) {
                echo "<tr><td colspan='6' style='color:red;text-align:center;'>Missing table(s): " . h(implode(', ', $missing)) . "</td></tr>";
            } else {
                $result = mysqli_query($mysqli, "
                    SELECT t.team_name, p.first_name, p.last_name,
                           ct.start_date, ct.end_date, ct.total_value, ct.guaranteed_money
                    FROM contract ct
                    JOIN player p ON ct.player_id = p.player_id
                    JOIN team t ON ct.team_id = t.team_id
                    WHERE t.team_name = 'Cardinals'
                    ORDER BY ct.total_value DESC
                    LIMIT 10
                ");
                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>" . h($row['team_name']) . "</td>";
                    echo "<td>" . h($row['first_name'] . ' ' . $row['last_name']) . "</td>";
                    echo "<td>" . h($row['start_date']) . "</td>";
                    echo "<td>" . h($row['end_date']) . "</td>";
                    echo "<td>" . h($row['total_value']) . "</td>";
                    echo "<td>" . h($row['guaranteed_money']) . "</td>";
                    echo "</tr>";
                }
            }
        }
        ?>
    </table>
</body>
</html>