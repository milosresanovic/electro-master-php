<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "functions/functions.php";
    include "../config/konekcija.php";

    try {
        $id = $_POST['id'];

        $upit = $konekcija->prepare("UPDATE proizvodi SET lager = 0 WHERE proizvod_id = :id");
        $upit->bindParam(":id", $id);
        $upit->execute();
        if ($upit) {
            http_response_code(201);
            $odgovor = ["odgovor" => "Promenili ste da prozivod vise nije na lageru!"];
            echo json_encode($odgovor);
            
        } else {
            http_response_code(409);
            $odgovor = ["odgovor" => "Trenutno nije moguce obrisati proizvod!"];
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
