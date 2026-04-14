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
            $this->mail->Body = 'Ceci est un <b>test</b> depuis PHPMailer.';
            $this->mail->AltBody = '(Alt) Ceci est un test depuis PHPMailer.';

            // Envoi
            $this->mail->send();
            echo "✅ Message envoyé avec succès !";
        } catch (Exception $e) {
            echo "❌ Erreur lors de l'envoi : {$this->mail->ErrorInfo}";
        }
    }
}

// Test
$a = new Mail();
$a->executeMailing();

?>