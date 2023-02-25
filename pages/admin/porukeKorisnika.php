<div class="section">
    <div class="container">
        <h1>Poruke na koje nije odgovoreno</h1><br/><br/>
        <table id="prikazPoruka" class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Br.</th>
                    <th scope="col">Korisničko ime</th>
                    <th scope="col">Email</th>
                    <th scope="col">Naslov</th>
                    <th scope="col">Vreme</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    global $konekcija;
                    $upit = $konekcija -> prepare("SELECT p.poruka_id, p.naslov, p.sadrzaj, p.datum, k.korisnicko_ime, k.email  
                    FROM poruke_za_admina p JOIN korisnici k ON p.korisnik_id = k.korisnik_id 
                    WHERE p.status_poruke = 0");
                    $upit -> execute();
                    $poruke = $upit -> fetchAll();
                ?>
                <?php $i=1; foreach($poruke as $p): ?>
                <tr>
                    <td scope="row td-center"><?php echo $i.'.' ?></td>
                    <td><?php echo $p->korisnicko_ime?></td>
                    <td><?php echo $p->email?></td>
                    <td><?php echo $p->naslov?></td>
                    <td><?php echo $p->datum?></td>
                    <td><button class="btn btn-info" onclick="otvoriPoruku(<?php echo $p->poruka_id ?>)">Odgovori</button></td>
                </tr>
                <?php $i++; endforeach; ?>
            </tbody>
        </table>
    </div>
</div>