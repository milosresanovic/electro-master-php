<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "functions/functions.php";
    include "../config/konekcija.php";

    try {
        $status = $_POST['status_ankete'];
        if ($status == 0 || $status == 1) {
            if($status == 1) $status=0; else $status = 1;
            $anketa_id = $_SESSION['anketa_id'];
            $upit = $konekcija->prepare("UPDATE `ankete` SET status_ankete=:status_ankete WHERE anketa_id = :anketa_id");
            $upit->bindParam(":anketa_id", $anketa_id);
            $upit->bindParam(":status_ankete", $status);
            $upit->execute();
            if ($upit) {
                http_response_code(201);
                $odgovor = ["nazivUloge" => "Uspesno ste promenili status ankete!"];
                echo json_encode($odgovor);
                
            } else {
                http_response_code(408);
                $odgovor = ["nazivUloge" => "Upit se nije izvrsio!"];
                echo json_encode($odgovor);
                
            }
        } else {
            http_response_code(409);
            $odgovor = ["nazivUloge" => "Prosledili ste los status ankete"];
            echo json_encode($odgovor);
            
        }
    } catch (PDOException $e) {
        http_response_code(500);
        $odgovor = ["nazivUloge" => "Doslo je do greske na serveru, pokusajte kasnije"];
        echo json_encode($e);
        
    }
} else {
    http_response_code(404);
}
