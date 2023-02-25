<div class="section">
    <div class="container">
        <div class="col-md-12">
            <form action="" method="post" onsubmit="return false">
                <div class="billing-details">
                    <div class="section-title">
                        <h3 class="title">Dodavanje ankete</h3><br><br>
                        <p class="text-danger">Vodite računa da korisnik može odgovoriti samo da: 'Da', 'Ne' i 'Ne želim da odgovorim'</p>
                    </div>
                    <div id="hahaha" class="form-group">
                        <input id="poljeNazivAnkete" class="input" type="text" name="nazivAnkete" value="" placeholder="Naziv ankete"/>
                        <span id="greskaNazivAnkete" class="greska"></span>
                    </div>
                    <div id="" class="form-group">
                        <input id="poljeTekstAnkete" class="input" type="text" name="tekstAnkete" value="" placeholder="Pitanje ankete"/>
                        <span id="greskaTekstAnkete" class="greska"></span>
                    </div>
                    <input type="button" id="dugmeUnesiAnketu" class="primary-btn order-submit" value="Izmeni" /></br></br>
                    <span id="uspesnaIzmena" class="tekst-green">Uspešno ste dodali anketu!</span>
                    <span id="neuspesnaIzmena" class="tekst-crveno">Greška pri dodavanju ankete.</span>
                </div>
            </form>
        </div>
    </div>
</div>