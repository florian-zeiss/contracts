<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Vertragsverwaltung</title>
    </head>
    <body>
<?php
require_once 'functions_vertraege.php';
//

if(isset($_GET["show"])){
    if($_GET["show"] == "all"){
        $contracts = getContracts();
        echo makeTable($contracts, "contracts", "show");
    }
    else{
        echo showContract($_GET["show"]);
    }
}
elseif (isset($_GET["edit"])){
    echo formEditContract($_GET["edit"]);
}

      else echo formSearchContracts();
       
        ?>
        <a href="./index.php?show=all">Alle Vertr&auml;ge anzeigen</a>
        <a href="./input.php?type=contract">Neuer Vertrag</a><br>
    </body>
</html>