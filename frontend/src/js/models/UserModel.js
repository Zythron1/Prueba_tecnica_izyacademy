class UserModel {

    validateUserData(credentialInformation) {
        if (!credentialInformation.terminos.checked) {
            alert('Debes aceptar los términos y condiciones');
            return false;   
        }

        if (!credentialInformation.tratamientoDatos.checked) {
            alert('Debes aceptar el tratamiento de datos');
            return false;
        }

        if (credentialInformation.userPassword !== credentialInformation.confirmPassword) {
            alert('Las contraseñas no coinciden');
            return false;
        }

        const nameRegex = /^[A-Za-zÁÉÍÓÚáéíóúÜüÑñ]+(?:\s[A-Za-zÁÉÍÓÚáéíóúÜüÑñ]+)*$/;
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/;

        if (!nameRegex.test(data.userName) ) {
            alert('Nombre de usuario no válido');
            return false;
        } else if (!nameRegex.test(data.lastName)) {
            alert('Apellido no válido');
            return false;
        } else if (!emailRegex.test(data.emailAddress)) {
            alert('Email no válido')
            return false;
        } else if (!passwordRegex.test(data.userPassword)) {
            alert('Contraseña no válida. Min 8 caracteres una letra mayúscula, minúscula y un número.')
            return false;
        } else {
            return true;
        }
    }
}

export default UserModel;