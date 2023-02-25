<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "functions/functions.php";
    include "../config/konekcija.php";

    $naziv = $_POST['naziv'];
    $cena = $_POST['cena'];
    $visina = $_POST['visina'];
    $sirina = $_POST['sirina'];
    $duzina = $_POST['duzina'];
    $kategorijaId = $_POST['kategorijaId'];
    $brendId = $_POST['brendId'];
    $brojZvezdica = $_POST['brojZvezdica'];
    $tezina = $_POST['tezina'];
    $potrosnjaEnergije = $_POST['potrosnjaEnergije'];
    $boja = $_POST['boja'];
    $snaga = $_POST['snaga'];
    $energetskaKlasa = $_POST['energetskaKlasa'];
    $tacSkrin = $_POST['tacSkrin'];
    $kolicina = $_POST['kolicina'];

    if ($tacSkrin == true)
        $tac = "Da";
    else
        $tac = "Ne";

    $regCena = "/^[1-9][0-9]{2,6}$/";
    $regDimenzije = "/^[1-9][0-9]{0,4}$/";
    $regTezina = "/^[1-9][0-9]{0,3}$/";
    $regPotrosnja = "/^[1-9][0-9]{2,4}$/";
    $regBoja = "/^[A-ZŠĐČĆŽ][a-zšđžčć]{2,12}$/";
    $regSnaga = "/^[1-9][0-9]{1,4}$/";
    $regKolicina = "/^[1-9][0-9]?$/";

    $nizGresaka = [];

    if (!preg_match($regCena, $cena))
        array_push($nizGresaka, "Greska cena");
    if (!preg_match($regDimenzije, $visina))
        array_push($nizGresaka, "Greska visina");
    if (!preg_match($regDimenzije, $duzina))
        array_push($nizGresaka, "Greska duzina");
    if (!preg_match($regDimenzije, $sirina))
        array_push($nizGresaka, "Greska sirina");
    if (!preg_match($regTezina, $tezina))
        array_push($nizGresaka, "Greska tezina");
    if (!preg_match($regPotrosnja, $potrosnjaEnergije))
        array_push($nizGresaka, "Greska potrosnja energije");
    if (!preg_match($regBoja, $boja))
        array_push($nizGresaka, "Greska boja");
    if (!preg_match($regSnaga, $snaga))
        array_push($nizGresaka, "Greska snaga");
    if (!preg_match($regKolicina, $kolicina) || $kolicina > 30 || $kolicina < 1)
        array_push($nizGresaka, "Greska kolicina");

    $novo = true;
    $upit = $konekcija->prepare("INSERT INTO proizvodi (naziv, kategorija_id, broj_zvezdica, brend_id, lager, novo) VALUES (:naziv, :kategorija_id, :broj_zvezdica, :brend_id, :lager, :novo)");
    $upit->bindParam(":naziv", $naziv);
    $upit->bindParam(":kategorija_id", $kategorijaId);
    $upit->bindParam(":broj_zvezdica", $brojZvezdica);
    $upit->bindParam(":brend_id", $brendId);
    $upit->bindParam(":lager", $kolicina);
    $upit->bindParam(":novo", $novo);
    $upit->execute();

    $proizvod_id_last = $konekcija->lastInsertId();
    $_SESSION['poslednji_id'] = $proizvod_id_last;
    $upitKarakteristike = $konekcija->prepare("INSERT INTO karakteristike (proizvod_id, visina, sirina, duzina, tezina, boja, potrosnja_energije, snaga, touch_screen, garancija, energetska_klasa) VALUES (:proizvod_id, :visina, :sirina, :duzina, :tezina, :boja, :potrosnja_energije, :snaga, :touch_screen, 2, :energetska_klasa)");
    $upitKarakteristike->bindParam(":proizvod_id", $proizvod_id_last);
    $upitKarakteristike->bindParam(":visina", $visina);
    $upitKarakteristike->bindParam(":sirina", $sirina);
    $upitKarakteristike->bindParam(":duzina", $duzina);
    $upitKarakteristike->bindParam(":tezina", $tezina);
    $upitKarakteristike->bindParam(":boja", $boja);
    $upitKarakteristike->bindParam(":potrosnja_energije", $potrosnjaEnergije);
    $upitKarakteristike->bindParam(":snaga", $snaga);
    $upitKarakteristike->bindParam(":touch_screen", $tac);
    $upitKarakteristike->bindParam(":energetska_klasa", $energetskaKlasa);
    //$upitKarakteristike -> execute();

    $upitCena = $konekcija->prepare("INSERT INTO cene_proizvoda (proizvod_id, cena) VALUES (:proizvod_id, :cena)");
    $upitCena->bindParam(":proizvod_id", $proizvod_id_last);
    $upitCena->bindParam(":cena", $cena);
    //$upitCena-> execute();

    if (count($nizGresaka) == 0) {
        try {
            $upitKarakteristike->execute();
            $upitCena->execute();
            if ($upit && $upitKarakteristike && $upitCena) {
                http_response_code(201);
                $odgovor = ["nazivUloge" => "Uspesno ste dodali novi proizvod!"];
                echo json_encode($odgovor);
                
            } else if (!$upit) {
                http_response_code(408);
                $odgovor = ["nazivUloge" => "Greska priliko unosa proizvoda!"];
                echo json_encode($odgovor);
                
            } else if (!$upitKarakteristike) {
                http_response_code(409);
                $odgovor = ["nazivUloge" => "Greska priliko unosa karakteristika u tabelu!"];
                echo json_encode($odgovor);
                
            } else if (!$cena) {
                http_response_code(410);
                $odgovor = ["nazivUloge" => "Greska priliko unosa cena u tabelu!"];
                echo json_encode($odgovor);
                
            } else {
                http_response_code(411);
                $odgovor = ["nazivUloge" => "Veoma velika greska!!!"];
                echo json_encode($odgovor);
               
            }
        } catch (PDOException $exception) {
            http_response_code(500);
            $odgovor = ["nazivUloge" => $proizvod_id_last];
            echo json_encode($odgovor);
            
        }
    } else {
        http_response_code(412);
        $odgovor = ["nazivUloge" => $nizGresaka];
        echo json_encode($odgovor);
        
    }
} else {
    http_response_code(404);
}
