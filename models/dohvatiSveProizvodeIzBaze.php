<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "functions/functions.php";
    include "../config/konekcija.php";

    try {
        $upit = $konekcija->prepare("SELECT p.proizvod_id as 'id', p.naziv as 'naziv', p.brend_id as 'proizvodjacId', p.kategorija_id as 'kategorijaId', p.broj_zvezdica, s.src as 'slika', c.cena
            FROM proizvodi as p JOIN kategorije k on p.kategorija_id = k.kategorija_id
            JOIN cene_proizvoda as c ON c.proizvod_id = p.proizvod_id
            JOIN slike_proizvoda as s ON s.proizvod_id = p.proizvod_id
            GROUP BY p.proizvod_id");
        $upit->execute();
        $rezultat = $upit->fetchAll();
        echo json_encode($rezultat);
        http_response_code(201);
    } catch (PDOException $e) {
        echo $e;
        http_response_code(500);
    }
} else {
    $odgovor = "Greska na serveru";
    http_response_code(404);
}
