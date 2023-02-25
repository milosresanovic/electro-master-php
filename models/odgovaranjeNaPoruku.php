<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "functions/functions.php";
    include "../config/konekcija.php";

    require '../assets/phpMailer/vendor/autoload.php';

    try {
        $poruka = $_POST['poruka'];
        $porukaId = $_SESSION['podaci_poruka']['poruka_id'];
        $naslov = $_SESSION['podaci_poruka']['naslov'];
        $emailKorisnika = $_SESSION['podaci_poruka']['email'];
        $imeKorisnika = $_SESSION['podaci_poruka']['ime'];
        $prezimeKorisnika = $_SESSION['podaci_poruka']['prezime'];

        $mail = new PHPMailer(true);
        $mail -> CharSet = "UTF-8";
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'testniemailzaphp@gmail.com';                     //SMTP username
        $mail->Password   = 'testniemail123';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('testniemailzaphp@gmail.com', 'Electro shop');
        $mail->addAddress($emailKorisnika);

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Re: ' . $naslov;
        $mail->Body    = '<h4>Poštovani/a ' . $imeKorisnika . ' ' . $prezimeKorisnika . ' </h4>' . '<br> <p>' . $poruka . '</p>';
        $mail->AltBody = "Odgovor admina.";

        $mail->send();

        $upitUpdatePoruku = $konekcija->prepare("UPDATE poruke_za_admina SET status_poruke = 1 WHERE poruka_id = $porukaId");
        $upitUpdatePoruku->execute();
        if (!$upitUpdatePoruku) {
            http_response_code(408);
            $odgovor = ["odgovor" => "Loste updejtovana poruka!"];
            echo json_encode($odgovor);
            
        }

        unset($_SESSION["podaci_poruka"]);

        http_response_code(201);
        $odgovor = ["odgovor" => "Uspesno ste poslali odgovor!"];
        echo json_encode($odgovor);
        
    } catch (PDOException $e) {
        http_response_code(409);
        $odgovor = ["odgovor" => "Doslo je do greske na serveru!"];
        echo json_encode($odgovor);
        
    }
} else {
    http_response_code(404);
}
