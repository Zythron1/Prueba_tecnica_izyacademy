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
        registerButton.disabled = false;
        registerButton.classList.remove('opacity50');
    } else {
        registerButton.disabled = true;
        registerButton.classList.add('opacity50');
    }
};


document.addEventListener('DOMContentLoaded', () => {
    updateButtonState();

    checkboxTerminos.addEventListener('change', updateButtonState);
    checkboxTratamientoDatos.addEventListener('change', updateButtonState);

    // Selección de elementos
    const hamburgerButton = document.querySelector(".navigation__button-hamburger");
    const menu = document.querySelector(".menu");
    const buttonExitMenu = document.querySelector(".menu__exit");

    // Abrir menú
    hamburgerButton.addEventListener("click", () => {
        menu.classList.add("active"); 
    });

    // Cerrar menú
    buttonExitMenu.addEventListener("click", () => {
        menu.classList.remove("active");
    });

    // Abrir y cerrar contenido footer
    const footerButtons = document.querySelectorAll(".footer__button");
    const footerLists = document.querySelectorAll(".footer__list");

    // Iterar sobre cada botón
    footerButtons.forEach((button, index) => {
        button.addEventListener("click", () => {
            const footerContent = footerLists[index];
            footerContent.classList.toggle("hidden");
        });
    });

    
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
        'terminos': checkboxTerminos.checked,
        'tratamientoDatos': checkboxTratamientoDatos.checked
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

