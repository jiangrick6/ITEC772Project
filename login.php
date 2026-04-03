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
    <div style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h2>Login to NFL Database</h2>

        <?php if(isset($error)) { ?>
            <div style="color: red; margin-bottom: 15px;"><?php echo $error; ?></div>
        <?php } ?>

        <form action="login.php" method="post" name="loginForm">
            <table width="100%" border="0">
                <tr>
                    <td>Username</td>
                    <td><input type="text" name="username" required></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="password" name="password" required></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" name="login" value="Login"></td>
                </tr>
            </table>
        </form>

        <p style="text-align: center; margin-top: 20px;">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>
</body>
</html>