<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Test</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Psie gówno</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
            <a class="nav-link" href="crm.php">CRM</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="sprzedaz.php">Sprzedaż</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="hr.php">HR</a>
            </li>
        </ul>
      </div>
    </nav>

    <h1>Moduł HR</h1>
    <form action="hr.php" method="post">
      <label for="imie"
        >Imię pracownika:
        <input type="text" id="imie" name="imie" required /> </label
      ><br /><br />

      <label for="nazwisko"
        >Nazwisko pracownika:
        <input type="text" id="nazwisko" name="nazwisko" required /> </label
      ><br /><br />

      <label for="data-urodz"
        >Data urodzenia pracownika:
        <input type="date" id="data-urodz" name="data-urodz" required /> </label
      ><br /><br />

      <label for="uprawnienia"
        >Poziom uprawnień:
        <input
          type="number"
          id="uprawnienia"
          name="uprawnienia"
          required
        /> </label
      ><br /><br />

      <label for="departament"
        >Departament:
        <input type="text" id="departament" name="departament" required /> </label
      ><br /><br />

      <button type="submit" name="addEmployee">Dodaj pracownika</button>
      <button type="submit" name="getStats">Pobierz statystyki</button>
    </form>

    <?php

    // Funkcja do odczytu danych pracowników z pliku
    function readEmployees($filename) {
        $employees = [];
        if (file_exists($filename)) {
            $file = fopen($filename, "r");
            while (($data = fgetcsv($file)) !== FALSE) {
                $employees[] = $data;
            }
            fclose($file);
        }
        return $employees;
    }

    // Funkcja do zapisu danych pracowników do pliku
    function writeEmployees($filename, $employees) {
        $file = fopen($filename, "w");
        foreach ($employees as $employee) {
            fputcsv($file, $employee);
        }
        fclose($file);
    }

    $employeesFile = "employees.txt";

    if (isset($_POST['addEmployee'])) {
        $newEmployee = [$_POST['imie'], $_POST['nazwisko'],$_POST['data-urodz'], $_POST['uprawnienia'], $_POST['departament']];
        $employees = readEmployees($employeesFile);
        $employees[] = $newEmployee;
        writeEmployees($employeesFile, $employees);
        echo "<p>Nowy pracownik został dodany.</p>";
    }

    $employees = reademployees($employeesFile);
    if (!empty($employees)) {
        echo "<h2>Pracownicy:</h2>";
        echo "<ul>";
        foreach ($employees as $employee) {
            echo "<li>Imie i nazwisko: $employee[0] $employee[1], data urodzenia: $employee[2] uprawnienia: $employee[3], departament: $employee[4]</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Brak Pracowników.</p>";
    }

    function oldestAndYoungest($employees) {
      $ages = [];
      foreach ($employees as $employee) {
          $ages[] = strtotime($employee[2]); // Konwersja daty urodzenia na timestamp
      }
      $oldestTimestamp = min($ages);
      $youngestTimestamp = max($ages);
      $oldestEmployee = '';
      $youngestEmployee = '';
      foreach ($employees as $employee) {
          if (strtotime($employee[2]) === $oldestTimestamp) {
              $oldestEmployee = $employee[0] . ' ' . $employee[1];
          }
          if (strtotime($employee[2]) === $youngestTimestamp) {
              $youngestEmployee = $employee[0] . ' ' . $employee[1];
          }
      }
      return [$oldestEmployee, $youngestEmployee];
    }

    function averageAge($employees) {
      $totalAge = 0;
      $numEmployees = count($employees);
      foreach ($employees as $employee) {
          $totalAge += strtotime('now') - strtotime($employee[2]);
      }
      return round($totalAge / ($numEmployees * 365 * 24 * 60 * 60)); // Średni wiek w latach
    }

    function upcomingBirthdays($employees, $inputDate) {
      $upcomingBirthdays = [];
      $inputTimestamp = strtotime($inputDate);
      foreach ($employees as $employee) {
          $employeeBirthday = strtotime($employee[2]);
          $diff = $employeeBirthday - $inputTimestamp;
          if ($diff >= 0 && $diff <= (14 * 24 * 60 * 60)) { // 14 dni w sekundach
              $upcomingBirthdays[] = $employee[0] . ' ' . $employee[1];
          }
      }
      return $upcomingBirthdays;
    }

    // Funkcja do zwracania liczby pracowników, którzy mają co najmniej określony poziom uprawnień
    function countEmployeesWithMinimumPermissions($employees, $minimumPermissions) {
      $count = 0;
      foreach ($employees as $employee) {
          if ($employee[3] >= $minimumPermissions) {
              $count++;
          }
      }
      return $count;
    }


    function employeesPerDepartment($employees) {
      $departments = [];
      foreach ($employees as $employee) {
          $department = $employee[4];
          if (!isset($departments[$department])) {
              $departments[$department] = 1;
          } else {
              $departments[$department]++;
          }
      }
      return $departments;
    }

    list($oldest, $youngest) = oldestAndYoungest($employees);
      echo "<p>Najstarszy pracownik: $oldest</p>";
      echo "<p>Najmłodszy pracownik: $youngest</p>";
      echo "<p>Średni wiek pracowników to: " . averageAge($employees) . " lat</p>";
      echo "<p>Pracownicy mający urodziny w ciągu dwóch tygodni od daty wejściowej: " . implode(', ', upcomingBirthdays($employees, date('Y-m-d'))) . "</p>";
      echo "<p>Liczba pracowników, którzy mają co najmniej określony poziom uprawnień: " . countEmployeesWithMinimumPermissions($employees, 3) . "</p>";
      echo "<p>Liczba pracowników na oddział:</p>";
      echo "<ul>";
      foreach (employeesPerDepartment($employees) as $department => $count) {
          echo "<li>$department: $count</li>";
      }
      echo "</ul>";

    ?>
  </body>
</html>
