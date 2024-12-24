class UserModel {

    /**
     * Valida los datos ingresados por el usuario durante el registro.
     * @param {Object} credentialInformation - Información de las credenciales del usuario.
     * @property {string} credentialInformation.userName - Nombre del usuario.
     * @property {string} credentialInformation.lastName - Apellido del usuario.
     * @property {string} credentialInformation.emailAddress - Correo electrónico del usuario.
     * @property {string} credentialInformation.userPassword - Contraseña ingresada por el usuario.
     * @property {string} credentialInformation.confirmPassword - Confirmación de la contraseña.
     * @returns {boolean} - Retorna `true` si los datos son válidos, de lo contrario `false`.
     */
    validateUserData(credentialInformation) {
        // Verificar si las contraseñas coinciden
        if (credentialInformation.userPassword !== credentialInformation.confirmPassword) {
            alert('Las contraseñas no coinciden');
            return false;
        }

        // Expresiones regulares para validar los datos
        const nameRegex = /^[A-Za-zÁÉÍÓÚáéíóúÜüÑñ]+(?:\s[A-Za-zÁÉÍÓÚáéíóúÜüÑñ]+)*$/; // Solo letras y espacios
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/; // Correo válido
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/; // Contraseña segura

        // Validar nombre de usuario
        if (!nameRegex.test(credentialInformation.userName)) {
            alert('Nombre de usuario no válido');
            return false;
        }
        // Validar apellido del usuario
        else if (!nameRegex.test(credentialInformation.lastName)) {
            alert('Apellido no válido');
            return false;
        }
        // Validar dirección de correo electrónico
        else if (!emailRegex.test(credentialInformation.emailAddress)) {
            alert('Email no válido');
            return false;
        }
        // Validar contraseña
        else if (!passwordRegex.test(credentialInformation.userPassword)) {
            alert('Contraseña no válida. Debe contener al menos 8 caracteres, una letra mayúscula, una minúscula y un número.');
            return false;
        } else {
            return true;
        }
    }

    /**
     * Valida las credenciales del usuario para iniciar sesión.
     * @param {Object} credentialInformation - Información de inicio de sesión del usuario.
     * @property {string} credentialInformation.emailAddress - Correo electrónico del usuario.
     * @property {string} credentialInformation.userPassword - Contraseña ingresada por el usuario.
     * @returns {boolean} - Retorna `true` si las credenciales son válidas, de lo contrario `false`.
     */
    validateUserDataLogin(credentialInformation) {
        // Expresiones regulares para validar correo electrónico y contraseña
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/; // Correo válido
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/; // Contraseña segura

        // Validar dirección de correo electrónico
        if (!emailRegex.test(credentialInformation.emailAddress)) {
            alert('Email no válido');
            return false;
        }
        // Validar contraseña
        else if (!passwordRegex.test(credentialInformation.userPassword)) {
            alert('Contraseña no válida. Debe contener al menos 8 caracteres, una letra mayúscula, una minúscula y un número.');
            return false;
        } else {
            return true;
        }
    }
}

export default UserModel;