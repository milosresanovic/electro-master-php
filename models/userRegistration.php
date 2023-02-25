<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "functions/functions.php";
    include "../config/konekcija.php";

    require '../assets/phpMailer/vendor/autoload.php';


    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $sifra = $_POST['sifra'];
    $sifriranaLozinka = md5($sifra);
    $regIme = "/^[A-ZČĆŠĐŽ][a-zčćšđž]{2,15}(\s[A-ZČĆŠĐŽ][a-zčćšđž]{2,15})?\s*$/";
    $regUsername = "/^[A-Za-z0-9_-]{2,10}$/";
    $regSifra = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/";
    $nizGresaka = [];
    if (!preg_match($regIme, $ime))
        array_push($nizGresaka, "Greska ime");
    if (!preg_match($regIme, $prezime))
        array_push($nizGresaka, "Greska  prezime");
    if (!preg_match($regUsername, $username))
        array_push($nizGresaka, "Greska username");
    if (!preg_match($regSifra, $sifra))
        array_push($nizGresaka, "Greska sifra");
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        array_push($nizGresaka, "Greska email");
    global $konekcija;
    $korisnik = $konekcija->prepare("SELECT email, korisnicko_ime FROM korisnici WHERE korisnicko_ime = :korisnickoIme OR email = :email");
    $korisnik->bindParam(":korisnickoIme", $username);
    $korisnik->bindParam(":email", $email);
    $korisnik->execute();
    $korisnikObj = $korisnik->fetch();

    $random_number = random_int(100000, 999999);
    if (count($nizGresaka) == 0) {
        if ($korisnikObj) {
            if ($korisnikObj->email == $email) {
                http_response_code(402);
                $greska = "Email koji ste uneli je zauzet!";
                $odgovor = "Greska email!";
                echo json_encode($odgovor);
                
            } else {
                http_response_code(401);
                $greska = "Korisnicko ime koje ste uneli je zauzeto!";
                $odgovor = "Greska username";
                echo json_encode($odgovor);
                
            }
        } else {
            try {
                $unosKorisnika = insertNewUser($ime, $prezime, $username, $email, $sifriranaLozinka, $random_number);
                if ($unosKorisnika) {
                    $_SESSION["verifikacija"] = ['email'=> $email, 'kod'=>$random_number];

                    

                    $mail = new PHPMailer(true);

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
                    $mail->addAddress($email);


                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = 'Verifikacioni kod';
                    $mail->Body    = "<h1><b>$random_number</b></h1>";
                    $mail->AltBody = $random_number;

                    $mail->send();

                    http_response_code(201);
                    $odgovor = ["nazivUloge" => "Uspesno ste se registrovali!"];
                    echo json_encode($odgovor);
                }
            } catch (PDOException $exception) {
                http_response_code(500);
                $odgovor = ["nazivUloge" => "Greska na serveru molimo pokusajte kasnije!"];
                echo json_encode($odgovor);
                
            }
        }
    } else {
        http_response_code(409);
        $odgovor = ["nazivUloge" => "Imate gresku u unetim podacima!"];
        echo json_encode($odgovor);
        
    }
} else {
    $odgovor = ["nazivUloge" => "Usao sam u else!"];
    echo json_encode($odgovor);
    return http_response_code(404);
}
