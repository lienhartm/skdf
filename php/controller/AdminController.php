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

    public function checkLogin($email, $password)
    {
        $pdo = new BD();
        // Récupération de l'utilisateur
        $requete = "SELECT a.username, a.password, n.email, c.nom, c.prenom, c.gardien, c.membre, c.newsletter
            FROM admin AS a
            LEFT JOIN conscrit_news AS cn ON a.courriel = cn.newsletter_id
            LEFT JOIN newsletter AS n ON cn.newsletter_id = n.id
            LEFT JOIN conscrit AS c ON cn.conscrit_id = c.id
            WHERE (n.email = :courriel OR a.username = :courriel OR a.courriel = :courriel)
            LIMIT 1";
        $stmt = $pdo->executeRequest($requete, [':courriel' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $code = random_int(100000, 999999);
            $_SESSION['pending_login'] = [
                'email' => $user['email'] ?? $email,
                'username' => $user['username'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom'],
                'gardien' => $user['gardien'],
                'membre' => $user['membre'],
                'newsletter' => $user['newsletter'],
                'code' => (string) $code,
                'expires_at' => time() + 600,
            ];

            $mail = new Mail();
            $sent = $mail->codeMailing($_SESSION['pending_login']['email'], $code);

            if (!$sent) {
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['prenom'] = $user['prenom'];
                $_SESSION['email'] = $_SESSION['pending_login']['email'];
                $_SESSION['gardien'] = $user['gardien'];
                $_SESSION['membre'] = $user['membre'];
                $_SESSION['newsletter'] = $user['newsletter'];
                if ((string) $user['username'] === '0') {
                    $_SESSION['admin'] = true;
                    header('Location: index.php?action=admin');
                } else {
                    $_SESSION['conscrit'] = true;
                    header('Location: index.php?action=conscrit');
                }
                unset($_SESSION['pending_login']);
                exit();
            }

            header('Location: index.php?action=confirm');
            exit();
        }

        session_unset();
        session_destroy();
        $error = 'Identifiants incorrects';
        require_once 'view/admin/login.php';
    }

    public function confirm($error = null)
    {
        if (!isset($_SESSION['pending_login']) || empty($_SESSION['pending_login']['email'])) {
            header('Location: index.php?action=login');
            exit();
        }

        if (isset($_SESSION['confirm_error'])) {
            $error = $_SESSION['confirm_error'];
            unset($_SESSION['confirm_error']);
        }

        require_once 'view/admin/confirm.php';
    }

    public function resendCode()
    {
        if (!isset($_SESSION['pending_login']['email'])) {
            header('Location: index.php?action=login');
            exit();
        }

        $email = $_SESSION['pending_login']['email'];
        $code = random_int(100000, 999999);
        $_SESSION['pending_login']['code'] = (string) $code;
        $_SESSION['pending_login']['expires_at'] = time() + 600;

        $mail = new Mail();
        $sent = $mail->codeMailing($email, $code);
        if (!$sent) {
            $_SESSION['confirm_error'] = 'Impossible de renvoyer le code. Essayez plus tard.';
        }

        header('Location: index.php?action=confirm');
        exit();
    }

    public function checkCode($code)
    {
        if (!isset($_SESSION['pending_login']['code'])) {
            header('Location: index.php?action=login');
            exit();
        }

        $pending = $_SESSION['pending_login'];
        if (time() > $pending['expires_at']) {
            session_unset();
            session_destroy();
            $error = 'Le code a expiré. Veuillez vous reconnecter.';
            require_once 'view/admin/login.php';
            return;
        }

        if ($code === $pending['code']) {
            $_SESSION['nom'] = $pending['nom'];
            $_SESSION['prenom'] = $pending['prenom'];
            $_SESSION['email'] = $pending['email'];
            $_SESSION['gardien'] = $pending['gardien'];
            $_SESSION['membre'] = $pending['membre'];
            $_SESSION['newsletter'] = $pending['newsletter'];

            if ($pending['username'] == 0) {
                $_SESSION['admin'] = true;
                //unset($_SESSION['pending_login']);
                header('Location: index.php?action=admin');
            } else {
                $_SESSION['conscrit'] = true;
                //unset($_SESSION['pending_login']);
                header('Location: index.php?action=conscrit');
            }
            exit();
        }

        $error = 'Code incorrect. Vérifiez votre email et réessayez.';
        require_once 'view/admin/confirm.php';
    }

    public function dashboard()
    {
        if (!isset($_SESSION['admin'])) {
            echo("OK");
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
        $requete = "SELECT a.username, n.email, c.nom, c.prenom, c.gardien, c.membre, c.newsletter, c.date_inscription FROM admin AS a INNER JOIN conscrit_news AS cn ON a.courriel = cn.newsletter_id INNER JOIN newsletter AS n ON cn.newsletter_id = n.id INNER JOIN conscrit AS c ON cn.conscrit_id = c.id";
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

        // Vérifier que c'est un fichier CSV
        $mime = mime_content_type($file['tmp_name']);
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['csv', 'txt']) && $mime !== 'text/plain' && $mime !== 'text/csv') {
            $_SESSION['flash'] = '❌ Seuls les fichiers CSV sont acceptés.';
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
        $insertConscrit = "INSERT INTO conscrit (nom, prenom, gardien, membre, newsletter, date_inscription) VALUES (:nom, :prenom, 0, 0, 1, :date_inscription)";
        $insertNewsletter = "INSERT INTO newsletter (nom, prenom, email, date_inscription) VALUES (:nom, :prenom, :email, :date_inscription)";
        $insertRelation = "INSERT INTO conscrit_news (conscrit_id, newsletter_id) VALUES (:conscrit_id, :newsletter_id)";

        $rowNumber = 0;
        $successCount = 0;
        $skipCount = 0;
        $errorCount = 0;
        $errors = [];

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            $rowNumber++;

            // Ignorer la première ligne si elle contient les en-têtes
            if ($rowNumber == 1 && isset($row[0]) && stripos($row[0], 'nom') !== false) {
                continue;
            }

            // Sauter les lignes incomplètes
            if (count($row) < 3) {
                $errorCount++;
                $errors[] = "Ligne $rowNumber : données insuffisantes (< 3 colonnes).";
                continue;
            }

            list($nom, $prenom, $email) = $row;
            $nom = trim($nom);
            $prenom = trim($prenom);
            $email = trim($email);

            // Valider les données
            if (empty($nom) || empty($prenom) || empty($email)) {
                $skipCount++;
                $errors[] = "Ligne $rowNumber : nom, prénom ou email vide.";
                continue;
            }

            // Valider le format email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errorCount++;
                $errors[] = "Ligne $rowNumber : email invalide ('$email').";
                continue;
            }

            // Vérifier les doublons
            $checkQuery = "SELECT COUNT(*) as count FROM newsletter WHERE LOWER(email) = LOWER(:email)";
            $checkStmt = $pdo->executeRequest($checkQuery, [':email' => $email]);
            $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($existing['count'] > 0) {
                $skipCount++;
                $errors[] = "Ligne $rowNumber : email '$email' déjà enregistré.";
                continue;
            }

            try {
                // Insérer dans conscrit
                $pdo->executeRequest($insertConscrit, [
                    ':nom' => $nom,
                    ':prenom' => $prenom,
                    ':date_inscription' => date('Y-m-d H:i:s')
                ]);
                $conscritId = $pdo->lastInsertId();

                // Insérer dans newsletter
                $pdo->executeRequest($insertNewsletter, [
                    ':nom' => $nom,
                    ':prenom' => $prenom,
                    ':email' => $email,
                    ':date_inscription' => date('Y-m-d H:i:s')
                ]);
                $newsletterId = $pdo->lastInsertId();

                // Créer la relation
                $pdo->executeRequest($insertRelation, [
                    ':conscrit_id' => $conscritId,
                    ':newsletter_id' => $newsletterId
                ]);

                $successCount++;
            } catch (Exception $e) {
                $errorCount++;
                $errors[] = "Ligne $rowNumber : erreur base de données - " . $e->getMessage();
            }
        }
        fclose($handle);

        // Construire le message de retour
        $msg = '✅ Import CSV terminé. ';
        $msg .= "Importés: $successCount, Ignorés: $skipCount, Erreurs: $errorCount.";

        if (!empty($errors) && count($errors) <= 10) {
            $msg .= "\n\n🔔 Détails :\n" . implode("\n", $errors);
        } elseif (!empty($errors)) {
            $msg .= "\n\n🔔 (Premiers détails) :\n" . implode("\n", array_slice($errors, 0, 10)) . "\n... et " . (count($errors) - 10) . " erreurs supplémentaires.";
        }

        $_SESSION['flash'] = $msg;
        header('Location: index.php?action=inscrit');
        exit();
    }

    public function export_inscrits()
    {
        $pdo = new BD();
        $requete = "SELECT a.username, n.email, c.nom, c.prenom, c.gardien, c.membre, c.newsletter, c.date_inscription FROM admin AS a INNER JOIN conscrit_news AS cn ON a.courriel = cn.newsletter_id INNER JOIN newsletter AS n ON cn.newsletter_id = n.id INNER JOIN conscrit AS c ON cn.conscrit_id = c.id";
        $stmt = $pdo->executeRequest($requete, []);
        $inscrits = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=inscrits.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Nom', 'Prénom', 'Email', 'Gardien', 'Membre', 'Newsletter', 'Date d\'inscription']);
        foreach ($inscrits as $inscrit) {
            fputcsv($output, [$inscrit['nom'], $inscrit['prenom'], $inscrit['email'], $inscrit['gardien'], $inscrit['membre'], $inscrit['newsletter'], $inscrit['date_inscription']]);
        }
        fclose($output);
        exit();
    }

    public function newsletter()
    {
        require_once 'view/admin/newsletter/index.php';
    }

    public function write_newsletter($author, $date, $type, $title, $content, $file)
    {
        $htmlBody = '<h2>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h2>';
        $htmlBody .= '<p><strong>Auteur :</strong> ' . htmlspecialchars($author, ENT_QUOTES, 'UTF-8') . '</p>';
        $htmlBody .= '<p><strong>Date :</strong> ' . htmlspecialchars($date, ENT_QUOTES, 'UTF-8') . '</p>';
        $htmlBody .= '<p>' . nl2br(htmlspecialchars($content, ENT_QUOTES, 'UTF-8')) . '</p>';

        $attachments = [];
        if ($file && isset($file['name'])) {
            $targetDir = __DIR__ . '/../documents/newsletter/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            if (is_array($file['name'])) {
                foreach ($file['name'] as $index => $name) {
                    if ($file['error'][$index] !== UPLOAD_ERR_OK) {
                        continue;
                    }
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    $storedName = uniqid('newsletter_', true) . '.' . $ext;
                    $path = $targetDir . $storedName;
                    if (move_uploaded_file($file['tmp_name'][$index], $path)) {
                        $attachments[] = ['path' => $path, 'name' => $name];
                    }
                }
            } else {
                if ($file['error'] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $storedName = uniqid('newsletter_', true) . '.' . $ext;
                    $path = $targetDir . $storedName;
                    if (move_uploaded_file($file['tmp_name'], $path)) {
                        $attachments[] = ['path' => $path, 'name' => $file['name']];
                    }
                }
            }
        }

        $pdo = new BD();
        $requete = "SELECT * FROM newsletter";
        $stmt = $pdo->executeRequest($requete, []);
        $inscrits = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($inscrits)) {
            $_SESSION['flash'] = '⚠️ Aucun abonné trouvé pour l’envoi de la newsletter.';
            header('Location: index.php?action=newsletter');
            exit();
        }

        $successCount = 0;
        $failureCount = 0;

        $mailWrapper = new Mail();
        $mail = $mailWrapper->getMail();
        $mail->setFrom('lienhartm8@gmail.com', 'SCG Newsletter');
        $mail->isHTML(true);
        $mail->Subject = $title;
        $mail->Body = $htmlBody;
        $mail->AltBody = strip_tags($htmlBody);

        foreach ($inscrits as $inscrit) {
            $mail->clearAddresses();
            $mail->clearAttachments();
            $mail->addAddress($inscrit['email']);

            foreach ($attachments as $attachment) {
                $mail->addAttachment($attachment['path'], $attachment['name']);
            }

            try {
                $mail->send();
                $successCount++;
            } catch (Exception $e) {
                error_log('Erreur envoi newsletter à ' . $inscrit['email'] . ' : ' . $e->getMessage());
                $failureCount++;
            }
        }

        if ($failureCount === 0) {
            $msg = '✅ Newsletter envoyée à ' . $successCount . ' destinataire(s).';
        } else {
            $msg = '❌ Envoi partiel : ' . $successCount . ' réussies, ' . $failureCount . ' échouées.';
        }

        $_SESSION['flash'] = $msg;
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
            $id = end($data)['id'] + 1;
        } else {
            $id = 1;
        }

        $data[] = [
            'id' => $id,
            'date' => $date,
            'auteur' => htmlspecialchars($author, ENT_QUOTES, 'UTF-8'),
            'type' => $type,
            'titre' => htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
            'article' => $content,
            'images' => $uploadedFiles
        ];
        file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        header('Location: index.php?action=article');
        exit();
    }

    public function garden()
    {
        $pdo = new BD();
        $requete = "SELECT conscrit.* FROM conscrit WHERE gardien = TRUE;";
        $stmt = $pdo->executeRequest($requete, []);
        $gardiens = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $events = [];
        $eventQuery = "SELECT g.id, g.title, g.start_date AS start, g.end_date AS end, g.color, g.extras, g.main_gardien_id FROM garde g ORDER BY g.start_date";
        $eventStmt = $pdo->executeRequest($eventQuery, []);
        $storedEvents = $eventStmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($storedEvents as $row) {
            $event = [
                'id' => (int) $row['id'],
                'title' => $row['title'],
                'start' => $row['start'],
                'end' => $row['end'],
                'color' => $row['color'],
                'mainGardienId' => (int) $row['main_gardien_id'],
                'extras' => $row['extras'] ? json_decode($row['extras'], true) : []
            ];
            $events[] = $event;
        }

        require_once 'view/admin/garden/index.php';
    }

    public function save_garden()
    {
        $payload = json_decode(file_get_contents('php://input'), true);
        if (!$payload) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Données invalides']);
            exit;
        }

        $mainGardienId = isset($payload['mainId']) ? (int) $payload['mainId'] : null;
        $start = $payload['start'] ?? null;
        $end = $payload['end'] ?? null;
        $title = trim($payload['title'] ?? '');
        $color = isset($payload['color']) ? substr($payload['color'], 0, 32) : null;
        $textColor = $payload['textColor'] ?? null;
        $extras = isset($payload['extras']) && is_array($payload['extras']) ? $payload['extras'] : [];

        if (!$mainGardienId || !$start || !$end || !$title) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis']);
            exit;
        }

        if ($end <= $start) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'La date de fin doit être après la date de début']);
            exit;
        }

        $pdo = new BD();
        $insert = "INSERT INTO garde (main_gardien_id, extras, title, start_date, end_date, color) VALUES (:mainId, :extras, :title, :start, :end, :color)";
        $params = [
            ':mainId' => $mainGardienId,
            ':extras' => json_encode($extras, JSON_UNESCAPED_UNICODE),
            ':title' => $title,
            ':start' => $start,
            ':end' => $end,
            ':color' => $color
        ];
        $stmt = $pdo->executeRequest($insert, $params);
        $eventId = $pdo->lastInsertId();

        echo json_encode(['success' => true, 'event' => ['id' => (int) $eventId, 'title' => $title, 'start' => $start, 'end' => $end, 'color' => $color, 'textColor' => $textColor, 'mainGardienId' => $mainGardienId, 'extras' => $extras]]);
        exit;
    }

    public function delete_garden()
    {
        $payload = json_decode(file_get_contents('php://input'), true);
        $eventId = isset($payload['id']) ? (int) $payload['id'] : null;

        if (!$eventId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de garde manquant']);
            exit;
        }

        $pdo = new BD();
        $delete = "DELETE FROM garde WHERE id = :id";
        $pdo->executeRequest($delete, [':id' => $eventId]);

        echo json_encode(['success' => true, 'deletedId' => $eventId]);
        exit;
    }

    public function planning()
    {
        $pdo = new BD();
        $reservations = $pdo->executeRequest("SELECT * FROM reservation", []);
        $reservations = $reservations->fetchAll(PDO::FETCH_ASSOC);

        $pdo = new BD();
        $requete = "SELECT conscrit.* FROM conscrit;";
        $conscrits = $pdo->executeRequest($requete, []);
        $conscrits = $conscrits->fetchAll(PDO::FETCH_ASSOC);
        require_once 'view/admin/planning/index.php';
    }

    public function reserved_admin($id_conscrit, $arrivalDate, $departureDate, $numPeople, $numChildren)
    {
        $pdo = new BD();
        $insert = "INSERT INTO reservation (id_conscrit, date_debut, date_fin, nb_adulte, nb_enfant, status) VALUES (:id_conscrit, :date_debut, :date_fin, :nb_adulte, :nb_enfant, 'reservé')";
        $params = [
            ':id_conscrit' => $id_conscrit,
            ':date_debut' => $arrivalDate,
            ':date_fin' => $departureDate,
            ':nb_adulte' => $numPeople,
            ':nb_enfant' => $numChildren
        ];
        $pdo->executeRequest($insert, $params);
        header('Location: index.php?action=planning');
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
            $id = end($data)['id'] + 1;
        } else {
            $data = [];
        }

        // Add new entry
        $data[] = [
            'id' => $id,
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

    public function parametre()
    {
        require_once 'view/admin/parametre/index.php';
    }

    public function new_admin($email, $password)
    {
        $pdo = new BD();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $requete = "INSERT INTO admin (username, courriel, password) VALUES (0, :courriel, :password)";
        $stmt = $pdo->executeRequest($requete, [':courriel' => $email, ':password' => $hashedPassword]);

        $requete ="UPDATE admin SET username = 1 WHERE courriel = :courriel";
        $stmt = $pdo->executeRequest($requete, [':courriel' => $_SESSION['email']]);
        header('Location: index.php?action=admin_parametre');
        exit();
    }

}