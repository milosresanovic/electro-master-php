<?php if (!isset($_GET['id'])) : ?>
    <h1>Došlo je do greške...</h1>
<?php else : ?>
    <div class="section">
        <div class="container">
            <?php
            global $konekcija;
            $idPoruke = $_GET['id'];
            $upit = $konekcija->prepare("SELECT p.poruka_id, p.korisnik_id, p.naslov, p.sadrzaj, p.datum, k.korisnicko_ime, k.email, k.ime, k.prezime 
            FROM poruke_za_admina p JOIN korisnici k ON p.korisnik_id = k.korisnik_id 
            WHERE poruka_id = $idPoruke");
            $upit->execute();
            $poruka = $upit->fetch();

            $_SESSION["podaci_poruka"] = ["email" => $poruka->email, "ime" => $poruka->ime, "prezime" => $poruka->prezime, "poruka_id" => $poruka->poruka_id, "naslov" => $poruka->naslov ];
            ?>
            <form action="" method="post" onsubmit="return false">
                <div class="billing-details">
                    <div class="section-title">
                        <h3 class="title">Pregled poruke</h3>
                    </div>
                    <span>
                        <p>
                            <b>Korisnik: </b> <?php echo $poruka->ime . ' ' . $poruka->prezime ?>
                        </p>
                    </span>
                    <span>
                        <p>
                            <b>Email: </b> <?php echo $poruka->email ?>
                        </p>
                    </span>
                    <span>
                        <p>
                            <b>Datum: </b> <?php echo $poruka->datum ?>
                        </p>
                    </span>
                    <span>
                        <p>
                            <b>Naslov: </b> <?php echo $poruka->naslov ?>
                        </p>
                    </span>
                    <span>
                        <p>
                            <b>Poruka: </b> <?php echo $poruka->sadrzaj ?>
                        </p>
                    </span>
                    <br><br>
                    <div id="" class="form-group">
                        <h4>Unesite odgovor: (odgovor se šalje na email korisnika)</h4>
                        <textarea placeholder="Tekst poruke..." value="" class="input" name="poruka" id="poljeOdgovor" cols="30" rows="20">
                        </textarea>
                        <span id="greskaPoruka" class="greska"></span>
                    </div>
                    <input type="button" id="dugmePosaljiOdgovor" class="primary-btn order-submit" value="Pošalji odgovor" />
                </div>
                <span id="uspesnoOdgovoreno" class="tekst-green">Uspešno ste odgovori na poruku.</span>
                <span id="neuspesnoOdgovoreno" class="tekst-crveno">Greška pria slanju poruke.</span>
            </form>
        </div>
    </div>
<?php endif; ?>