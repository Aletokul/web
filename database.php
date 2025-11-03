<?php
require_once("constants.php");

class Database {
    private $conn;

    public function __construct($configFile = "config.ini") {
        if ($config = parse_ini_file($configFile)) {
            $host = $config["host"];
            $database = $config["database"];
            $user = $config["user"];
            $password = $config["password"];
            try {
                $this->conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Konekcija neuspešna: " . $e->getMessage());
            }
        }
    }

    public function __destruct() {
        $this->conn = null;
    }

    public function storeRememberToken($user_id, $token) {
        $sql = "UPDATE users SET remember_token = :token WHERE id = :id";
        $st = $this->conn->prepare($sql);
        $st->bindValue(":token", $token, PDO::PARAM_STR);
        $st->bindValue(":id", $user_id, PDO::PARAM_INT);
        $st->execute();
    }

    public function getUserByToken($token) {
        $sql = "SELECT * FROM users WHERE remember_token = :token";
        $st = $this->conn->prepare($sql);
        $st->bindValue(":token", $token, PDO::PARAM_STR);
        $st->execute();
        return $st->fetch(PDO::FETCH_ASSOC);
    }


    
    // ---------- LOGIN ----------
    public function login($username, $password) {
        $sql = "SELECT * FROM " . TBL_USER . " WHERE " . COL_USER_USERNAME . "=:username";
        $st = $this->conn->prepare($sql);
        $st->bindValue(":username", $username, PDO::PARAM_STR);
        $st->execute();
        $user = $st->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user[COL_USER_PASSWORD])) {
        return $user;
        }
        return false;
    }

    // ---------- REGISTRACIJA ----------
    public function register($username, $password, $role = "user") {
        // Provera da li postoji
        $sql_check = "SELECT * FROM " . TBL_USER . " WHERE " . COL_USER_USERNAME . "=:username";
        $st = $this->conn->prepare($sql_check);
        $st->bindValue(":username", $username, PDO::PARAM_STR);
        $st->execute();
        if ($st->fetch()) return false;

        $sql = "INSERT INTO " . TBL_USER . " (" . COL_USER_USERNAME . "," . COL_USER_PASSWORD . "," . COL_USER_ROLE . ") 
                VALUES (:username, :password, :role)";
        $st = $this->conn->prepare($sql);
        $st->bindValue(":username", $username, PDO::PARAM_STR);
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $st->bindValue(":password", $hashed, PDO::PARAM_STR);
        $st->bindValue(":role", $role, PDO::PARAM_STR);
        return $st->execute();
    }

    // ---------- TERENI ----------
    public function getFields() {
        $sql = "SELECT * FROM " . TBL_FIELDS;
        $st = $this->conn->query($sql);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFieldById($field_id) {
        $sql = "SELECT * FROM fields WHERE id = :id";
        $st = $this->conn->prepare($sql);
        $st->bindValue(":id", $field_id, PDO::PARAM_INT);
        $st->execute();
        return $st->fetch(PDO::FETCH_ASSOC);
    }


    public function getReservationsByFieldAndDate($field_id, $date) {
        $sql = "SELECT r.*, u.username, f.name AS field_name
                FROM reservations r
                JOIN users u ON r.user_id = u.id
                JOIN fields f ON r.field_id = f.id
                WHERE r.field_id = :field_id AND r.date = :date
                ORDER BY r.start_time";
        $st = $this->conn->prepare($sql);
        $st->bindValue(":field_id", $field_id, PDO::PARAM_INT);
        $st->bindValue(":date", $date, PDO::PARAM_STR);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }


    public function insertField($name, $sport) {
        $sql = "INSERT INTO " . TBL_FIELDS . " (" . COL_FIELD_NAME . "," . COL_FIELD_SPORT . ") VALUES (:name, :sport)";
        $st = $this->conn->prepare($sql);
        $st->bindValue(":name", $name, PDO::PARAM_STR);
        $st->bindValue(":sport", $sport, PDO::PARAM_STR);
        return $st->execute();
    }


    public function getUserReservations($user_id) {
        $sql = "SELECT r.*, f." . COL_FIELD_NAME . " as name
                FROM reservations r
                JOIN " . TBL_FIELDS . " f ON r.field_id = f.id
                WHERE r.user_id = :user_id
                ORDER BY r.date, r.start_time";
        $st = $this->conn->prepare($sql);
        $st->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }


    public function insertReservation($user_id, $field_id, $date, $start_time, $end_time) {
        // 1. Uzima tip terena
        $sql_type = "SELECT COALESCE(type, sport_type) AS tip FROM fields WHERE id = :field_id";
        $st = $this->conn->prepare($sql_type);
        $st->bindValue(":field_id", $field_id, PDO::PARAM_INT);
        $st->execute();
        $field = $st->fetch(PDO::FETCH_ASSOC);

        if (!$field) return false;

        $type = $field['tip'];

        // 2. Provera preklapanja samo po tipu terena
        $sql_check = "SELECT r.* 
                    FROM reservations r
                    JOIN fields f ON r.field_id = f.id
                    WHERE COALESCE(f.type, f.sport_type) = :type
                        AND r.date = :date
                        AND (:start_time < r.end_time AND :end_time > r.start_time)";
        $st = $this->conn->prepare($sql_check);
        $st->bindValue(":type", $type, PDO::PARAM_STR);
        $st->bindValue(":date", $date, PDO::PARAM_STR);
        $st->bindValue(":start_time", $start_time, PDO::PARAM_STR);
        $st->bindValue(":end_time", $end_time, PDO::PARAM_STR);
        $st->execute();

        if ($st->fetch()) {
            return false; // postoji preklapanje istog tipa terena
        }

        // 3. Ubacivanje rezervacije
        $sql_insert = "INSERT INTO reservations (user_id, field_id, date, start_time, end_time)
                    VALUES (:user_id, :field_id, :date, :start_time, :end_time)";
        $st = $this->conn->prepare($sql_insert);
        $st->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $st->bindValue(":field_id", $field_id, PDO::PARAM_INT);
        $st->bindValue(":date", $date, PDO::PARAM_STR);
        $st->bindValue(":start_time", $start_time, PDO::PARAM_STR);
        $st->bindValue(":end_time", $end_time, PDO::PARAM_STR);
        return $st->execute();
    }   

    public function getAllReservations() {
        $sql = "SELECT r.*, f.name as field_name 
                FROM reservations r 
                JOIN fields f ON r.field_id = f.id
                ORDER BY r.date, r.start_time";
        $st = $this->conn->query($sql);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteReservation($id) {
        $sql = "DELETE FROM reservations WHERE id = :id";
        $st = $this->conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        return $st->execute();
    }

    public function updateReservationTime($id, $start_time, $end_time) {
        $sql = "UPDATE reservations SET start_time=:start_time, end_time=:end_time WHERE id=:id";
        $st = $this->conn->prepare($sql);
        $st->bindValue(":start_time", $start_time);
        $st->bindValue(":end_time", $end_time);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        return $st->execute();
    }




    

}


?>
