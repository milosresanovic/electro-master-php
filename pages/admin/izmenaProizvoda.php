<?php

$kategorije = selectQuery("kategorije");
$brendovi = selectQuery("brendovi");


if (isset($_GET['id'])) {
    global $konekcija;
    $id = $_GET['id'];
    $_SESSION['proizvod_id'] = $id;
    $upit = $konekcija->prepare("SELECT p.kategorija_id, p.brend_id, p.proizvod_id as id, p.naziv as naziv_proizvoda, p.lager as broj_na_stanju, p.broj_zvezdica, k.naziv as naziv_kategorija, b.naziv as naziv_brenda, c.cena as cena_proizvoda
        FROM proizvodi p 
        JOIN kategorije k ON p.kategorija_id = k.kategorija_id 
        JOIN brendovi b ON p.brend_id = b.brend_id
        JOIN cene_proizvoda c ON p.proizvod_id = c.proizvod_id
        WHERE p.proizvod_id = :proizvodId
        ");
    $upit->bindParam(":proizvodId", $id);
    $upit->execute();
    if ($upit) {
        $proizvod = $upit->fetch();
        $karakteristike = $konekcija->prepare("SELECT * FROM karakteristike WHERE proizvod_id = :proizvodId");
        $karakteristike->bindParam(":proizvodId", $id);
        $karakteristike->execute();
        $ka = $karakteristike->fetch();
    } else {
        http_response_code(408);
    }
} else {
    http_response_code(404);
}

?>

<div class="section">
    <div class="container">
        <form action="" method="POST" onsubmit="return false">
            <div class="billing-details">
                <div class="section-title">
                    <h3 class="title">Izmena proizvoda</h3>
                </div>
                <div id="hahaha" class="form-group">
                    <input id="poljeNaziv" class="input" type="text" name="naziv" value="<?php echo $proizvod->naziv_proizvoda ?>" placeholder="Naziv proizvoda" />
                </div>
                <div class="form-group">
                    <input id="poljeCena" class="input" type="text" name="cena" value="<?php echo $proizvod->cena_proizvoda ?>" placeholder="Cena" />
                    <span id="greskaCena" class="greska"></span>
                </div>
                <div class="div-select">
                    <div>
                        Kategorija:
                        <select class="form-control" name="kategorije" id="kategorije">
                            <option value="<?php echo $proizvod->kategorija_id ?>"><?php echo  $proizvod->naziv_kategorija ?></option>
                            <?php foreach ($kategorije as $k) : ?>
                                <option value="<?php echo $k->kategorija_id ?>"><?php echo $k->naziv ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        Brend:
                        <select class="form-control" name="brendovi" id="brendovi">
                            <option value="<?php echo $proizvod->brend_id ?>"><?php echo  $proizvod->naziv_brenda ?></option>
                            <?php foreach ($brendovi as $b) : ?>
                                <option value="<?php echo $b->brend_id ?>"><?php echo $b->naziv ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        Ocena: (broj zvezdica)
                        <select class="form-control" name="zvezdice" id="brojZvezdica">
                            <option value=""><?php echo $proizvod->broj_zvezdica ?></option>
                            <option value="0">0 (nema ocenu)</i></option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="div-select">
                    <div>
                        Visina:
                        <input id="poljeVisina" class="input" type="text" name="visina" value="<?php echo $ka->visina ?>" placeholder="Visina (u cm)" />
                        <span id="greskaVisina" class="greska"></span>
                    </div>
                    <div>
                        Duzina:
                        <input id="poljeDuzina" class="input" type="text" name="duzina" value="<?php echo $ka->duzina ?>" placeholder="Duzina (u cm)" />
                        <span id="greskaDuzina" class="greska"></span>
                    </div>
                    <div>
                        Sirina:
                        <input id="poljeSirina" class="input" type="text" name="sirina" value="<?php echo $ka->sirina ?>" placeholder="Širina (u cm)" />
                        <span id="greskaSirina" class="greska"></span>
                    </div>
                </div>
                <div class="div-select">
                    <div>
                        Težina:
                        <input id="poljeTezina" class="input" type="text" name="tezina" value="<?php echo $ka->tezina ?>" placeholder="Težina (u kg)" />
                        <span id="greskaTezina" class="greska"></span>
                    </div>
                    <div>
                        Potrošnja energije:
                        <input id="poljePotrosnja" class="input" type="text" name="potrosnja" value="<?php echo $ka->potrosnja_energije ?>" placeholder="Potrošnja (u kWh)" />
                        <span id="greskaPotrosnja" class="greska"></span>
                    </div>
                    <div>
                        Boja:
                        <input id="poljeBoja" class="input" type="text" name="boja" value="<?php echo $ka->boja ?>" placeholder="Boja" />
                        <span id="greskaBoja" class="greska"></span>
                    </div>
                </div>
                <div class="div-select">
                    <div>
                        Snaga:
                        <input id="poljeSnaga" class="input" type="text" name="snaga" value="<?php echo $ka->snaga ?>" placeholder="Snaga (u kWh)" />
                        <span id="greskaSnaga" class="greska"></span>
                    </div>
                    <div>
                        Energetska klasa:
                        <select class="form-control" name="energetskaKlasa" id="energetskaKlasa">
                            <option value="a">A</i></option>
                            <option value="a++">A++</option>
                            <option value="a+++">A+++</i></option>
                            <option value="b">B</option>
                            <option value="b++">B++</i></option>
                            <option value="b+++">B+++</i></option>
                            <option value="c">C</option>
                            <option value="c++">C++</i></option>
                            <option value="c+++">C+++</i></option>
                        </select>
                    </div>
                    <div>
                        Na lageru: (min 1 max 30)
                        <input min="1" max="30" class="form-control" type="number" value="<?php echo $proizvod->broj_na_stanju ?>" name="kolicina" id="kolicina">
                        <span id="greskaKolicina" class="greska"></span>
                    </div>
                </div>
                <div>
                    <div class="div-center-align">
                        Tač skrin:
                        <input <?php if ($ka->touch_screen == "Da") echo "checked"; ?> class="dugme-check" type="checkbox" name="" id="tacSkrin">
                    </div>
                </div>
                <br /><br />
                <input type="button" id="izmeniProizvod" class="primary-btn order-submit" value="Izmeni proizvod" /></br></br>
                <span id="uspesnaIzmenaProizvoda" class="tekst-green">Uspešno ste izmenili proizvod!</span>
                <span id="neuspesnaIzmenaProizvoda" class="tekst-crveno">Došlo je do greške na serveru!</span>
            </div>
        </form>
    </div>
</div>