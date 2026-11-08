
<?php
// KONTROLER kalkulatora rat
require_once dirname(__FILE__).'/../config.php';

// 1. Pobranie parametrów
$kwota = $_POST['kwota'] ?? '';
$oprocentowanie = $_POST['oprocentowanie'] ?? '';
$miesiace = $_POST['miesiace'] ?? '';

$messages = [];

// 2. Walidacja danych
if ($kwota === '' || $oprocentowanie === '' || $miesiace === '') {
    $messages[] = 'Wypełnij wszystkie pola.';
} else {
    if (!is_numeric($kwota) || $kwota <= 0) {
        $messages[] = 'Kwota kredytu musi być większa od 0.';
    }

    if (!is_numeric($oprocentowanie) || $oprocentowanie < 0) {
        $messages[] = 'Oprocentowanie nie może być ujemne.';
    }

    if (!filter_var($miesiace, FILTER_VALIDATE_INT)
        || $miesiace <= 0) {
        $messages[] = 'Podaj poprawną liczbę miesięcy.';
    }
}

// 3. Obliczenie raty
if (empty($messages)) {

    $kwota = floatval($kwota);
    $oprocentowanie = floatval($oprocentowanie);
    $miesiace = intval($miesiace);

    // Miesięczna stopa procentowa
    $r = $oprocentowanie / 100 / 12;

    // Obliczanie raty równej
    if ($r == 0) {
        $rata = $kwota / $miesiace;
    } else {
        $rata = $kwota * $r / (1 - pow(1 + $r, -$miesiace));
    }

    // Całkowita kwota do spłaty
    $suma = $rata * $miesiace;

    // Łączne odsetki
    $odsetki = $suma - $kwota;
}

// 4. Wywołanie widoku
include 'calc_view.php';
