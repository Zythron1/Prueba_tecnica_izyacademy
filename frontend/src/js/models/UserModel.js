class UserModel {

    validateUserData(credentialInformation) {

        if (credentialInformation.userPassword !== credentialInformation.confirmPassword) {
            alert('Las contraseñas no coinciden');
            return false;
        }

        const nameRegex = /^[A-Za-zÁÉÍÓÚáéíóúÜüÑñ]+(?:\s[A-Za-zÁÉÍÓÚáéíóúÜüÑñ]+)*$/;
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/;

        if (!nameRegex.test(credentialInformation.userName) ) {
            alert('Nombre de usuario no válido');
            return false;
        } else if (!nameRegex.test(credentialInformation.lastName)) {
            alert('Apellido no válido');
            return false;
        } else if (!emailRegex.test(credentialInformation.emailAddress)) {
            alert('Email no válido')
            return false;
        } else if (!passwordRegex.test(credentialInformation.userPassword)) {
            alert('Contraseña no válida. Min 8 caracteres una letra mayúscula, minúscula y un número.')
            return false;
        } else {
            return true;    
        }
    }
}

export default UserModel;