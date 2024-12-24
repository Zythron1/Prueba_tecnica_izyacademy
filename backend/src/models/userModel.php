<?php

// Configuración de la sesión para mejorar la seguridad
/**
    * Se configuran los parámetros de la sesión para garantizar la seguridad.
    * 
    * - Se establece el tiempo de vida de la cookie de la sesión a 0, lo que significa que la cookie de sesión se eliminará
    *   cuando se cierre el navegador.
    * - Se habilita la opción `cookie_httponly`, lo que asegura que la cookie de sesión no sea accesible a través de JavaScript.
    * - Se inicia la sesión con `session_start()`.
    * - Se regenera el ID de sesión al comenzar, lo que ayuda a prevenir ataques de fijación de sesión.
*/
ini_set('session.cookie_lifetime', 0);
ini_set('session.cookie_httponly', true);
session_start();
session_regenerate_id(true);


/**
    * Carga de archivos necesarios.
    * 
    * Se incluyen los archivos PHP necesarios para el funcionamiento del backend.
    * 
    * - `dbConnection.php`: Configuración de la conexión a la base de datos.
    * - `ShoppingBagModel.php`: Modelo para gestionar la bolsa de compras.
    * - `UserHelpers.php`: Funciones auxiliares relacionadas con los usuarios.
    * - `autoload.php`: Carga automática de las dependencias gestionadas por Composer.
*/
require_once './backend/src/config/dbConnection.php';
require_once './backend/src/helpers/UserHelpers.php';

/**
    * Clase UserModel
    * 
    * Esta clase maneja la interacción con la base de datos relacionada con los usuarios.
*/
class UserModel {
    /**
        * Crea un nuevo usuario en la base de datos.
        *
        * Este método inserta un nuevo usuario en la tabla `users` utilizando los datos proporcionados.
        * La contraseña del usuario se encripta antes de ser almacenada en la base de datos.
        * Se utiliza una consulta preparada para evitar inyecciones SQL.
        *
        * @param PDO $connection Instancia de la conexión a la base de datos.
        * @param array $userData Un arreglo asociativo con los datos del usuario:
        *                        - 'userName' => Nombre del usuario.
        *                        - 'emailAddress' => Dirección de correo electrónico del usuario.
        *                        - 'userPassword' => Contraseña del usuario (será encriptada antes de almacenarse).
        *
        * @return int|false El ID del nuevo usuario insertado si la operación es exitosa, o `false` en caso de error.
    */
    public function createUser ($connection, $credentialInformation) {

        $hashedPassword = password_hash($credentialInformation['userPassword'], PASSWORD_BCRYPT);

        $stmt = $connection->prepare('INSERT INTO users (user_name, user_lastname, email_address, user_password) VALUES (:userName, :userLastname, :emailAddress, :userPassword);');

        $stmt->bindParam(':userName', $credentialInformation['userName'], PDO::PARAM_STR);
        $stmt->bindParam(':userLastname', $credentialInformation['lastName'], PDO::PARAM_STR);
        $stmt->bindParam(':emailAddress', $credentialInformation['emailAddress'], PDO::PARAM_STR);
        $stmt->bindParam(':userPassword', $hashedPassword, PDO::PARAM_STR);

        if($stmt->execute()) {
            return $connection->lastInsertId();
        } else {
            return false;
        }
    }


    /**
        * Inicia sesión de un usuario y valida sus credenciales.
        *
        * Este método verifica si el usuario ya tiene una sesión activa. Si no tiene, valida las credenciales proporcionadas
        * (email y contraseña). Si las credenciales son correctas, se inicia la sesión del usuario.
        * Además, se obtiene la bolsa de compras del usuario si existe, y se retorna el estado del inicio de sesión.
        *
        * @param PDO $connection Instancia de la conexión a la base de datos.
        * @param array $data Un arreglo asociativo con los datos del usuario:
        *                    - 'emailAddress' => Dirección de correo electrónico del usuario.
        *                    - 'userPassword' => Contraseña del usuario (será verificada).
        *
        * @return array Un arreglo con el estado y mensaje del inicio de sesión, y los datos del usuario si la autenticación es exitosa:
        *               - 'status' => 'success' o 'error'.
        *               - 'message' => Mensaje correspondiente al estado del login.
        *               - 'userId' => ID del usuario en sesión si el login es exitoso.
        *               - 'shoppingBag' => Bolsa de compras del usuario si existe, o `false` si no tiene productos en la bolsa.
    */
    public function login ($connection, $data) {
        if (isset($_SESSION['userId'])) {
            return [
                'status' => 'error',
                'message' => 'Hay una sessión abierta en este momento.'
            ];
        }

        $stmt = $connection->prepare('SELECT user_id, email_address, user_password FROM users WHERE email_address = :emailAddress');
        $stmt->bindParam(':emailAddress', $data['emailAddress'], PDO::PARAM_STR);
        $stmt->execute();
        $realUserData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$realUserData) {
            return [
                'status' => 'error',
                'message' => 'El email no existe.'
            ];
        }

        if (!password_verify($data['userPassword'], $realUserData['user_password'])) {
            return [
                'status' => 'error',
                'message' => 'La contraseña no coincide.'
            ];
        }

        $_SESSION['userId'] = $realUserData['user_id'];

        
        return [
            'status' => 'success',
            'message' => 'Inicio de sesión exitoso.',
            'userId' => $_SESSION['userId'],
            'shoppingBag' => false,
        ];
    }


    /**
        * Cierra la sesión de un usuario.
        *
        * Este método verifica si el usuario tiene una sesión activa. Si la sesión está activa, intenta destruir la sesión
        * y limpiar la variable `$_SESSION`. Si la sesión no está activa o hay un error en el proceso de cierre, 
        * retorna un mensaje de error. En caso de éxito, retorna un mensaje de confirmación.
        *
        * @return array Un arreglo con el estado y mensaje del cierre de sesión:
        *               - 'status' => 'success' o 'error'.
        *               - 'message' => Mensaje correspondiente al estado del cierre de sesión.
        *               - 'messageToDeveloper' => Información adicional para los desarrolladores, útil para la depuración.
    */
    public function logout () {
        if (empty($_SESSION['userId'])) {
            return [
                'status' => 'error',
                'message' => 'No hay una sesión activa.',
                'messageToDeveloper' => 'No está el userId en la variable $_SESSION.'
            ];
        }
        
        if (!session_unset()) {
            return [
                'status' => 'error',
                'message' => 'Hubo problemas al cerrar sesión, intenta de nuevo.',
                'messageToDeveloper' => 'Error al limpiar la variable $_SESSION.'
            ];
        }
        
        if (!session_destroy()) {
            return [
                'status' => 'error',
                'message' => 'Error al destruir la sesión.',
                'messageToDeveloper' => 'Error al destruir la variable $_SESSION.'
            ];
        }

        $_SESSION= [];
        
        return [
            'status' => 'succes',
            'message' => 'Sesión cerrada exitosamente.',
            'messageToDeveloper' => 'Ningún error.'
        ];
    }
}