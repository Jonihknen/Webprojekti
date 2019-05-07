<?php include("server.php");?>
<!DOCTYPE html>
<html>
<head>
    <title>Account settings</title>
    <link rel="stylesheet" type="text/css" href="css/highscores.css">

</head>
<body>

<h1>Account settings</h1>

<h2>Username: <?php echo ($_SESSION['username'])?></h2>

<h3>Highscore: <?php $pisteet = vertaa(($_SESSION['username']));
    echo $pisteet["points"]
    ?>

</h3>

<form action="account.php" method="post">
    <?php include("errors.php"); ?>
    <p>Delete account</p>
    Password: <input type="password" name="delete">
    <input type="submit">
</form>
