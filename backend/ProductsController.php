<?php
header("Content-Type: application/json"); // Establecer el tipo de contenido
header("Access-Control-Allow-Origin: *");   // Permitir solicitudes desde cualquier origen
require_once '../config/Database.php';       // Importar la clase Database

class ProductsController { // Clase para manejar la solicitud
    private $db;            // Objeto de conexión

    /**
     * Constructor de la clase ProductsController
     *
     * Establece la conexión con la base de datos
     */
    public function __construct() { // Constructor
        $database = new Database();     // Crear una instancia de la clase Database
        $this->db = $database->connect();   // Establecer la conexión
    }

    public function getProducts($searchTerm = '') { // Función para obtener productos
        try {
            $query = "SELECT * FROM products    // Consulta SQL    
                     WHERE name LIKE :search OR brand LIKE :search   // Filtro por nombre o marca
                     ORDER BY created_at DESC";
            $stmt = $this->db->prepare($query);     // Preparar la consulta
            $stmt->bindValue(':search', "%$searchTerm%", PDO::PARAM_STR); // Asignar el filtro
            $stmt->execute();                       // Ejecutar la consulta
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtener los productos
            echo json_encode($products);           // Devolver los productos
        } catch (PDOException $e) { // Manejo de errores
            http_response_code(500); // Establecer el codigo de respuesta
            echo json_encode(['error' => 'Error al obtener productos']); // Devolver el error
        }
    }
}

// Manejo de la solicitud
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';   // Obtener el termino de busqueda
$controller = new ProductsController();     // Crear una instancia de la clase ProductsController
$controller->getProducts($searchTerm);      // Obtener los productos
?>