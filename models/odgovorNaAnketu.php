<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "functions/functions.php";
    include "../config/konekcija.php";

    try {
        $anketa_id = $_POST['id'];
        $odgovor = $_POST['odgovor'];
        $korisnik_id = $_SESSION['user'] -> korisnik_id;
        $upit = $konekcija->prepare("INSERT INTO odgovori_ankete (korisnik_id, anketa_id, odgovor) VALUES (:korisnik_id, :anketa_id, :odgovor)");
        $upit->bindParam(":korisnik_id", $korisnik_id);
        $upit->bindParam(":anketa_id", $anketa_id);
        $upit->bindParam(":odgovor", $odgovor);
        $upit->execute();

        if ($upit) {
            http_response_code(201);
            $odgovor = "Uspesno ste uneli odgovor";
            echo $odgovor;
            
        } else {
            http_response_code(408);
            $odgovor = "Upit nije uspeo da se izvrsi";
            echo $odgovor;
            
        }
    } catch (PDOException $e) {
        http_response_code(500);
        $odgovor = "Doslo je do greske na serveru";
        echo $odgovor;
        
    }
} else {
    http_response_code(404);
}
