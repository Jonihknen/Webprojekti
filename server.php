<?php

    session_start();
    $username = "";
    $errors = array();
    $db = OpenCon();

function OpenCon(){

    $conn = new mysqli("localhost", "olso", "olso", "webprojekti") or die("coccet failed: %s\n". $conn->error);

    return $conn;

}


    if (isset($_POST["register"])) {
        $username = strip_tags($_POST["username"]);
        $password_1 = strip_tags($_POST["password_1"]);
        $password_2 = strip_tags($_POST["password_2"]);

        if (empty($username)) {
            array_push($errors, "username is required");
        }
        if (empty($password_1)) {
            array_push($errors, "password is required");
        }

        if ($password_1 != $password_2) {
            array_push($errors, "Passwords do not match");
        }
        if(count($errors) == 0) {
            $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password_1')";
            mysqli_query($db, $sql);
            $_SESSION["username"] = $username;
            $_SESSION["success"] = "you are now logged in";
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
            $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
            $result = mysqli_query($db, $query);
            if (mysqli_num_rows($result) == 1){
                $SESSION["username"] = $username;
                $_SESSION["success"] = "you are now logged in";
                header("Location: \popo.php");
            }else{
                array_push($errors, "wrong username or password");
            }
        }
    }


    if (isset($_GET["logout"])) {
        session_destroy();
        unset($_SESSION["username"]);
        header("location: login.php");
    }