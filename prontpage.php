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

</head>

<body>

<?php include("game.php");?>

    <div class="header">
        <img id=topimage src="/assets/pagemedia/po.jpg">
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

<div class="userbar">
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

    <p class="loggedin">You are logged in as</p>
    <?php if (isset($_SESSION["username"])): ?>
        <p class="username"><?php echo $_SESSION["username"]; ?></p>

        <div class="dropdown">
        <span>▼</span>
            <div class="dropdown-content">
                <p><a href="account.php">Account settings</a></p>
                <p><a href="highscore.php?name=<?php echo ($_SESSION['username'])?>">Personal Highscore</a></p>
                <a href="prontpage.php?logout='1'" style="color: red;">logout</a>
            </div>
        </div>

    <?php endif ?>
</div>
</body>



</html>
