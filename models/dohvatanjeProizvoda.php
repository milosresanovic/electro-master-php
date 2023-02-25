<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "functions/functions.php";
    include "../config/konekcija.php";

    try {
        $kategorije = $_POST['kategorije'];
        $brendovi = $_POST['brendovi'];
        $minCena = $_POST['minCena'];
        $maxCena = $_POST['maxCena'];
        $keyword = $_POST['keyword'];
        $soritranje = $_POST['sortiranje'];
        $prikazPoStrani = $_POST['brojPoStrani'];
        $startCount = $_POST['startCount'];

        $whereUpit = "";
        if ($kategorije != "[]") {
            $kategorijeString = substr($kategorije, 1);
            $kategorijeString = substr($kategorijeString, 0, strlen($kategorijeString) - 1);
            $whereUpit .= "AND (p.kategorija_id IN ($kategorijeString))";
        }
        if ($brendovi != "[]") {
            $brendoviString = substr($brendovi, 1);
            $brendoviString = substr($brendoviString, 0, strlen($brendoviString) - 1);
            $whereUpit .= "AND (p.brend_id IN ($brendoviString))";
        }
        if ($keyword != "")
            $whereUpit .= "AND (p.naziv like '%$keyword%')";

        $whereUpit .= "AND (c.cena >= $minCena AND c.cena <= $maxCena)";

        $orderBy = "";
        switch ($soritranje) {
            case 0:
                $orderBy .= "ORDER BY c.cena ASC";
                break;
            case 1:
                $orderBy .= "ORDER BY c.cena DESC";
                break;
            case 2:
                $orderBy .= "ORDER BY p.broj_zvezdica";
                break;
            case 3:
                $orderBy .= "ORDER BY p.naziv ASC";
                break;
            case 4:
                $orderBy .= "ORDER BY p.naziv DESC";
                break;
        }
        $prvaTriSlova = substr($whereUpit, 0, 3);
        if ($prvaTriSlova == "AND")
            $whereUpit = substr($whereUpit, 3);

        $upit = $konekcija->prepare("SELECT p.proizvod_id as 'id', p.naziv as 'naziv', p.brend_id as 'proizvodjacId', p.kategorija_id as 'kategorijaId', p.broj_zvezdica, s.src as 'slika', c.cena
            FROM proizvodi as p JOIN kategorije k on p.kategorija_id = k.kategorija_id
            JOIN cene_proizvoda as c ON c.proizvod_id = p.proizvod_id
            JOIN slike_proizvoda as s ON s.proizvod_id = p.proizvod_id 
            where $whereUpit
            GROUP BY p.proizvod_id
            $orderBy
            LIMIT $startCount, $prikazPoStrani");

        $upitDva = $konekcija->prepare("SELECT p.proizvod_id as 'id', p.naziv as 'naziv', p.brend_id as 'proizvodjacId', p.kategorija_id as 'kategorijaId', p.broj_zvezdica, s.src as 'slika', c.cena
            FROM proizvodi as p JOIN kategorije k on p.kategorija_id = k.kategorija_id
            JOIN cene_proizvoda as c ON c.proizvod_id = p.proizvod_id
            JOIN slike_proizvoda as s ON s.proizvod_id = p.proizvod_id 
            where $whereUpit
            GROUP BY p.proizvod_id");

        $upit->execute();
        $rezultat = $upit->fetchAll();
        $upitDva->execute();
        $rezultatDva = $upitDva->fetchAll();
        $rezultatDva = count($rezultatDva);
        $niz = [$rezultat, $rezultatDva];
        echo json_encode($niz);
        http_response_code(201);
    } catch (PDOException $e) {
        echo $e;
        http_response_code(500);
    }
} else {
    $odgovor = "Greska brt";
    http_response_code(404);
}
