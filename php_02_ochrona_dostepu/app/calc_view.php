
<?php
//Tu już nie ładujemy konfiguracji - sam widok nie będzie już punktem wejścia do aplikacji.
//Wszystkie żądania idą do kontrolera, a kontroler wywołuje skrypt widoku.
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Kalkulator rat kredytu</title>
	<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
</head>
<body>

<div style="width:90%; margin: 2em auto;">
	<a href="<?php print(_APP_ROOT); ?>/app/inna_chroniona.php" class="pure-button">kolejna chroniona strona</a>
	<a href="<?php print(_APP_ROOT); ?>/app/security/logout.php" class="pure-button pure-button-active">Wyloguj</a>
</div>

<div style="width:90%; margin: 2em auto;">

<form action="<?php print(_APP_ROOT); ?>/app/calc.php" method="post" class="pure-form pure-form-stacked">
	<legend>Kalkulator rat kredytu</legend>
	<fieldset>
		<label for="id_kwota">Kwota kredytu (zł): </label>
		<input id="id_kwota" type="number" step="0.01" name="kwota" value="<?php out($kwota) ?>" />

		<label for="id_oprocentowanie">Oprocentowanie roczne (%): </label>
		<input id="id_oprocentowanie" type="number" step="0.01" name="oprocentowanie" value="<?php out($oprocentowanie) ?>" />

		<label for="id_miesiace">Okres spłaty (miesiące): </label>
		<input id="id_miesiace" type="number" step="1" name="miesiace" value="<?php out($miesiace) ?>" />
	</fieldset>
	<input type="submit" value="Oblicz ratę" class="pure-button pure-button-primary" />
</form>

<?php
//wyświetlenie listy błędów, jeśli istnieją
if (isset($messages)) {
	if (count ( $messages ) > 0) {
		echo '<ol style="margin-top: 1em; padding: 1em 1em 1em 2em; border-radius: 0.5em; background-color: #f88; width:25em;">';
		foreach ( $messages as $key => $msg ) {
			echo '<li>'.htmlspecialchars($msg, ENT_QUOTES, 'UTF-8').'</li>';
		}
		echo '</ol>';
	}
}
?>

<?php if (isset($result)){ ?>
<div style="margin-top: 1em; padding: 1em; border-radius: 0.5em; background-color: #ff0; width:25em;">
	<?php echo 'Miesięczna rata: '.number_format($result['rata'], 2, ',', ' ').' zł'; ?>

	<?php if (isset($result['suma'])){ ?>
		<p><?php echo 'Całkowita kwota do spłaty: '.number_format($result['suma'], 2, ',', ' ').' zł'; ?></p>
		<p><?php echo 'Suma odsetek: '.number_format($result['odsetki'], 2, ',', ' ').' zł'; ?></p>
	<?php } else { ?>
		<p>Szczegóły kosztów kredytu są dostępne tylko dla administratora.</p>
	<?php } ?>
</div>
<?php } ?>

</div>

</body>
</html>
