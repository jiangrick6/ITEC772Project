-- database: Projectdb.db
PRAGMA foreign_keys;

-- =========================
-- Drop tables in reverse dependency order
-- =========================
;

-- =========================
-- Conference
-- =========================
CREATE TABLE Conference (
    conference_id INTEGER PRIMARY KEY,
    conference_name TEXT NOT NULL
);

INSERT INTO Conference (conference_id, conference_name) VALUES
(1, 'AFC'),
(2, 'NFC');

-- =========================
-- Division
-- =========================
CREATE TABLE Division (
    division_id INTEGER PRIMARY KEY,
    division_name TEXT NOT NULL,
    conference_id INTEGER NOT NULL,
    FOREIGN KEY (conference_id) REFERENCES Conference(conference_id)
);

INSERT INTO Division (division_id, division_name, conference_id) VALUES
(1, 'AFC East', 1),
(2, 'AFC North', 1),
(3, 'AFC South', 1),
(4, 'AFC West', 1),
(5, 'NFC East', 2),
(6, 'NFC North', 2),
(7, 'NFC South', 2),
(8, 'NFC West', 2);

-- =========================
-- Stadium
-- =========================
CREATE TABLE Stadium (
    stadium_id INTEGER PRIMARY KEY,
    stadium_name TEXT NOT NULL,
    city TEXT NOT NULL,
    state TEXT NOT NULL,
    capacity INTEGER,
    surface_type TEXT
);

-- Note: duplicate stadium rows were removed.
INSERT INTO Stadium (stadium_id, stadium_name, city, state, capacity, surface_type) VALUES
(1,  'Highmark Stadium', 'Orchard Park', 'NY', 71608, 'Artificial Turf'),
(2,  'Hard Rock Stadium', 'Miami Gardens', 'FL', 65326, 'Grass'),
(3,  'Gillette Stadium', 'Foxborough', 'MA', 65878, 'Artificial Turf'),
(4,  'MetLife Stadium', 'East Rutherford', 'NJ', 82500, 'Artificial Turf'),
(5,  'M&T Bank Stadium', 'Baltimore', 'MD', 71008, 'Grass'),
(6,  'Paycor Stadium', 'Cincinnati', 'OH', 65515, 'Artificial Turf'),
(7,  'Huntington Bank Field', 'Cleveland', 'OH', 67431, 'Grass'),
(8,  'Acrisure Stadium', 'Pittsburgh', 'PA', 68400, 'Grass'),
(9,  'NRG Stadium', 'Houston', 'TX', 72220, 'Artificial Turf'),
(10, 'Lucas Oil Stadium', 'Indianapolis', 'IN', 67000, 'Artificial Turf'),
(11, 'EverBank Stadium', 'Jacksonville', 'FL', 69132, 'Grass'),
(12, 'Nissan Stadium', 'Nashville', 'TN', 69143, 'Grass'),
(13, 'Empower Field at Mile High', 'Denver', 'CO', 76125, 'Grass'),
(14, 'GEHA Field at Arrowhead Stadium', 'Kansas City', 'MO', 76416, 'Grass'),
(15, 'Allegiant Stadium', 'Las Vegas', 'NV', 65000, 'Artificial Turf'),
(16, 'SoFi Stadium', 'Inglewood', 'CA', 70240, 'Artificial Turf'),
(17, 'AT&T Stadium', 'Arlington', 'TX', 80000, 'Artificial Turf'),
(19, 'Lincoln Financial Field', 'Philadelphia', 'PA', 69596, 'Grass'),
(20, 'FedExField', 'Landover', 'MD', 62000, 'Grass'),
(21, 'Soldier Field', 'Chicago', 'IL', 61500, 'Grass'),
(22, 'Ford Field', 'Detroit', 'MI', 65000, 'Artificial Turf'),
(23, 'Lambeau Field', 'Green Bay', 'WI', 81441, 'Grass'),
(24, 'U.S. Bank Stadium', 'Minneapolis', 'MN', 66860, 'Artificial Turf'),
(25, 'Mercedes-Benz Stadium', 'Atlanta', 'GA', 71000, 'Artificial Turf'),
(26, 'Bank of America Stadium', 'Charlotte', 'NC', 74867, 'Grass'),
(27, 'Caesars Superdome', 'New Orleans', 'LA', 73208, 'Artificial Turf'),
(28, 'Raymond James Stadium', 'Tampa', 'FL', 65890, 'Grass'),
(29, 'State Farm Stadium', 'Glendale', 'AZ', 63400, 'Grass'),
(31, 'Levi''s Stadium', 'Santa Clara', 'CA', 68500, 'Grass'),
(32, 'Lumen Field', 'Seattle', 'WA', 68740, 'Artificial Turf');

-- =========================
-- Team
-- =========================
CREATE TABLE Team (
    team_id INTEGER PRIMARY KEY,
    team_name TEXT NOT NULL,
    city TEXT NOT NULL,
    abbreviation TEXT NOT NULL UNIQUE,
    founded_year INTEGER,
    division_id INTEGER NOT NULL,
    stadium_id INTEGER NOT NULL,
    FOREIGN KEY (division_id) REFERENCES Division(division_id),
    FOREIGN KEY (stadium_id) REFERENCES Stadium(stadium_id)
);

INSERT INTO Team (team_id, team_name, city, abbreviation, founded_year, division_id, stadium_id) VALUES
(1,  'Bills', 'Buffalo', 'BUF', 1960, 1, 1),
(2,  'Dolphins', 'Miami', 'MIA', 1966, 1, 2),
(3,  'Patriots', 'New England', 'NE', 1960, 1, 3),
(4,  'Jets', 'New York', 'NYJ', 1960, 1, 4),
(5,  'Ravens', 'Baltimore', 'BAL', 1996, 2, 5),
(6,  'Bengals', 'Cincinnati', 'CIN', 1968, 2, 6),
(7,  'Browns', 'Cleveland', 'CLE', 1946, 2, 7),
(8,  'Steelers', 'Pittsburgh', 'PIT', 1933, 2, 8),
(9,  'Texans', 'Houston', 'HOU', 2002, 3, 9),
(10, 'Colts', 'Indianapolis', 'IND', 1953, 3, 10),
(11, 'Jaguars', 'Jacksonville', 'JAX', 1995, 3, 11),
(12, 'Titans', 'Tennessee', 'TEN', 1960, 3, 12),
(13, 'Broncos', 'Denver', 'DEN', 1960, 4, 13),
(14, 'Chiefs', 'Kansas City', 'KC', 1960, 4, 14),
(15, 'Raiders', 'Las Vegas', 'LV', 1960, 4, 15),
(16, 'Chargers', 'Los Angeles', 'LAC', 1960, 4, 16),
(17, 'Cowboys', 'Dallas', 'DAL', 1960, 5, 17),
(18, 'Giants', 'New York', 'NYG', 1925, 5, 4),
(19, 'Eagles', 'Philadelphia', 'PHI', 1933, 5, 19),
(20, 'Commanders', 'Washington', 'WAS', 1932, 5, 20),
(21, 'Bears', 'Chicago', 'CHI', 1920, 6, 21),
(22, 'Lions', 'Detroit', 'DET', 1930, 6, 22),
(23, 'Packers', 'Green Bay', 'GB', 1919, 6, 23),
(24, 'Vikings', 'Minnesota', 'MIN', 1961, 6, 24),
(25, 'Falcons', 'Atlanta', 'ATL', 1966, 7, 25),
(26, 'Panthers', 'Carolina', 'CAR', 1995, 7, 26),
(27, 'Saints', 'New Orleans', 'NO', 1967, 7, 27),
(28, 'Buccaneers', 'Tampa Bay', 'TB', 1976, 7, 28),
(29, 'Cardinals', 'Arizona', 'ARI', 1898, 8, 29),
(30, 'Rams', 'Los Angeles', 'LAR', 1936, 8, 16),
(31, '49ers', 'San Francisco', 'SF', 1946, 8, 31),
(32, 'Seahawks', 'Seattle', 'SEA', 1976, 8, 32);

-- =========================
-- Season
-- =========================
CREATE TABLE Season (
    season_id INTEGER PRIMARY KEY,
    season_year INTEGER NOT NULL,
    start_date TEXT,
    end_date TEXT,
    num_weeks INTEGER
);

INSERT INTO Season (season_id, season_year, start_date, end_date, num_weeks) VALUES
(1, 2025, '2025-09-01', '2026-02-15', 18);

-- =========================
-- Game
-- =========================
CREATE TABLE Game (
    game_id INTEGER PRIMARY KEY,
    game_date TEXT,
    week_number INTEGER,
    season_id INTEGER NOT NULL,
    stadium_id INTEGER NOT NULL,
    home_team_id INTEGER NOT NULL,
    away_team_id INTEGER NOT NULL,
    FOREIGN KEY (season_id) REFERENCES Season(season_id),
    FOREIGN KEY (stadium_id) REFERENCES Stadium(stadium_id),
    FOREIGN KEY (home_team_id) REFERENCES Team(team_id),
    FOREIGN KEY (away_team_id) REFERENCES Team(team_id)
);

-- =========================
-- GameResult
-- =========================
CREATE TABLE GameResult (
    result_id INTEGER PRIMARY KEY,
    home_score INTEGER,
    away_score INTEGER,
    overtime INTEGER,
    attendance INTEGER,
    game_id INTEGER UNIQUE,
    FOREIGN KEY (game_id) REFERENCES Game(game_id)
);

-- =========================
-- Position
-- =========================
CREATE TABLE Position (
    position_id INTEGER PRIMARY KEY,
    position_name TEXT NOT NULL,
    position_group TEXT NOT NULL
);

INSERT INTO Position (position_id, position_name, position_group) VALUES
(1, 'Quarterback', 'Offense'),
(2, 'Running Back', 'Offense'),
(3, 'Fullback', 'Offense'),
(4, 'Wide Receiver', 'Offense'),
(5, 'Tight End', 'Offense'),
(6, 'Offensive Tackle', 'Offense'),
(7, 'Offensive Guard', 'Offense'),
(8, 'Center', 'Offense'),
(9, 'Left Tackle', 'Offense'),
(10, 'Right Tackle', 'Offense'),
(11, 'Left Guard', 'Offense'),
(12, 'Right Guard', 'Offense'),
(13, 'Slot Receiver', 'Offense'),
(14, 'Wide Receiver 1', 'Offense'),
(15, 'Wide Receiver 2', 'Offense'),
(16, 'Third Down Back', 'Offense'),
(17, 'Defensive End', 'Defense'),
(18, 'Defensive Tackle', 'Defense'),
(19, 'Linebacker', 'Defense'),
(20, 'Cornerback', 'Defense'),
(21, 'Safety', 'Defense'),
(22, 'Outside Linebacker', 'Defense'),
(23, 'Inside Linebacker', 'Defense'),
(24, 'Middle Linebacker', 'Defense'),
(25, 'Nickel Corner', 'Defense'),
(26, 'Free Safety', 'Defense'),
(27, 'Strong Safety', 'Defense'),
(28, 'Edge Rusher', 'Defense'),
(29, 'Kicker', 'Special Teams'),
(30, 'Punter', 'Special Teams'),
(31, 'Long Snapper', 'Special Teams'),
(32, 'Kick Returner', 'Special Teams'),
(33, 'Punt Returner', 'Special Teams'),
(34, 'Holder', 'Special Teams'),
(35, 'Gunner', 'Special Teams'),
(36, 'Kickoff Specialist', 'Special Teams');

-- =========================
-- Player
-- =========================
CREATE TABLE Player (
    player_id INTEGER PRIMARY KEY,
    first_name TEXT,
    last_name TEXT,
    date_of_birth TEXT,
    height TEXT,
    weight INTEGER,
    college TEXT,
    jersey_number INTEGER,
    position_id INTEGER,
    current_team_abbreviation TEXT,
    FOREIGN KEY (position_id) REFERENCES Position(position_id),
    FOREIGN KEY (current_team_abbreviation) REFERENCES Team(abbreviation)
);

-- =========================
-- Roster
-- =========================
CREATE TABLE Roster (
    roster_id INTEGER PRIMARY KEY,
    status TEXT,
    player_id INTEGER,
    team_id INTEGER,
    season_id INTEGER,
    FOREIGN KEY (player_id) REFERENCES Player(player_id),
    FOREIGN KEY (team_id) REFERENCES Team(team_id),
    FOREIGN KEY (season_id) REFERENCES Season(season_id)
);

-- =========================
-- Coach
-- =========================
CREATE TABLE Coach (
    coach_id INTEGER PRIMARY KEY,
    first_name TEXT,
    last_name TEXT,
    date_of_birth TEXT,
    years_experience INTEGER
);

INSERT INTO Coach (coach_id, first_name, last_name, date_of_birth, years_experience) VALUES
(1, 'Sean', 'McDermott', '1974-03-21', 8),
(2, 'Mike', 'McDaniel', '1983-03-06', 3),
(3, 'Jerod', 'Mayo', '1986-02-23', 1),
(4, 'Aaron', 'Glenn', '1972-07-16', 1),
(5, 'John', 'Harbaugh', '1962-09-23', 17),
(6, 'Zac', 'Taylor', '1983-05-10', 6),
(7, 'Kevin', 'Stefanski', '1982-05-08', 5),
(8, 'Mike', 'Tomlin', '1972-03-15', 18),
(9, 'DeMeco', 'Ryans', '1984-07-28', 3),
(10, 'Shane', 'Steichen', '1985-05-11', 3),
(11, 'Doug', 'Pederson', '1968-01-31', 9),
(12, 'Brian', 'Callahan', '1984-06-10', 1),
(13, 'Sean', 'Payton', '1963-12-29', 3),
(14, 'Andy', 'Reid', '1958-03-19', 26),
(15, 'Antonio', 'Pierce', '1978-10-26', 2),
(16, 'Jim', 'Harbaugh', '1963-12-23', 2),
(17, 'Brian', 'Schottenheimer', '1973-10-16', 1),
(18, 'Brian', 'Daboll', '1975-04-14', 4),
(19, 'Nick', 'Sirianni', '1981-06-15', 5),
(20, 'Dan', 'Quinn', '1970-09-11', 2),
(21, 'Ben', 'Johnson', '1986-05-11', 1),
(22, 'Dan', 'Campbell', '1976-04-13', 5),
(23, 'Matt', 'LaFleur', '1979-11-03', 7),
(24, 'Kevin', 'O''Connell', '1985-05-25', 4),
(25, 'Raheem', 'Morris', '1976-09-03', 5),
(26, 'Dave', 'Canales', '1981-05-07', 2),
(27, 'Kellen', 'Moore', '1988-07-12', 1),
(28, 'Todd', 'Bowles', '1963-11-18', 9),
(29, 'Jonathan', 'Gannon', '1983-01-04', 3),
(30, 'Sean', 'McVay', '1986-01-24', 10),
(31, 'Kyle', 'Shanahan', '1979-12-14', 10),
(32, 'Mike', 'Macdonald', '1987-06-26', 2);

-- =========================
-- CoachAssignment
-- =========================
CREATE TABLE CoachAssignment (
    assignment_id INTEGER PRIMARY KEY,
    role TEXT,
    coach_id INTEGER,
    team_id INTEGER,
    season_id INTEGER,
    FOREIGN KEY (coach_id) REFERENCES Coach(coach_id),
    FOREIGN KEY (team_id) REFERENCES Team(team_id),
    FOREIGN KEY (season_id) REFERENCES Season(season_id)
);

INSERT INTO CoachAssignment (assignment_id, role, coach_id, team_id, season_id) VALUES
(1,  'Head Coach', 1,  1,  1),
(2,  'Head Coach', 2,  2,  1),
(3,  'Head Coach', 3,  3,  1),
(4,  'Head Coach', 4,  4,  1),
(5,  'Head Coach', 5,  5,  1),
(6,  'Head Coach', 6,  6,  1),
(7,  'Head Coach', 7,  7,  1),
(8,  'Head Coach', 8,  8,  1),
(9,  'Head Coach', 9,  9,  1),
(10, 'Head Coach', 10, 10, 1),
(11, 'Head Coach', 11, 11, 1),
(12, 'Head Coach', 12, 12, 1),
(13, 'Head Coach', 13, 13, 1),
(14, 'Head Coach', 14, 14, 1),
(15, 'Head Coach', 15, 15, 1),
(16, 'Head Coach', 16, 16, 1),
(17, 'Head Coach', 17, 17, 1),
(18, 'Head Coach', 18, 18, 1),
(19, 'Head Coach', 19, 19, 1),
(20, 'Head Coach', 20, 20, 1),
(21, 'Head Coach', 21, 21, 1),
(22, 'Head Coach', 22, 22, 1),
(23, 'Head Coach', 23, 23, 1),
(24, 'Head Coach', 24, 24, 1),
(25, 'Head Coach', 25, 25, 1),
(26, 'Head Coach', 26, 26, 1),
(27, 'Head Coach', 27, 27, 1),
(28, 'Head Coach', 28, 28, 1),
(29, 'Head Coach', 29, 29, 1),
(30, 'Head Coach', 30, 30, 1),
(31, 'Head Coach', 31, 31, 1),
(32, 'Head Coach', 32, 32, 1);

-- =========================
-- Contract
-- =========================
CREATE TABLE Contract (
    contract_id INTEGER PRIMARY KEY,
    start_date TEXT,
    end_date TEXT,
    total_value REAL,
    guaranteed_money REAL,
    player_id INTEGER,
    team_id INTEGER,
    FOREIGN KEY (player_id) REFERENCES Player(player_id),
    FOREIGN KEY (team_id) REFERENCES Team(team_id)
);

-- =========================
-- Injury
-- =========================
CREATE TABLE Injury (
    injury_id INTEGER PRIMARY KEY,
    injury_type TEXT,
    injury_date TEXT,
    return_date TEXT,
    games_missed INTEGER,
    player_id INTEGER,
    season_id INTEGER,
    FOREIGN KEY (player_id) REFERENCES Player(player_id),
    FOREIGN KEY (season_id) REFERENCES Season(season_id)
);

-- =========================
-- Award
-- =========================
CREATE TABLE Award (
    award_id INTEGER PRIMARY KEY,
    award_name TEXT,
    award_date TEXT,
    player_id INTEGER,
    season_id INTEGER,
    team_id INTEGER,
    FOREIGN KEY (player_id) REFERENCES Player(player_id),
    FOREIGN KEY (season_id) REFERENCES Season(season_id),
    FOREIGN KEY (team_id) REFERENCES Team(team_id)
);