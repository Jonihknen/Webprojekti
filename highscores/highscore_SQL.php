<?php

class SQL
{

    private $servername = "localhost";
    private $username = "aarop";
    private $password = "kakka";
    private $db = "webprojekti";
    public $conn;

// Create connection
    public function __construct(){
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->db, 3305);
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        //echo "Connected successfully";
    }

    public function haeKaikki(){
        $result = $this->conn->query("SELECT * FROM highscores");

        $outp = "[";
        while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
            if ($outp != "[") {$outp .= ",";}
            $outp .= '{"User":"'  . $rs["user"] . '",';
            $outp .= '"Points":"'   . $rs["points"]        . '"}';
        }
        $outp .="]";
        return ($outp);
    }
    public function hae($haku){
        $haku = strip_tags($haku);
        $query = $this->conn->prepare("SELECT * FROM highscores WHERE user=?");
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
    public function muuta($pisteet, $haku){
        $haku = strip_tags($haku);
        $query = $this->conn->prepare("UPDATE highscores SET points =? WHERE user =?");
        $query->bind_param("ss", $pisteet, $haku);
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
    public function tee($pisteet, $haku){//Tänne pitää viel tehä tarkastus et onko se jo tietokannassa
        $query = $this->conn->prepare("INSERT INTO highscores VALUES (?,?)");
        $query->bind_param("ss",  $haku, $pisteet);
        $query->execute();
        $query->close();
    }

}




?>
