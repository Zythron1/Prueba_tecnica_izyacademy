// -------------------------------  Imports and Instances  -------------------------------
import UserController from "./frontend/src/js/controllers/UserController.js";
import ProductView from "./frontend/src/js/views/ProductView.js";

const UserControllerInstance = new UserController();
const ProductsViewInstance = new ProductView();

// -------------------------------  PÁGINA PRINCIPAL  -------------------------------
const checkboxTerminos = document.getElementById('terminos');
const checkboxTratamientoDatos = document.getElementById('tratamiento-datos');
const registerButton = document.getElementById('register-button');

const updateButtonState = () => {
    if (checkboxTerminos.checked && checkboxTratamientoDatos.checked) {
        registerButton.disabled = false; // Habilitar botón
        registerButton.classList.remove('opacity50'); // Quitar opacidad
    } else {
        registerButton.disabled = true; // Deshabilitar botón
        registerButton.classList.add('opacity50'); // Añadir opacidad
    }
};


document.addEventListener('DOMContentLoaded', () => {
    updateButtonState();

    checkboxTerminos.addEventListener('change', updateButtonState);
    checkboxTratamientoDatos.addEventListener('change', updateButtonState);
});


// Crear nuevo usuario
const registerForm = document.getElementById('register-form');
registerForm.addEventListener('submit', e => {
    e.preventDefault();

    const name = document.getElementById('name').value;
    const lastName = document.getElementById('last-name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    let credentialInformation = {
        'userName': name,
        'lastName': lastName,
        'emailAddress': email,
        'userPassword': password,
        'confirmPassword': confirmPassword,
        'terminos': checkboxTerminos.checked,  // Asegúrate de enviar el estado del checkbox
        'tratamientoDatos': checkboxTratamientoDatos.checked // Igual aquí
    };

    UserControllerInstance.createUser(credentialInformation);
});




    // Iniciar sesión
    // const loginForm = document.getElementById('login-form');
    // loginForm.addEventListener('submit', e => {
    //     e.preventDefault();

    //     const emailLogin = document.getElementById('email-login').value;
    //     const passwordLogin = document.getElementById('password-login').value;

    //     let data = {
    //         'userName': 'Shanks Ace',
    //         'emailAddress': emailLogin,
    //         'userPassword': passwordLogin
    //     };

    //     UserControllerInstance.login(data);
    // });

