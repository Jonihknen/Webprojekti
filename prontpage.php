<?php include("server.php");

    if (empty($_SESSION["username"])) {
        header("location: logreg.php");
    }?>
<!DOCTYPE html>
<html>
<head>
    <title>Selainpeli X</title>
    <link rel="stylesheet" type="text/css" href="css/prontpage.css">
    <script src="//cdn.jsdelivr.net/npm/phaser@3.1.1/dist/phaser.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<body>

<?php include("game.php");?>

    <div class="header">
        <h1> HOME PAGE </h1>
    </div>

<div id="peli" class="peli"></div>

<h3>RULES</h3>
<p>WASD to move the player</p>
<p>Spacebar to shoot</p>
<p>Get points for killing enemies. Collect berries to get more hp. Shieldkills give extra points.</p>
<p>You can poke enemies with your mouse but it doesn't do anything...</p>

<div id="valikko" class="valikko">
    <nav>
        <ul>
            <li><a href="highscore.php?name=">Highscores</a></li>
        </ul>
    </nav>
</div>

<div class="content">
    <?php if (isset($_SESSION["success"])): ?>
        <div class="error success">
            <h3>
                <?php
                echo $_SESSION["success"];
                unset($_SESSION["success"]);
                ?>
            </h3>
        </div>
    <?php endif ?>

    <?php if (isset($_SESSION["username"])): ?>
        <p><?php echo $_SESSION["username"]; ?></p>
        <p><a href="account.php">Account settings</a></p>
        <p><a href="highscore.php?name=<?php echo ($_SESSION['username'])?>">Personal Highscore</a></p>
        <a href="prontpage.php?logout='1'" style="color: red;">logout</a>
    <?php endif ?>
</div>
</body>



</html>
