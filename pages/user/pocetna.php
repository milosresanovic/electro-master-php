<div class="section">
  <!-- container -->
  <div class="container">
    <!-- row -->
    <div id="divKolekcije" class="row">
      <!-- shop -->

      <!-- /shop -->
    </div>
    <!-- /row -->
  </div>
  <!-- /container -->
</div>
<!-- /SECTION -->

<!-- SECTION -->
<div class="section">
  <!-- container -->
  <div class="container">
    <!-- row -->
    <div class="row">
      <!-- section title -->
      <div class="col-md-12">
        <div class="section-title">
          <h3 class="title">Novo u ponudi</h3>
        </div>
      </div>
      <!-- /section title -->

      <!-- Products tab & slick -->
      <div class="col-md-12">
        <div class="row">
          <div class="products-tabs">
            <div id="tab1" class="tab-pane active">
              <div id="noviProizvodi" class="products-slick" data-nav="#slick-nav-1">
                <?php
                $upit = $konekcija->prepare("SELECT p.proizvod_id, p.naziv as naziv_proizvoda, p.broj_zvezdica, k.naziv as naziv_kategorije, b.naziv as naziv_brenda, c.cena, s.src, s.alt 
                  FROM proizvodi p JOIN kategorije k ON p.kategorija_id = k.kategorija_id
                  JOIN brendovi b on p.brend_id = b.brend_id 
                  JOIN cene_proizvoda c ON c.proizvod_id = p.proizvod_id 
                  JOIN slike_proizvoda s on s.proizvod_id = p.proizvod_id
                  GROUP BY p.proizvod_id");
                $upit->execute();
                $proizvodi = $upit->fetchAll();
                ?>
                <?php foreach ($proizvodi as $p) : ?>

                  <div class="product">
                    <div class="product-img">
                      <img src="assets/img/slikeBaza/<?php echo $p->src ?>" alt="" />
                      <div class="product-label">
                        <span class="new">NEW</span>
                      </div>
                    </div>
                    <div class="product-body">
                      <p class="product-category"><?php echo $p->naziv_kategorije ?></p>
                      <h3 class="product-name">
                        <a href="#"><?php echo $p->naziv_proizvoda ?></a>
                      </h3>
                      <h4 class="product-price">
                        <?php echo $p->cena ?> <del class="product-old-price"></del>
                      </h4>
                      <div class="product-rating">
                        <?php for ($i = 0; $i < $p->broj_zvezdica; $i++) echo "<i class='fa fa-star zvezdica-boja'></i>" ?>
                      </div>
                      <div class="product-btns">

                        <button class="quick-view">
                          <a href="index.php?page=product&id=<?php echo $p->proizvod_id ?>"><i class="fa fa-eye"></i></a><span class="tooltipp">Detaljan prikaz</span>
                        </button>
                      </div>
                    </div>
                    <div class="add-to-cart">
                      <button class="add-to-cart-btn" onclick="dodajProizvodKorpa(<?php echo $p->proizvod_id ?>)">
                        <i class="fa fa-shopping-cart"></i> Dodaj u korpu
                      </button>
                    </div>
                  </div>

                <?php endforeach; ?>

              </div>
              <div id="slick-nav-1" class="products-slick-nav"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /SECTION -->

<!-- HOT DEAL SECTION -->
<div id="hot-deal" class="section">
  <!-- container -->
  <div class="container">
    <!-- row -->
    <div class="row">
      <div class="col-md-12">
        <div class="hot-deal">
          <h2 class="text-uppercase">Top ponuda</h2>
          <p>SVI artikli na snižnju!</p>
          <a class="primary-btn cta-btn" href="store.php">Kupujte sada</a>
        </div>
      </div>
    </div>
    <!-- /row -->
  </div>
  <!-- /container -->
</div>
<!-- /HOT DEAL SECTION -->

<!-- SECTION -->
<div class="section">
  <!-- container -->
  <div class="container">
    <!-- row -->
    <div class="row">
      <!-- section title -->

      <!-- /section title -->

      <!-- Products tab & slick -->
      <div class="col-md-12">
        <div class="row">
          <div class="products-tabs">
            <!-- tab -->
            <div id="tab2" class="tab-pane fade in active">
              <div id="topProizvodi" class="products-slickk" data-nav="#slick-nav-2">
                <!-- product -->

              </div>
              <div id="slick-nav-2" class="products-slick-nav"></div>
            </div>
            <!-- /tab -->
          </div>
        </div>
      </div>
      <!-- /Products tab & slick -->
    </div>
    <!-- /row -->
  </div>
  <!-- /container -->
</div>
<!-- /SECTION -->

<!-- NEWSLETTER -->
<div id="newsletter" class="section">
  <?php include "pages/newsletter.php" ?>
</div>
<!-- /NEWSLETTER -->
