<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "functions/functions.php";
    include "../config/konekcija.php";
    try {
        $kod = $_POST['kod'];
        $regKod = "/^[1-9][0-9]{5}$/";
        $greske = [];
        if (!preg_match($regKod, $kod)) {
            array_push($greske, "Pogresan kod!");
        }

        $emailKorisnika = $_SESSION['verifikacija']['email'];
        $kodKorisnika = $_SESSION['verifikacija']['kod'];

        $upitDohvatiKod = $konekcija->prepare("SELECT verifikacioni_kod FROM korisnici WHERE email = :email");
        $upitDohvatiKod->bindParam(":email", $emailKorisnika);
        $upitDohvatiKod->execute();
        $korisnik = $upitDohvatiKod->fetch();

        if ($korisnik && count($greske) == 0) {
            if ($korisnik->verifikacioni_kod == $kod) {
                $upitVerifikuj = $konekcija->prepare("UPDATE korisnici SET verifikacioni_kod = :kod, verifikovan = 1 WHERE email = :email");
                $upitVerifikuj->bindParam(":kod", $kodKorisnika);
                $upitVerifikuj->bindParam(":email", $emailKorisnika);
                $upitVerifikuj->execute();
                unset($_SESSION["verifikacija"]);
            } else {
                http_response_code(409);
                $odgovor = ["odgovor" => "Nije dobra verifikacija!"];
                echo json_encode($odgovor);
                
            }
        } else {
            http_response_code(409);
            $odgovor = ["odgovor" => "Morate prvo proci proces registracije!"];
            echo json_encode($odgovor);
            
        }
    } catch (PDOException $exception) {
        http_response_code(500);
        $odgovor = ["odgovor" => "Greska na serveru molimo pokusajte kasnije!"];
        echo json_encode($exception);
        
    }
} else {
    http_response_code(404);
}
