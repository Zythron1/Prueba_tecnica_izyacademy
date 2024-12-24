# Prueba_tecnica_izyacademy
IziAcademy API

Descripción
La API de IziAcademy es una plataforma diseñada para gestionar usuarios con funcionalidades básicas como registro, inicio de sesión y cierre de sesión. Está construida utilizando PHP con arquitectura MVC y una base de datos MySQL para un manejo eficiente de los datos.

Tecnologías Utilizadas
Backend: PHP 8.x
Base de datos: MySQL 8.x
Frontend: Hecho en HTML, css y JavaScript

Estructura del Proyecto.

IziAcademy/
├── backend/
│   ├── src/
│   │   ├── config/
│   │   │   ├── dbConfig.php           # Configuración general de la base de datos
│   │   │   └── dbConnection.php       # Conexión a la base de datos
│   │   ├── controllers/
│   │   │   └── userController.php     # Controlador para la gestión de usuarios
│   │   ├── helpers/
│   │   │   ├── DecodeEncodeRequestData.php  # Helper para la codificación/decodificación de datos
│   │   │   └── UserHelpers.php        # Funciones de ayuda para la validación de usuarios
│   │   ├── models/
│   │   │   └── userModel.php          # Modelos de datos de usuarios
│   │   ├── routers/
│   │   │   ├── Router.php            # Gestión general de rutas
│   │   │   └── UserRouter.php        # Rutas específicas para usuarios
├── frontend/
│   ├── node_modules/                   # Dependencias de Node.js
│   ├── src/
│   │   ├── assets/                     # Archivos estáticos (imágenes, logos, etc.)
│   │   ├── css/
│   │   │   └── styles.css              # Archivo de estilos globales
│   │   ├── html/
│   │   │   └── index.html              # Archivo HTML principal
│   │   ├── js/
│   │   │   ├── controllers/            # Controladores de JS
│   │   │   │   └── userController.js   # Controlador para la gestión de usuarios en frontend
│   │   │   ├── models/                 # Modelos de JS
│   │   │   │   └── userModels.js       # Modelos de datos de usuarios
│   │   │   └── services/               # Servicios de JS
│   │   │       └── userServices.js     # Lógica de negocio para los usuarios
├── database/
│   └── create_database.sql             # Script SQL para crear la base de datos
├── .gitignore                          # Archivos o carpetas que se deben ignorar por git
├── index.php                           # Archivo principal para el frontend (opcional en algunos casos)
├── main.js                             # Archivo JS principal
├── package-lock.json                   # Archivo de bloqueo de dependencias de Node.js
├── package.json                        # Archivo de configuración de dependencias de Node.js
├── README.md                           # Documentación del proyecto
└── sitio guia.jpeg                     # Imagen de la guía del sitio




Configuración del Entorno
Clona el repositorio:

bash
Copiar código
git clone https://github.com/tu-usuario/IziAcademy.git
cd IziAcademy
Configura la base de datos:

Usa el script SQL proporcionado para crear base de datos:

Copiar código

DROP DATABASE IF EXISTS IziAcademy_db;
CREATE DATABASE IziAcademy_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE IziAcademy_db;

CREATE TABLE users (
    user_id INT NOT NULL AUTO_INCREMENT,
    user_name VARCHAR(50) NOT NULL,
    user_lastname VARCHAR(50) NOT NULL,
    email_address VARCHAR(100) NOT NULL UNIQUE,
    user_password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT current_timestamp,
    PRIMARY KEY (user_id)
);

php

<?php
try {
    $connection = new PDO('mysql:host=localhost;dbname=IziAcademy_db;charset=utf8mb4', 'root', 'tu_password');
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Error: ' . $e->getMessage());
}

Métodos Disponibles en la API

1. Registro de Usuario
Ruta: POST /register
Descripción: Crea un nuevo usuario.
Parámetros:
json
{
  "userName": "string",
  "lastName": "string",
  "emailAddress": "string",
  "userPassword": "string",
  "confirmPassword": "string"
}

Ejemplo de Respuesta:
json
{
  "status": "success",
  "message": "Usuario registrado exitosamente.",
  "userId": 1
}

2. Inicio de Sesión
Ruta: POST /login
Descripción: Inicia sesión para un usuario existente.
Parámetros:
json
{
  "emailAddress": "string",
  "userPassword": "string"
}

Ejemplo de Respuesta:
json
{
  "status": "success",
  "message": "Inicio de sesión exitoso.",
  "userId": 1,
  "userName": "Juan"
}

3. Cerrar Sesión
Ruta: POST /logout
Descripción: Cierra la sesión del usuario actual.
Ejemplo de Respuesta:

json
{
  "status": "success",
  "message": "Sesión cerrada exitosamente."
}
Validaciones de Usuario
Se realizan las siguientes validaciones mediante la clase UserHelpers.php:


Contraseña:

Longitud mínima: 8 caracteres.
Debe contener al menos una letra mayúscula, una minúscula y un número.
Nombre y Apellidos:

Solo letras (acentos y ñ incluidos).
Permite espacios.

Email:
Formato válido (ejemplo: usuario@dominio.com).

Modelos
UserModel.php
Métodos disponibles:
createUser: Inserta un nuevo usuario en la base de datos.
login: Verifica las credenciales de inicio de sesión.
logout: Cierra la sesión del usuario actual.

Ejemplo de Uso:
Registro
php
Copiar código
require_once './backend/src/models/UserModel.php';

$userModel = new UserModel();
$connection = // conexión a la base de datos;

$response = $userModel->createUser($connection, [
    'userName' => 'Carlos',
    'lastName' => 'López',
    'emailAddress' => 'carlos.lopez@example.com',
    'userPassword' => 'Secure1234',
    'confirmPassword' => 'Secure1234'
]);

echo json_encode($response);

Sesión Segura.
Opciones configuradas:
session.cookie_lifetime = 0: Expira al cerrar el navegador.
session.cookie_httponly = true: Evita acceso por JavaScript.
session_regenerate_id(true): Regenera el ID de sesión al inicio para prevenir secuestros.

Notas de Seguridad
Uso de contraseñas hasheadas con password_hash.
Validación de entradas en el lado servidor.
Preparación de consultas con PDO para prevenir SQL Injection.