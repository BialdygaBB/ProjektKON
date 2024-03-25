<!DOCTYPE html>
<html>
<head>
    <title>Transakcje</title>
</head>
<body>

<h2>Dodaj nową transakcję</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    Nazwa: <input type="text" name="nazwa"><br>
    Produkt: <input type="text" name="produkt"><br>
    Kwota: <input type="text" name="kwota"><br>
    <input type="submit" value="Dodaj transakcję">
</form>

<h2>Odczytaj transakcje</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <input type="hidden" name="action" value="odczytaj">
    <input type="submit" value="Odczytaj transakcje">
</form>

<h2>Usuń transakcję</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    Numer transakcji do usunięcia: <input type="text" name="id_usun"><br>
    <input type="submit" value="Usuń transakcję">
</form>

<h2>Dodatkowe operacje</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <select name="operacja">
        <option value="najwyzsze_przychody">Najwyższe przychody</option>
        <option value="najwyzsze_przychody_produktu">Najwyższe przychody z jednego produktu</option>
        <option value="policz_transakcje">Policz transakcje</option>
        <option value="policz_kwote">Policz kwotę</option>
    </select>
    <input type="submit" value="Wykonaj">
</form>

<h2>Transakcje</h2>
<ul>
<?php
// Funkcja do dodawania transakcji wraz z ID i datą
function dodajTransakcje($nazwa, $produkt, $kwota) {
    $id = pobierzKolejneId(); // Pobierz kolejne ID
    $data = date('Y-m-d H:i:s');
    $transakcja = "$id, $nazwa, $produkt, $kwota, $data";
    zapiszTransakcje($transakcja);
}

// Funkcja do zapisywania transakcji do pliku
function zapiszTransakcje($transakcja) {
    file_put_contents("transakcje.txt", $transakcja . PHP_EOL, FILE_APPEND);
}

// Funkcja do odczytywania transakcji z pliku i wyświetlania ich
function wyswietlTransakcje() {
    $transakcje = file("transakcje.txt", FILE_IGNORE_NEW_LINES);
    foreach ($transakcje as $transakcja) {
        echo "<li>$transakcja</li>";
    }
}

// Funkcja do usuwania transakcji z pliku na podstawie numeru transakcji
function usunTransakcje($numer) {
    $transakcje = file("transakcje.txt", FILE_IGNORE_NEW_LINES);
    $noweTransakcje = array();
    foreach ($transakcje as $transakcja) {
        $id = explode(",", $transakcja)[0]; // Pobierz numer transakcji
        if ($id != $numer) {
            $noweTransakcje[] = $transakcja;
        }
    }
    file_put_contents("transakcje.txt", implode(PHP_EOL, $noweTransakcje));
}

// Funkcja do pobierania kolejnego ID
function pobierzKolejneId() {
    if (!file_exists("id.txt")) {
        file_put_contents("id.txt", "0");
    }
    $id = file_get_contents("id.txt");
    file_put_contents("id.txt", ++$id);
    return $id;
}

// Obsługa dodawania, odczytywania, usuwania i dodatkowych operacji
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nazwa']) && isset($_POST['produkt']) && isset($_POST['kwota'])) {
        $nazwa = $_POST['nazwa'];
        $produkt = $_POST['produkt'];
        $kwota = $_POST['kwota'];
        dodajTransakcje($nazwa, $produkt, $kwota);
    }
    if (isset($_POST['action']) && $_POST['action'] == 'odczytaj') {
        wyswietlTransakcje();
    }
    if (isset($_POST['id_usun'])) {
        $id_usun = $_POST['id_usun'];
        usunTransakcje($id_usun);
        echo "<p>Transakcja o numerze '$id_usun' została usunięta.</p>";
    }
}

?>
</ul>

</body>
</html>
