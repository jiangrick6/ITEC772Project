# NFL Database Management System

A complete web application built with PHP backend, MySQL database, and HTML frontend for managing NFL data.

## Features

### ✅ Backend Implementation (PHP)
- **Database Connection**: Proper MySQLi connection with error handling
- **Session Management**: User session tracking across pages
- **CRUD Operations**: Full Create, Read, Update, Delete for all major tables
- **Form Validation**: Client and server-side validation with error messages
- **Security**: Input sanitization and prepared statements

### ✅ Database (MySQL)
- **Schema**: Complete NFL database with 15+ tables
- **Relationships**: Foreign keys and data integrity
- **Data**: Pre-populated with real NFL teams, players, coaches, and stadiums

### ✅ Frontend (HTML/CSS/JavaScript)
- **Responsive Design**: Mobile-friendly layout
- **Navigation**: Clean menu with active page highlighting
- **Forms**: User-friendly input forms with validation
- **Tables**: Sortable data tables with search functionality
- **Alerts**: Success/error message display

### ✅ CRUD Operations (3 Major Tables)

#### 1. Teams Management (`team.php`)
- ✅ Create new teams with division and stadium assignment
- ✅ Read/list all teams with related data
- ✅ Update team information
- ✅ Delete teams with confirmation

#### 2. Players Management (`player.php`)
- ✅ Add players with position, team, and stats
- ✅ View all players with team and position info
- ✅ Edit player details
- ✅ Remove players with confirmation

#### 3. Coaches Management (`coach.php`)
- ✅ Create coach records with experience
- ✅ Display coaches with age calculation
- ✅ Update coach information
- ✅ Delete coaches safely

### ✅ Complex SQL Queries (5 Advanced Reports)

#### Query 1: Teams with Stadiums and Coaches
```sql
SELECT teams with stadium details and head coaches
```

#### Query 2: Players by Position and Team
```sql
SELECT players grouped by position and current team
```

#### Query 3: Recent Game Results
```sql
SELECT game results with scores and attendance
```

#### Query 4: Team Roster Statistics
```sql
SELECT team stats: player count, average weight/height
```

#### Query 5: Division Standings
```sql
SELECT division standings with win/loss records
```

### ✅ Form Validation
- **Required Fields**: All mandatory fields validated
- **Data Types**: Numeric validation for weights, jersey numbers
- **Length Checks**: Abbreviation must be 3 characters
- **Range Validation**: Years between 1920-2030, weights 150-400 lbs
- **Error Display**: Clear error messages for each validation failure

### ✅ Navigation & Layout
- **Responsive Navbar**: Mobile-friendly navigation menu
- **Page Headers**: Clear page titles with action buttons
- **Dashboard**: Home page with statistics and quick links
- **Consistent Layout**: Uniform design across all pages

### ✅ Sessions
- **Message Storage**: Success/error messages persist across redirects
- **State Management**: Form data and user actions tracked
- **Security**: Session-based message display

## File Structure

```
/
├── index.php          # Homepage with dashboard
├── config.php         # Database configuration
├── functions.php      # Utility functions
├── navigation.php     # Navigation menu
├── style.css          # CSS styles
├── script.js          # JavaScript functionality
├── team.php           # Teams CRUD
├── player.php         # Players CRUD
├── coach.php          # Coaches CRUD
├── queries.php        # Advanced reports
└── README.md          # This file
```

## Database Schema

### Core Tables
- `conference` - AFC/NFC
- `division` - 8 divisions across conferences
- `stadium` - All NFL stadiums with capacity/surface
- `team` - 32 NFL teams with division/stadium links
- `position` - Player positions (QB, RB, WR, etc.)
- `player` - Player roster with stats
- `coach` - Coaching staff
- `season` - Season information
- `game` - Game schedule
- `gameresult` - Game scores and results

## Installation & Setup

### 1. Database Setup
```bash
# Import the database schema
mysql -u root -p < nfldb_mysql.sql
```

### 2. Web Server
```bash
# Start PHP development server
php -S localhost:8000

# Or use Apache/Nginx with PHP
```

### 3. Access Application
```
http://localhost:8000/index.php
```

## Usage Examples

### Adding a New Team
1. Navigate to Teams page
2. Click "Add New Team"
3. Fill required fields (name, city, abbreviation, etc.)
4. Select division and stadium
5. Submit form

### Running Reports
1. Go to Reports page
2. Click any query button
3. View results in formatted table
4. Export or analyze data

### Managing Players
1. Access Players page
2. Add/edit player information
3. Include position, team, physical stats
4. Save changes

## Security Features

- **Input Sanitization**: All user inputs cleaned
- **Prepared Statements**: SQL injection prevention
- **CSRF Protection**: Form tokens (recommended addition)
- **Error Handling**: Safe error display
- **Session Security**: Proper session management

## Technologies Used

- **Backend**: PHP 8.3+
- **Database**: MySQL 8.0+
- **Frontend**: HTML5, CSS3, JavaScript
- **Architecture**: MVC-inspired structure
- **Styling**: Responsive design with Flexbox/Grid

## Future Enhancements

- User authentication system
- API endpoints for mobile apps
- Advanced search and filtering
- Data export (CSV/PDF)
- Real-time statistics updates
- Admin panel for bulk operations

## Requirements

- PHP 8.0 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx) or PHP built-in server
- Modern web browser

---

**Built for ITEC772 Project - Complete NFL Database Management System**