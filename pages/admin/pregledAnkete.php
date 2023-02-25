
<?php if (!isset($_GET['id'])) : ?>
    <h1>Došlo je do greške, nije prosledjen ispravan identifikator ankete...</h1>
<?php else : ?>
    <div class="section">
        <div class="container">
            <?php
            $anketa_id = $_GET['id'];
            $_SESSION['anketa_id']= $anketa_id;
            $podaciAnketa = $konekcija->prepare("SELECT a.naziv, a.pitanje, a.datum, a.status_ankete, k.korisnicko_ime from ankete as a join korisnici as k on a.admin_id = k.korisnik_id where a.anketa_id = :anketa_id");
            $podaciAnketa->bindParam(":anketa_id", $anketa_id);
            $podaciAnketa->execute();
            $anketa = $podaciAnketa->fetch();

            $upitAnketa = $konekcija->prepare("SELECT * FROM odgovori_ankete WHERE anketa_id = :id");
            $upitAnketa->bindParam(":id", $anketa_id);
            $upitAnketa->execute();
            $odgovori = $upitAnketa->fetchAll();

            if ($odgovori) {
                $pitanjeJedanOdgovorDa = 0;
                $pitanjeJedanOdgovorNe = 0;
                $pitanjeJedanOdgovorBez = 0;
                $ukupnoOdgovora = count($odgovori);

                foreach ($odgovori as $o) {
                    if ($o->odgovor == 0)
                        $pitanjeJedanOdgovorNe++;
                    else if ($o->odgovor == 1)
                        $pitanjeJedanOdgovorDa++;
                    else if ($o->odgovor == 2)
                        $pitanjeJedanOdgovorBez++;
                }
                $pitanjeJedanProcenatDa = $pitanjeJedanOdgovorDa / ($ukupnoOdgovora / 100);
                $pitanjeJedanProcenatNe = $pitanjeJedanOdgovorNe / ($ukupnoOdgovora / 100);
                $pitanjeJedanProcenatBez = $pitanjeJedanOdgovorBez / ($ukupnoOdgovora / 100);
                ?>
                <h1>Pregled ankete <i>'<?php echo $anketa->naziv ?>'</i></h1><br><br>
                <h4>Pitanje: <span class="text-primary"><?php echo $anketa->pitanje ?></span></h4>
                <p>Odgovorili sa <span class="tekst-green">'Da':</span> <b><?php echo $pitanjeJedanOdgovorDa ?></b> (<?php echo round($pitanjeJedanProcenatDa) ?>%) </p>
                <p>Odgovorili sa <span class="tekst-crveno">'Ne':</span> <b><?php echo $pitanjeJedanOdgovorNe ?></b> (<?php echo round($pitanjeJedanProcenatNe) ?>%)</p>
                <p>Odgovorili sa <span class="tekst-narandzasto">'Ne želim da odgovorim':</span> <b><?php echo $pitanjeJedanOdgovorBez ?></b> (<?php echo round($pitanjeJedanProcenatBez) ?>%)</p>
                <br>

                <p class="text-info">Ukupno odgovorilo: <?php echo $ukupnoOdgovora ?> korisnika</p>
                <p class="">Anketu postavio: <u><?php echo $anketa->korisnicko_ime ?></u> Datum: <?php echo $anketa->datum ?></p>
                <p>Status ankete: <?php if($anketa -> status_ankete == 0) echo "<span class='text-danger'>Neaktivna</span>"; else echo "<span class='tekst-green'>Aktivna</span>" ?></p>
                <input onclick="promeniStatusAnkete(<?php echo $anketa -> status_ankete ?>)" class="<?php if($anketa -> status_ankete == 0) echo "alert alert-success"; else echo "alert alert-danger"?>" type="button" value="<?php if($anketa -> status_ankete == 0) echo "Aktiviraj"; else echo "Deaktiviraj" ?>">
                <br><span id="odgovorServeraNaAnketu"></span>
            <?php
            }
            else{
                echo "<h4>Trenutno nema odgovora na anketu...</h4>";
            }
            ?>
            
        </div>

    </div>
<?php endif; ?>