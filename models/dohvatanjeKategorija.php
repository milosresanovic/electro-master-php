<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    include "functions/functions.php";
    include "../config/konekcija.php";

    $upit = $konekcija->prepare("SELECT * FROM kategorije");
    $upit->execute();
    try {
        if ($upit) {
            $rezultat = $upit->fetchAll();
            http_response_code(201);
            echo json_encode($rezultat);
            $odgovor = "Uspesno dohvaceno!";
            
        } else {
            http_response_code(408);
            $odgovor = "Greska, upit se nije izvrsio!";
            
        }
    } catch (PDOException $e) {
        http_response_code(500);
        $odgovor = "Greska, upit se nije izvrsio!";
        
    }
} else {
    http_response_code(404);
    $odgovor = "Greska brt";
    
}
