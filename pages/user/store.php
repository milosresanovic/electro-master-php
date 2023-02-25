<div class="section">
	<div class="container">
		<div class="row">
			<div id="aside" class="col-md-3">
				<div class="aside">
					<h3 class="aside-title">Kategorije</h3>
					<div id="categories" class="checkbox-filter">
						<?php
						echo displayCategories();
						?>

					</div>
				</div>

				<div class="aside">
					<h3 class="aside-title">Opseg cena</h3>
					<div class="price-filter">
						<div id="price-slider"></div>
						<div class="input-number price-min">
							<input id="price-min" type="number" disabled />
						</div>
						<span>-</span>
						<div class="input-number price-max">
							<input id="price-max" type="number" disabled />
						</div>
					</div>
				</div>

				<div class="aside">
					<h3 class="aside-title">Brendovi</h3>
					<div id="brands" class="checkbox-filter">
						<?php
						echo displayBrands();
						?>

					</div>
				</div>


			</div>

			<div id="store" class="col-md-9">
				<div class="store-filter clearfix">
					<div class="store-sort" id="dropdown-pozicije">
						<div>
							<label>
								Sortiraj po:
								<select id="sortiranje" class="input-select">
									<option value="0">Ceni rastuće</option>
									<option value="1">Ceni opadajuće</option>
									<option value="2">Broju zvezdica</option>
									<option value="3">Nazivu A-Z</option>
									<option value="4">Nazivu Z-A</option>
								</select>
							</label>
						</div>

						<div>
							<label>
								<!-- Pretraga: -->
								<div id="label-search" class=" d-flex row">
									Pretraži: <input type="search" class="mojSearch" id="pretraga">
								</div>
							</label>
						</div>

						<div>
							<label>
								Prikaži:
								<select id="prikazPoStrani" class="input-select">
									<option value="2">2</option>
									<option value="4">4</option>
								</select>
							</label>
						</div>
					</div>
				</div>

				<div id="proizvodi" class="row">



				</div>

				<div class="store-filter clearfix">
					<span class="store-qty"></span>
					<ul id="stranice" class="store-pagination">

					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include "./pages/newsletter.php";
echo "</br></br>" ?>