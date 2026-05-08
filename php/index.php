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
$email = isset($_POST['email']) ? test_input($_POST['email']) : null;
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
$confirmationCode = isset($_POST['code']) ? test_input($_POST['code']) : null;

// reservation admin
$id_conscrit = isset($_POST['name']) ? test_input($_POST['name']) : null;
$arrivalDate = isset($_POST['arrivalDate']) ? test_input($_POST['arrivalDate']) : null;
$departureDate = isset($_POST['departureDate']) ? test_input($_POST['departureDate']) : null;
$numPeople = isset($_POST['numPeople']) ? test_input($_POST['numPeople']) : null;
$numChildren = isset($_POST['numChildren']) ? test_input($_POST['numChildren']) : null;

// routing
switch ($action) {
    // ADMIN
    case 'login':
        $adminController->login();
        break;
    case 'checkLogin':
        $adminController->checkLogin($email, $password);
        break;
    case 'confirm':
        $adminController->confirm();
        break;
    case 'checkCode':
        $adminController->checkCode($confirmationCode);
        break;
    case 'code_mail':
        $adminController->resendCode();
        break;
    case 'admin':
        isset($_SESSION['admin']) ? $adminController->dashboard() : header('Location: ?action=not_access');
        break;
    case 'logout':
        $adminController->logout();
        break;

    // ADMIN FUNCTIONNALITY
    case 'not_access':
        $webSiteController->not_access();
        break;
    case 'inscrit':
        isset($_SESSION['admin']) ? $adminController->inscrit() : header('Location: ?action=not_access');
        break;
    case 'write_inscrit':
        isset($_SESSION['admin']) ? $adminController->write_inscrit($nom, $prenom, $email) : header('Location: ?action=not_access');
        break;
    case 'write_file_inscrit':
        isset($_SESSION['admin']) ? $adminController->write_file_inscrit($file) : header('Location: ?action=not_access');
        break;
    case 'export_inscrits':
        isset($_SESSION['admin']) ? $adminController->export_inscrits() : header('Location: ?action=not_access');
        break;
    case 'newsletter':
        isset($_SESSION['admin']) ? $adminController->newsletter() : header('Location: ?action=not_access');
        break;
    case 'write_newsletter':
        isset($_SESSION['admin']) ? $adminController->write_newsletter($author, $date, $type, $title, $content, $file) : header('Location: ?action=not_access');
        break;
    case 'article':
        isset($_SESSION['admin']) ? $adminController->article() : header('Location: ?action=not_access');
        break;
    case 'write_article':
        isset($_SESSION['admin']) ? $adminController->write_article($author, $date, $type, $title, $content, $file) : header('Location: ?action=not_access');
        break;
    case 'garden':
        isset($_SESSION['admin']) ? $adminController->garden() : header('Location: ?action=not_access');
        break;
    case 'save_garden':
        isset($_SESSION['admin']) ? $adminController->save_garden() : header('Location: ?action=not_access');
        break;
    case 'delete_garden':
        isset($_SESSION['admin']) ? $adminController->delete_garden() : header('Location: ?action=not_access');
        break;
    case 'write_garden':
        isset($_SESSION['admin']) ? $adminController->write_garden($author, $date, $type, $title, $content, $file) : header('Location: ?action=not_access');
        break;
    case 'planning':
        isset($_SESSION['admin']) ? $adminController->planning() : header('Location: ?action=not_access');
        break;
    case 'reserved_admin':
        isset($_SESSION['admin']) ? $adminController->reserved_admin($id_conscrit, $arrivalDate, $departureDate, $numPeople, $numChildren) : header('Location: ?action=not_access');
        break;
    case 'document':
        isset($_SESSION['admin']) ? $adminController->document() : header('Location: ?action=not_access');
        break;
    case 'write_document':
        isset($_SESSION['admin']) ? $adminController->write_document($type, $title, $file) : header('Location: ?action=not_access');
        break;
    case 'markdown':
        isset($_SESSION['admin']) ? $adminController->markdown() : header('Location: ?action=not_access');
        break;
    case 'mode_emploi':
        isset($_SESSION['admin']) ? $adminController->mode_emploi() : header('Location: ?action=not_access');
        break;
    case 'admin_parametre':
        isset($_SESSION['admin']) ? $adminController->parametre() : header('Location: ?action=not_access');
        break;
    case 'new_admin':
        isset($_SESSION['admin']) ? $adminController->new_admin($email, $password) : header('Location: ?action=not_access');
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