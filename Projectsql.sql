-- database: Projectdb.db
-- Conference
CREATE TABLE Conference (
    conference_id INT PRIMARY KEY,
    conference_name VARCHAR(100)
);

-- Division
CREATE TABLE Division (
    division_id INT PRIMARY KEY,
    division_name VARCHAR(100),
    conference_id INT,
    FOREIGN KEY (conference_id) REFERENCES Conference(conference_id)
);

-- Stadium
CREATE TABLE Stadium (
    stadium_id INT PRIMARY KEY,
    stadium_name VARCHAR(100),
    city VARCHAR(100),
    state VARCHAR(50),
    capacity INT,
    surface_type VARCHAR(50)
);

-- Team
CREATE TABLE Team (
    team_id INT PRIMARY KEY,
    team_name VARCHAR(100),
    city VARCHAR(100),
    abbreviation VARCHAR(10),
    founded_year YEAR,
    division_id INT,
    stadium_id INT,
    FOREIGN KEY (division_id) REFERENCES Division(division_id),
    FOREIGN KEY (stadium_id) REFERENCES Stadium(stadium_id)
);

-- Season
CREATE TABLE Season (
    season_id INT PRIMARY KEY,
    season_year YEAR,
    start_date DATE,
    end_date DATE,
    num_weeks INT
);

-- Game
CREATE TABLE Game (
    game_id INT PRIMARY KEY,
    game_date DATE,
    week_number INT,
    season_id INT,
    stadium_id INT,
    home_team_id INT,
    away_team_id INT,
    FOREIGN KEY (season_id) REFERENCES Season(season_id),
    FOREIGN KEY (stadium_id) REFERENCES Stadium(stadium_id),
    FOREIGN KEY (home_team_id) REFERENCES Team(team_id),
    FOREIGN KEY (away_team_id) REFERENCES Team(team_id)
);

-- GameResult
CREATE TABLE GameResult (
    result_id INT PRIMARY KEY,
    home_score INT,
    away_score INT,
    overtime BOOLEAN,
    attendance INT,
    game_id INT UNIQUE,
    FOREIGN KEY (game_id) REFERENCES Game(game_id)
);

-- Position
CREATE TABLE Position (
    position_id INT PRIMARY KEY,
    position_name VARCHAR(50),
    position_group VARCHAR(50)
);

-- Player
CREATE TABLE Player (
    player_id INT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    date_of_birth DATE,
    height VARCHAR(10),
    weight INT,
    college VARCHAR(100),
    jersey_number INT,
    position_id INT,
    FOREIGN KEY (position_id) REFERENCES Position(position_id)
);

-- Roster (Associative)
CREATE TABLE Roster (
    roster_id INT PRIMARY KEY,
    status VARCHAR(50),
    player_id INT,
    team_id INT,
    season_id INT,
    FOREIGN KEY (player_id) REFERENCES Player(player_id),
    FOREIGN KEY (team_id) REFERENCES Team(team_id),
    FOREIGN KEY (season_id) REFERENCES Season(season_id)
);

-- Coach
CREATE TABLE Coach (
    coach_id INT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    date_of_birth DATE,
    years_experience INT
);

-- CoachAssignment (Associative)
CREATE TABLE CoachAssignment (
    assignment_id INT PRIMARY KEY,
    role VARCHAR(50),
    coach_id INT,
    team_id INT,
    season_id INT,
    FOREIGN KEY (coach_id) REFERENCES Coach(coach_id),
    FOREIGN KEY (team_id) REFERENCES Team(team_id),
    FOREIGN KEY (season_id) REFERENCES Season(season_id)
);

-- Contract
CREATE TABLE Contract (
    contract_id INT PRIMARY KEY,
    start_date DATE,
    end_date DATE,
    total_value DECIMAL(15,2),
    guaranteed_money DECIMAL(15,2),
    player_id INT,
    team_id INT,
    FOREIGN KEY (player_id) REFERENCES Player(player_id),
    FOREIGN KEY (team_id) REFERENCES Team(team_id)
);

-- Injury
CREATE TABLE Injury (
    injury_id INT PRIMARY KEY,
    injury_type VARCHAR(100),
    injury_date DATE,
    return_date DATE,
    games_missed INT,
    player_id INT,
    season_id INT,
    FOREIGN KEY (player_id) REFERENCES Player(player_id),
    FOREIGN KEY (season_id) REFERENCES Season(season_id)
);

-- Draft
CREATE TABLE Draft (
    draft_id INT PRIMARY KEY,
    draft_year YEAR,
    num_rounds INT,
    location VARCHAR(100),
    season_id INT,
    FOREIGN KEY (season_id) REFERENCES Season(season_id)
);

-- DraftPick (Weak Entity)
CREATE TABLE DraftPick (
    pick_id INT PRIMARY KEY,
    round_number INT,
    pick_number INT,
    draft_id INT,
    team_id INT,
    player_id INT,
    FOREIGN KEY (draft_id) REFERENCES Draft(draft_id),
    FOREIGN KEY (team_id) REFERENCES Team(team_id),
    FOREIGN KEY (player_id) REFERENCES Player(player_id)
);

-- Award
CREATE TABLE Award (
    award_id INT PRIMARY KEY,
    award_name VARCHAR(100),
    award_date DATE,
    player_id INT,
    season_id INT,
    team_id INT,
    FOREIGN KEY (player_id) REFERENCES Player(player_id),
    FOREIGN KEY (season_id) REFERENCES Season(season_id),
    FOREIGN KEY (team_id) REFERENCES Team(team_id)
);