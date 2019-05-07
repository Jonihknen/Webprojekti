<?php

    session_start();
    $username = "";
    $errors = array();
    $db = OpenCon();

function OpenCon(){

    $conn = new mysqli("localhost", "olso", "olso", "webprojekti", 3306) or die("connect failed: %s\n". $conn->error);

    return $conn;
}

function userexists($username){
    $db = OpenCon();
    $sql = $db->prepare("SELECT * FROM users WHERE username=?");
    $sql->bind_param("s", $username);
    $sql->execute();
    $result = $sql->get_result();
    if (mysqli_num_rows($result) == 1){
        return true;

    } else{
        return false;
    }
}
    //REGISTERING USER
    if (isset($_POST["register"])) {
        $username = strip_tags($_POST["username"]);
        $password_1 = strip_tags($_POST["password"]);
        $password_2 = strip_tags($_POST["password_2"]);

        if (empty($username)) {
            array_push($errors, "Username is required");
        }
        if (empty($password_1)) {
            array_push($errors, "Password is required");
        }

        if ($password_1 != $password_2) {
            array_push($errors, "Passwords do not match");
        }
        if (userexists($username)){
            array_push($errors, "username already exists");
        }
        if (preg_match("/^[0-9A-Za-z_]+$/", $username) == 0) {
            array_push($errors, "invalid username");
        }
        if (strlen($username) < 3) {
            array_push($errors, "username must have at least 3 characters");
        }
        if (strlen($username) > 10) {
            array_push($errors, "username too long (maximum is 10 characters)");
        }
        if (strlen($password_1) < 8) {
            array_push($errors, "password must contain at least 8 characters");

        }

        if(count($errors) == 0) {
            $password_1 = MD5($password_1);
            $sql = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $sql->bind_param("ss", $username,$password_1);
            $sql->execute();
            $_SESSION["username"] = $username;
            $_SESSION["success"] = "You are now logged in";
            header("location: prontpage.php");
        }

    }
    //LOGGING IN
    if(isset($_POST["login"])){
        $username = ($_POST["username"]);
        $password = MD5($_POST["password"]);

        if (empty($username)) {
            array_push($errors, "username is required");
        }
        if (empty($password)) {
            array_push($errors, "password is required");
        }

        if (count($errors) == 0) {
            //$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
            $sql = $db->prepare("SELECT * FROM users WHERE username=? AND password=?");
            $sql->bind_param("ss", $username,$password);
            $sql->execute();
            $result = $sql->get_result();
            //$result = mysqli_query($db, $query);
            if (mysqli_num_rows($result) == 1){
                $_SESSION["username"] = $username;
                $_SESSION["success"] = "you are now logged in";
                header("Location: prontpage.php");
            }else{
                array_push($errors, "wrong username or password");
            }
        }
    }
    //DELETING USER
    if(isset($_POST["delete"])){
        $name = ($_SESSION["username"]);
        $password = MD5($_POST["delete"]);
        if (empty($password)) {
            array_push($errors, "password is required");
        }
        if (count($errors) == 0) {
            $sql = $db->prepare("SELECT * FROM users WHERE username=? AND password=?");
            $sql->bind_param("ss", $name,$password);
            $sql->execute();
            $result = $sql->get_result();
            if (mysqli_num_rows($result) == 1){
                $sql = $db->prepare("DELETE FROM users WHERE username=? AND password=?");
                $sql->bind_param("ss", $name,$password);
                $sql->execute();
                header("Location: prontpage.php?logout='1'");
            }else{
                array_push($errors, "wrong password");
            }
        }
    }


    if (isset($_GET["logout"])) {
        session_destroy();
        unset($_SESSION["username"]);
        header("location: logreg.php");
    }
    function haeKaikki(){
        $db = OpenCon();
        $result = $db->query("SELECT * FROM highscores");

        $outp = "[";
        while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
            if ($outp != "[") {$outp .= ",";}
            $outp .= '{"User":"'  . $rs["user"] . '",';
            $outp .= '"Points":"'   . $rs["points"]        . '"}';
        }
        $outp .="]";
        return ($outp);
    }
    function hae($haku){
        $db = OpenCon();
        $haku = strip_tags($haku);
        $query = $db->prepare("SELECT * FROM highscores WHERE user=?");
        $query->bind_param("s", $haku);
        $query->execute();
        $result = $query->get_result();

        $outp = "[";
        while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
            if ($outp != "[") {$outp .= ",";}
            $outp .= '{"User":"'  . $rs["user"] . '",';
            $outp .= '"Points":"'   . $rs["points"]        . '"}';
        }
        $outp .="]";
        $query->close();
        return ($outp);
    }
    function vertaa($haku){
        $db = OpenCon();
        $haku = strip_tags($haku);
        $query = $db->prepare("SELECT * FROM highscores WHERE user=?");
        $query->bind_param("s", $haku);
        $query->execute();
        $result = $query->get_result();
        $rs = $result->fetch_array(MYSQLI_ASSOC);
        $query->close();
        return($rs);
    }
    function muuta($pisteet, $haku){
        $db = OpenCon();
        $haku = strip_tags($haku);
        $query = $db->prepare("UPDATE highscores SET points =? WHERE user =?");
        $query->bind_param("ss", $pisteet, $haku);
        $query->execute();
        $query->close();
    }
    function tee($pisteet, $haku){
        $nykyiset = vertaa($haku);
        //$rs = $nykyiset->fetch_array(MYSQLI_ASSOC);
        if($nykyiset["user"]!=null){
            if($nykyiset["points"] < $pisteet){
                muuta($pisteet, $haku);
            }
        }
        else{
            $db = OpenCon();
            $query = $db->prepare("INSERT INTO highscores VALUES (?,?)");
            $query->bind_param("ss",  $haku, $pisteet);
            $query->execute();
            $query->close();
        }

    }
