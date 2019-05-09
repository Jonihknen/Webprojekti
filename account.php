<?php include("server.php");?>
<!DOCTYPE html>
<html>
<head>
    <title>Account settings</title>
    <link rel="stylesheet" type="text/css" href="css/account.css">
</head>
<body>

<h1>Account settings</h1>

<h2>Username: <?php echo ($_SESSION['username'])?></h2>

<h3>Highscore: <?php $pisteet = vertaa(($_SESSION['username']));
    if($pisteet["points"]==null){
        echo "No highscores yet.";
    }
    else{
        echo $pisteet["points"];
    }
    ?>

</h3>
<div id="delnappi"><button>Delete account</button></div>

<div id="delform">
<form action="account.php" method="post">
    <?php include("errors.php"); ?>
    <p>Insert password to delete account</p>
    Password: <input type="password" name="delete">
    <input type="submit">
</form>
    <button id="peruuta">Cancel deletion</button>
</div>

<script src="js/account.js"></script>
</body>
</html>