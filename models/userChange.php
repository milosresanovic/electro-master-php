<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "functions/functions.php";
    include "../config/konekcija.php";

    $id = $_POST['id'];
    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    //$sifra = $_POST['sifra'];
    //$sifriranaLozinka = md5($sifra);
    $regIme = "/^[A-ZČĆŠĐŽ][a-zčćšđž]{2,15}(\s[A-ZČĆŠĐŽ][a-zčćšđž]{2,15})?\s*$/";
    $regUsername = "/^[A-Za-z0-9_-]{2,10}$/";
    $regSifra = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/";
    $nizGresaka = [];
    if (!preg_match($regIme, $ime))
        array_push($nizGresaka, "Greska ime");
    if (!preg_match($regIme, $prezime))
        array_push($nizGresaka, "Greska  prezime");
    if (!preg_match($regUsername, $username))
        array_push($nizGresaka, "Greska username");
    /* if (!preg_match($regSifra, $sifra))
        array_push($nizGresaka, "Greska sifra"); */
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        array_push($nizGresaka, "Greska email");
    
    global $konekcija;
    $korisnik = $konekcija->prepare("UPDATE korisnici SET ime = :ime, prezime = :prezime, korisnicko_ime = :korisnicko_ime, email = :email WHERE korisnik_id = :id");
    $korisnik->bindParam(":id", $id);
    $korisnik->bindParam(":ime", $ime);
    $korisnik->bindParam(":prezime", $prezime);
    $korisnik->bindParam(":korisnicko_ime", $username);
    $korisnik->bindParam(":email", $email);
    //$korisnik->bindParam(":sifra", $sifriranaLozinka);

    if (count($nizGresaka) == 0) {
        try {
            $korisnik->execute();
            if ($korisnik) {
                http_response_code(201);
                $odgovor = ["nazivUloge" => "Uspesno ste izmenili podatke!"];
                echo json_encode($odgovor);
                
            }
        } catch (PDOException $exception) {
            http_response_code(500);
            $odgovor = ["nazivUloge" => "Greska na serveru molimo pokusajte kasnije!"];
            echo json_encode($odgovor);
            
        }
    } else {
        http_response_code(409);
        $odgovor = ["nazivUloge" => "Imate gresku u unetim podacima!"];
        echo json_encode($odgovor);
        
    }
} else {
    $odgovor = ["nazivUloge" => "Usao sam u else!"];
    echo json_encode($odgovor);
    return http_response_code(404);
}
