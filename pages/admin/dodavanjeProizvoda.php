<?php
$kategorije = selectQuery("kategorije");
$brendovi = selectQuery("brendovi");
?>

<div class="section">
    <div class="container">
        <form action="models/addProduct.php" method="POST" enctype="multipart/form-data" onsubmit="return false">
            <div class="billing-details">
                <div class="section-title">
                    <h3 class="title">Dodavanje novog proizvoda</h3>
                </div>
                <div id="hahaha" class="form-group">
                    <input id="poljeNaziv" class="input" type="text" name="naziv" placeholder="Naziv proizvoda" />
                </div>
                <div class="form-group">
                    <input id="poljeCena" class="input" type="text" name="cena" placeholder="Cena" />
                    <span id="greskaCena" class="greska"></span>
                </div>
                <div class="div-select">
                    <div>
                        Kategorija:
                        <select class="form-control" name="kategorije" id="kategorije">
                            <?php foreach ($kategorije as $k) : ?>
                                <option value="<?php echo $k->kategorija_id ?>"><?php echo $k->naziv ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        Brend:
                        <select class="form-control" name="brendovi" id="brendovi">
                            <?php foreach ($brendovi as $b) : ?>
                                <option value="<?php echo $b->brend_id ?>"><?php echo $b->naziv ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        Ocena: (broj zvezdica)
                        <select class="form-control" name="zvezdice" id="brojZvezdica">
                            <option value="0">0 (nema ocenu)</i></option>
                            <option value="1">1</option>
                            <option value="2">2</i></option>
                            <option value="3">3</option>
                            <option value="4">4</i></option>
                            <option value="5">5</i></option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="div-select">
                    <div>
                        Visina:
                        <input id="poljeVisina" class="input" type="text" name="visina" placeholder="Visina (u cm)" />
                        <span id="greskaVisina" class="greska"></span>
                    </div>
                    <div>
                        Duzina:
                        <input id="poljeDuzina" class="input" type="text" name="duzina" placeholder="Duzina (u cm)" />
                        <span id="greskaDuzina" class="greska"></span>
                    </div>
                    <div>
                        Sirina:
                        <input id="poljeSirina" class="input" type="text" name="sirina" placeholder="Širina (u cm)" />
                        <span id="greskaSirina" class="greska"></span>
                    </div>
                </div>
                <div class="div-select">
                    <div>
                        Težina:
                        <input id="poljeTezina" class="input" type="text" name="visina" placeholder="Težina (u kg)" />
                        <span id="greskaTezina" class="greska"></span>
                    </div>
                    <div>
                        Potrošnja energije:
                        <input id="poljePotrosnja" class="input" type="text" name="duzina" placeholder="Potrošnja (u kWh)" />
                        <span id="greskaPotrosnja" class="greska"></span>
                    </div>
                    <div>
                        Boja:
                        <input id="poljeBoja" class="input" type="text" name="sirina" placeholder="" />
                        <span id="greskaBoja" class="greska"></span>
                    </div>
                </div>
                <div class="div-select">
                    <div>
                        Snaga:
                        <input id="poljeSnaga" class="input" type="text" name="visina" placeholder="Snaga u W" />
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
                        Količina: (min 1 max 30)
                        <input min="1" max="30" class="form-control" type="number" name="kolicina" id="kolicina">
                        <span id="greskaKolicina" class="greska"></span>
                    </div>
                </div>
                <div>
                    <div class="div-center-align">
                        Tač skrin:
                        <input class="dugme-check" type="checkbox" name="" id="tacSkrin">
                    </div>
                </div>
                <div>
                    <br>
                    <label for="slike">Izaberite sliku:</label>
                    <input class="" type="file" multiple name="slika" id="slike">
                </div>
                <br /><br />
                <input type="button" id="dugmeDodajProizvodBaza" class="primary-btn order-submit" value="Dodaj proizvod" /></br></br>
                <span id="uspesnoDodavanjeProizvoda" class="tekst-green">Uspešno ste dodali proizvod!</span>
                <span id="neUspesnoDodavanjeProizvoda" class="tekst-crveno">Došlo je do greške na serveru!</span>
            </div>
        </form>
    </div>
</div>