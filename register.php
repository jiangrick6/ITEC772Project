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
    <div style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h2>Register for NFL Database</h2>

        <?php if(isset($error)) { ?>
            <div style="color: red; margin-bottom: 15px;"><?php echo $error; ?></div>
        <?php } ?>

        <?php if(isset($success)) { ?>
            <div style="color: green; margin-bottom: 15px;"><?php echo $success; ?></div>
        <?php } ?>

        <form action="register.php" method="post" name="registerForm">
            <table width="100%" border="0">
                <tr>
                    <td>Username</td>
                    <td><input type="text" name="username" required></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type="email" name="email" required></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="password" name="password" required></td>
                </tr>
                <tr>
                    <td>Confirm Password</td>
                    <td><input type="password" name="confirm_password" required></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" name="register" value="Register"></td>
                </tr>
            </table>
        </form>

        <p style="text-align: center; margin-top: 20px;">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </div>
</body>
</html>