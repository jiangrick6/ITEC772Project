<?php session_start(); ?>

<?php
//including the database connection file
include_once("user_connection.php");

if(isset($_POST['login'])) {
    $username = mysqli_real_escape_string($user_conn, $_POST['username']);
    $password = mysqli_real_escape_string($user_conn, $_POST['password']);

    // checking empty fields
    if(empty($username) || empty($password)) {
        $error = "Username and password are required.";
    } else {
        // check if user exists
        $result = mysqli_query($user_conn, "SELECT * FROM users WHERE username='$username'");

        if(mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            // verify password
            if(password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['valid'] = true;

                header("Location: index.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "User not found.";
        }
    }
}
?>

<html>
<head>
    <title>Login - NFL Database</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="page-wrap">
        <div class="auth-card">
            <h2 class="auth-title">Login to NFL Database</h2>

            <?php if(isset($error)) { ?>
                <div class="error"><?php echo $error; ?></div>
            <?php } ?>

            <form action="login.php" method="post" name="loginForm" class="auth-form">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-actions">
                    <input type="submit" name="login" value="Login" class="btn btn-primary">
                </div>
            </form>

            <div class="auth-footer">
                Don't have an account? <a href="register.php">Register here</a>
            </div>
        </div>
    </div>
</body>
</html>