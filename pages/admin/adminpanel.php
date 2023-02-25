<div class="section">
    <div class="container d-flex justify-content-between">
        <div class="col-md-3 d-flex flex-column align-items-center margina-dole-20">
            <div class="sirina-95">
                <div class="aloe">
                    <i class="fa fa-users slicica-kartica" aria-hidden="true"></i>
                </div>
                <p class="text-center"><?php echo countUsers(); ?></p>
                <h4 class="text-center">Korisnici</h4>
            </div>
        </div>
        <div class="col-md-3 d-flex flex-column align-items-center margina-dole-20">
            <div class="sirina-95">
                <div class="aloe">
                    <i class="fa fa-envelope slicica-kartica" aria-hidden="true"></i>
                </div>
                <p class="text-center"><?php echo countUnreadMessages() ?></p>
                <h4 class="text-center">Nove poruke</h4>
            </div>
        </div>
        <div class="col-md-3 d-flex flex-column align-items-center margina-dole-20">
            <div class="sirina-95">
                <div class="aloe">
                    <i class="fa fa-shopping-cart slicica-kartica" aria-hidden="true"></i>
                </div>
                <p class="text-center"><?php echo countOrders() ?></p>
                <h4 class="text-center">Broj porudžbina</h4>
            </div>
        </div>
        <div class="col-md-3 d-flex flex-column align-items-center margina-dole-20">
            <div class="sirina-95">
                <div class="aloe">
                    <i class="fa fa-money slicica-kartica" aria-hidden="true"></i>
                </div>
                <p class="text-center"><?php echo sumOrderPrice() ?></p>
                <h4 class="text-center">Ukupan profit</h4>
            </div>
        </div>
    </div>
</div>
<div class="section">
    <div class="container">
        <h2>Svi administratori sajta</h2><br>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Br.</th>
                    <th scope="col">Ime</th>
                    <th scope="col">Korisničko ime</th>
                    <th scope="col">Uloga</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $upit = $konekcija->prepare("SELECT CONCAT(k.ime, ' ', k.prezime)  AS ime, k.korisnicko_ime AS korisnicko, k.status , u.naziv as naziv FROM korisnici k JOIN uloga_korisnika u ON k.uloga_id = u.uloga_id WHERE k.uloga_id = 2");
                $upit->execute();
                $rezultat = $upit->fetchAll();
                $i = 0;
                foreach ($rezultat as $r) :
                    $i++;
                ?>
                    <tr>
                        <th scope="row"><?php echo $i ?></th>
                        <td><?php echo $r->ime ?></td>
                        <td><?php echo $r->korisnicko ?></td>
                        <td><?php echo $r->naziv ?></td>
                        <td><?php if ($r->status == 0) echo "<p style='color:#ff471a;'>offline</p>";
                            else echo "<p style='color:green;'>online</p>"; ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="5">
                        <a href="index.php?page=dodajAdmina" class="btn btn-info">Dodaj novog administratora</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="section">
    <div class="container">
        <h3>Preuzmi podatke o brendovima u <a href="models/exportExcelBrands.php" class="link-slova">Excel fajlu.</a></h3><br>
        <h3>Preuzmi podatke o autoru u <a href="models/exportWordAuthor.php" class="slova-word">Word fajlu.</a></h3>
    </div>
</div>
<div class="section">
    <div class="container">
        <h3>Statistika pristupa stranicama u %</h3>
        <h4>Korisničke stranice</h4>
        <p class="statistika">Pocetna -> <?= statistika('pocetna'); ?></p>
        <p class="statistika">Store -> <?= statistika('store'); ?></p>
        <p class="statistika">Product -> <?= statistika('product'); ?></p>
        <p class="statistika">Cart -> <?= statistika('cart'); ?></p>
        <p class="statistika">Checkout -> <?= statistika('checkout'); ?></p>
        <p class="statistika">Poruke -> <?= statistika('poruke'); ?></p>
        <p class="statistika">Ankete -> <?= statistika('ankete'); ?></p>
        <p class="statistika">Registar -> <?= statistika('register'); ?></p>
        <p class="statistika">Verifikacija -> <?= statistika('verifikacija'); ?></p>
        <p class="statistika">Login -> <?= statistika('login'); ?></p>
        <br>
        <h4>Admin stranice</h4>
        <p class="statistika">Adminpanel -> <?= statistika('adminpanel'); ?></p>
        <p class="statistika">Prikaz anketa -> <?= statistika('anketeAdmin'); ?></p>
        <p class="statistika">Dodaj admina -> <?= statistika('dodajAdmina'); ?></p>
        <p class="statistika">Dodaj anketu -> <?= statistika('dodajAnketu'); ?></p>
        <p class="statistika">Dodavanje proizvoda -> <?= statistika('dodavanjeProizvoda'); ?></p>
        <p class="statistika">Izmena korisnika -> <?= statistika('izmenaKorisnika'); ?></p>
        <p class="statistika">Izmena proizvoda -> <?= statistika('izmenaProizvoda'); ?></p>
        <p class="statistika">Odogovaranje na poruku -> <?= statistika('odgovorNaPoruku'); ?></p>
        <p class="statistika">Poruke korisnika -> <?= statistika('porukeKorisnika'); ?></p>
        <p class="statistika">Pregled ankete -> <?= statistika('pregledAnkete'); ?></p>
        <p class="statistika">Prikaz korisnika -> <?= statistika('prikaziKorisnika'); ?></p>
        <p class="statistika">Prikaz proizvoda -> <?= statistika('proizvodi'); ?></p>
        <?php
        function statistika($str)
        {
            $fajl = file("./data/log.txt");
            $brPosecenosti = 0;
            $brOdredjeneStranice = 0;
            foreach ($fajl as $f) {
                list($stranica, $url, $ip, $fullDatum) = explode("\t", $f);
                $datum = explode(" ", $fullDatum);
                if ($datum[0] == date("d/m/Y")) {
                    $brPosecenosti = $brPosecenosti + 1;
                    if ($stranica == $str) {
                        $brOdredjeneStranice = $brOdredjeneStranice + 1;
                    }
                }
            }

            $posto = ceil(($brOdredjeneStranice / $brPosecenosti) * 100);

            return ("$posto%");
        }
        ?>
    </div>
</div>
<div class="section">
    <div class="container">
        <h3>Broj prijavljivanja na sajt u toku ovog dana: <span class="brojLogovanja">
                <?php
                $fajl = file("./data/login.txt");
                $brojPoseta = 0;
                foreach ($fajl as $f) {
                    $exp = explode("\t", $f);
                    $datum = explode(" ", $exp[1]);
                    if ($datum[0] == date("d/m/Y")) {
                        $brojPoseta = $brojPoseta + 1;
                    }
                }
                echo $brojPoseta;
                ?>
            </span></h3>
    </div>
</div>
<div class="section">
    <div class="container">
        <h3>Broj poseta sajtu u proteklih 24h: <span class="brojPoseta">
                <?php
                $fajl = file("./data/log.txt");
                $brPosecenosti = 0;
                foreach ($fajl as $f) {
                    $exp = explode("\t", $f);
                    $datum = explode(" ", $exp[3]);
                    if ($datum[0] == date("d/m/Y")) {
                        $brPosecenosti = $brPosecenosti + 1;
                    }
                }
                echo $brPosecenosti;
                ?>
            </span></h3>
    </div>
</div>
<div class="section">
    <div class="container">
        <?php
        $file = file("./data/log.txt");
        foreach ($file as $f) {
            $lmao = explode("\t", $f);
            $page = $lmao[0];
            $url = $lmao[1];
            $ip = $lmao[2];
            $vreme = $lmao[3];
            echo "<p>$page ---> $url ---> $ip ---> $vreme</p>";
        }
        ?>
    </div>
</div>