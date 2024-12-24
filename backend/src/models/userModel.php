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
 * Clase UserModel
 * 
 * Maneja las operaciones relacionadas con los usuarios en la base de datos,
 * incluyendo la creación de usuarios, inicio de sesión y cierre de sesión.
 */
class UserModel {

    /**
     * Crea un nuevo usuario en la base de datos.
     * 
     * @param PDO $connection Objeto de conexión a la base de datos.
     * @param array $credentialInformation Array asociativo con la información del usuario. Contiene:
     * - 'userName' (string): Nombre del usuario.
     * - 'lastName' (string): Apellido del usuario.
     * - 'emailAddress' (string): Correo electrónico del usuario.
     * - 'userPassword' (string): Contraseña del usuario.
     * 
     * @return mixed Retorna el ID del usuario recién creado en caso de éxito o `false` en caso de error.
     */
    public function createUser($connection, $credentialInformation) {
        $hashedPassword = password_hash($credentialInformation['userPassword'], PASSWORD_BCRYPT);

        $stmt = $connection->prepare('INSERT INTO users (user_name, user_lastname, email_address, user_password) VALUES (:userName, :userLastname, :emailAddress, :userPassword);');

        $stmt->bindParam(':userName', $credentialInformation['userName'], PDO::PARAM_STR);
        $stmt->bindParam(':userLastname', $credentialInformation['lastName'], PDO::PARAM_STR);
        $stmt->bindParam(':emailAddress', $credentialInformation['emailAddress'], PDO::PARAM_STR);
        $stmt->bindParam(':userPassword', $hashedPassword, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return $connection->lastInsertId();
        } else {
            return false;
        }
    }

    /**
     * Inicia sesión de usuario validando las credenciales proporcionadas.
     * 
     * @param PDO $connection Objeto de conexión a la base de datos.
     * @param array $credentialInformation Array asociativo con las credenciales del usuario. Contiene:
     * - 'emailAddress' (string): Correo electrónico del usuario.
     * - 'userPassword' (string): Contraseña del usuario.
     * 
     * @return array Array asociativo con el resultado del inicio de sesión:
     * - 'status' (string): "success" si el inicio de sesión es exitoso, "error" en caso contrario.
     * - 'message' (string): Mensaje descriptivo del resultado.
     * - 'userId' (int): ID del usuario (si el inicio de sesión es exitoso).
     * - 'userName' (string): Nombre del usuario (si el inicio de sesión es exitoso).
     */
    public function login($connection, $credentialInformation) {
        if (isset($_SESSION['userId'])) {
            return [
                'status' => 'error',
                'message' => 'Hay una sesión abierta en este momento.'
            ];
        }

        $stmt = $connection->prepare('SELECT user_id, user_name, email_address, user_password FROM users WHERE email_address = :emailAddress');
        $stmt->bindParam(':emailAddress', $credentialInformation['emailAddress'], PDO::PARAM_STR);
        $stmt->execute();
        $realUserData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$realUserData) {
            return [
                'status' => 'error',
                'message' => 'El email no existe.'
            ];
        }

        if (!password_verify($credentialInformation['userPassword'], $realUserData['user_password'])) {
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
            'userName' => $realUserData['user_name']
        ];
    }

    /**
     * Cierra la sesión activa del usuario.
     * 
     * @return array Array asociativo con el resultado del cierre de sesión:
     * - 'status' (string): "success" si el cierre de sesión es exitoso, "error" en caso contrario.
     * - 'message' (string): Mensaje descriptivo del resultado.
     * - 'messageToDeveloper' (string): Información adicional para el desarrollador en caso de error.
     */
    public function logout() {
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

        $_SESSION = [];

        return [
            'status' => 'success',
            'message' => 'Sesión cerrada exitosamente.',
            'messageToDeveloper' => 'Ningún error.'
        ];
    }
}