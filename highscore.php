<?php
include("../server.php");

if (empty($_SESSION["username"])) {
    header("location: ../logreg.php");
} ?>
<!DOCTYPE html>
<html>
<head>
    <title>Highscores</title>
    <link rel="stylesheet" type="text/css" href="css/highscores.css">

</head>
<body>

<h1>HIGHSCORES</h1>
<nav>
    <ul>
        <li><a href="prontpage.php">Frontpage</a></li>
        <li>Account
            <ul>
                <li><a href="game.php">Account settings</a></li>
                <li><a href="highscore.php?name=<?php echo ($_SESSION['username'])?>">Personal Highscore</a></li>
                <li><a href="prontpage.php?logout='1'" style="color: red;">logout</a></li>
            </ul>
        </li>
        <li>Game
            <ul>
                <li><a href="game.php">Play the game</a></li>
                <li><a href="22.html">Read the rules</a></li>
                <li><a href="23.html">Item 2.3</a></li>
                <li><a href="24.html">Item 2.4</a></li>
            </ul>
        </li>
        <li><a href="highscore.php">Highscores</a></li>
    </ul>
</nav>
<div id="divi"></div>
<h2>Search for highcores with a username</h2>

<form action="highscore.php" method="get">
    Username: <input type="text" name="name">
    <input type="submit" id="asd">
</form>
<h3 id="search"></h3>
<div id="divi2"></div>

<?php
    include("highscore_SQL.php");
    $yhteys = new SQL();
    $hae = null;
    //$yhteys->makeConnection();
    //$yhteys->hae();
if (isset($_POST["name"]) && isset($_POST["points"])) {
    $yhteys->tee(($_POST["points"]), ($_POST["name"]));
    echo "<p>AAAAAAAAAAAAAAAAAAA</p>";
}
?>
<script>
    var onko = false;
    function naytaKaikki(response) {
        var arr = JSON.parse(response);
        arr.sort(function(a, b){
          return (b.Points) - (a.Points);
        });
        var i;
        var out = "<table>";

        for(i = 0; i < arr.length; i++) {
            out += "<tr><td>" +
                arr[i].User +
                "</td><td>" +
                arr[i].Points +
                "</td></tr>";
        }
        out += "</table>";
        document.getElementById("divi").innerHTML = out;
        document.getElementById("divi").style.textAlign = 'center';
    }
    window.onload = naytaKaikki('<?php echo $yhteys->haeKaikki(); ?>');

        function nayta(response) {
        var arr = JSON.parse(response);
        var i;
        var out = "<table>";

        for(i = 0; i < arr.length; i++) {
            out += "<tr><td>" +
                arr[i].User +
                "</td><td>" +
                arr[i].Points +
                "</td></tr>";
        }
        out += "</table>";
        document.getElementById("divi2").innerHTML = out;
            document.getElementById("search").innerHTML = "Search results";
    }
    var asd <?php
        if (isset($_GET["name"])){
            $hae = $yhteys->hae($_GET["name"]);
            //echo $hae;
            echo "='".$hae."'";
        }
        ?>;

    document.getElementById("asd").onclick = nayta(asd);
</script>


</body>
</html>