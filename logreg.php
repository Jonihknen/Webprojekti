<?php include("server.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="assets/pagemedia/logreg.css">

</head>
<body>
<main>
<div id="login">
<div class="header">
    <h2>Login</h2>
</div>

<form method="post" action="logreg.php">
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
    <p> Not a member? <a id= regclick href="#register">register</a></p>
</form>
</div>
<div id="register">
<div class="header">
    <h2>Register</h2>
</div>

<form method="post" action="logreg.php#register">
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
        <button id=regbutton type="submit" name="register" class="btn">
            <span>REGISTER </span></button>
    </div>
    <p>Already a member? <a id=logclick href="#login">login</a></p>

</form>
</div>
</main>
</body>
<script src="js/logreg.js"></script>
</html>