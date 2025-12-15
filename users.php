<?php
class User {
    private $conn;
    private $table = "users";

    public function __construct($db){
        $this->conn = $db;
    }

    // === REGISTER ===
    public function register($username, $email, $password, $role = 'user') {
        // cek dulu apakah username atau email sudah ada
        $check = $this->conn->prepare("SELECT id FROM $this->table WHERE username=? OR email=?");
        $check->bind_param("ss", $username, $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            return false; // username/email sudah dipakai
        }

        // hash password
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // insert data baru
        $stmt = $this->conn->prepare("INSERT INTO $this->table (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashed, $role);
        return $stmt->execute();
    }

    // === LOGIN ===
    public function login($username, $password) {
        // login bisa pakai username atau email
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE username=? OR email=?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result && password_verify($password, $result['password'])) {
            return $result; // sukses login
        }

        return false; // gagal
    }
}
?>
