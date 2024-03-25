<?php
// Pobierz dane z formularza
$nazwa = $_POST['nazwa'];
$produkt = $_POST['produkt'];
$kwota = $_POST['kwota'];
$data = date("Y-m-d");

// Formatuj transakcję
$transakcja = "$nazwa, $produkt, $kwota, $data";

// Zapisz transakcję do pliku
file_put_contents("transakcje.txt", $transakcja . PHP_EOL, FILE_APPEND);

// Przekieruj użytkownika z powrotem do strony głównej
header("Location: index.php");
exit();
?>
