<?php
include "../config/konekcija.php";
try {
    $tekst = "
Ime i prezime: Miloš Resanović
Broj indeksa: 7/20
O autoru: Moje ime je Miloš. Završio sam Eletrotehničku školu Rade Končar u Beogradu. Tokom školovanja zavole sam programiranje a ICT škola mi je privukla pažnju kada smo imalo praksu u njoj. Student sam druge dogine osnovnih studija, smer IT. ";

    header("Content-Type: application/vnd.msword");
    header("Content-Disposition: attachment; filename=author.doc");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);
    header("Pragma: no-cache");
    echo $tekst;
    exit();
} catch (PDOException $e) {
    http_response_code(500);
    $odgovor = "Greska, upit se nije izvrsio!";
}
