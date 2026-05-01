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

    public function inscrit()
    {
        $pdo = new BD();
        $requete = "SELECT * FROM newsletter";
        $stmt = $pdo->executeRequest($requete, []);
        $inscrits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'view/admin/inscrit/index.php';
    }

    public function write_inscrit($nom, $prenom, $email)
    {
        $pdo = new BD();
        $requete = "INSERT INTO newsletter (nom, prenom, email, date_inscription) VALUES (:nom, :prenom, :email, :date_inscription)";
        $stmt = $pdo->executeRequest($requete, [':nom' => $nom, ':prenom' => $prenom, ':email' => $email, ':date_inscription' => date('Y-m-d H:i:s')]);
        header('Location: index.php?action=inscrit');
        exit();
    }

    public function write_file_inscrit($file)
    {
        // Vérifier l'upload du CSV
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash'] = '❌ Aucun fichier CSV fourni ou erreur d\'upload.';
            header('Location: index.php?action=inscrit');
            exit();
        }

        // Ouvrir le fichier CSV (délimiteur virgule)
        $handle = fopen($file['tmp_name'], 'r');
        if ($handle === false) {
            $_SESSION['flash'] = '❌ Impossible d\'ouvrir le fichier CSV.';
            header('Location: index.php?action=inscrit');
            exit();
        }

        $pdo = new BD();
        $insert = "INSERT INTO newsletter (nom, prenom, email, date_inscription) VALUES (:nom, :prenom, :email, :date)";
        $rowNumber = 0;
        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            $rowNumber++;
            // Ignorer la première ligne si elle contient les en‑têtes
            if ($rowNumber == 1 && (stripos($row[0], 'nom') !== false)) {
                continue;
            }
            // Sauter les lignes incomplètes
            if (count($row) < 3) {
                continue;
            }
            list($nom, $prenom, $email) = $row;
            $nom = trim($nom);
            $prenom = trim($prenom);
            $email = trim($email);
            if ($nom === '' && $prenom === '' && $email === '') {
                continue;
            }
            $pdo->executeRequest($insert, [
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':date_inscription' => date('Y-m-d H:i:s')
            ]);
        }
        fclose($handle);

        $_SESSION['flash'] = '✅ Import CSV terminé.';
        header('Location: index.php?action=inscrit');
        exit();
    }

    public function newsletter()
    {
        require_once 'view/admin/newsletter/index.php';
    }

    public function write_newsletter($author, $date, $type, $title, $content, $file)
    {
        // 1️⃣  Construire le corps du mail (HTML)
        $htmlBody = '<h2>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h2>';
        $htmlBody .= '<p><strong>Auteur :</strong> ' . htmlspecialchars($author, ENT_QUOTES, 'UTF-8') . '</p>';
        $htmlBody .= '<p><strong>Date :</strong> ' . htmlspecialchars($date, ENT_QUOTES, 'UTF-8') . '</p>';
        $htmlBody .= '<p>' . nl2br(htmlspecialchars($content, ENT_QUOTES, 'UTF-8')) . '</p>';

        // 2️⃣  Instancier le wrapper mail
        $mail = new Mail();

        $pdo = new BD();
        $requete = "SELECT * FROM newsletter";
        $stmt = $pdo->executeRequest($requete, []);
        $inscrits = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 3️⃣  Configurer l’expéditeur / destinataire
        // → on peut récupérer le destinataire depuis la base de données / configurati

        foreach ($inscrits as $inscrit) {
            $mail->getMail()->setFrom('lienhartm8@gmail.com', 'SCG Newsletter');
            $mail->getMail()->addAddress($inscrit['email'], $inscrit['nom']);


            // 4️⃣  Définir le sujet et le corps
            $mail->getMail()->Subject = $title;
            $mail->getMail()->Body = $htmlBody;
            $mail->getMail()->AltBody = strip_tags($htmlBody);

            // 5️⃣  Gérer éventuellement un fichier joint (PDF/ZIP)
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                // stocker le fichier dans documents/newsletter/
                $targetDir = __DIR__ . '/../documents/newsletter/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $name = uniqid('newsletter_', true) . '.' . $ext;
                $path = $targetDir . $name;

                if (move_uploaded_file($file['tmp_name'], $path)) {
                    $mail->getMail()->addAttachment($path, $file['name']);
                }
            }

            // 6️⃣  Envoyer le mail – on attrape les exceptions pour ne pas casser le flux
            try {
                $mail->getMail()->send();
                $msg = '✅ Newsletter envoyée !';
            } catch (Exception $e) {
                error_log('Erreur envoi newsletter : ' . $e->getMessage());
                $msg = '❌ Échec de l’envoi de la newsletter.';
            }
        }
        // 7️⃣  Rediriger avec un petit flash‑message (via session ou query‑string)
        $_SESSION['flash'] = $msg;               // ou <‑ ?msg=…
        header('Location: index.php?action=newsletter');
        exit();
    }

    public function article()
    {
        require_once 'view/admin/article/index.php';
    }

    public function write_article($author, $date, $type, $title, $content, $files)
    {
        $path = __DIR__ . '/../documents/actualite/';
        // Ensure target directory exists
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        // Handle file uploads (may be multiple)
        $uploadedFiles = [];
        if ($files && isset($files['name']) && is_array($files['name'])) {
            foreach ($files['name'] as $idx => $name) {

                if ($files['error'][$idx] !== UPLOAD_ERR_OK) {
                    continue;
                }

                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $uniqueName = uniqid('img_', true) . '.' . $ext;
                $target = $path . $uniqueName;

                if (!move_uploaded_file($files['tmp_name'][$idx], $target)) {
                    error_log("Failed upload: " . $name);
                } else {
                    $uploadedFiles[] = $uniqueName;
                }
            }
        }

        // Load existing JSON or initialize
        $jsonFile = $path . 'actus.json';
        $data = [];
        if (file_exists($jsonFile)) {
            $json = file_get_contents($jsonFile);
            $data = json_decode($json, true) ?? [];
        }
        $data[] = [
            'titre' => htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
            'auteur' => htmlspecialchars($author, ENT_QUOTES, 'UTF-8'),
            'date' => $date,
            'type' => $type,
            'article' => $content,
            'images' => $uploadedFiles
        ];
        file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        header('Location: index.php?action=article');
        exit();
    }

    public function document()
    {
        require_once 'view/admin/upload/index.php';
    }

    public function write_document($type, $title, $file)
    {
        $path = 'documents/' . $type . '/';

        $filename = '';

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['pdf'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowedTypes)) {
                die('Invalid file type.');
            }

            // Generate unique filename
            $filename = uniqid('doc_', true) . '.' . $ext;
            $target_file = $path . $filename;

            if (!move_uploaded_file($file['tmp_name'], $target_file)) {
                die('Upload failed.');
            }
        }

        $jsonFile = $path . $type . '.json';

        // Load existing data or initialize
        if (file_exists($jsonFile)) {
            $json = file_get_contents($jsonFile);
            $data = json_decode($json, true) ?? [];
        } else {
            $data = [];
        }

        // Add new entry
        $data[] = [
            'date' => date("d/m/Y"),
            'titre' => htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
            'document' => $filename
        ];

        // Save JSON
        file_put_contents(
            $jsonFile,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        header('Location: index.php?action=document');
        exit();
    }

    public function markdown()
    {
        require_once 'view/admin/markdown/index.php';
    }

    public function mode_emploi()
    {
        require_once 'view/admin/emploi/index.php';
    }

}