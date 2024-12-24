// -------------------------------  Imports and Instances  -------------------------------
import UserController from "./frontend/src/js/controllers/UserController.js";

const UserControllerInstance = new UserController();

// -------------------------------  PÁGINA PRINCIPAL  -------------------------------

// Selección de elementos relacionados con los términos y condiciones
const checkboxTerminos = document.getElementById('terminos');
const checkboxTratamientoDatos = document.getElementById('tratamiento-datos');
const registerButton = document.getElementById('register-button');

// Actualización del estado del botón de registro según los checkboxes
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

    // Manejo del menú de navegación
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


    // Abrir y cerrar secciones del footer
    const footerButtons = document.querySelectorAll(".footer__button");
    const footerLists = document.querySelectorAll(".footer__list");

    footerButtons.forEach((button, index) => {
        button.addEventListener("click", () => {
            const footerContent = footerLists[index];
            footerContent.classList.toggle("hidden");
        });
    });


    // Cambio de estado del botón de inicio/cierre de sesión
    let userId = window.localStorage.getItem('userId');
    let userName = window.localStorage.getItem('userName');

    if (userId && userName) {
        // Si el usuario está autenticado, mostrar botón para cerrar sesión
        const buttonSignOut = document.getElementById('login-button-2');
        buttonSignOut.textContent = `${userName} | Cerrar sesión`;

        buttonSignOut.addEventListener('click', () => {
            UserControllerInstance.logout();
        });
    } else {
        // Manejo del modal de inicio de sesión si no está autenticado
        const modalOverlay = document.getElementById('modal-login');
        const navigationLink = document.getElementById('login-button-2');
        const modalCloseButton = document.getElementById('login-close');

        const openModal = () => modalOverlay.classList.remove('hidden');
        const closeModal = () => modalOverlay.classList.add('hidden');

        navigationLink.addEventListener('click', (event) => {
            event.preventDefault();
            openModal();
        });

        modalCloseButton.addEventListener('click', closeModal); // Cerrar modal
        modalOverlay.addEventListener('click', (event) => {
            if (event.target === modalOverlay) closeModal(); // Cerrar modal si se hace clic fuera
        });
    }
});

// Manejo del formulario de registro de usuarios
const registerForm = document.getElementById('register-form');
registerForm.addEventListener('submit', e => {
    e.preventDefault();

    // Obtener valores del formulario
    const name = document.getElementById('name').value;
    const lastName = document.getElementById('last-name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    // Crear un objeto con la información del usuario
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

// Manejo del formulario de inicio de sesión
const loginForm = document.getElementById('login-form');
loginForm.addEventListener('submit', e => {
    e.preventDefault(); // Prevenir comportamiento por defecto del formulario

    // Obtener valores del formulario
    const emailLogin = document.getElementById('email-login').value;
    const passwordLogin = document.getElementById('password-login').value;

    // Crear un objeto con la información del usuario
    let credentialInformation = {
        'emailAddress': emailLogin,
        'userPassword': passwordLogin
    };

    UserControllerInstance.login(credentialInformation);
});
