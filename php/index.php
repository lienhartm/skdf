<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// database configuration
include 'includes/config.bd.php';
include 'includes/config.markdown.php';
include 'includes/config.mail.php';

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

// newsletter // article
$author = isset($_POST['author']) ? test_input($_POST['author']) : null;
$date = isset($_POST['date']) ? test_input($_POST['date']) : null;
$type = isset($_POST['type']) ? test_input($_POST['type']) : null;
$title = isset($_POST['title']) ? test_input($_POST['title']) : null;
$content = isset($_POST['content']) ? test_input($_POST['content']) : null;
$file = isset($_FILES['file']) ? $_FILES['file'] : null;

// inscrit
$nom = isset($_POST['nom']) ? test_input($_POST['nom']) : null;
$prenom = isset($_POST['prenom']) ? test_input($_POST['prenom']) : null;
$email = isset($_POST['email']) ? test_input($_POST['email']) : null;

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

    // ADMIN FUNCTIONNALITY
    case 'inscrit':
        $adminController->inscrit();
        break;
    case 'write_inscrit':
        $adminController->write_inscrit($nom, $prenom, $email);
        break;
    case 'write_file_inscrit':
        $adminController->write_file_inscrit($file);
        break;
    case 'newsletter':
        $adminController->newsletter();
        break;
    case 'write_newsletter':
        $adminController->write_newsletter($author, $date, $type, $title, $content, $file);
        break;
    case 'article':
        $adminController->article();
        break;
    case 'write_article':
        $adminController->write_article($author, $date, $type, $title, $content, $file);
        break;
    case 'document':
        $adminController->document();
        break;
    case 'write_document':
        $adminController->write_document($type, $title, $file);
        break;
    case 'markdown':
        $adminController->markdown();
        break;
    case 'mode_emploi':
        $adminController->mode_emploi();
        break;

    // WEBSITE
    case 'index':
        $webSiteController->index();
        break;
    case 'refuge':
        $webSiteController->refuge();
        break;
    case 'reservation':
        $webSiteController->reservation();
        break;
    case 'paypal':
        $webSiteController->paypal();
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