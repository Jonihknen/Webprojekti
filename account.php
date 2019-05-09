<?php include("server.php");

if (empty($_SESSION["username"])) {
    header("location: logreg.php");
}?>
<!DOCTYPE html>
<html>
<head>
    <title>Account settings</title>
    <link rel="stylesheet" type="text/css" href="css/account.css">
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
<?php include("errors.php"); ?>
<div id="delform" class="delform">
<form action="account.php" method="post">
    <p class="normi">Insert password to delete account</p>
    Password: <input type="password" name="delete">
    <input type="submit" value="Delete account">
</form>
    <button id="peruuta">Cancel deletion</button>
</div>

<script src="js/account.js"></script>
</body>
</html>