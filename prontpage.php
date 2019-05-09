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
            <li class="left"><a href="highscore.php?name=">Highscores</a></li>
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">you are logged in as <?php echo ($_SESSION['username'])?></a>
                <div class="dropdown-content">
                    <a href="account.php">Account settings</a>
                    <a href="highscore.php?name=<?php echo ($_SESSION['username'])?>">Personal Highscore</a>
                    <a href="prontpage.php?logout='1'" style="color: red;">logout</a>
                </div>
            </li>
        </ul>
    </nav>
</div>

<?php include("game.php");?>

    <div class="header">
        <h1> PELIN NIMI </h1>
    </div>

<div id="peli" class="peli"></div>

<h3>RULES</h3>
<p>WASD to move the player</p>
<p>Spacebar to shoot 20</p>
<p>Get points for killing enemies. Collect berries to get more hp. Shieldkills give extra points.</p>
<p>You can poke enemies with your mouse but it doesn't do anything...</p>


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

    <?php endif ?>
</div>
</body>



</html>
