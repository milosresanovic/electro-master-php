<div class="section">
    <div class="container">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Br.</th>
                    <th scope="col">Ime</th>
                    <th scope="col">Korisničko ime</th>
                    <th scope="col">Email</th>
                    <th scope="col">Uloga</th>
                    <th scope="col">Datum reg.</th>
                    <th scope="col">Izmena</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $upit = $konekcija->prepare("SELECT korisnik_id AS id,  CONCAT(k.ime, ' ', k.prezime) AS ime, k.email, k.sifra, k.datum_registracije, k.korisnicko_ime AS korisnicko, k.status , u.naziv as naziv FROM korisnici k JOIN uloga_korisnika u ON k.uloga_id = u.uloga_id");
                $upit->execute();
                $rezultat = $upit->fetchAll();
                $i = 0;
                foreach ($rezultat as $r) :
                    $i++;
                ?>
                    <tr>
                        <th scope="row"><?php echo $i ?></th>
                        <td><?php echo $r->ime ?></td>
                        <td><?php echo $r->korisnicko ?></td>
                        <td><?php echo $r->email ?></td> 
                        <td><?php echo $r->naziv ?></td>
                        <td><?php echo $r->datum_registracije ?></td>
                        <td><button name="dugmeIzmena" value="<?php echo $r -> id  ?>" onclick="izmenaKorisnikaSaId(<?php echo $r -> id ?>)">Izmeni</button></td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>
</div>