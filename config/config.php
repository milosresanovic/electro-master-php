<?php
define("SRV", env("SRV"));
define("DATABASE", env("DATABASE"));
define("USER_NAME", env("USER_NAME"));
define("PASSWORD", env("PASSWORD"));

function env($marker)
{
    $fajl = file(__DIR__ . "/.env");
    foreach ($fajl as $f) {
        $f = trim($f);
        list($prvo, $drugo) = explode("=", $f);
        if ($prvo == $marker) {
            return $drugo;
        }
    }
}

$param = "";
$ipAdresa = $_SERVER['REMOTE_ADDR'];
$urlAdresa = $_SERVER['REQUEST_URI'];
if(substr($urlAdresa, 26, 6)!="models"){
    if (isset($_GET['page'])) {
        if ($_GET['page'] == null) {
            $param = "pocetna";
        }
        $param = $_GET['page'];
    
        $fajl = fopen("data/log.txt", "a+");
    
        $vreme = date("d/m/Y H:i:s");
        $zaUpisivanje = "$param\t$urlAdresa\t$ipAdresa\t$vreme\n";
        fwrite($fajl, $zaUpisivanje);
        fclose($fajl);
    } else {
        $param = "pocetna";
        $fajl = fopen("data/log.txt", "a+");
        $vreme = date("d/m/Y H:i:s");
        $zaUpisivanje = "$param\t$urlAdresa\t$ipAdresa\t$vreme\n";
        fwrite($fajl, $zaUpisivanje);
        fclose($fajl);
    }
}

