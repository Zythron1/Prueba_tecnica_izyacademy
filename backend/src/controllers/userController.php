<?php
require_once './backend/src/config/dbConnection.php';
require_once './backend/src/models/UserModel.php';
require_once './backend/src/helpers/UserHelpers.php';

/**
 * Clase UserController
 * Controlador para manejar las operaciones relacionadas con usuarios.
 */
class UserController{
    /**
     * @var PDO $connection Conexión a la base de datos.
     */
    private $connection;

    /**
     * @var UserModel $userModel Instancia del modelo de usuario.
     */
    private $userModel;

    /**
     * Constructor de la clase UserController.
     * Inicializa la conexión a la base de datos y la instancia del modelo de usuario.
     * Maneja errores de conexión a la base de datos.
     *
     * @throws Exception Si ocurre un error en la conexión a la base de datos.
     */
    public function __construct() {
        try {
            $this->connection = DatabaseConnection::getConnection();
            $this->userModel = new UserModel();
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Error de conexión a la base de datos: ' . $e->getMessage()
            ]);
            exit();
        }
    }

    /**
     * Crea un nuevo usuario.
     *
     * @param array $credentialInformation Información del usuario a registrar.
     * @return void
     */
    public function createUser($credentialInformation) {
        // Validar credenciales de registro
        $validationResponse = UserHelpers::validateRegistrationCredentials($credentialInformation);

        if ($validationResponse['status'] === 'error') {
            http_response_code(400);
            echo json_encode($validationResponse);
            return;
        }

        // Crear usuario
        $userId = $this->userModel->createUser($this->connection, $credentialInformation);

        if ($userId) {
            http_response_code(201);
            echo json_encode([
                'status' => 'success',
                'message' => 'Usuario creado exitosamente',
                'userId' => $userId
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al crear el usuario'
            ]);
        }
    }

    /**
     * Inicia sesión de un usuario.
     *
     * @param array $credentialInformation Información de inicio de sesión (email y contraseña).
     * @return void
     */
    public function login($credentialInformation) {
        // Validar credenciales de inicio de sesión
        $validationResponse = UserHelpers::validateLoginCredentials($credentialInformation);

        if ($validationResponse['status'] === 'error') {
            http_response_code(400);
            echo json_encode($validationResponse);
            return;
        }

        // Procesar inicio de sesión
        $loginResponse = $this->userModel->login($this->connection, $credentialInformation);

        if ($loginResponse['status'] === 'error') {
            http_response_code(404);
            echo json_encode($loginResponse);
        } else {
            http_response_code(201);
            echo json_encode($loginResponse);
        }
    }

    /**
     * Cierra sesión de un usuario.
     *
     * @return void
     */
    public function logout() {
        // Procesar cierre de sesión
        $responseLogout = $this->userModel->logout();

        if ($responseLogout['status'] === 'error') {
            http_response_code(400);
            echo json_encode($responseLogout);
        } else {
            http_response_code(200);
            echo json_encode($responseLogout);
        }
    }
}