<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "functions/functions.php";
    include "../config/konekcija.php";

    try {
        $korisnik_id = $_SESSION['user']->korisnik_id;
        $adresa = $_POST['adresa'];
        $proizvodi = $_POST['proizvodi'];
        $postanski_broj = $_POST['zip'];
        $kolicine = $_POST['kolicine'];
        $nizCena = [];

        $ukupnaSuma = 0;
        for ($i = 0; $i < count($proizvodi); $i++) {
            $upit = $konekcija->prepare("SELECT (c.cena * :kolicina) as cena
                FROM cene_proizvoda AS c JOIN proizvodi AS p ON c.proizvod_id = p.proizvod_id
                WHERE p.proizvod_id = :proizvod_id");
            $upit->bindParam(":kolicina", $kolicine[$i]);
            $upit->bindParam(":proizvod_id", $proizvodi[$i]);
            $upit->execute();
            $pom = $upit->fetch();
            array_push($nizCena, $pom->cena);
            $ukupnaSuma += $pom->cena;

        }
        //echo $ukupnaSuma;
        $upitInsertRacun = $konekcija->prepare("INSERT INTO racuni (korisnik_id, suma, adresa_dostave, postanski_broj) VALUES (:korisnik_id, :suma, :adresa_dostave, :postanski_broj)");
        $upitInsertRacun->bindParam(":korisnik_id", $korisnik_id);
        $upitInsertRacun->bindParam(":suma", $ukupnaSuma);
        $upitInsertRacun->bindParam(":adresa_dostave", $adresa);
        $upitInsertRacun->bindParam(":postanski_broj", $postanski_broj);
        $upitInsertRacun->execute();

        if ($upitInsertRacun) {
            $racun_id = $konekcija->lastInsertId();
            for ($i = 0; $i < count($proizvodi); $i++) {
                $upitStavka = $konekcija->prepare("INSERT INTO stvke_racuna (proizvod_id, racun_id, cena_stavke, kolicina) VALUES (:proizvod_id, :racun_id, :cena_stavke, :kolicina)");
                $upitStavka->bindParam(":proizvod_id", $proizvodi[$i]);
                $upitStavka->bindParam(":racun_id", $racun_id);
                $upitStavka->bindParam(":cena_stavke", $nizCena[$i]);
                $upitStavka->bindParam(":kolicina", $kolicine[$i]);
                $upitStavka->execute();

                if ($upitStavka) {
                    http_response_code(201);
                    $odgovor = "Bravo, sve je uspelo da se unese!";
                    echo $odgovor;
                    
                } else {
                    http_response_code(408);
                    $odgovor = "Greska prilikom unosa stavki racuna!";
                    echo $odgovor;
                    
                }
            }
        } else {
            http_response_code(409);
            $odgovor = "Greska unosa samog racuna!";
            echo $odgovor;
            
        }
    } catch (PDOException $e) {
        http_response_code(500);
        $odgovor = "Greska na serveru!";
        echo $e;
        
    }
} else {
    http_response_code(404);
}
