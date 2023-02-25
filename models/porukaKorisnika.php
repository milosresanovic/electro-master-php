<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();
    include "functions/functions.php";
    include "../config/konekcija.php";

    try {
        $idKorisnika = $_SESSION['user']->korisnik_id;
        $naslov = $_POST['naslov'];
        $poruka = $_POST['poruka'];
        $regNaslov = "/^$/";
        $regPoruka = "/^$/";
        $greske = [];

        $upit = $konekcija->prepare("INSERT INTO poruke_za_admina (naslov, sadrzaj, korisnik_id, status_poruke) VALUES (:naslov, :poruka, :idKorisnika, 0)");
        $upit->bindParam(":naslov", $naslov);
        $upit->bindParam(":poruka", $poruka);
        $upit->bindParam(":idKorisnika", $idKorisnika);
        $upit->execute();

        if ($upit) {
            http_response_code(201);
            $odgovor = "Poruka je uspesno poslata!";
            echo json_encode($odgovor);
            
        } else {
            http_response_code(408);
            $odgovor = "Nije se ubacilo u bazu!";
            echo json_encode($odgovor);
            
        }
    } catch (PDOException $e) {
        http_response_code(500);
        $odgovor = $e;
        echo json_encode($odgovor);
        
    }
} else {
    http_response_code(404);
}
