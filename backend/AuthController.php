<?php
require_once '../config/JWT.php';   // Importar la clase JWT
require_once '../config/Database.php';  // Importar la clase Database

use Firebase\JWT\JWT;               // Importar la clase JWT

class AuthController {      
    private $db;
    private $key = "tu_clave_secreta_jwt"; // Cambiar en producción

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function login($email, $password) {
        $query = "SELECT id, password FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
                $token = [
                    "iss" => "tyres_api",
                    "exp" => time() + 3600, // 1 hora
                    "uid" => $row['id']
                ];
                $jwt = 'firebase\jwt\JWT'::encode($token, $this->key);
                echo json_encode(["token" => $jwt]);
            } else {
                http_response_code(401);
                echo json_encode(["error" => "Credenciales inválidas"]);
            }
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Usuario no encontrado"]);
        }
    }
}
?>