
<?php
require_once dirname(__FILE__).'/../config.php';

// KONTROLER strony kalkulatora rat

// W kontrolerze niczego nie wysyła się do klienta.
// Wysłaniem odpowiedzi zajmie się odpowiedni widok.
// Parametry do widoku przekazujemy przez zmienne.

//ochrona kontrolera - poniższy skrypt przerwie przetwarzanie w tym punkcie gdy użytkownik jest niezalogowany
include _ROOT_PATH.'/app/security/check.php';

//pobranie parametrów
function getParams(&$kwota,&$oprocentowanie,&$miesiace){
	$kwota = isset($_REQUEST['kwota']) ? $_REQUEST['kwota'] : null;
	$oprocentowanie = isset($_REQUEST['oprocentowanie']) ? $_REQUEST['oprocentowanie'] : null;
	$miesiace = isset($_REQUEST['miesiace']) ? $_REQUEST['miesiace'] : null;
}

//walidacja parametrów z przygotowaniem zmiennych dla widoku
function validate(&$kwota,&$oprocentowanie,&$miesiace,&$messages){
	// sprawdzenie, czy parametry zostały przekazane
	if ( ! (isset($kwota) && isset($oprocentowanie) && isset($miesiace))) {
		// nie wykonujemy obliczeń, gdy kontroler otwarto bez formularza
		return false;
	}

	// sprawdzenie, czy potrzebne wartości zostały przekazane
	if ( $kwota == "") {
		$messages [] = 'Nie podano kwoty kredytu';
	}
	if ( $oprocentowanie == "") {
		$messages [] = 'Nie podano oprocentowania';
	}
	if ( $miesiace == "") {
		$messages [] = 'Nie podano okresu spłaty';
	}

	//nie ma sensu walidować dalej gdy brak parametrów
	if (count ( $messages ) != 0) return false;

	// sprawdzenie poprawności parametrów kredytu
	if (!is_numeric($kwota) || !is_finite((float)$kwota) || $kwota <= 0) {
		$messages [] = 'Kwota kredytu musi być liczbą większą od zera';
	}
	if (!is_numeric($oprocentowanie) || !is_finite((float)$oprocentowanie) || $oprocentowanie < 0) {
		$messages [] = 'Oprocentowanie musi być liczbą nieujemną';
	}
	if (filter_var($miesiace, FILTER_VALIDATE_INT) === false || $miesiace <= 0) {
		$messages [] = 'Okres spłaty musi być dodatnią liczbą całkowitą';
	}

	if (count ( $messages ) != 0) return false;
	else return true;
}

function process(&$kwota,&$oprocentowanie,&$miesiace,&$messages,&$result){
	global $role;

	//konwersja parametrów na liczby
	$kwota = floatval($kwota);
	$oprocentowanie = floatval($oprocentowanie);
	$miesiace = intval($miesiace);

	//obliczenie miesięcznej stopy procentowej
	$r = $oprocentowanie / 100 / 12;

	//wykonanie obliczeń raty równej
	if ($r == 0){
		$rata = $kwota / $miesiace;
	} else {
		$rata = $kwota * $r / (1 - pow(1 + $r, -$miesiace));
	}

	if (!is_finite($rata) || $rata <= 0) {
		$messages [] = 'Nie udało się obliczyć raty';
		return;
	}

	$result = array();
	$result['rata'] = $rata;

	//szczegóły kosztów kredytu dostępne tylko administratorowi
	if ($role == 'admin'){
		$result['suma'] = $rata * $miesiace;
		$result['odsetki'] = $result['suma'] - $kwota;
	}
}

//definicja zmiennych kontrolera
$kwota = null;
$oprocentowanie = null;
$miesiace = null;
$result = null;
$messages = array();

//pobierz parametry i wykonaj zadanie jeśli wszystko w porządku
getParams($kwota,$oprocentowanie,$miesiace);
if ( validate($kwota,$oprocentowanie,$miesiace,$messages) ) { // gdy brak błędów
	process($kwota,$oprocentowanie,$miesiace,$messages,$result);
}

// Wywołanie widoku z przekazaniem zmiennych
include 'calc_view.php';
