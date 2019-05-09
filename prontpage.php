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
<div id="valikko" class="valikko">
    <nav>
        <ul>


            <li class="left"><a href="prontpage.php">Frontpage</a></li>
            <li class="left"><a href="highscore.php">Highscores</a></li>

            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">You are logged in as <?php echo ($_SESSION['username'])?></a>
                <div class="dropdown-content">
                    <a href="account.php">Account settings</a>
                    <a href="highscore.php?name=<?php echo ($_SESSION['username'])?>">Personal Highscore</a>
                    <a href="prontpage.php?logout='1'" style="color: red;">Logout</a>
                </div>
            </li>
        </ul>
    </nav>
</div>

<?php include("game.php");?>

    <div class="header">
        <h1> Metropelia </h1>
    </div>

<div id="peli" class="peli"></div>

<div id="rules" class="rules">
<h3>RULES</h3>
<p>WASD to move the player</p>
<p>Spacebar to shoot 20</p>
<p>Get points for killing enemies. Collect berries to get more hp. Shieldkills give extra points.</p>
<p>You can poke enemies with your mouse but it doesn't do anything...</p>
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

</div>
</body>



</html>
