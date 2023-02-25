<?php
$upit = $konekcija->prepare("SELECT p.proizvod_id as id, p.naziv as naziv_proizvoda, p.lager as broj_na_stanju, p.broj_zvezdica, k.naziv as naziv_kategorija, b.naziv as naziv_brenda, s.src as slika, s.alt , c.cena as cena_proizvoda
    FROM proizvodi p 
    JOIN kategorije k ON p.kategorija_id = k.kategorija_id 
    JOIN brendovi b ON p.brend_id = b.brend_id
    JOIN cene_proizvoda c ON p.proizvod_id = c.proizvod_id
    JOIN slike_proizvoda s on s.proizvod_id = p.proizvod_id
    GROUP BY p.proizvod_id
    ");
$upit->execute();
$proizvodi = $upit->fetchAll();
?>
<div class="section">
    <div class="container">
        <button id="dugmeDodajProizvod" class="alert alert-info">Dodaj novi proizvod</button>
    </div>
</div>
<div class="section">
    <div class="container">
        <h2>Svi proizvodi</h2>
        <br>
        <?php foreach ($proizvodi as $p) : ?>
            <div class="w-100 border border-secondary row div-proizvod">
                <div class="div-slika-proizvodi">
                    <img src="assets/img/slikeBaza/<?php echo $p->slika?>" alt="<?php echo $p->alt?>" class="slika-proizvod">
                </div>
                <div class="div-podaci-proizvodi">
                    <h4><?php echo $p->naziv_proizvoda ?></h4>
                    <p><b>Brend: </b> <?php echo $p->naziv_brenda ?></p>
                    <p><b>Kategorija: </b> <?php echo $p->naziv_kategorija ?></p>
                    <span>
                        <?php echo printStars($p->broj_zvezdica) ?>
                    </span>
                    <div class="div-cena-stanje">
                        <p><b>Cena: </b> <?php echo $p->cena_proizvoda ?></p>
                        <p <?php if($p->broj_na_stanju == 0) echo "class='bg-danger'" ?>><b>Na stanju: </b> <?php echo $p->broj_na_stanju ?></p>
                    </div>
                </div>
                <div class="div-dugmici-proizvodi">
                    <div>
                        <button onclick="izmeniProizvodSaId(<?php echo $p->id ?>)" class="alert alert-warning">Izmeni</button>
                    </div>
                    <div>
                        <button onclick="izbrisiProizvod(<?php echo $p->id ?>)" class="alert alert-danger">Obrisi</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>