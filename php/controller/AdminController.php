<?php

class AdminController
{

    public function login()
    {
        if (isset($_SESSION['admin'])) {
            header('Location: index.php?action=admin');
            exit();
        }
        require_once 'view/admin/login.php';
    }

    public function checkLogin($username, $password)
    {
        $pdo = new BD();
        // Récupération de l'utilisateur
        $requete = "SELECT * FROM admin WHERE username = :username";
        $stmt = $pdo->executeRequest($requete, [':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin'] = $user['id'];
            header('Location: index.php?action=admin');
            exit();
        } else {
            $error = "Identifiants incorrects";
            require_once 'view/admin/login.php';
        }
    }

    public function dashboard()
    {
        if (!isset($_SESSION['admin'])) {
            header('Location: index.php?action=login');
            exit();
        }
        require_once 'view/admin/admin.php';
    }

    public function logout()
    {
        session_destroy();
        header('Location: index.php');
        exit();
    }

}