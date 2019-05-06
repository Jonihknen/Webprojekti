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
            $sql = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $sql->bind_param("ss", $username,$password_1);
            $sql->execute();
            $_SESSION["username"] = $username;
            $_SESSION["success"] = "You are now logged in";
            header("location: prontpage.php");
        }

    }
    if(isset($_POST["login"])){
        $username = strip_tags($_POST["username"]);
        $password = strip_tags($_POST["password"]);

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


    if (isset($_GET["logout"])) {
        session_destroy();
        unset($_SESSION["username"]);
        header("location: logreg.php");
    }