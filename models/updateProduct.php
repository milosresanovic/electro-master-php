<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "functions/functions.php";
    include "../config/konekcija.php";

    $id = $_SESSION['proizvod_id'];
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
    $novo = true;

    if ($tacSkrin == true)
        $tac = "Da";
    else
        $tac = "Ne";

    $regCena = "/^[1-9][0-9]{2,6}$/";
    $regDimenzije = "/^[1-9][0-9]{1,4}$/";
    $regTezina = "/^[1-9][0-9]{2,3}$/";
    $regPotrosnja = "/^[1-9][0-9]{2,4}$/";
    $regBoja = "/^[a-zšđžčć]{4,12}$/";
    $regSnaga = "/^[1-9][0-9]{3,4}$/";
    $regKolicina = "/^[0-9][1-9]?$/";

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

    $update = $konekcija->prepare("UPDATE proizvodi
          SET naziv = :naziv, kategorija_id = :kategorija_id, broj_zvezdica = :broj_zvezdica, brend_id = :brend_id, lager = :lager, novo = 1
          WHERE proizvod_id = :proizvod_id");
    $update->bindParam(":naziv", $naziv);
    $update->bindParam(":kategorija_id", $kategorijaId);
    $update->bindParam(":broj_zvezdica", $brojZvezdica);
    $update->bindParam(":brend_id", $brendId);
    $update->bindParam(":lager", $kolicina);
    $update->bindParam(":proizvod_id", $id);

    $updateKarakteristike = $konekcija->prepare("UPDATE karakteristike 
        SET visina = :visina, sirina = :sirina, duzina = :duzina, tezina = :tezina, boja = :boja, potrosnja_energije = :potrosnja_energije, snaga = :snaga, touch_screen = :touch_screen, garancija = 2, energetska_klasa = :energetska_klasa 
        WHERE proizvod_id = :proizvod_idd");
    
    $updateKarakteristike->bindParam(":visina", $visina);
    $updateKarakteristike->bindParam(":sirina", $sirina);
    $updateKarakteristike->bindParam(":duzina", $duzina);
    $updateKarakteristike->bindParam(":tezina", $tezina);
    $updateKarakteristike->bindParam(":boja", $boja);
    $updateKarakteristike->bindParam(":potrosnja_energije", $potrosnjaEnergije);
    $updateKarakteristike->bindParam(":snaga", $snaga);
    $updateKarakteristike->bindParam(":touch_screen", $tacSkrin);
    $updateKarakteristike->bindParam(":energetska_klasa", $energetskaKlasa);
    $updateKarakteristike->bindParam(":proizvod_idd", $id);

    $updateCena = $konekcija->prepare("UPDATE cene_proizvoda
        SET cena = :cena
        WHERE proizvod_id = :proizvod_iddd");
    $updateCena->bindParam(":cena", $cena);
    $updateCena->bindParam(":proizvod_iddd", $id);
    //$updateCena->bindParam(":datum", $date);

    try {
        $update->execute();
        $updateKarakteristike->execute();
        $updateCena->execute();

        if ($update && $updateKarakteristike && $updateCena) {
            http_response_code(201);
            $odgovor = ["nazivUloge" => "Uspesno ste updejtovali proizvod!"];
            echo json_encode($odgovor);
            
        } else if (!$update) {
            http_response_code(407);
            $odgovor = ["nazivUloge" => "Greska priliko updejta proizvoda!"];
            echo json_encode($odgovor);
            
        } else if (!$updateKarakteristike) {
            http_response_code(408);
            $odgovor = ["nazivUloge" => "Greska priliko updejta karakteristika!"];
            echo json_encode($odgovor);
            
        } else if (!$updateCena) {
            http_response_code(409);
            $odgovor = ["nazivUloge" => "Greska priliko updejta cene!"];
            echo json_encode($odgovor);
            
        }
    } catch (PDOException $e) {
        http_response_code(500);
        $odgovor = ["nazivUloge" => "Greska na serveru molimo pokusajte kasnije!"];
        echo json_encode($e);
        
    }
} else {
    http_response_code(404);
}
