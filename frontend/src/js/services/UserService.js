/**
 * Servicio para gestionar las peticiones relacionadas con usuarios.
 */
class UserService {

    /**
     * Envía una petición para crear un nuevo usuario.
     * @param {Object} credentialInformation - Información de las credenciales del usuario.
     * @property {string} credentialInformation.userName - Nombre del usuario.
     * @property {string} credentialInformation.lastName - Apellido del usuario.
     * @property {string} credentialInformation.emailAddress - Correo electrónico del usuario.
     * @property {string} credentialInformation.userPassword - Contraseña del usuario.
     * @returns {Promise<Object>} - Retorna una promesa que resuelve a los datos de la respuesta o un objeto de error.
     */
    async requestToCreateUser(credentialInformation) {
        try {
            const response = await fetch('http://localhost:3000/user', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(credentialInformation)
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Error en la petición.');
            }

            const data = await response.json();

            if (data.status === 'error') {
                throw new Error(data.message);
            }

            return data;

        } catch (error) {
            console.error('Error en la petición:', error.message);
            alert('Hubo un error al procesar la solicitud. Inténtalo de nuevo.');
            return { status: 'error', message: error.message };
        }
    }

    /**
     * Envía una petición para iniciar sesión con las credenciales del usuario.
     * @param {Object} credentialInformation - Información de inicio de sesión del usuario.
     * @property {string} credentialInformation.emailAddress - Correo electrónico del usuario.
     * @property {string} credentialInformation.userPassword - Contraseña del usuario.
     * @returns {Promise<Object>} - Retorna una promesa que resuelve a los datos de la respuesta o un objeto de error.
     */
    async requestToLogin(credentialInformation) {
        try {
            const response = await fetch('http://localhost:3000/user/login', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(credentialInformation)
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Error en la petición.');
            }

            const data = await response.json();

            if (data.status === 'error') {
                throw new Error(data.message);
            }

            return data;
        } catch (error) {
            console.error('Error en la petición:', error.message);
            alert(error.message);
            return { status: 'error', message: error.message };
        }
    }

    /**
     * Envía una petición para cerrar sesión del usuario actual.
     * @returns {Promise<Object>} - Retorna una promesa que resuelve a los datos de la respuesta o un objeto de error.
     */
    async requestToLogout() {
        try {
            const response = await fetch('http://localhost:3000/user/logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    'userId': window.localStorage.getItem('userId')
                })
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Error en la petición.');
            }

            const data = await response.json();

            if (data.status === 'error') {
                throw new Error(data.message);
            }

            return data;

        } catch (error) {
            console.error('Error en la petición:', error.message);
            alert(error.message);
            return { status: 'error', message: error.message };
        }
    }
}

export default UserService;