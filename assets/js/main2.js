var proizvodi = [];
var kategorije = [];
var brendovi = [];
var proizvodiBaza = [];
var kategorijeBaza = [];
var brendoviBaza = [];
/* Funkcija za slanje podataka na php stranu putem ajaxa */
function sendAjax(putanja, metod, podatak, funkcija, funkcijaGreska) {
    $.ajax({
        url: putanja,
        method: metod,
        data: podatak,
        datatype: "JSON",
        success: function (data) {
            funkcija(data);
        },
        error: function (xhr) {
            funkcijaGreska(xhr);
        }
    })
}
/* Dohvati sve proizvode iz baze i smesti u jednu promenjivu */
function dohvatiSveIzBaze(funkcija) {
    $.ajax({
        url: "models/dohvatiSveProizvodeIzBaze.php",
        method: "POST",
        datatype: "JSON",
        success: function (data) {
            proizvodiBaza = JSON.parse(data);
            funkcija(proizvodiBaza);
        },
        error: function (err) {
            console.log(err);
        }
    })
}
dohvatiSveIzBaze(function (niz) {
    proizvodiBaza = niz;
});
function dohvatiSveKategorijeIzBaze() {
    $.ajax({
        url: "models/dohvatanjeKategorija.php",
        method: "GET",
        datatype: "JSON",
        async: false,
        success: function (data) {
            //console.log(data);
            //kategorijeBaza = JSON.parse(data);
            //console.log(kategorijeBaza);
        },
        error: function (err) {
            //console.log(err);
        }
    })
}
dohvatiSveKategorijeIzBaze();

window.onload = function () {
    var brojZaPrikaz;
    /* Funkcija za pozivanja ajaxa */
    function callBackAjax(nazivFajla, rezultat) {
        $.ajax({
            url: "data/" + nazivFajla + ".json",
            method: "get",
            dataType: "json",
            success: function (data) {
                rezultat(data);
            },
            error: function (xhr) {
                //console.log(xhr);
            }
        });
    }

    /* Uzimanje stranice sa koje se poziva js */
    var url = $(location).attr("href");
    url = url.substring(url.lastIndexOf('/'));
    if (url == "/" || url == "/index.php" || url == "/index.php?page=pocetna") {

        callBackAjax("proizvodi", function (rezultat) {
            proizvodi = [...rezultat];
        });
        uradiSliderMain();
    }
    if (url == "/index.php?page=store") {
        const urlSearchParams = new URLSearchParams(window.location.search);
        const params = Object.fromEntries(urlSearchParams.entries());
        pomId = params.kat;
        brojZaPrikaz = $("#prikazPoStrani").val();

        filterAndPrintProducts();
        uradiMain();
    }
    var pomId;
    if (url == "/index.php?page=product") {
        const urlSearchParams = new URLSearchParams(window.location.search);
        const params = Object.fromEntries(urlSearchParams.entries());
        pomId = params.id;
    }
    if (url == "/index.php?page=checkout") {
        setTimeout(function () {
            ispisUnutarPlacanjeProizvode(); // will show devices array
        }, 300)

    }
    if (url == "/index.php?page=cart") {
        setTimeout(() => {
            ispisKorpa();
        }, 250);
        izdracunajPodatkeRacuna()
    }

    /* Funkcija za pravljenje dugmica za stranicenje */
    function napraviDugmiceStranicenje(brojProizvoda) {
        console.log("broj proizvoda " + brojProizvoda)
        let brojDugmica = Math.ceil(brojProizvoda / $("#prikazPoStrani").val());
        console.log("Broj dugmica " + brojDugmica);
        let html = ``;
        for (let i = 0; i < brojDugmica; i++) {
            html += `<li data-min="${$("#prikazPoStrani").val() * (i)}" data-max="${$("#prikazPoStrani").val()}" class="dugmeStranica">${i + 1}</li>`;
        }
        $("#stranice").html(html);

        let dugmici = $(".dugmeStranica");
        for (let i = 0; i < dugmici.length; i++) {
            dugmici[i].addEventListener("click", () => {
                let min = dugmici[i].getAttribute('data-min');
                let max = dugmici[i].getAttribute('data-max');
                brojZaPrikaz = $("#prikazPoStrani").val();
            })
        }

    }
    /* Funkcija za odabir kolicine proizvoda unutar product.html */
    function counter() {
        var proQty = $(".pro-qty");
        proQty.prepend('<span onclick="" class="dec qtybtn minus">-</span>');
        proQty.append('<span class="inc qtybtn plus">+</span>');
        proQty.on("click", ".qtybtn", function () {
            var $button = $(this);
            var oldValue = $button.parent().find("input").val();
            if ($button.hasClass("inc")) {
                var newVal = parseFloat(oldValue) + 1;
            } else {
                // Don't allow decrementing below zero
                if (oldValue > 0) {
                    var newVal = parseFloat(oldValue) - 1;
                } else {
                    newVal = 0;
                }
            }
            $button.parent().find("input").val(newVal);
        });
    }

    /* Funkcija za ispisivanje proizvoda unutar stranice store.html */
    function ispisProizvoda(niz) {
        let html = ``;
        console.table(niz);
        proizvodiBaza = niz;
        napraviDugmiceStranicenje(niz.length);
        if (niz.length == 0)
            html = `<h2 class="alert-danger">Žao nam je, nemamo proizvode sa izabranim karakteristikama.</h2>`
        for (x of niz) {
            proizvodi.push(x)
            html += `
            <a href="index.php?page=product&id=${x.id}"><div class="col-md-4 col-xs-6">
                <div class="product">
                    <div class="product-img">
                        <img src="assets/img/slikeBaza/${x.slika}" alt="${x.slika}">
                        
                    </div>
                    <div class="product-body">
                        <p class="product-category">${ispisKategorije(x.kategorijaId)} </p>
                        <h3 class="product-name"><a href="#">${x.naziv}</a></h3>
                        <h4 class="product-price">${x.cena} <!--<del class="product-old-price">${x.cena}</del>--></h4>
                        <div class="product-rating">
                            ${brojZvezdica(x.broj_zvezdica)}
                        </div>
                    </div>
                    <div class="add-to-cart">
                        <button onclick="dodajProizvodKorpa(${x.id})" class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> Dodaj u korpu</button>
                    </div>
                </div>
            </div></a>
            `
        }
        $("#proizvodi").html(html);
    }

    /* Funkcija za ispis broja zvezdica proizvoda */
    function brojZvezdica(br) {
        let html = `<div class="product-rating">`;
        for (let i = 0; i < br; i++) {
            html += `<i class="fa fa-star"></i>`;
        }
        html += `</div>`;
        return html;
    }

    /* Funkcija koja ispisuje da li je proizvod nov */
    function daLiJeNov(nov, top) {
        let html = `<div class="product-label">`;
        if (nov)
            html += `<span class="new">NOVO</span>`;
        if (top)
            html += `<span class="sale">TOP</span>`;
        html += `</div>`
        return html;
    }

    /* Dohvatanje svih kategorija iz jsona */
    function dohvatiSveKategorije(nizKategorija) {
        for (n of nizKategorija)
            kategorije.push(n)
    }

    /* Ispis kategorije unutar proizvoda */
    function ispisKategorije(id) {
        let html = ``;
        kategorijeBaza.forEach(element => {
            if (element.kategorija_id == id)
                html += element.naziv
        })
        return html;

    }

    /* Funkcija za ucitavanje sliddera in main.js */
    function lmao() {
        $('.products-slick').each(function () {
            var $this = $(this),
                $nav = $this.attr('data-nav');

            $this.slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                autoplay: true,
                infinite: true,
                speed: 300,
                dots: false,
                arrows: true,
                appendArrows: $nav ? $nav : false,
                responsive: [{
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                },
                ]
            });
        });
    }


    /* Funkcija za mali ispis korpe kad se klikne na nju */
    function maliPrikazKorpe() {
        let proizvodiKorpaId = uzmiItemIzLocalStorage("proizvodiKorpa");
        if (proizvodiKorpaId) {
            html = `<div class="cart-list">`;
            let pomNiz = []
            let nizKolicine = []
            console.log(proizvodiBaza.length)
            console.table(proizvodiKorpaId);
            for (x of proizvodiBaza) {
                for (p of proizvodiKorpaId) {
                    if (x.id == p.id) {
                        pomNiz.push(x);
                        nizKolicine.push(p.kolicina)
                    }
                }
            }

            for (let i = 0; i < pomNiz.length; i++) {
                html += `
                    <div class="product-widget">
                        <div class="product-img">
                            <img src="assets/img/slikeBaza/${pomNiz[i].slika}" alt="">
                        </div>
                        <div class="product-body">
                            <h3 class="product-name"><a href="#">${pomNiz[i].naziv}</a></h3>
                            <h4 class="product-price"><span class="qty">${nizKolicine[i]}x</span>${pomNiz[i].cena}</h4>
                        </div>
                        <!--<button class="delete"><i class="fa fa-close"></i></button>-->
                    </div>    
                `
            }
            html += `</div>`;
            html += `
            <div class="cart-summary">
                <small>${pomNiz.length} ${vratiMnozinuJedniuinu(pomNiz.length)}</small>
                <h5>Račun: ${izracunajSumu(proizvodiKorpaId, proizvodiBaza)} rsd.</h5>
            </div>
            `
            html += `
            <div class="cart-btns">
                <a href="index.php?page=cart">Pregled korpe</a>
                <!--<input type="button" onclick="otvoriPregledKorpe()" value="Korpa"/>-->
                <a href="index.php?page=checkout">Plaćanje  <i class="fa fa-arrow-circle-right"></i></a>
            </div>
            `
            $("#maliPrikazKorpeStavke").html(html);

            function izracunajSumu(proizvodiKorpaId, proizvodi) {
                let suma = 0;
                for (p of proizvodiKorpaId) {
                    for (x of proizvodi) {
                        if (p.id == x.id) {
                            suma += p.kolicina * x.cena;
                        }
                    }
                }
                return suma;
            }
            function vratiMnozinuJedniuinu(duzina) {
                let html = ``;
                if (duzina % 10 == 1)
                    return html += `Proizvod u korpi`
                else
                    return html += `Proizvoda u korpi`
            }
        }
        else {
            $("#maliPrikazKorpeStavke").html("Korpa je prazna");
        }
    }

    /* Moja funkciza za porpavku slidera na pocetnoj za php */
    function uradiSliderMain() {
        $('.products-slick').each(function () {
            var $this = $(this),
                $nav = $this.attr('data-nav');

            $this.slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                autoplay: true,
                infinite: true,
                speed: 300,
                dots: false,
                arrows: true,
                appendArrows: $nav ? $nav : false,
                responsive: [{
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                },
                ]
            });
        });

        // Products Widget Slick
        $('.products-widget-slick').each(function () {
            var $this = $(this),
                $nav = $this.attr('data-nav');

            $this.slick({
                infinite: true,
                autoplay: true,
                speed: 300,
                dots: false,
                arrows: true,
                appendArrows: $nav ? $nav : false,
            });
        });
    }

    /* Prekopirano iz main.js jer nije htelo da radi kad je u onom fajlu */
    function uradiMain() {
        $('.products-slick').each(function () {
            var $this = $(this),
                $nav = $this.attr('data-nav');

            $this.slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                autoplay: true,
                infinite: true,
                speed: 300,
                dots: false,
                arrows: true,
                appendArrows: $nav ? $nav : false,
                responsive: [{
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                },
                ]
            });
        });

        // Product Main img Slick
        $('#product-main-img').slick({
            infinite: true,
            speed: 300,
            dots: false,
            arrows: true,
            fade: true,
            asNavFor: '#product-imgs',
        });

        var priceInputMax = document.getElementById('price-max'),
            priceInputMin = document.getElementById('price-min');

        priceInputMax.addEventListener('change', function () {
            updatePriceSlider($(this).parent(), this.value)
        });

        priceInputMin.addEventListener('change', function () {
            updatePriceSlider($(this).parent(), this.value)

        });

        var priceSlider = document.getElementById('price-slider');
        if (priceSlider) {
            noUiSlider.create(priceSlider, {
                start: [10000, 250000],
                connect: true,
                step: 1,
                range: {
                    'min': 10000,
                    'max': 250000
                }
            });

            priceSlider.noUiSlider.on('update', function (values, handle) {
                var value = values[handle];
                handle ? priceInputMax.value = value : priceInputMin.value = value
            });
        }
    }

    /* FUnkcija za dinamicko ispisivanje korpe */
    function ispisKorpa() {
        let velikiHtml = ``;
        let proizvodiKorpa = uzmiItemIzLocalStorage("proizvodiKorpa");
        if (proizvodiKorpa.length == 0) {
            $("#naslov-korpa").html("Nema proizvoda u korpi... <u><a href='store.php'> nazad u prodavnicu</a></u></br>")
            $("#naslov-korpa").addClass("crveno")
            $("#dugmeDoPlacanja").prop("href", "javascript:void(0)")
            $("#sakrijAkoJePrazno").addClass("hide")
        }
        let nizId = [];
        for (pk of proizvodiKorpa) {
            nizId.push(pk.id)
            for (p of proizvodiBaza) {
                if (pk.id == p.id) {
                    nizId.push(pk.id)
                    velikiHtml += (ispisiUnutarKorpe(p, pk.kolicina));

                }
            }
        }

        /*  */
        $("#sadrzajKorpe").html(velikiHtml);
        function ispisiUnutarKorpe(obj, kolicina) {

            let html = ``;
            html += `
            <div id="divJedanRedKorpa${obj.id}" class="glavniDiv d-flex flex-column justify-content-between align-items-center flex-md-row">
                  <div>
                    <img class="mb-2 malaSlika" src="assets/img/slikeBaza/${obj.slika}" alt="${obj.slika}" />
                  </div>
                  <div class="fix-duzina-naziv"><h5 class="mb-2">${obj.naziv}</h5></div>
                  <div id="cenaJednogKomada${obj.id}" class="shopingcartprice">${obj.cena}</div>
                  <div class="shopingcartquantity" class="mb-2">
                    <div class="counterDiv" class="mb-2">
                      <button onclick="smanji(${obj.id})" id="dugmeMinus${obj.id}" class="btnMinuIPlus">-</button>
                      <input
                        id="text${obj.id}"
                        class="counterInput"
                        type="text"
                        name=""
                        disabled
                        value="${kolicina}"
                      />
                      <button onclick="povecaj(${obj.id})" id="dugmePlus${obj.id}" class="btnMinuIPlus">+</button>
                    </div>
                  </div>
                  <div><p class="shopingcarttotal m-0"  id="ukupnaCena${obj.id}">${izracunajCenu(obj.cena, kolicina)}</p></div>
                  <div class="shopingcartitem__close mb-2">
                    <span onclick="izbaciIzKorpe(${obj.id})" class="icon_close alert-danger dugmeIzbrisi">Izbaci</span>
                  </div>
                </div>
            `
            return html;
        }
        function izracunajCenu(cena, kolicina) {
            let html = ``
            return html += cena * kolicina;
        }
        counter();
        $(".minus").on("click", () => {
            $(this).html("lamo")
        });
    }

    /* Funkcija za proveru podataka priliko registracije */
    function proveraRegistracije() {
        let poljeIme = $("#poljeIme");
        let poljePrezime = $("#poljePrezime");
        let poljeEmail = $("#poljeEmail");
        let poljeKorisnickoIme = $("#poljeKorisnickoIme");
        let poljeSifra = $("#poljeSifra");
        let poljePonovljenaSifra = $("#poljePonovljenaSifra");
        let uspesnaRegistracija = proveriSvaPoljaRegistracija(poljeIme, poljePrezime, poljeEmail, poljeKorisnickoIme, poljeSifra, poljePonovljenaSifra);
        let nizGresaka = [];

        if (uspesnaRegistracija) {
            let zaSlanje = {
                ime: poljeIme.val(),
                prezime: poljePrezime.val(),
                username: poljeKorisnickoIme.val(),
                email: poljeEmail.val(),
                sifra: poljeSifra.val()
            }
            sendAjax("models/userRegistration.php", "POST", zaSlanje, function (data) {
                $("#uspesnaRegistracija").show();
                setTimeout(() => {
                    window.location.href = "verifikacija.php";
                }, 2000);
            },
                function (xhr) {
                    if (xhr.status == 402)
                        $("#greskaEmail").html("Email je zauzet!");
                    if (xhr.status == 401)
                        $("#greskaKorisnickoIme").html("Koriničko ime je zauzeto!");
                });
        }
        else {
            return false
        }
    }
    /* Funkcija za kad se klikne na dugme za registraciju */
    $("#dugmeRegistar").on("click", () => {
        proveraRegistracije();
    })

    /* Funkcija za verifikaciju koda */
    function proveriVerifikacioniKod() {
        let kod = $("#poljeKod").val();
        let regKod = /^[1-9][0-9]{5}$/
        if (regKod.test(kod)) {
            let podatak = {
                kod: kod
            }
            sendAjax("models/verifikacija.php", "POST", podatak, function (data) {
                $("#losKod").hide();
                $("#dobarKod").show();
                setTimeout(() => {
                    window.location.href = "index.php?page=login.php";
                }, 2000);
            },
                function (xhr) {
                    if (xhr.status == 408) {
                        window.location.href = "index.php?page=verifikacija";
                    }
                    $("#neuspesnaVerifikacija").show();
                });
        }
        else {
            $("#losKod").show();
        }
    }
    $("#dugmeVerifikuj").on("click", () => {
        proveriVerifikacioniKod();
    })
    $("#dobarKod").hide();
    $("#losKod").hide();
    /* Funkcija za proveru svih polja prilikom registracije */
    function proveriSvaPoljaRegistracija(poljeIme, poljePrezime, poljeEmail, poljeKorisnickoIme, poljeSifra, poljePonovljenaSifra) {
        let nizGresaka = [];
        if (!proveriImePrezime(poljeIme.val())) {
            nizGresaka.push("greska ime");
            $("#greskaIme").html("Prvo slovo veliko, maks. 2 reči.");
        }
        else
            $("#greskaIme").html("");
        if (!proveriImePrezime(poljePrezime.val())) {
            nizGresaka.push("greska prezime");
            $("#greskaPrezime").html("Prvo slovo veliko, maks. 2 reči.");
        }
        else
            $("#greskaPrezime").html("");
        if (!proveriUsername(poljeKorisnickoIme.val())) {
            nizGresaka.push("greska username");
            $("#greskaKorisnickoIme").html("Mala slova, brojevi i '_', maks. 15 karaktera.");
        }
        else
            $("#greskaKorisnickoIme").html("");
        if (!proveriEmail(poljeEmail.val())) {
            nizGresaka.push("greska email");
            $("#greskaEmail").html("Prvo slovo veliko, maks. 2 reči.");
        }
        else
            $("#greskaEmail").html("");

        if (nizGresaka.length == 0)
            return true;
        else
            return false;
    }
    /* Funkcija za proveru prilikom izmene podataka korisnika */
    function izmenaKorisnika() {
        let poljeIme = $("#poljeIme");
        let poljePrezime = $("#poljePrezime");
        let poljeEmail = $("#poljeEmail");
        let poljeKorisnickoIme = $("#poljeKorisnickoIme");
        //let poljeSifra = $("#poljeSifra");
        //let poljePonovljenaSifra = $("#poljePonovljenaSifra");
        let nizGresaka = [];
        let proveraPodataka = proveriSvaPoljaRegistracija(poljeIme, poljePrezime, poljeEmail, poljeKorisnickoIme);

        const urlSearchParams = new URLSearchParams(window.location.search);
        const params = Object.fromEntries(urlSearchParams.entries());
        pomId = params.id;
        if (proveraPodataka) {
            let zaSlanje = {
                id: pomId,
                ime: poljeIme.val(),
                prezime: poljePrezime.val(),
                username: poljeKorisnickoIme.val(),
                email: poljeEmail.val(),
                //sifra: poljeSifra.val()
            }
            sendAjax("models/userChange.php", "POST", zaSlanje, function (data) {
                $("#uspesnaIzmena").show();
                $("#neuspesnaIzmena").hide();
                setTimeout(() => {
                    window.location.href = "index.php?page=prikazKorisnika";
                }, 2000);
            },
                function (xhr) {
                    if (xhr.status == 409)
                        $("#greskaEmail").html("Email je zauzet!");
                    if (xhr.status == 408)
                        $("#greskaKorisnickoIme").html("Koriničko ime je zauzeto!");
                    if (xhr.status == 500) {
                        $("#neuspesnaIzmena").html("Korisničko ime ili email su zauzeti!");
                        $("#neuspesnaIzmena").show();
                    }
                });
        }
        else {
            return false
        }
    }
    $("#dugmeIzmenaKorisnika").on("click", () => {
        izmenaKorisnika();
    })
    $("#uspesnaIzmena").hide();
    $("#neuspesnaIzmena").hide();

    $("#dugmeDodajProizvod").on("click", () => {
        window.location.href = "index.php?page=dodavanjeProizvoda";
    })
    /* Funkcija za proveru podataka pre unosa ili izmene proizovda */
    function proveriPodatkeProizvoda(cena, visina, duzina, sirina, tezina, boja, potrosnja, snaga, kolicina) {
        let nizGreske = [];
        let regKolicina = /^[1-9][0-9]?$/;
        let regCena = /^[1-9][0-9]{2,6}$/;
        let regDimenzije = /^[1-9][0-9]{0,4}$/;
        let regTezina = /^[1-9][0-9]{0,2}$/;
        let regPotrosnja = /^[1-9][0-9]{2,4}$/;
        let regBoja = /^[A-ZŠĐČĆŽ][a-zšđžčć]{2,12}$/
        let regSnaga = /^[1-9][0-9]{1,4}$/
        if (!regCena.test(cena)) {
            nizGreske.push("Greska cena");
            $("#greskaCena").html("Samo cifre (min 3 max 6)");
        }
        if (!regDimenzije.test(visina)) {
            nizGreske.push("Greska visina");
            $("#greskaVisina").html("Samo cifre (min 2 max 4)");
        }
        if (!regDimenzije.test(duzina)) {
            nizGreske.push("Greska duzina");
            $("#greskaDuzina").html("Samo cifre (min 2 max 4)");
        }
        if (!regDimenzije.test(sirina)) {
            nizGreske.push("Greska sirina");
            $("#greskaSirina").html("Samo cifre (min 2 max 4)");
        }
        if (!regTezina.test(tezina)) {
            nizGreske.push("Greska tezina");
            $("#greskaTezina").html("Samo cifre (min 2 max 3)");
        }
        if (!regPotrosnja.test(potrosnja)) {
            nizGreske.push("Greska potrosnja energije");
            $("#greskaPotrosnja").html("Samo cifre (min 2 max 4)");
        }
        if (!regBoja.test(boja)) {
            nizGreske.push("Greska boja");
            $("#greskaBoja").html("Samo slova min 4 max 12");
        }
        else {
            $("#greskaBoja").html("");
        }
        if (!regSnaga.test(snaga)) {
            nizGreske.push("Greska snaga");
            $("#greskaSnaga").html("Samo cifre min 3 max 4");
        }
        if (!regKolicina.test(kolicina)) {
            nizGreske.push("Greska kolicina");
            $("#greskaKolicina").html("Samo cifre min 1 max 30 proizvoda");
        }
        if (nizGreske.length == 0) {
            return true;
        }
        else {
            return false;
        }
    }
    /* Funkcija za izmenu proizvoda */
    function izmeniProizvod() {
        let nizGreske = [];
        let naziv = $("#poljeNaziv").val();
        let cena = $("#poljeCena").val();
        let visina = $("#poljeVisina").val();
        let duzina = $("#poljeDuzina").val();
        let sirina = $("#poljeSirina").val();
        let tezina = $("#poljeTezina").val();
        let boja = $("#poljeBoja").val();
        let potrosnja = $("#poljePotrosnja").val();
        let snaga = $("#poljeSnaga").val();
        let kolicina = $("#kolicina").val();

        let kategorijaId = $("#kategorije").val();
        let brendId = $("#brendovi").val();
        let ocena = $("#brojZvezdica").val();
        let energetskaKlasa = $("#energetskaKlasa").val();
        let tacSkrin = document.querySelector('#tacSkrin').checked;

        if (proveriPodatkeProizvoda(cena, visina, duzina, sirina, tezina, boja, potrosnja, snaga, kolicina)) {
            let podatakZaSlanje = {
                naziv: naziv,
                cena: cena,
                visina: visina,
                sirina: sirina,
                duzina: duzina,
                kategorijaId: kategorijaId,
                brendId: brendId,
                brojZvezdica: ocena,
                tezina: tezina,
                potrosnjaEnergije: potrosnja,
                boja: boja,
                snaga: snaga,
                energetskaKlasa: energetskaKlasa,
                tacSkrin: tacSkrin,
                kolicina: kolicina
            }
            sendAjax("models/updateProduct.php", "POST", podatakZaSlanje, function () {
                $("#uspesnaIzmenaProizvoda").show();
                $("#neuspesnaIzmenaProizvoda").hide();
                setTimeout(() => {
                    window.location.href = "index.php?page=adminpanel";
                }, 2000);
            },
                function (xhr) {
                    if (xhr.status == 500) {
                        //$("#neuspesnaIzmena").html("Gre");
                        $("#neuspesnaIzmenaProizvoda").show();
                    }
                });
        }
        else {
            //console.log("Pogresni podaci!");
        }
    }
    $("#izmeniProizvod").on("click", () => {
        izmeniProizvod()
    })
    $("#uspesnaIzmenaProizvoda").hide();
    $("#neuspesnaIzmenaProizvoda").hide();
    /* Funkcija za dodavanje novog proizvoda preko admin panela */
    function dodajProizvod() {
        let nizGreske = [];
        var fd = new FormData();
        var sveSlike = document.getElementById("slike").files.length;
        for (var i = 0; i < sveSlike; i++)
            fd.append('slike[]', document.getElementById('slike').files[i])
        let naziv = $("#poljeNaziv").val();
        let cena = $("#poljeCena").val();
        let visina = $("#poljeVisina").val();
        let duzina = $("#poljeDuzina").val();
        let sirina = $("#poljeSirina").val();
        let tezina = $("#poljeTezina").val();
        let boja = $("#poljeBoja").val();
        let potrosnja = $("#poljePotrosnja").val();
        let snaga = $("#poljeSnaga").val();
        let kolicina = $("#kolicina").val();

        let kategorijaId = $("#kategorije").val();
        let brendId = $("#brendovi").val();
        let ocena = $("#brojZvezdica").val();
        let energetskaKlasa = $("#energetskaKlasa").val();
        let tacSkrin = document.querySelector('#tacSkrin').checked;

        if (proveriPodatkeProizvoda(cena, visina, duzina, sirina, tezina, boja, potrosnja, snaga, kolicina)) {
            let podatakZaSlanje = {
                naziv: naziv,
                cena: cena,
                visina: visina,
                sirina: sirina,
                duzina: duzina,
                kategorijaId: kategorijaId,
                brendId: brendId,
                brojZvezdica: ocena,
                tezina: tezina,
                potrosnjaEnergije: potrosnja,
                boja: boja,
                snaga: snaga,
                energetskaKlasa: energetskaKlasa,
                tacSkrin: tacSkrin,
                kolicina: kolicina
            }
            sendAjax("models/addProduct.php", "POST", podatakZaSlanje, function () {
                $.ajax({
                    url: "models/addProductImage.php",
                    method: "POST",
                    datatype: "json",
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        //console.log("uspesno poslata slika");
                    },
                    error: function (err) {
                        //console.log("nije uspela da se posalje slika");
                    }
                })
                $("#uspesnoDodavanjeProizvoda").show();
                $("#neUspesnoDodavanjeProizvoda").hide();
                setTimeout(() => {
                    window.location.href = "index.php?page=proizvodi";
                }, 2000);
            },
                function (xhr) {
                    if (xhr.status == 500) {
                        $("#neuspesnaIzmena").html("Korisničko ime ili email su zauzeti!");
                        $("#neUspesnoDodavanjeProizvoda").show();
                    }
                });
        }
        else {
            console.table(nizGreske);
        }
    }
    $("#dugmeDodajProizvodBaza").on("click", () => {
        dodajProizvod();
    })
    $("#uspesnoDodavanjeProizvoda").hide();
    $("#neUspesnoDodavanjeProizvoda").hide();

    /* Funkcija za slanje poruke kao odgovor korisniku */
    function odgovoriKorisniku() {
        let poruka = $("#poljeOdgovor").val();
        let podatak = {
            poruka: poruka
        };
        sendAjax("models/odgovaranjeNaPoruku.php", "POST", podatak, function () {
            $("#uspesnoOdgovoreno").show();
            /* setTimeout(() => {
                window.location.href = "adminpanel.php?page=proizvodi";
            }, 2000); */
        },
            function (xhr) {
                $("#neuspesnoOdgovoreno").show();
            });
    }
    $("#uspesnoOdgovoreno").hide();
    $("#neuspesnoOdgovoreno").hide();
    $("#dugmePosaljiOdgovor").on("click", () => {
        odgovoriKorisniku();
    })

    /* Funckija za dodavanje novog admina */
    function dodajAdmina(id) {
        let poljeIme = $("#poljeIme");
        let poljePrezime = $("#poljePrezime");
        let poljeEmail = $("#poljeEmail");
        let poljeKorisnickoIme = $("#poljeKorisnickoIme");
        let poljeSifra = $("#poljeSifra");
        let poljePonovljenaSifra = $("#poljePonovljenaSifra");
        let nizGresaka = [];
        let proveraPodataka = proveriSvaPoljaRegistracija(poljeIme, poljePrezime, poljeEmail, poljeKorisnickoIme, poljeSifra, poljePonovljenaSifra);

        if (proveraPodataka) {
            let zaSlanje = {
                id: pomId,
                ime: poljeIme.val(),
                prezime: poljePrezime.val(),
                username: poljeKorisnickoIme.val(),
                email: poljeEmail.val(),
                sifra: poljeSifra.val()
            }
            sendAjax("models/addAdmin.php", "POST", zaSlanje, function (data) {
                $("#uspesnaRegistracija").show();
                $("#neuspesnaIzmena").hide();
                setTimeout(() => {
                    window.location.href = "index.php?page=adminpanel";
                }, 2000);
            },
                function (xhr) {
                    if (xhr.status == 409)
                        $("#greskaEmail").html("Email je zauzet!");
                    if (xhr.status == 408)
                        $("#greskaKorisnickoIme").html("Koriničko ime je zauzeto!");
                    if (xhr.status == 500) {
                        $("#neuspesnaIzmena").html("Korisničko ime ili email su zauzeti!");
                        $("#neuspesnaIzmena").show();
                    }
                });
        }
        else {
            return false
        }
    }
    $("#dugmeDodajAdmina").on("click", () => {
        dodajAdmina();
    });
    $("#uspesnaRegistracijaAdmina").hide();
    $("#neuspesnaRegistracijaAdmina").hide();
    /* Funkcija za logovanje korisnika */
    function logovanje() {
        let email = $("#poljeEmailLogovanje").val();
        let sifra = $("#poljeSifraLogovanje").val();
        //console.log("lmao bre logovanje")
        if (proveriEmail(email) && proveriSifru(sifra)) {
            $("#greskaEmailLogovanje").html("");
            $("#greskaSifraLogovanje").html("");
            let podatakZaSlanje = {
                email: email,
                sifra: sifra
            }
            sendAjax("models/login.php", "POST", podatakZaSlanje, function (data) {
                let podatak = JSON.parse(data);
                $("#neuspesnoLogovanje").hide();
                $("#uspesnoLogovanje").show();
                if (podatak.odgovor.naziv == "korisnik") {
                    setTimeout(() => {
                        window.location.href = "index.php?page=pocetna";
                    }, 2000);
                }
                else if (podatak.odgovor.naziv == "admin") {
                    setTimeout(() => {
                        window.location.href = "index.php?page=adminpanel";
                    }, 2000);
                }
            },
                function (xhr) {
                    if (xhr.status == 401) {
                        $("#neuspesnoLogovanje").html("Nalog nije verifikovan!");
                        $("#neuspesnoLogovanje").show();
                        /* setTimeout(() => {
                            window.location.href = "verifikacija.php";
                        }, 2000); */
                    }
                    if (xhr.status == 402) {
                        $("#neuspesnoLogovanje").html("Loš email ili lozinka!");
                        $("#neuspesnoLogovanje").show();
                    }


                    if (xhr.status == 409)
                        $("#greskaEmailLogovanje").html("Uneti podaci nisu u ispravnom formatu!");
                });
        }
        else if (!proveriEmail(email)) {
            $("#neuspesnoLogovanje").html("Email mora biti u ispravnom formatu!");
        }
        else if (!proveriSifru(sifra)) {
            $("#neuspesnoLogovanje").html("Šifra nije u dobrom formatu!");
        }
        /* else {
            if (!proveriEmail(email)) {
                $("#greskaEmailLogovanje").html("Pogresan email")
            }
            if (!proveriSifru(sifra)) {
                $("#greskaSifraLogovanje").html("Pogresna sifra")
            }
        } */
    }
    $("#dugmeLogin").on("click", () => {
        logovanje();
    })
    $("#uspesnoLogovanje").hide();
    /* Funkcija za ispis gresaka */
    function ispisiGresku(imePolja) {
        let el = $(`#${imePolja}`);
        if (imePolja == "poljeImePrezime") ispisGreskeIspodInputa("greskaIme", "Prva slova velika, maks. 3 reči, samo slova!");
        if (imePolja == "poljeEmail") ispisGreskeIspodInputa("greskaEmail", "Email nije u ispravnom formatu!");
        if (imePolja == "poljeAdresa") ispisGreskeIspodInputa("greskaAdresa", "Samo slova i brojevi!");
        if (imePolja == "poljeGrad") ispisGreskeIspodInputa("greskaGrad", "Prva slova velika, maks. 3 reči!");
        if (imePolja == "poljeZip") ispisGreskeIspodInputa("greskaZip", "Samo brojevi, od 4 do 6 cifara!");
        if (imePolja == "poljeTelefon") ispisGreskeIspodInputa("greskaTelefon", "Mora početi sa '06', od 7 do 9 cifara!");
    }

    /* Ispis greske ispod inputa */
    function ispisGreskeIspodInputa(id, greska) {
        $(`#${id}`).html(greska)
        $(`#${id}`).show();
    }

    /* Funkcija za proveru ispravnosti sifre */
    function proveriSifru(sifra) {
        let uzorakSifra = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
        if (uzorakSifra.test(sifra))
            return true;
        else
            return false;
    }
    /* Funkcija za proveru username */
    function proveriUsername(username) {
        let uzorakUsername = /^[A-Za-z0-9_-]{2,10}$/;
        if (uzorakUsername.test(username))
            return true;
        else
            return false;
    }
    /* Funkcija za ispitivanje unosa ime-prezimena */
    function proveriImePrezime(ime) {
        let uzorakIme = /^[A-ZČĆŠĐŽ][a-zčćšđž]{2,15}(\s[A-ZČĆŠĐŽ][a-zčćšđž]{2,15})?\s*$/;
        if (uzorakIme.test(ime))
            return true;
        else
            return false;
    }

    /* Funkcija za proveru adrese */
    function proveriAdresu(adresa) {
        let uzorakAdresa = /^[A-ZČĆŠĐŽ][a-zčćšđž]{1,15}(\s[1-9](?:[A-ZČĆŠĐŽ]|[a-zčćšđž]))?(\s[A-ZČĆŠĐŽ][a-zčćšđž]{1,15})?(?:\s[0-9]{0,3}|\s[1-9](?:[A-ZČĆŠĐŽ]|[a-zčćšđž]))?\s*$/;
        if (uzorakAdresa.test(adresa))
            return true;
        else
            return false;
    }
    /* Funkcija za ispitivanje unosa adrese */
    function proveriEmail(adresa) {
        let uzorakAdresa = /^[\w-.]+@([\w-]+.)+[\w-]{2,4}\s*$/;
        if (uzorakAdresa.test(adresa))
            return true;
        else
            return false;
    }
    /* Funkcija za ispitivanje unosa grada */
    function proveraGrad(grad) {
        let uzorakGrad = /^[A-ZČĆŠĐŽ][a-zčćšđž]{1,15}(\s[A-ZČĆŠĐŽ][a-zčćšđž]{1,15})?(\s[A-ZČĆŠĐŽ][a-zčćšđž]{1,15})?\s*$/;
        if (uzorakGrad.test(grad))
            return true;
        else
            return false;
    }
    /* Funkcija za ispitivanje unosa postanskog broja */
    function proveraPostanskiBroj(broj) {
        let uzorakBroj = /^[0-9]{3,6}\s*$/;
        if (uzorakBroj.test(broj))
            return true;
        else
            return false;
    }
    /* Funkcija za ispitivanje unosa telefona */
    function proveraTelefon(broj) {
        let uzorakBroj = /^06[0-9]{6,9}\s*$/;
        if (uzorakBroj.test(broj))
            return true;
        else
            return false;
    }
    /* Funkcija za ispisivanje proizvoda prilikom placanja */
    function ispisUnutarPlacanjeProizvode() {
        let proizvodiKorpa = uzmiItemIzLocalStorage("proizvodiKorpa");
        let html = ``;
        let suma = 0;
        for (pk of proizvodiKorpa) {
            for (p of proizvodiBaza) {
                if (pk.id == p.id) {
                    suma += pk.kolicina * p.cena;
                    html += `
                    <div class="order-col">
                        <div>${pk.kolicina}x ${p.naziv}</div>
                        <div>${p.cena}</div>
                    </div>
                    `
                }
            }
        }
        if (suma > 30000) $("#besplatno").html("0.0 RSD"); else $("#besplatno").html("500 RSD");
        $("#porudzbina-stavke").html(html)
        $("#ukupno").html(suma + ",00 RSD")
    }

    /* Funkcija za dodavanje ankete */
    function dugmeUnesiAnketu() {
        let naslov = $("#poljeNazivAnkete").val();
        let tekst = $("#poljeTekstAnkete").val();
        let reg = /^([A-Z][a-z\-?!.0-9]+)(\s[A-Za-z\-?!.0-9]+)*\s*$/;
        let greske = [];
        if (!reg.test(naslov)) {
            greske.push("Greska naslov");
            $("#greskaNazivAnkete").html("Samo slova, brojevi i znakovi interpunkcije.");
            $("#greskaNazivAnkete").show();
        }
        if (!reg.test(tekst)) {
            greske.push("Greska tekst pitanja");
            $("#greskaTekstAnkete").html("Samo slova, brojevi i znakovi interpunkcije.");
            $("#greskaTekstAnkete").show();
        }

        if (greske.length == 0) {
            $("#greskaNazivAnkete").hide();
            $("#greskaTekstAnkete").hide();
            let podatakZaSlanje = {
                naslov: naslov,
                tekst: tekst
            }
            sendAjax("models/dodajAnketu.php", "POST", podatakZaSlanje, function (data) {
                $("#uspesnaIzmena").show();
                setTimeout(() => {
                    window.location.href = "index.php?page=adminpanel";
                }, 2000);
            },
                function (xhr) {
                    $("#greskaEmailLogovanje").show();
                });
        }
        else {
            /* console.table(greske); */
        }
    }
    $("#uspesnaIzmena").hide();
    $("#greskaEmailLogovanje").hide();
    $("#dugmeUnesiAnketu").on("click", () => {
        dugmeUnesiAnketu();
    })
    /* Funkcija za potvrdu porudzbine */
    function posaljiPorudzbinu() {
        let podaciIzKorpe = uzmiItemIzLocalStorage("proizvodiKorpa");
        let proizvodi = [];
        let kolicine = [];
        for (let i = 0; i < podaciIzKorpe.length; i++) {
            proizvodi.push(podaciIzKorpe[i].id);
            kolicine.push(podaciIzKorpe[i].kolicina);
        }
        let adresa = $("#adresaZaPorudzbinu").val();
        let zip = $("#postanskiBrojZaPorudzbinu").val();
        let regAdresa = /^[A-ZČĆŠĐŽ][a-zčćšđž]{1,15}(\s[1-9](?:[A-ZČĆŠĐŽ]|[a-zčćšđž]))?(\s[A-ZČĆŠĐŽ][a-zčćšđž]{1,15})?(?:\s[0-9]{0,3}|\s[1-9](?:[A-ZČĆŠĐŽ]|[a-zčćšđž]))?$/;
        let regZip = /^[1-9][0-9]{4}$/
        let zaSlanje = {
            proizvodi: proizvodi,
            kolicine: kolicine,
            adresa: adresa,
            zip: zip
        }
        if (regAdresa.test(adresa) && regZip.test(zip)) {
            sendAjax("models/dodajNoviRacun.php", "POST", zaSlanje, function (data) {
                $("#neuspesna-porudzbina").hide();
                $("#uspesna-porudzbina").html("Uspesno ste naručili proizvode!");
                $("#uspesna-porudzbina").show();
                setTimeout(() => {
                    window.location.href = "index.php?page=pocetna";
                    localStorage.removeItem("proizvodiKorpa");
                }, 2000);
            }, function (xhr) {
                //console.log("Desila se neka greska");
                //console.log(xhr.status);
            })
        }
        else {
            $("#neuspesna-porudzbina").html("Morate uneti ispravno adresu i poštanski broj");
            $("#neuspesna-porudzbina").show();
        }

    }
    $("#dugmePosaljiPorudzbinu").on("click", () => {
        posaljiPorudzbinu();
    })
    $("#uspesna-porudzbina").hide();
    $("#neuspesna-porudzbina").hide();
    /* Funkcija za slanje poruke korisnika adminu */
    function posaljiPoruku() {
        let naslov = $("#poljeNaslov").val();
        let poruka = $("#poljePoruka").val();
        let regPoruka = /^.{1,999}$/
        poruka = poruka.trim();
        let greske = [];
        if (!regPoruka.test(naslov)) {
            $("#greskaNaslov").show();
            greske.push("Greska naslov");
            $("#greskaNaslov").html("Pogrešan format naslova.");
        }
        if (!regPoruka.test(poruka)) {
            greske.push("Greska poruka")
            $("#greskaPoruka").show();
            $("#greskaPoruka").html("Dozvoljeni su svi karakteri (bez entera)");
        }
        if (poruka == "") {
            $("#greskaPoruka").show();
            $("#greskaPoruka").html("Unesite tekst poruke.");
        }
        if (greske.length == 0) {
            $("#greskaNaslov").hide();
            $("#greskaPoruka").hide();
            let podatakZaSlanje = {
                naslov: naslov,
                poruka: poruka
            }
            sendAjax("models/porukaKorisnika.php", "POST", podatakZaSlanje, function (data) {
                $("#uspesnoSlanjePoruke").show();
                setTimeout(() => {
                    window.location.href = "index.php?page=pocetna";
                }, 2000);
            },
                function (xhr) {
                    $("#greskaEmailLogovanje").html("Greska na serveru, molimo pokušajte kasnije!");
                });
        }
    }
    $("#greskaNaslov").hide();
    $("#greskaPoruka").hide();
    $("#uspesnoSlanjePoruke").hide();
    $("#dugmePosaljiPoruku").on("click", () => {
        posaljiPoruku();
    })

    /* Funkcija za prikaz i filtriranje proizvoda preko phpa */
    function filterAndPrintProducts(startCount) {
        if (!startCount) {
            startCount = 0;
        }
        console.log("uso sam")
        let kategorije = [];
        let brendovi = [];
        let minCena = 0;
        let maxCena = 0;
        let sortiranje = 0;
        let brojPoStrani = 0;
        let keyword = "";

        for (let i = 0; i < $(".kategorija-check:checked").length; i++) {
            kategorije.push(parseInt($(".kategorija-check:checked")[i].value));
        }
        for (let i = 0; i < $(".brend-check:checked").length; i++) {
            brendovi.push(parseInt($(".brend-check:checked")[i].value));
        }
        minCena = $("#price-min").val();
        maxCena = $("#price-max").val();
        sortiranje = $("#sortiranje").val();
        brojPoStrani = $("#prikazPoStrani").val();
        keyword = $("#pretraga").val();
        keyword.trim();

        if (minCena == "")
            minCena = 0;
        if (maxCena == "")
            maxCena = 250000;

        kategorije = JSON.stringify(kategorije);
        brendovi = JSON.stringify(brendovi);

        $.ajax({
            url: "models/dohvatanjeProizvoda.php",
            method: "POST",
            datatype: "JSON",
            data: {
                kategorije: kategorije,
                brendovi: brendovi,
                minCena: minCena,
                maxCena: maxCena,
                sortiranje: sortiranje,
                brojPoStrani: brojPoStrani,
                keyword: keyword,
                startCount: startCount
            },
            success: function (response) {
                response = JSON.parse(response);
                ispisProizvoda(response[0]);
                napraviDugmad(response[1]);
            },
            error: function (error) {
                alert(error);
            }
        })
    }

    function napraviDugmad(brojProizvoda) {
        brojDugmica = Math.ceil(brojProizvoda / $("#prikazPoStrani").val());
        let html = ``;
        for (let i = 0; i < brojDugmica; i++) {
            html += `<li data-min="${$("#prikazPoStrani").val() * (i)}" data-max="${$("#prikazPoStrani").val()}" class="dugmeStranica">${i + 1}</li>`;
        }
        $("#stranice").html(html);

        let dugmici = $(".dugmeStranica");
        for (let i = 0; i < dugmici.length; i++) {
            dugmici[i].addEventListener("click", () => {
                let min = dugmici[i].getAttribute('data-min');
                let max = dugmici[i].getAttribute('data-max');
                brojZaPrikaz = $("#prikazPoStrani").val();
                filterAndPrintProducts(min);
            })
        }
    }

    $("#sortiranje").on("change", () => {
        filterAndPrintProducts()
    });
    $("#prikazPoStrani").on("change", () => {
        filterAndPrintProducts();
    })
    $("#categories").on("change", () => {
        filterAndPrintProducts();
    });
    $("#brands").on("change", () => {
        filterAndPrintProducts();
    });
    $("#price-slider").on("mousemove", () => {
        filterAndPrintProducts();
    });
    ispisBrojaStavkiKorpe();

    $("#dugmeZaDodavanjeViseStavki").on("click", () => {
        dugmeDodajUKorpu(pomId)
    });
    $("#maliPrikazKorpe").on("click", () => {
        maliPrikazKorpe();
    });

    $("#dugmeDoPlacanja").on("click", () => {
    })
    $("#pretraga").keyup(function () {
        filterAndPrintProducts()
    })

    $("#text-uspesno").hide();
    $("#text-neuspesno").hide();
    $("#uspesnaRegistracija").hide();
}


/* Funkcija za ispitivanje unosa emaila */
function proveraEmail(email) {
    let uzorakEmail = /^[\w-.]+@([\w-]+.)+[\w-]{2,4}\s*$/;
    if (uzorakEmail.test(email))
        return true;
    else
        return false;
}
/* Provera emaila za subscribe */
function proveriEmailSubscribe() {
    let email = $("#emailUnos").val();
    if (proveraEmail(email)) {
        $("#text-uspesno").show().delay(5000).fadeOut();
        let podatakZaSlanje = {
            mail: email
        }
        sendAjax("models/emailSubscribe.php", "POST", podatakZaSlanje, function (poruka) {
            //console.log(poruka);
        });
    }
    else {
        $("#text-neuspesno").show().delay(5000).fadeOut();
    }
}

var brStavki;

function dodajItemULocalStorage(ime, podatak) {
    localStorage.setItem(ime, JSON.stringify(podatak));
}

function uzmiItemIzLocalStorage(ime) {
    return JSON.parse(localStorage.getItem(ime))
}

function ispisBrojaStavkiKorpe() {
    let brojPodataka = uzmiItemIzLocalStorage("proizvodiKorpa");
    if (brojPodataka == null) {
        $("#korpicaBroj").addClass("invisible");
        $("#korpicaBroj").addClass("qty");
    }
    else {
        $("#korpicaBroj").removeClass("invisible");
        $("#korpicaBroj").addClass("visible");
        $("#korpicaBroj").addClass("qty");
        $("#korpicaBroj").html(brojPodataka.length);
    }
}

let proizvodiUnutarKorpe = []
function dodajProizvodKorpa(id, brojStavki) {
    if (brojStavki == undefined)
        brojStavki = 1;

    if (!localStorage.getItem("proizvodiKorpa")) {
        dodajPrviProizvod(id);
    }
    else {
        let korpa = uzmiItemIzLocalStorage("proizvodiKorpa");
        let xd = korpa.find(x => x.id == id)
        if (!xd) {
            dodajNoviProizvod(id)
        }
        else {
            uvecajKolicinu(id)
        }
    }
    ispisBrojaStavkiKorpe();

    /* Funkcija koja dodaje prvi proizvod u korpu koja je prazna */
    function dodajPrviProizvod(idProduct) {
        let zaKorpu = ({
            id: idProduct,
            kolicina: brojStavki
        })
        proizvodiUnutarKorpe.push(zaKorpu);
        dodajItemULocalStorage("proizvodiKorpa", proizvodiUnutarKorpe);
    }

    /* Funkcija za dodavanje proizvoda u korpu koji trenutno nije u korpi */
    function dodajNoviProizvod(idProduct) {
        let zaKorpu = ({
            id: idProduct,
            kolicina: brojStavki
        })
        let korpa = uzmiItemIzLocalStorage("proizvodiKorpa");
        korpa.push(zaKorpu);
        dodajItemULocalStorage("proizvodiKorpa", korpa);
    }

    /* Funkcija za povecavanje kolicine proizvoda koji je vec u korpi */
    function uvecajKolicinu(idProduct) {
        let korpa = uzmiItemIzLocalStorage("proizvodiKorpa");
        let xd = korpa.find(x => x.id == idProduct)
        korpa.filter(x => x.id != idProduct)
        xd.kolicina += parseInt(brojStavki);
        dodajItemULocalStorage("proizvodiKorpa", korpa);
    }
}

/* Funkcija za dodoavanje vise elemenata u korpu sa stranice product.html */
function dugmeDodajUKorpu(id) {
    brStavki = parseInt($("#poljeZaKolicinu").val());
    dodajProizvodKorpa(id, brStavki);
}

/* Povecaj broj kolicine */
function povecaj(id) {
    let broj = parseInt($(`#text${id}`).val());
    broj += 1;
    $(`#text${id}`).val(broj);
    let cena = parseInt($(`#cenaJednogKomada${id}`).html())
    let suma = cena * broj;
    $(`#ukupnaCena${id}`).html(suma)
}
/* Smanji broj kolicine */
function smanji(id) {
    let broj = parseInt($(`#text${id}`).val());
    if (broj != 1) {
        broj -= 1;
        $(`#text${id}`).val(broj);
        let cena = parseInt($(`#cenaJednogKomada${id}`).html())
        let suma = cena * broj;
        $(`#ukupnaCena${id}`).html(suma)
    }
}

/* Funkcija za ispis broja stavki korpe i ukupnog iznosa racuna korpe */
function izdracunajPodatkeRacuna() {
    let korpa = uzmiItemIzLocalStorage("proizvodiKorpa");
    let divovi = $(".glavniDiv .shopingcarttotal")
    let suma = 0;
    for (let i = 0; i < divovi.length; i++) {
        suma += parseInt(divovi[i].textContent)
    }
    $("#ukupanBrojProizvoda").html(korpa.length);
    $("#ukupnaCenaRacuna").html(suma);
}
/* Osvezavanje cele korpe */
function osveziKorpu() {
    let inputi = $(".counterInput");
    let ids;
    let objekti = []
    for (let i = 0; i < inputi.length; i++) {
        objekti.push({
            id: parseInt(inputi[i].id.substr(4, inputi[i].id.length)),
            kolicina: parseInt(inputi[i].value)
        })
    }
    dodajItemULocalStorage("proizvodiKorpa", objekti);
    izdracunajPodatkeRacuna();
}
/* Funkcija za izbacivanje proizvoda iz korpe */
function izbaciIzKorpe(id) {
    let obrisi = $(`#divJedanRedKorpa${id}`)
    obrisi.remove()
    osveziKorpu();
    ispisBrojaStavkiKorpe()
    let proizvodiKorpa = uzmiItemIzLocalStorage("proizvodiKorpa");
    let nizId = [];
    for (pk of proizvodiKorpa) {
        nizId.push(pk.id)
    }
    window.location.href = `index.php?page=cart`;
}

/* Funkcija za prosledjivanje id korisnika na stranu za izmenu korisnika */
function izmenaKorisnikaSaId(id) {
    let zaSlanje = {
        id: id
    }
    window.location.href = `index.php?page=izmenaKorisnika&id=${id}`;
    /* sendAjax("pages/admin/izmenaKorisnika.php", "GET", zaSlanje, function () {
        window.location.href = `adminpanel.php?page=izmenaKorisnika&id=${id}`;
    }, function (xhr) {
        //console.log("Nije poslato brt...");
    }) */
}
/* FUnkcija za izmenu proizvoda */
function izmeniProizvodSaId(id) {
    let regId = /^[1-9][0-9]*$/;
    if (!regId.test(id)) {
        alert("greska!");
    }
    else {
        let zaSlanje = {
            id: id
        };
        window.location.href = `index.php?page=izmenaProizvoda&id=${id}`;
    }
}
/* Funkcija za brisanje proizvoda */
function izbrisiProizvod(id) {
    let regId = /^[1-9]+$/;
    if (!regId.test(id)) {
        alert("greska!");
    }
    else {
        let zaSlanje = {
            id: id
        };
        sendAjax("models/brisanjeProizvoda.php", "POST", zaSlanje, function (data) {
            /* setTimeout"obrisanj(() => {
                window.location.href = "verifikacija.php";
            }, 2000); */
        },
            function (xhr) {
                if (xhr.status == 409)
                    $("#greskaEmail").html("Email je zauzet!");
                if (xhr.status == 408)
                    $("#greskaKorisnickoIme").html("Koriničko ime je zauzeto!");
            });
    }
}
function otvoriPoruku(id) {
    let regId = /^[1-9]([0-9]{0,4})$/;
    greske = [];
    if (!regId.test(id)) {
        greske.push("Greska id");
    }
    if (greske.length == 0) {
        let zaSlanje = {
            id: id
        }
        window.location.href = `index.php?page=odgovorNaPoruku&id=${id}`
        /* sendAjax("pages/admin/odgovorNaPoruku.php", "GET", zaSlanje, function (data) {
            window.location.href = `adminpanel.php?page=odgovorNaPoruku&id=${id}`;
        },
            function (xhr) {
                //console.log(xhr.status);
            }); */
    }
}

function loadAdminEditProductPage() {
    $("#btn-submit").click(function (e) {

        resetFormErrors();
        let regexTitle = /^\p{Uppercase_Letter}.{4,99}$/u;
        let regexDescription = /^\p{Uppercase_Letter}.{19,999}$/u;
        let regexPrice = /^\d+\.\d+$/;

        testFormElement($("#producttitle"), regexTitle, 100, " Title should be between 5 and 100 characters long start witha capital letter.");
        testFormElement($("#productdescription"), regexDescription, 1000, " Description should be between 20 and 1000 characters long start with a capital letter.");
        testFormElement($("#productprice"), regexPrice, 100, " Price must be in XX.XX format, use only numbers.");
        testFormElement($("#product-package"), /.{2,20}/, 20);
        let productImage = $("#product-image").val();
        if ($("#product-operation").val() == "New" && productImage == "") {
            formError($("#productimage"), "You must provide a product picture when creating a new product.");
        }
        let allowedFormats = ["jpg", "jpeg", "gif", "png", "webp"];
        if (productImage != "" && !allowedFormats.includes(productImage.split(".").pop())
        ) {
            formError($("#productimage"), "Allowed formats for images are jpg, jpeg, gif, webp, png.");
        }
        if (data.error) {
            e.preventDefault();
        }
    });
}
/* Funkcija za prelazak na detaljan prikaz ankete */
function pregledAnkete(id) {
    let zaSlanje = {
        id: id
    }
    window.location.href = `index.php?page=pregledAnkete&id=${id}`;
    /* sendAjax("pages/admin/pregledAnkete.php", "GET", zaSlanje, function () {
        window.location.href = `adminpanel.php?page=pregledAnkete&id=${id}`;
    }, function (xhr) {
        //console.log(xhr.status);
    }) */
}
/* Funkcija za menjanje statusa ankete */
function promeniStatusAnkete(id) {
    if (id == 0 || id == 1) {
        zaSlanje = {
            status_ankete: id
        }
        sendAjax("models/promenaStatusaAnkete.php", "POST", zaSlanje, function () {
            $("#odgovorServeraNaAnketu").html("Uspesno ste promenili status ankete");
            setTimeout(() => {
                window.location.href = "index.php?page=ankete"
            }, 2000);
        }, function (xhr) {
            if (xhr.status == 408) {
                $("#odgovorServeraNaAnketu").html("Upit se nije izvrsio...");

            }
            if (xhr.status == 409) {
                $("#odgovorServeraNaAnketu").html("Prosledili ste los status");
            }
            if (xhr.status == 500) {
                $("#odgovorServeraNaAnketu").html("Doslo je do greske na serveru");
            }
        })
    }
    else {
        alert("Korisnice ne diraj HTML kod!");
    }
}
/* Funkcija za otvaranje i ucitavanje stranice pregled korpe */
function otvoriPregledKorpe() {
    let proizvodiKorpa = uzmiItemIzLocalStorage("proizvodiKorpa");
    let nizId = [];
    for (pk of proizvodiKorpa) {
        nizId.push(pk.id)
    }
    let zaSlanje = {
        niz: nizId
    }

    sendAjax("models/prikazKorpe.php", "POST", zaSlanje, function (data) {
        window.location.href = "cart.php";
    }, function (xhr) {
        //console.log(xhr.status);
    })
}
/* Funkcija za odgovaranje na enktetu */
function odgovoriNaAnketu(id) {
    let odgovor = $(`#odgovor${id}`).val();
    let zaSlanje = {
        id: id,
        odgovor: odgovor
    }
    sendAjax("models/odgovorNaAnketu.php", "POST", zaSlanje, function (data) {
        $(`#veliki-div-${id}`).fadeOut();
    }, function (xhr) {
        //console.log(xhr.status);
    })
}
/* Funkcija za zamenu velike slicice sa slikom iz manje slicice */
function uzmiMaleSlike(n) {
    $("#velikaSlika").attr("src", n.src);
}
let xd = $(".malaSlicica");
for (let i = 0; i < xd.length; i++) {
    xd[i].addEventListener("click", () => {
        uzmiMaleSlike(xd[i])
    })
}
/* Funkcija za povecavanje kolicine proizvoda unutar detaljnog ispisa proizvoda */
function counter() {
    var proQty = $(".pro-qty");
    proQty.prepend('<span onclick="" class="dec qtybtn minus">-</span>');
    proQty.append('<span class="inc qtybtn plus">+</span>');
    proQty.on("click", ".qtybtn", function () {
        var $button = $(this);
        var oldValue = $button.parent().find("input").val();
        if ($button.hasClass("inc")) {
            var newVal = parseFloat(oldValue) + 1;
        } else {
            // Don't allow decrementing below zero
            if (oldValue > 0) {
                var newVal = parseFloat(oldValue) - 1;
            } else {
                newVal = 0;
            }
        }
        $button.parent().find("input").val(newVal);
    });
}
counter();