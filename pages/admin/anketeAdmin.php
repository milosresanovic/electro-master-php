<div class="section">
    <div class="container">
        <h1>Sve ankete na sajtu</h1><br>
        <a href="index.php?page=dodajAnketu" class="btn btn-info">Dodaj novu anketu</a><br><br>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Br.</th>
                    <th scope="col">Naslov ankete</th>
                    <th scope="col">Datum</th>
                    <th scope="col">Objavio</th>
                    <th scope="col">Status ankete</th>
                    <th scope="col">Pregled</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $upit = $konekcija->prepare("SELECT a.anketa_id, a.naziv, a.datum, a.status_ankete, k.korisnik_id, k.korisnicko_ime
                FROM ankete as a JOIN korisnici as k ON a.admin_id = k.korisnik_id");
                $upit->execute();
                $rezultat = $upit->fetchAll();
                $i = 0;
                foreach ($rezultat as $r) :
                    $i++;
                ?>
                    <tr>
                        <th scope="row"><?php echo $i ?></th>
                        <td><?php echo $r->naziv ?></td>
                        <td><?php echo $r->datum ?></td>
                        <td><?php echo $r->korisnicko_ime ?></td>
                        <td><?php if ($r->status_ankete == 0) echo "<p style='color:#ff471a;'>Neaktivna</p>";
                            else echo "<p style='color:green;'>Aktivna</p>"; ?></td>
                        <td><input type="button" class="pregledAnkete" onclick="pregledAnkete(<?php echo $r->anketa_id ?>)" name="dugmePregledAnkete" value="Pregled"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>