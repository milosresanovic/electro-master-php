<?php
session_start();
include "config/konekcija.php";
include "models/functions/functions.php";

if (isset($_GET['page'])) {
  $page = $_GET['page'];
  if ($page == 'checkout' || $page == 'cart' || $page == 'ankete' || $page == 'poruke') {
    if (!isset($_SESSION['user'])) {
      header("Location: index.php?page=login");
    }
  }
  if ($page == 'adminpanel' || $page == 'anketeAdmin' || $page == 'dodajAdmina' || $page == 'dodajAnketu' || $page == 'dodavanjeProizvoda' || $page == 'izmenaKorisnika' || $page == 'izmenaProizvoda' || $page == 'odgovorNaPoruku' || $page == 'porukeKorisnika' || $page == 'pregledAnkete' || $page == 'prikazKorisnika' || $page == 'proizvodi') {
    if (!isset($_SESSION['user']) || $_SESSION['user']->uloga_id == 1) {
      header("Location: index.php?page=login");
    }
  }
  
}

include "pages/head.php";
include "pages/header.php";
include "pages/nav.php";

if (isset($_GET['page'])) {
  $page = $_GET['page'];

  switch ($page) {
    case "pocetna":
      include "./pages/user/pocetna.php";
      break;
    case "cart":
      include "./pages/user/cart.php";
      break;
    case "checkout":
      include "./pages/user/checkout.php";
      break;
    case "login":
      include "./pages/user/login.php";
      break;
    case "poruke":
      include "./pages/user/poruke.php";
      break;
    case "product":
      include "./pages/user/product.php";
      break;
    case "register":
      include "./pages/user/register.php";
      break;
    case "store":
      include "./pages/user/store.php";
      break;
    case "verifikacija":
      include "./pages/user/verifikacija.php";
      break;
    case "ankete":
      include "./pages/user/ankete.php";
      break;

      /* Za admin korisnike */
    case "adminpanel":
      include "./pages/admin/adminpanel.php";
      break;
    case "anketeAdmin":
      include "./pages/admin/anketeAdmin.php";
      break;
    case "dodajAdmina":
      include "./pages/admin/dodajAdmina.php";
      break;
    case "dodajAnketu":
      include "./pages/admin/dodajAnketu.php";
      break;
    case "dodavanjeProizvoda":
      include "./pages/admin/dodavanjeProizvoda.php";
      break;
    case "izmenaKorisnika":
      include "./pages/admin/izmenaKorisnika.php";
      break;
    case "izmenaProizvoda":
      include "./pages/admin/izmenaProizvoda.php";
      break;
    case "odgovorNaPoruku":
      include "./pages/admin/odgovorNaPoruku.php";
      break;
    case "porukeKorisnika":
      include "./pages/admin/porukeKorisnika.php";
      break;
    case "pregledAnkete":
      include "./pages/admin/pregledAnkete.php";
      break;
    case "prikazKorisnika":
      include "./pages/admin/prikazKorisnika.php";
      break;
    case "proizvodi":
      include "./pages/admin/proizvodi.php";
      break;
    default:
      include "./pages/user/pocetna.php";
  }
} else {
  include "./pages/user/pocetna.php";
}

include "pages/footer.php";
