<?php 
    session_start();
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        include "functions/functions.php";
        include "../config/konekcija.php";
        global $konekcija;
   
        $email = $_POST['email'];
        $sifra = $_POST['sifra'];
        $sifrovanaLozinka = md5($sifra);
        $regSifra = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/";
        $nizGresaka = [];
        if(!preg_match($regSifra, $sifra))            
            array_push($nizGresaka, "Greska sifra");
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            array_push($nizGresaka, "Greska email");  
        
        if(count($nizGresaka)==0){
            $login = $konekcija -> prepare("SELECT * FROM korisnici k JOIN uloga_korisnika u ON k.uloga_id = u.uloga_id WHERE k.email = :email AND k.sifra = :sifra");
            $login->bindParam(":email", $email);
            $login->bindParam(":sifra", $sifrovanaLozinka);
            $login->execute();
            $korisnik = $login->fetch();
            if($korisnik && $korisnik->verifikovan == 1){
                $_SESSION["user"]=$korisnik;
                $id = $_SESSION["user"] -> korisnik_id;
                $updateStatus = $konekcija -> prepare("UPDATE korisnici SET status = 1 WHERE korisnik_id = :id");
                $updateStatus -> bindParam(":id", $id);
                $updateStatus -> execute();

                $file = fopen("../data/login.txt", "a+");
                $vreme = date("d/m/Y H:i:s");
                $zaUpis = "$email\t$vreme\n";
                fwrite($file, $zaUpis);
                fclose($file);

                http_response_code(201);
                $odgovor = ["odgovor"=> $korisnik];
                echo json_encode($odgovor);
                
            }
            else if($korisnik == null){
                http_response_code(402);
                $odgovor = ["odgovor"=>"Pogresan email ili sifra"];
                echo json_encode($odgovor);
            }
            else if($korisnik -> verifikovan == 0){
                http_response_code(401);
                $odgovor = ["odgovor"=>"Korisnik nije verifikovan!"];
                echo json_encode($odgovor);
            }  
            else{
                http_response_code(409);
            }
        }
        else{
            http_response_code(409);
            $odgovor = ["odgovor"=>"Niste uneli podatke u ispravnom formatu!"];
            echo json_encode($odgovor);
            
        }
    }
    else{
        return http_response_code(404);
    }