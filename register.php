<?php session_start(); ?>

<?php
//including the database connection file
include_once("user_connection.php");

if(isset($_POST['register'])) {
    $username = mysqli_real_escape_string($user_conn, $_POST['username']);
    $email = mysqli_real_escape_string($user_conn, $_POST['email']);
    $password = mysqli_real_escape_string($user_conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($user_conn, $_POST['confirm_password']);

    // checking empty fields
    if(empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif($password != $confirm_password) {
        $error = "Passwords do not match.";
    } elseif(strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // check if username already exists
        $result = mysqli_query($user_conn, "SELECT * FROM users WHERE username='$username'");
        if(mysqli_num_rows($result) > 0) {
            $error = "Username already exists.";
        } else {
            // check if email already exists
            $result = mysqli_query($user_conn, "SELECT * FROM users WHERE email='$email'");
            if(mysqli_num_rows($result) > 0) {
                $error = "Email already exists.";
            } else {
                // hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // insert user
                $result = mysqli_query($user_conn, "INSERT INTO users(username,email,password,created_at) VALUES('$username','$email','$hashed_password',NOW())");

                if($result) {
                    $success = "Registration successful! You can now <a href='login.php'>login</a>.";
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
        }
    }
}
?>

<html>
<head>
    <title>Register - NFL Database</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="page-wrap">
        <div class="auth-card">
            <h2 class="auth-title">Register for NFL Database</h2>

            <?php if(isset($error)) { ?>
                <div class="error"><?php echo $error; ?></div>
            <?php } ?>

            <?php if(isset($success)) { ?>
                <div class="success"><?php echo $success; ?></div>
            <?php } ?>

            <form action="register.php" method="post" name="registerForm" class="auth-form">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <div class="form-actions">
                    <input type="submit" name="register" value="Register" class="btn btn-primary">
                </div>
            </form>

            <div class="auth-footer">
                Already have an account? <a href="login.php">Login here</a>
            </div>
        </div>
    </div>
</body>
</html>