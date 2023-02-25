<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "functions/functions.php";
    include "../config/konekcija.php";

    try {
        $naslov = $_POST['naslov'];
        $tekst = $_POST['tekst'];
        $admin_id = $_SESSION['user']->korisnik_id;
        $reg = "/^([A-Z][a-z\-?!.0-9]+)(\s[A-Za-z\-?!.0-9]+)*\s*$/";
        $greske = [];
        if (!preg_match($reg, $naslov)) {
            array_push($greske, "Greska naslov");
        }
        if (!preg_match($reg, $tekst)) {
            array_push($greske, "Greska pitanje");
        }

        if (count($greske) == 0) {
            $upit = $konekcija->prepare("INSERT INTO ankete (admin_id, naziv, pitanje, status_ankete) VALUES (:admin_id, :naziv, :pitanje, 1)");
            $upit->bindParam(":admin_id", $admin_id);
            $upit->bindParam(":naziv", $naslov);
            $upit->bindParam(":pitanje", $tekst);
            $upit->execute();
            if ($upit) {
                http_response_code(201);
                $odgovor = "Uspesno ste dodali anketu!";
                echo json_encode($odgovor);
                
            } else {
                http_response_code(408);
                $odgovor = "Greska prilikom dodavanja ankete";
                echo json_encode($odgovor);
                
            }
        }
    } catch (PDOException $e) {
        http_response_code(500);
        $odgovor = $e;
        echo json_encode($odgovor);
        
    }
} else {
    http_response_code(404);
}
