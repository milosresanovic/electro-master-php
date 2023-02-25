<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "functions/functions.php";
    include "../config/konekcija.php";

    try {
        $slika = $_FILES['slike'];
        $brojSlika = count($_FILES['slike']['name']);
        $greske = [];
        $dozvoljeniTipoviSlika = ["image/jpg", "image/jpeg", "image/png"];
        for ($i = 0; $i < $brojSlika; $i++) {
            $slikaIme[$i] = $slika['name'][$i];
            $slikaTmpFajla[$i] = $slika['tmp_name'][$i];
            $slikaVelicina[$i] = $slika['size'][$i];
            if ($slikaVelicina[$i] > 200000)
                array_push($greske, "Greska velicina fajla");
            $slikaTipFajla[$i] = $slika['type'][$i];
            if (!in_array($slikaTipFajla[$i], $dozvoljeniTipoviSlika))
                array_push($greske, "Greska tip fajla");
            $slikaGreskaFajla[$i] = $slika['error'][$i];
        }
        $proizvod_id = $_SESSION['poslednji_id'];
        if (count($greske) == 0) {
            $greskaInsert = [];
            for ($i = 0; $i < $brojSlika; $i++) {
                $novoImeSlike = time() . "_" . $slikaIme[$i];
                $putanja = "../img/slikeBaza/" . $novoImeSlike;
                if (move_uploaded_file($slikaTmpFajla[$i], $putanja)) {
                    $upitInsertSlike = $konekcija->prepare("INSERT INTO slike_proizvoda (proizvod_id, src, alt) VALUES (:proizvod_id, :src, :alt)");
                    $upitInsertSlike->bindParam("proizvod_id", $proizvod_id);
                    $upitInsertSlike->bindParam("src", $novoImeSlike);
                    $upitInsertSlike->bindParam("alt", $slikaIme[$i]);

                    $upitInsertSlike->execute();

                    if (!$upitInsertSlike) {
                        array_push($greskaInsert, "Greska prilikom inserta");
                    }
                }
            }
            if (count($greskaInsert) == 0) {
                http_response_code(201);
                $odgovor = ["nazivUloge" => "SUper ste uneli sve slike"];
                echo json_encode($odgovor);
                
            } else {
                http_response_code(408);
                $odgovor = ["nazivUloge" => "Nisu se unele slike"];
                echo json_encode($odgovor);
                
            }
        } else {
            http_response_code(409);
            $odgovor = ["nazivUloge" => "Greska, tip fajla ili velicina fajla nisu odgovoarajuci."];
            echo json_encode($odgovor);
            
        }
    } catch (PDOException $e) {
        http_response_code(500);
        $odgovor = ["nazivUloge" => $e];
        echo json_encode($odgovor);
        
    }
} else {
    http_response_code(404);
}
