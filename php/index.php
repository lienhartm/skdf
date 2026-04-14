<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// database configuration
include 'includes/config.bd.php';
include 'includes/config.markdown.php';

// controller includes
include 'controller/AdminController.php';
include 'controller/WebSiteController.php';

// class instanciation
$adminController = new AdminController();
$webSiteController = new WebSiteController();

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$action = isset($_GET['action']) ? test_input($_GET['action']) : null;

// Admin login
$username = isset($_POST['username']) ? test_input($_POST['username']) : null;
$password = isset($_POST['password']) ? test_input($_POST['password']) : null;

// routing
switch ($action) {
    // ADMIN
    case 'login':
        $adminController->login();
        break;
    case 'checkLogin':
        $adminController->checkLogin($username, $password);
        break;
    case 'admin':
        $adminController->dashboard();
        break;
    case 'logout':
        $adminController->logout();
        break;
    // WEBSITE
    case 'index':
        $webSiteController->index();
        break;
    case 'refuge':
        $webSiteController->refuge();
        break;
    case 'club':
        $webSiteController->club();
        break;
    case 'histoire':
        $webSiteController->histoire();
        break;
    case 'actualite':
        $webSiteController->actualite();
        break;
    case 'presse':
        $webSiteController->presse();
        break;
    case 'resultat':
        $webSiteController->resultat();
        break;
    case 'acces':
        $webSiteController->acces();
        break;
    case 'contact':
        $webSiteController->contact();
        break;
    default:
        $webSiteController->index();
        break;
}

?>