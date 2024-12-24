class UserService {

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



    async requestToLogin (userData) {
        return fetch('http://localhost:3000/user/login', {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(userData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'error') {
                throw new Error(data.message);
            }

            return data;
        })
        .catch(error => {
            console.error('Error en la petición. ' + error);
            alert(error);
            return {'status': 'error'};
        })
    }



    async requestToLogout () {
        return fetch('http://localhost:3000/user/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'  
            },
            body: JSON.stringify({'ShanksAce': 'ShanksAce'})
        }) 
        .then(response => response.json())
        .then(data => {
            if (data.status === 'error') {
                alert(data.message);
                throw new Error(data.messageToDeveloper);
            }

            return data;
        })
        .catch(error => {
            console.error('Error en la petición. ' + error);
            return {'status': 'error'};
        })
    }
    
    /*
    requestToLogout () {
        fetch('http://localhost:3000/user/logout', {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ "ShanksAce1": 'ShanksAce1'})
        })
        .then(response => response.text())
        .then(text => {
            console.log("Respuesta en texto: " + text);
            const data = JSON.parse(text);

            if (data.error === 'error') {
                throw new Error(data.message);
            }

            alert(data.message);
            
        })
        .catch(error => {
            console.error('Error al hacer la petición ' + error);
            alert('Hubo un error al procesar la solicitud. Inténtalo de nuevo.');
            
        })
    }
    */

}

export default UserService;