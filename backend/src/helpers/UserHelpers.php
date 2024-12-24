<?php
/**
 * Clase UserHelpers
 * 
 * Proporciona métodos auxiliares para la validación de credenciales
 * de registro e inicio de sesión de usuarios.
 */
class UserHelpers{

    /**
     * Valida las credenciales de registro proporcionadas por el usuario.
     * 
     * @param array $credentialInformation Array asociativo con la información de registro del usuario. 
     * Contiene:
     * - 'userPassword' (string): Contraseña del usuario.
     * - 'confirmPassword' (string): Confirmación de la contraseña.
     * - 'userName' (string): Nombre del usuario.
     * - 'lastName' (string): Apellido del usuario.
     * - 'emailAddress' (string): Correo electrónico del usuario.
     * 
     * @return array Array asociativo con el resultado de la validación:
     * - 'status' (string): "success" si las credenciales son válidas, "error" en caso contrario.
     * - 'message' (string): Mensaje descriptivo del resultado de la validación.
     */
    public static function validateRegistrationCredentials($credentialInformation) {
        if ($credentialInformation['userPassword'] !== $credentialInformation['confirmPassword']) {
            return [
                'status' => 'error',
                'message' => 'Las contraseñas no coinciden.'
            ];
        }

        if (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÜüÑñ]+(?:\s[A-Za-zÁÉÍÓÚáéíóúÜüÑñ]+)*$/', $credentialInformation['userName'])) {
            return [
                'status' => 'error',
                'message' => 'Nombre de usuario no válido'
            ];
        }

        if (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÜüÑñ]+(?:\s[A-Za-zÁÉÍÓÚáéíóúÜüÑñ]+)*$/', $credentialInformation['lastName'])) {
            return [
                'status' => 'error',
                'message' => 'Apellido no válido'
            ];
        }

        if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $credentialInformation['emailAddress'])) {
            return [
                'status' => 'error',
                'message' => 'Email no válido'
            ];
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/', $credentialInformation['userPassword'])) {
            return [
                'status' => 'error',
                'message' => 'Contraseña no válida. Min 8 caracteres una letra mayúscula, minúscula y un número.'
            ];
        } else {
            return [
                'status' => 'success',
                'message' => 'Credenciales válidas'
            ];
        }
    }

    /**
     * Valida las credenciales de inicio de sesión proporcionadas por el usuario.
     * 
     * @param array $credentialInformation Array asociativo con la información de inicio de sesión del usuario.
     * Contiene:
     * - 'emailAddress' (string): Correo electrónico del usuario.
     * - 'userPassword' (string): Contraseña del usuario.
     * 
     * @return array Array asociativo con el resultado de la validación:
     * - 'status' (string): "success" si las credenciales son válidas, "error" en caso contrario.
     * - 'message' (string): Mensaje descriptivo del resultado de la validación.
     */
    public static function validateLoginCredentials($credentialInformation) {
        if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $credentialInformation['emailAddress'])) {
            return [
                'status' => 'error',
                'message' => 'Email no válido'
            ];
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/', $credentialInformation['userPassword'])) {
            return [
                'status' => 'error',
                'message' => 'Contraseña no válida. Min 8 caracteres una letra mayúscula, minúscula y un número.'
            ];
        } else {
            return [
                'status' => 'success',
                'message' => 'Credenciales válidas'
            ];
        }
    }
}