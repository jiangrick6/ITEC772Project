<?php session_start(); ?>

<?php
// Check if user is logged in
if(!isset($_SESSION['valid']) || !$_SESSION['valid']) {
    header("Location: login.php");
    exit();
}
?>

<?php
//including the database connection file
include_once("connection.php");
include_once("functions.php");

function tableExists($conn, $tableName) {
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
?>

<html>
<head>
	<title>NFL Reports</title>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<a href="index.php">Home</a>
	<br/><br/>

	<h2>NFL Database Reports</h2>
	<p>Choose report section to execute using each section button; or run all at once.</p>
	<form method="get" style="margin-bottom:20px;"><input type="hidden" name="run" value="all"><input type="submit" value="Run All Reports"></form>

<?php
$run = isset($_GET['run']) ? $_GET['run'] : '';
function shouldRun($section) {
	global $run;
	return $run === 'all' || $run === $section;
}
?>

	<h3>1. Team Standings</h3>
	<p>Will calculate wins/losses/ties per team based on games and results.</p>
	<form method="get" style="margin-bottom:10px;"><input type="hidden" name="run" value="team"><input type="submit" value="Run Team Standings"></form>
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
				echo "<tr><td colspan='6' style='color:red;text-align:center;'>Missing table(s): " . implode(', ', $missing) . "</td></tr>";
			} else {
				$result = mysqli_query($mysqli, "
					SELECT t.team_name, d.division_name,
					       COUNT(CASE WHEN gr.home_score > gr.away_score AND g.home_team_id = t.team_id THEN 1 END) +
					       COUNT(CASE WHEN gr.away_score > gr.home_score AND g.away_team_id = t.team_id THEN 1 END) as wins,
					       COUNT(CASE WHEN gr.home_score < gr.away_score AND g.home_team_id = t.team_id THEN 1 END) +
					       COUNT(CASE WHEN gr.away_score < gr.home_score AND g.away_team_id = t.team_id THEN 1 END) as losses,
					       COUNT(CASE WHEN gr.home_score = gr.away_score AND (g.home_team_id = t.team_id OR g.away_team_id = t.team_id) THEN 1 END) as ties
					FROM team t
					JOIN division d ON t.division_id = d.division_id
					LEFT JOIN game g ON (g.home_team_id = t.team_id OR g.away_team_id = t.team_id)
					LEFT JOIN gameresult gr ON gr.game_id = g.game_id
					GROUP BY t.team_id, t.team_name, d.division_name
					ORDER BY wins DESC, losses ASC
				");
				while($res = mysqli_fetch_array($result)) {
					$total_games = $res['wins'] + $res['losses'] + $res['ties'];
					$win_pct = $total_games > 0 ? round(($res['wins'] / $total_games) * 100, 1) : 0;
					echo "<tr>";
					echo "<td>".$res['team_name']."</td>";
					echo "<td>".$res['division_name']."</td>";
					echo "<td>".$res['wins']."</td>";
					echo "<td>".$res['losses']."</td>";
					echo "<td>".$res['ties']."</td>";
					echo "<td>".$win_pct."%</td>";
					echo "</tr>";
				}
			}
		}
		?>
	</table>

	<h3>2. Quarterbacks</h3>
	<p>Description: shows quarterbacks and optionally passing stats if <code>player_stats</code> exists.</p>
	<form method="get" style="margin-bottom:10px;"><input type="hidden" name="run" value="qb"><input type="submit" value="Run Quarterbacks Query"></form>
	<table width='100%' border=1>
		<tr bgcolor='#CCCCCC'>
			<td>Player</td>
			<td>Team</td>
			<td>Position</td>
			<td>Passing Yards</td>
			<td>Touchdowns</td>
		</tr>
		<?php
		if (!shouldRun('qb')) {
			echo "<tr><td colspan='5' style='color:blue;text-align:center;'>Press the 'Run Quarterbacks Query' button above to execute this query.</td></tr>";
		} else {
			$required = ['player', 'team', 'position'];
			$missing = requireTables($mysqli, $required);
			if (!empty($missing)) {
				echo "<tr><td colspan='5' style='color:red;text-align:center;'>Missing table(s): " . implode(', ', $missing) . "</td></tr>";
			} else {
				$statsTable = tableExists($mysqli, 'player_stats');
				if ($statsTable) {
					$result = mysqli_query($mysqli, "
						SELECT p.first_name, p.last_name, t.team_name, pos.position_name,
							ps.passing_yards, ps.passing_touchdowns
						FROM player p
						JOIN team t ON p.current_team_abbreviation = t.abbreviation
						JOIN position pos ON p.position_id = pos.position_id
						JOIN player_stats ps ON p.player_id = ps.player_id
						WHERE pos.position_name = 'Quarterback'
						ORDER BY ps.passing_yards DESC
						LIMIT 10
					");
					while($res = mysqli_fetch_array($result)) {
						echo "<tr>";
						echo "<td>".$res['first_name']." ".$res['last_name']."</td>";
						echo "<td>".$res['team_name']."</td>";
						echo "<td>".$res['position_name']."</td>";
						echo "<td>".number_format($res['passing_yards'])."</td>";
						echo "<td>".$res['passing_touchdowns']."</td>";
						echo "</tr>";
					}
				} else {
					$result = mysqli_query($mysqli, "
						SELECT p.first_name, p.last_name, t.team_name, pos.position_name
						FROM player p
						JOIN team t ON p.current_team_abbreviation = t.abbreviation
						JOIN position pos ON p.position_id = pos.position_id
						WHERE pos.position_name = 'Quarterback'
						ORDER BY p.last_name, p.first_name
					");
					while($res = mysqli_fetch_array($result)) {
						echo "<tr>";
						echo "<td>".$res['first_name']." ".$res['last_name']."</td>";
						echo "<td>".$res['team_name']."</td>";
						echo "<td>".$res['position_name']."</td>";
						echo "<td colspan='2' style='text-align:center;color:#900;'>No player_stats table</td>";
						echo "</tr>";
					}
					echo "<tr><td colspan='5' style='color:#900;'>Data table 'player_stats' not found. To enable full passing stats, create the table and populate data.</td></tr>";
				}
			}
		}
		?>
	</table>

	<h3>3. Coach Performance by Team Wins</h3>
	<p>Description: calculates total wins per coach from game results.</p>
	<form method="get" style="margin-bottom:10px;"><input type="hidden" name="run" value="coach"><input type="submit" value="Run Coach Performance"></form>
	<table width='100%' border=1>
		<tr bgcolor='#CCCCCC'>
			<td>Coach</td>
			<td>Team</td>
			<td>Years Experience</td>
			<td>Team Wins</td>
		</tr>
		<?php
		if (!shouldRun('coach')) {
			echo "<tr><td colspan='4' style='color:blue;text-align:center;'>Press the 'Run Coach Performance' button above to execute this query.</td></tr>";
		} else {
			$missing = requireTables($mysqli, ['coach', 'team', 'game', 'gameresult']);
			if (!empty($missing)) {
				echo "<tr><td colspan='4' style='color:red;text-align:center;'>Missing table(s): " . implode(', ', $missing) . "</td></tr>";
			} else {
			$result = mysqli_query($mysqli, "
				SELECT c.first_name, c.last_name, t.team_name, c.years_experience,
				       COUNT(CASE WHEN gr.home_score > gr.away_score AND g.home_team_id = t.team_id THEN 1 END) +
				       COUNT(CASE WHEN gr.away_score > gr.home_score AND g.away_team_id = t.team_id THEN 1 END) as wins
				FROM coach c
				JOIN team t ON c.coach_id = t.coach_id
				LEFT JOIN game g ON (g.home_team_id = t.team_id OR g.away_team_id = t.team_id)
				LEFT JOIN gameresult gr ON gr.game_id = g.game_id
				GROUP BY c.coach_id, c.first_name, c.last_name, t.team_name, c.years_experience
				ORDER BY wins DESC
			");
			while($res = mysqli_fetch_array($result)) {
				echo "<tr>";
				echo "<td>".$res['first_name']." ".$res['last_name']."</td>";
				echo "<td>".$res['team_name']."</td>";
				echo "<td>".$res['years_experience']."</td>";
				echo "<td>".$res['wins']."</td>";
				echo "</tr>";
			}
		}
		}
		?>
	</table>

	<h3>4. Recent Game Results</h3>
	<p>Description: shows the last 10 games with decoded winner field.</p>
	<form method="get" style="margin-bottom:10px;"><input type="hidden" name="run" value="games"><input type="submit" value="Run Recent Games"></form>
	<table width='100%' border=1>
		<tr bgcolor='#CCCCCC'>
			<td>Date</td>
			<td>Home Team</td>
			<td>Away Team</td>
			<td>Home Score</td>
			<td>Away Score</td>
			<td>Winner</td>
		</tr>
		<?php
		if (!shouldRun('games')) {
			echo "<tr><td colspan='6' style='color:blue;text-align:center;'>Press the 'Run Recent Games' button above to execute this query.</td></tr>";
		} else {
			$missing = requireTables($mysqli, ['game', 'gameresult', 'team']);
			if (!empty($missing)) {
				echo "<tr><td colspan='6' style='color:red;text-align:center;'>Missing table(s): " . implode(', ', $missing) . "</td></tr>";
			} else {
			$result = mysqli_query($mysqli, "
				SELECT g.game_date, ht.team_name as home_team, at.team_name as away_team,
				   gr.home_score, gr.away_score,
				   CASE
				       WHEN gr.home_score > gr.away_score THEN ht.team_name
				       WHEN gr.away_score > gr.home_score THEN at.team_name
				       ELSE 'Tie'
				   END as winner
				FROM game g
				JOIN gameresult gr ON gr.game_id = g.game_id
				JOIN team ht ON g.home_team_id = ht.team_id
				JOIN team at ON g.away_team_id = at.team_id
				ORDER BY g.game_date DESC
				LIMIT 10
			");
			while($res = mysqli_fetch_array($result)) {
				echo "<tr>";
				echo "<td>".formatDate($res['game_date'])."</td>";
				echo "<td>".$res['home_team']."</td>";
				echo "<td>".$res['away_team']."</td>";
				echo "<td>".$res['home_score']."</td>";
				echo "<td>".$res['away_score']."</td>";
				echo "<td>".$res['winner']."</td>";
				echo "</tr>";
			}
		}
		}
		?>
	</table>

	<h3>5. Division Rankings</h3>
	<p>Description: shows team rankings inside each division by wins/losses/ties.</p>
	<form method="get" style="margin-bottom:10px;"><input type="hidden" name="run" value="division"><input type="submit" value="Run Division Rankings"></form>
	<table width='100%' border=1>
		<tr bgcolor='#CCCCCC'>
			<td>Division</td>
			<td>Team</td>
			<td>Wins</td>
			<td>Losses</td>
			<td>Ties</td>
		</tr>
		<?php
		if (!shouldRun('division')) {
			echo "<tr><td colspan='5' style='color:blue;text-align:center;'>Press the 'Run Division Rankings' button above to execute this query.</td></tr>";
		} else {
			$missing = requireTables($mysqli, ['division', 'team', 'game', 'gameresult']);
			if (!empty($missing)) {
				echo "<tr><td colspan='5' style='color:red;text-align:center;'>Missing table(s): " . implode(', ', $missing) . "</td></tr>";
			} else {
			$result = mysqli_query($mysqli, "
				SELECT d.division_name, t.team_name,
				       COUNT(CASE WHEN gr.home_score > gr.away_score AND g.home_team_id = t.team_id THEN 1 END) +
				       COUNT(CASE WHEN gr.away_score > gr.home_score AND g.away_team_id = t.team_id THEN 1 END) as wins,
				       COUNT(CASE WHEN gr.home_score < gr.away_score AND g.home_team_id = t.team_id THEN 1 END) +
				       COUNT(CASE WHEN gr.away_score < gr.home_score AND g.away_team_id = t.team_id THEN 1 END) as losses,
				       COUNT(CASE WHEN gr.home_score = gr.away_score AND (g.home_team_id = t.team_id OR g.away_team_id = t.team_id) THEN 1 END) as ties
				FROM team t
				JOIN division d ON t.division_id = d.division_id
				LEFT JOIN game g ON (g.home_team_id = t.team_id OR g.away_team_id = t.team_id)
				LEFT JOIN gameresult gr ON gr.game_id = g.game_id
				GROUP BY d.division_name, t.team_id, t.team_name
				ORDER BY d.division_name, wins DESC, losses ASC
			");
			while($res = mysqli_fetch_array($result)) {
				echo "<tr>";
				echo "<td>".$res['division_name']."</td>";
				echo "<td>".$res['team_name']."</td>";
				echo "<td>".$res['wins']."</td>";
				echo "<td>".$res['losses']."</td>";
				echo "<td>".$res['ties']."</td>";
				echo "</tr>";
			}
		}
		}
		?>
	</table>
</body>
</html>