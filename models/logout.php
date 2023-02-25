<?php 
    session_start();
    include "../config/konekcija.php";
    $id = $_SESSION["user"] -> korisnik_id;
    $updateStatus = $konekcija -> prepare("UPDATE korisnici SET status = 0 WHERE korisnik_id = :id");
    $updateStatus -> bindParam(":id", $id);
    $updateStatus -> execute();
    unset($_SESSION["user"]);
    header("Location: ../index.php?page=pocetna");