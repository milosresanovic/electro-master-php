<?php


$upitPrikazAnketa = $konekcija->prepare("SELECT * FROM ankete WHERE anketa_id NOT IN (SELECT anketa_id FROM odgovori_ankete WHERE korisnik_id = :korisnik_id) AND status_ankete = 1");
$korisnik_id = $_SESSION['user']->korisnik_id;
$upitPrikazAnketa->bindParam(":korisnik_id", $korisnik_id);
$upitPrikazAnketa->execute();
$ankete = $upitPrikazAnketa->fetchAll();
//print_r($ankete);
?>

<div class="section">
    <div class="container">
        <?php if (!$ankete) : ?>
            <h3>Odgovorili ste na sve ankete. Hvala!</h3>
        <?php else : ?>
            <h3>Na sledeće ankete niste odogovrili</h3>
            <div class="w-100">
                <?php foreach($ankete as $a): ?>
                <div id="veliki-div-<?php echo $a->anketa_id?>" class="div-anketa ">
                    <div class=""><?php echo $a -> naziv ?></div>
                    <div class=""><?php echo $a -> pitanje ?></div>
                    <div class="">
                        <select id="odgovor<?php echo $a->anketa_id ?>" class="select-anketa-odgovori sirina-100 btn btn-primary" name="" id="">
                            <option value="0">Da</option>
                            <option value="1">Ne</option>
                            <option value="2">Mozda</option>
                        </select>
                    </div>
                    <div class=""><input onclick="odgovoriNaAnketu(<?php echo $a->anketa_id ?>)" class="sirina-100 btn btn-success" type="button" value="Potvrdi"></div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

