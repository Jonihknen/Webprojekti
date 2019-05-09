<?php
include("server.php");
if (empty($_SESSION["username"])) {
    header("location: logreg.php");
} ?>
<!DOCTYPE html>
<html>
<head>
    <title>Highscores</title>
    <link rel="stylesheet" type="text/css" href="css/highscores.css">

</head>
<body>


<div id="valikko" class="valikko">
    <nav>
        <ul>


            <li class="left"><a href="prontpage.php">Frontpage</a></li>
            <li class="left"><a href="highscore.php">Highscores</a></li>

            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">Account</a>
                <div class="dropdown-content">
                    <a href="account.php">Account settings</a>
                    <a href="highscore.php?name=<?php echo ($_SESSION['username'])?>">Personal Highscore</a>
                    <a href="prontpage.php?logout='1'" style="color: red;">Logout</a>
                </div>
            </li>
            <li class="loginname"><p>You are logged in as <?php echo ($_SESSION['username'])?></p></li>
        </ul>
    </nav>
</div>
<h1>HIGHSCORES TOP 10</h1>

<div id="divi" class="top10"></div>
<h2>Search for highscores with a username</h2>

<form action="highscore.php" method="get">
    Username: <input type="text" name="name">
    <input type="submit" id="getname" value="Search">
</form>
<h3 id="search"></h3>
<div id="divi2"></div>

<?php
if (isset($_POST["name"]) && isset($_POST["points"])) {
    tee(($_POST["points"]), ($_POST["name"]));
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
		for(i = 0; i < 10; i++) {
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
	window.onload = naytaKaikki('<?php echo haeKaikki(); ?>');

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
		if(arr.length == 0){
			document.getElementById("search").innerHTML = "No matching results";
		}
		else{
			document.getElementById("search").innerHTML = "Search results";
		}
	}
    <?php
        if (isset($_GET["name"])){
            $hae = hae($_GET["name"]);
            //echo "='".$hae."'";
            echo "document.getElementById('getname').onclick = nayta('$hae');";
        }
        ?>;
</script>


</body>
</html>