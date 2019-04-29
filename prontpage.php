<?php include("server.php");

    if (empty($_session["username"])) {
        header("location: login.php");
    }?>
<!DOCTYPE html>
<html>
<head>
    <title> title 000000</title>
</head>
<body>
    <div class="header">
        <h2> HOME PAGE </h2>
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
            <p>Welcome <strong<?php echo $_SESSION["username"]; ?></p>
            <P><a href="prontpage.php?logout='1'" style="color: red;">logout</a></P>
        <?php endif ?>
    </div>



</body>


</html>
