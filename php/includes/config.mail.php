<?php

require __DIR__ . '/../vendor/autoload.php';  // Chargement de Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail
{

    private $mail;

    // ✅ Le nom du constructeur doit être __construct (et pas __contruction)
    public function __construct()
    {
        try {
            // ✅ Pas de $ avant mail ici
            $this->mail = new PHPMailer(true);

            // Configuration SMTP
            $this->mail->isSMTP();
            $this->mail->Host = 'smtp.gmail.com';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = 'lienhartm8@gmail.com';
            $this->mail->Password = 'tksf ltgp zznl lbik';  // ⚠️ 
            $this->mail->SMTPSecure = 'tls';
            $this->mail->Port = 587;
            $this->mail->CharSet = 'UTF-8';
            $this->mail->Encoding = 'base64';
        } catch (Exception $e) {
            echo "Erreur d'initialisation : {$e->getMessage()}";
        }
    }

    public function getMail()
    {
        return $this->mail;
    }

    public function executeMailing()
    {
        try {
            // Expéditeur et destinataire
            $this->mail->setFrom('lienhartm8@gmail.com', 'Nom de l\'expéditeur');   // ⚠️ 
            $this->mail->addAddress('lienhartm8@gmail.com', 'Nom du destinataire'); // ⚠️ 

            // Contenu
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Test PHPMailer';
            $this->mail->Body = 'Ceci est un <b>test</b> depuis PHPMailer.<br>Ski club de guebwiller';
            $this->mail->AltBody = '(Alt) Ceci est un test depuis PHPMailer.';

            // Envoi
            $this->mail->send();
            echo "✅ Message envoyé avec succès !";
        } catch (Exception $e) {
            echo "❌ Erreur lors de l'envoi : {$this->mail->ErrorInfo}";
        }
    }

    public function codeMailing($email, $code)
    {
        try {
            // Expéditeur et destinataire
            $this->mail->setFrom('lienhartm8@gmail.com', 'SCG Authentication');
            $this->mail->addAddress($email);

            // Contenu
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Votre code de confirmation SCG';
            $this->mail->Body = '<p>Bonjour,</p>' .
                '<p>Votre code de confirmation pour accéder à l\'espace administrateur est : <strong>' . htmlspecialchars($code, ENT_QUOTES, 'UTF-8') . '</strong></p>' .
                '<p>Ce code est valable 10 minutes.</p>' .
                '<p>Si vous n\'avez pas demandé cette connexion, ignorez ce message.</p>';
            $this->mail->AltBody = 'Votre code de confirmation SCG : ' . $code;

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

// Test
/*
$a = new Mail();
$a->executeMailing();
*/

?>