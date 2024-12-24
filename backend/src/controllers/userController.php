<?php

/**
    * Carga de archivos necesarios.
    * 
    * Se incluyen los archivos PHP necesarios para el funcionamiento del backend.
    * 
    * - `dbConnection.php`: Configuración de la conexión a la base de datos.
    * - `UserModel.php`: Modelo para gestionar la información y operaciones relacionadas con los usuarios.
    * - `UserHelpers.php`: Funciones auxiliares relacionadas con los usuarios, como validaciones de datos.
*/
require_once './backend/src/config/dbConnection.php';
require_once './backend/src/models/UserModel.php';
require_once './backend/src/helpers/UserHelpers.php';


class UserController {
    private $connection;
    private $userModel;

    /**
        * Constructor de la clase.
        *
        * Este método establece la conexión a la base de datos y crea una instancia del modelo de usuario. 
        * Si ocurre un error durante la conexión a la base de datos, se lanza una excepción que detiene 
        * la ejecución y devuelve un mensaje de error con el código de estado HTTP 500.
        *
        * @return void No retorna ningún valor.
    */
    public function __construct () {
        try {
            $this->connection = DatabaseConnection::getConnection();
            $this->userModel = new UserModel();
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Error de conexión a la base de datos'. $e->getMessage()
            ]);
            exit();
        }
    }


    /**
        * Crea un nuevo usuario en la base de datos.
        *
        * Este método permite registrar un nuevo usuario verificando que los datos esenciales sean proporcionados.
        * Si los datos son válidos, llama al modelo para realizar la inserción en la base de datos y responde
        * con el resultado del proceso.
        *
        * @param array $data Datos necesarios para crear el usuario. El arreglo debe contener:
        *     - 'userName': Nombre de usuario (requerido, cadena de texto).
        *     - 'emailAddress': Dirección de correo electrónico (requerido, cadena de texto).
        *     - 'userPassword': Contraseña del usuario (requerido, cadena de texto).
        *
        * @return void No retorna un valor directo, pero responde con un código de estado HTTP y un mensaje:
        *     - HTTP 201: Si el usuario fue creado exitosamente, incluye el ID del nuevo usuario en el mensaje.
        *     - HTTP 400: Si faltan datos requeridos.
        *     - HTTP 500: Si hubo un error al intentar crear el usuario.
    */
    public function createUser ($credentialInformation) {
        $validationResponse = UserHelpers::validateRegistrationCredentials($credentialInformation);

        if ($validationResponse['status'] === 'error') {
            http_response_code(400);
            echo json_encode($validationResponse);
        }

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
        * Inicia sesión de un usuario en el sistema.
        *
        * Este método valida los datos proporcionados para el inicio de sesión. Si los datos son válidos,
        * se llama al modelo para verificar las credenciales del usuario. Según el resultado, responde con
        * los detalles del inicio de sesión o un error.
        *
        * @param array $data Datos necesarios para iniciar sesión. El arreglo debe contener:
        *     - 'emailAddress': Dirección de correo electrónico del usuario (requerido, cadena de texto).
        *     - 'userPassword': Contraseña del usuario (requerido, cadena de texto).
        *
        * @return void No retorna un valor directo, pero responde con un código de estado HTTP y un mensaje:
        *     - HTTP 201: Si el inicio de sesión fue exitoso, incluye detalles del usuario o token en la respuesta.
        *     - HTTP 400: Si los datos enviados no son válidos.
        *     - HTTP 404: Si las credenciales son incorrectas o el usuario no existe.
    */
    public function login ($data) {
        $validationResponse = UserHelpers::validateRegistrationCredentials($data);

        if ($validationResponse['status'] === 'error') {
            http_response_code(400);
            echo json_encode($validationResponse);
        }

        $loginResponse = $this->userModel->login($this->connection, $data);

        if ($loginResponse['status'] === 'error') {
            http_response_code(404);
            echo json_encode($loginResponse);
        } else {
            http_response_code(201);
            echo json_encode($loginResponse);
        }
    }


    /**
        * Cierra la sesión de un usuario en el sistema.
        *
        * Este método llama al modelo para realizar las operaciones necesarias de cierre de sesión,
        * como invalidar tokens o limpiar datos de sesión. Responde según el resultado de la operación.
        *
        * @return void No retorna un valor directo, pero responde con un código de estado HTTP y un mensaje:
        *     - HTTP 200: Si la sesión se cerró exitosamente.
        *     - HTTP 400: Si hubo un error al intentar cerrar la sesión.
    */
    public function logout () {
        $ResponseLogout = $this->userModel->logout();

        if ($ResponseLogout['status'] === 'error') {
            http_response_code(400);
            echo json_encode($ResponseLogout);
        } else {
            http_response_code(200);
            echo json_encode($ResponseLogout);
        }
    }
}