
<?php
require_once dirname(__FILE__).'/../config.php';

$kwota = $kwota ?? '';
$oprocentowanie = $oprocentowanie ?? '';
$miesiace = $miesiace ?? '';
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8" />
    <title>Kalkulator rat kredytu</title>
</head>
<body>


<form action="<?php print(_APP_URL); ?>/app/calc.php" method="post">

    <label for="id_kwota">Kwota kredytu (zł): </label>
    <input id="id_kwota" type="number" step="0.01"
           name="kwota"
           value="<?php echo htmlspecialchars((string)$kwota); ?>" />
    <br /><br />

    <label for="id_oprocentowanie">Oprocentowanie roczne (%): </label>
    <input id="id_oprocentowanie" type="number" step="0.01"
           name="oprocentowanie"
           value="<?php echo htmlspecialchars((string)$oprocentowanie); ?>" />
    <br /><br />

    <label for="id_miesiace">Okres spłaty (miesiące): </label>
    <input id="id_miesiace" type="number" step="1"
           name="miesiace"
           value="<?php echo htmlspecialchars((string)$miesiace); ?>" />
    <br /><br />

    <input type="submit" value="Oblicz ratę" />

</form>

<?php
// Wyświetlanie błędów
if (!empty($messages)) {
    echo '<div style="color: red;">';
    foreach ($messages as $msg) {
        echo '<p>'.htmlspecialchars($msg).'</p>';
    }
    echo '</div>';
}
?>

<?php if (isset($rata)) { ?>

<div style="margin: 20px; padding: 15px;
            background-color: #ddffdd; width: 350px;">

    <h3>Wynik obliczeń</h3>

    <p>Miesięczna rata:
    <b><?php echo number_format($rata, 2, ',', ' '); ?> zł</b></p>

    <p>Całkowita kwota do spłaty:
    <b><?php echo number_format($suma, 2, ',', ' '); ?> zł</b></p>

    <p>Łączne odsetki:
    <b><?php echo number_format($odsetki, 2, ',', ' '); ?> zł</b></p>

</div>

<?php } ?>

</body>
</html>
