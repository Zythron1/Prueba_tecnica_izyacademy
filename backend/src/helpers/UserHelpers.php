<?php

class UserHelpers {
    public static function  validateRegistrationCredentials($credentialInformation) {
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

}