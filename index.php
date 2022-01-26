<!DOCTYPE html>
<?php
$start = microtime(true);
require __DIR__ . '/vendor/autoload.php';

//Reading data from spreadsheet.

$client = new \Google_Client();

$client->setApplicationName('West CSHS');

$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);

$client->setAccessType('offline');

$client->setAuthConfig(__DIR__ . '/credentials.json');

$service = new Google_Service_Sheets($client);

$spreadsheetId = "1V3ibQx8ds5cmkLU5-MuzGLaIR0Z0XugMeMYZ4ckm2jE";

$get_range = "Form Responses 1!B2:H";

if ( !empty($_GET["query"]) ) {
    $response = $service->spreadsheets_values->get($spreadsheetId, $get_range);

    $values = $response->getValues();

    $goodQuery = false;
    $queryResp = [];

    foreach ($values as $row) {
        if ($row[0] == $_GET["query"]) {
          $queryResp[] = $row;
          $goodQuery = true;
        }
     }
}
?>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <title>West NHS Community Service</title>
  </head>
  <body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">West NHS</a>
        </div>
    </nav>

    <div class="container">
        
        <?php
            if ( empty($_GET["query"]) ) {
                include("dosearch.html");
            } else {
                if ($goodQuery) {
                    $totalHours = 0;
                    echo "<h3>All recorded hours</h3>";
                    echo "<div class=\"table-responsive\">";
                    echo "<table class=\"table table-striped\">";
                    echo "<thead><tr><td>Email</td><td>Last Name, First Name</td><td>Grade</td><td>Computer Science Related?</td><td>Activity</td><td>Activity Hours</td><td>Dates</td><td>Contact</td></tr></thead>";
                    foreach ($queryResp as $row) {
                       echo "<tr>";
                       $totalHours = $totalHours + $row[4];
                       foreach ($row as $column) {
                          echo "<td>$column</td>";
                       }
                       echo "</tr>";
                    }
                    echo "</table>";
                    echo "<p>" . $totalHours . " total hours recorded.</p>";
                    echo "<p>" . count($queryResp) . " records found.</p>";
                } else {
                    include("dosearch.html");
                    echo "<p>No records found, check your input.</p>";
                }
                echo "<br /><a href=\"/\">Reset</a>";
            }
        ?>

        <p class="text-muted">Page generated in <?php echo microtime(true) - $start; ?> seconds.</p>
        <p class="text-muted">Developed by <a href="https://kevsal.me">Kevin Salvatorelli</a> for <a href="https://www.chclc.org/west">Cherry Hill HS West</a>.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
  </body>
</html>
