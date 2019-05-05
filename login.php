<?php include("server.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/logreg.css">

</head>
<body>


<div class="header">
    <h2>Login</h2>
</div>

<form method="post" action="login.php">
    <?php include("errors.php"); ?>
    <div class="input-group">
        <input type="text" name="username" placeholder="Username">
    </div>
    <div class="input-group">
        <input type="password" name="password" placeholder="Password">
    </div>

    <div class="input-group">
        <button type="submit" name="login" class="btn">
        <span>LOGIN </span></button>
    </div>
    <p>
        not a member? <a href="register.php">Sign up</a>
    </p>
</form>
</body>
</html>