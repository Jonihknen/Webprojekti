<?php include("server.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <title> Registration</title>
    <link rel="stylesheet" type="text/css" href="assets/pagemedia/logreg.css">
</head>
<body>
    <div class="header">
        <h2>Register</h2>
    </div>

    <form method="post" action="register.php">
        <?php include("errors.php"); ?>


        <div class="input-group">

            <input type="text" name="username" placeholder="Username">
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="Password">
        </div>
        <div class="input-group">

            <input type="password" name="password_2" placeholder="Confirm password">
        </div>
        <div class="input-group">
            <button type="submit" name="register" class="btn">
                <span>REGISTER </span></button>
        </div>
        <p>
            Already a member? <a href="login.php">Sign in</a>
        </p>
    </form>
</body>
</html>