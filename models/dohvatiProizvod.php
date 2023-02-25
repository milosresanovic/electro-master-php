<?php 
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        include "functions/functions.php";
        include "../config/konekcija.php";
        
        try{
            $proizvod_id = $_POST['id'];
            $upit = $konekcija -> prepare("");
        }
        catch(PDOException $e){
            http_response_code(500);
            $odgovor = "Greska na serveru";
            echo $odgovor;
            
        }
    }
    else{
        http_response_code(404);
    }