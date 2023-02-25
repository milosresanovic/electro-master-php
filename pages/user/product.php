<?php



$proizvod_id = $_GET['id'];
$upitProizvod = $konekcija->prepare("SELECT p.proizvod_id, p.naziv as naziv_proizvoda, p.broj_zvezdica, k.naziv as kategorija, b.naziv as brend, c.cena from proizvodi p join kategorije k on p.kategorija_id = k.kategorija_id JOIN brendovi b on p.brend_id = b.brend_id JOIN cene_proizvoda c on p.proizvod_id = c.proizvod_id WHERE p.proizvod_id = :proizvod_id");
$upitProizvod->bindParam(":proizvod_id", $proizvod_id);
$upitProizvod->execute();
$proizvod = $upitProizvod->fetch();
if ($proizvod) {
  $upitSlike = $konekcija->prepare("SELECT * from slike_proizvoda WHERE proizvod_id = :proizvod_id");
  $upitSlike->bindParam(":proizvod_id", $proizvod_id);
  $upitSlike->execute();
  $slike = $upitSlike->fetchAll();

  $specifikacije = $konekcija->prepare("SELECT * from karakteristike WHERE proizvod_id = :proizvod_id");
  $specifikacije->bindParam(":proizvod_id", $proizvod_id);
  $specifikacije->execute();
  $spec = $specifikacije->fetch();
} else {
  echo "Ne postoji trazeni proizvod";
}
?>
<?php if ($proizvod) : ?>
  <div class="section">
    <div class="container">
      <div id="proizvodDiv" class="row">
        <div class="col-md-5 col-md-push-2">
          <div id="velikaSLika"></div>
          <div class="product-preview">
            <img id="velikaSlika" src="assets/img/slikeBaza/<?php echo $slike[0]->src ?>" alt="<?php echo $slike[0]->alt ?>">
          </div>
        </div>

        <div id="triMaleSlike" class="col-md-2 col-md-pull-5 d-flex flex-lg-column align-content-between">
          <div class="maliBlokSlika">
            <img class="w-inherit malaSlicica" src="assets/img/slikeBaza/<?php echo $slike[0]->src ?>" alt="<?php echo $slike[0]->alt ?>">
          </div>
          <div class="maliBlokSlika">
            <img class="w-inherit malaSlicica" src="assets/img/slikeBaza/<?php echo $slike[1]->src ?>" alt="<?php echo $slike[1]->alt ?>">
          </div>
          <div class="maliBlokSlika">
            <img class="w-inherit malaSlicica" src="assets/img/slikeBaza/<?php echo $slike[2]->src ?>" alt="<?php echo $slike[2]->alt ?>">
          </div>
        </div>

        <div class="col-md-5">
          <div id="detaljiProizvoda" class="product-details">
            <h2 class="product-name"><?php echo $proizvod -> naziv_proizvoda ?></h2>
            <div>
              <div class="product-rating">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star-o"></i>
              </div>
            </div>
            <div>
              <h3 class="product-price"><?php echo $proizvod -> cena ?>
              <span class="product-available">Na stanju</span>
            </div>


            <div class="add-to-cart">
              <button onclick="dugmeDodajUKorpu(<?php echo $proizvod->proizvod_id ?>)" class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> Dodaj u korpu</button>
            </div>

            <div class="productdetailsquantity">
              <div class="quantity">
                <div class="pro-qty">
                  <input id="poljeZaKolicinu" type="text" value="1" />
                </div>
              </div>
            </div>


            <ul>
              <li>Proizvodjač: <?php echo '&nbsp;' . $proizvod->brend ?></li>
              <li>Garancija: &nbsp; 2 godine</li>
              <li>Dimenzije: <?php echo '&nbsp;' . $spec->visina . ' x ' . $spec->sirina . ' x ' . $spec->duzina ?> </li>
              <li></li>
            </ul>

            <ul class="product-links">
              <li>Kategorija: <?php echo '&nbsp;' . $proizvod->kategorija ?></li>
            </ul>
          </div>
        </div>

        <div class="col-md-12">
          <div id="product-tab">
            <ul class="tab-nav">
              <li class="active">
                <a data-toggle="tab" href="#tab1">Specifikacije</a>
              </li>
              <!-- <li><a data-toggle="tab" href="#tab2">Opis proizvoda</a></li> -->
            </ul>

            <div class="tab-content">
              <div id="tab1" class="tab-pane fade in active">
                <div class="row">
                  <div class="col-md-12">
                    <table class="table table-striped">
                      <tbody id="teloTabele"></tbody>
                      
                        <?php foreach ($spec as $s => $value): ?>
                          <tr>
                        <th scope="row"><?php echo ucfirst($s)?></th>
                        <td><?php  echo $value ?></td>
                        </tr>
                        <?php endforeach; ?>
                      
                    </table>
                  </div>
                </div>
              </div>

              <div id="tab2" class="tab-pane fade in">
                <div class="row">
                  <div class="col-md-12">
                    <p>Scecifikacije tabela</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php else : ?>
  echo "<h1>Ne postoji proizvod koji ste pretrazivali.</h1>"
<?php endif; ?>
<!-- SECTION -->

<!-- /SECTION -->



<!-- NEWSLETTER -->

<!-- /NEWSLETTER -->

<!-- FOOTER -->
<?php
include "pages/newsletter.php";
echo "</br></br>";
?>