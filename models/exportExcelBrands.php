<?php
include "../config/konekcija.php";
$upit = $konekcija->prepare("SELECT * FROM brendovi");
$upit->execute();
try {
    if ($upit) {
        $rezultat = $upit->fetchAll();

        $file = "brendovi.xlsx";
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Type: application/x-msexcel");
        header("Content-Disposition: attachment; filename=brendovi.xls");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header("Pragma: no-cache");
        $zaUpis = "ID\tNaziv\n";
        foreach ($rezultat as $r) {
            $data = "$r->brend_id\t$r->naziv\n";
            $zaUpis .= $data;
        }

        echo $zaUpis;
        exit();
    } else {
        http_response_code(408);
        $odgovor = "Greska, upit se nije izvrsio!";
    }
} catch (PDOException $e) {
    http_response_code(500);
    $odgovor = "Greska, upit se nije izvrsio!";
}
