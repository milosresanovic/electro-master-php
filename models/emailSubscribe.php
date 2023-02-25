<?php 
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        include "functions/functions.php";
        include "../config/konekcija.php";
        
        try{
            $email = $_POST['mail'];
            $unos = insertEmailForPromocodes($email);

            if($unos){
                http_response_code(201);
                $odgovor_servera = ["poruka" => "Uspesan unos!"];
                echo json_encode($odgovor_servera);
                
            }
            else{
                http_response_code(500);
                $odgovor_servera = ["poruka" => "Greska pri unosu!"];
                echo json_encode($odgovor_servera);
                
            }
        }
        catch(PDOException $exception){
            http_response_code(500);
        }
    }
    else{
        http_response_code(404);
    }
    