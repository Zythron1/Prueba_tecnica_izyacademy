// Importación de los módulos necesarios para el controlador
import UserModel from "../models/UserModel.js"; // Modelo para la validación y gestión de datos de usuario
import UserService from "../services/UserService.js"; // Servicio para realizar las peticiones al backend


// Creación de instancias de las clases importadas
const UserModelInstance = new UserModel();
const UserServiceInstance = new UserService();



class UserController {
    /**
     * Método para crear un usuario.
     * @param {Object} credentialInformation - Información del usuario (nombre, correo, contraseña, etc.)
     */
    async createUser(credentialInformation) {
        // Validación de los datos del usuario
        if (!UserModelInstance.validateUserData(credentialInformation)) {
            return; // Detener si los datos no son válidos
        }

        // Solicitar al servicio la creación del usuario
        const data = await UserServiceInstance.requestToCreateUser(credentialInformation);

        // Manejo de la respuesta
        if (data.status === 'success') {
            alert(data.message); // Notificar éxito al usuario

            // Reiniciar el formulario de registro
            const form = document.getElementById('register-form');
            form.reset();
        } else {
            alert(data.message); // Mostrar mensaje de error
        }
    }

    /**
     * Método para iniciar sesión.
     * @param {Object} credentialInformation - Credenciales del usuario (correo y contraseña)
     */
    async login(credentialInformation) {
        // Validación de las credenciales de inicio de sesión
        if (!UserModelInstance.validateUserDataLogin(credentialInformation)) {
            return; // Detener si las credenciales no son válidas
        }

        // Solicitar al servicio iniciar sesión
        const data = await UserServiceInstance.requestToLogin(credentialInformation);

        // Manejo de la respuesta
        if (data.status === 'success') {
            alert(data.message); // Notificar éxito al usuario

            // Almacenar datos del usuario en localStorage
            localStorage.setItem('userId', data.userId);
            localStorage.setItem('userName', data.userName);

            // Redirigir a la página principal
            window.location.href = 'http://localhost:3000/frontend/src/html/index.html';
        }
        return;
    }

    /**
     * Método para cerrar sesión.
     */
    async logout() {
        // Obtener el ID del usuario desde localStorage
        let userId = localStorage.getItem('userId');

        // Verificar si hay una sesión activa
        if (!userId) {
            alert('No tienes una sesión abierta'); // Notificar al usuario
            return;
        }

        // Solicitar al servicio cerrar sesión
        const data = await UserServiceInstance.requestToLogout();

        // Manejo de la respuesta
        if (data && data.status === 'success') {
            alert(data.message); // Notificar éxito al usuario

            // Eliminar datos del usuario de localStorage
            localStorage.removeItem('userId');
            localStorage.removeItem('userName');

            // Redirigir a la página principal
            window.location.href = 'http://localhost:3000/frontend/src/html/index.html';
        }
    }
}

export default UserController;