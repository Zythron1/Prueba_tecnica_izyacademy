import UserModel from "../models/UserModel.js";
import UserService from "../services/UserService.js";
import UserView from "../views/UserView.js";


const UserModelInstance = new UserModel();
const UserServiceInstance = new UserService();
const UserViewInstance = new UserView;


class UserController {
    async createUser(credentialInformation) {
        if (!UserModelInstance.validateUserData(credentialInformation)) {
            return;
        }

        const data = await UserServiceInstance.requestToCreateUser(credentialInformation);

        if (data.status === 'success') {
            alert(data.message);
            
            const form = document.getElementById('register-form');
            form.reset();
        } else {
            alert(data.message);
        }
    }



    login (data) {
        if (!UserModelInstance.validateUserData(data)) {
            return;
        }

        UserServiceInstance.requestToLogin(data)
            .then(data => {
                if (data.status === 'success') {
                localStorage.setItem('userId', data.userId);
                localStorage.setItem('shoppingBagProducts', JSON.stringify(data.shoppingBag));

                alert(data.message);
                window.location.href = 'http://localhost:3000/frontend/src/html/index.html';
            }
        });
        return;
    }

    logout() {
        let userId = localStorage.getItem('userId');

        if (!userId) {
            alert('No tienes una sessión abierta');
            return;
        } 

        UserServiceInstance.requestToLogout()
        .then(data => {
            if (data.status === 'succes') {
                localStorage.removeItem('userId');
                localStorage.removeItem('shoppingBagProducts');
                alert(data.message);
                UserViewInstance.renderProductInShoppingBag(localStorage.getItem('shoppingBagProducts'));
            }
        });
    }
}

export default UserController;