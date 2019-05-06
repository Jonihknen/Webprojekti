<?php include("server.php");

    if (empty($_SESSION["username"])) {
        header("location: logreg.php");
    }?>
<!DOCTYPE html>
<html>
<head>
    <title>Selainpeli X</title>
    <link rel="stylesheet" type="text/css" href="css/prontpage.css">
</head>
<body>
    <div class="header">
        <h1> HOME PAGE </h1>
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
            <p>Welcome <?php echo $_SESSION["username"]; ?></p>
        <?php endif ?>
    </div>

    <nav>
        <ul>
            <li>Account
            <ul>
                <li><a href="game.html">Account settings</a></li>
                <li><a href="highscores/highscore.php?name=<?php echo ($_SESSION['username'])?>">Highscore</a></li>
                <li><a href="prontpage.php?logout='1'" style="color: red;">logout</a></li>
            </ul>
            </li>
            <li>Game
                <ul>
                    <li><a href="game/game.php">Play the game</a></li>
                    <li><a href="22.html">Read the rules</a></li>
                    <li><a href="23.html">Item 2.3</a></li>
                    <li><a href="24.html">Item 2.4</a></li>
                </ul>
            </li>
            <li><a href="highscores/highscore.php">Highscores</a></li>
        </ul>
    </nav>
<p>fill</p>
    <p>fill</p>
    <p>fill</p>
    <p>fill</p>
    <p>fill</p>
    <p>fill</p>
    <p>fill</p>
    <p>fill</p>
    <p>fill</p>
    <p>fill</p>
    <p>fill</p>
    <p>fill</p><p>fill</p>
    <p>fill</p>


</body>


</html>
