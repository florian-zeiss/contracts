<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Vertragsverwaltung</title>
    </head>
    <body>
<?php
require_once 'functions_vertraege.php';
if (isset($_GET["contract"])) {
    #Einen Vertrag editieren...
    echo formEditContract($_GET["contract"]);
    exit();
    
}
if (isset($_POST["name"])){
    //Ein Vertrag soll geaendert werden...
    $sql = 'update contracts set name="';
    $sql = $sql . $_POST["name"] . '",number="';
    $sql = $sql . $_POST["number"] . '",start="';
    $sql = $sql . $_POST["start"] . '",end="';
    $sql = $sql . $_POST["end"] . '",frist="';
    $sql = $sql . $_POST["frist"] . '",fee="';
    $sql = $sql . $_POST["monthly"] . '"';
    $sql = $sql . ' where name="' . $_POST["name"] . '"';
   
    print $sql;
    updateDB($sql);
    echo "<p>";
 echo '<a href="./index.php?show=' . $_POST["name"] . '">Vertrag ' . $_POST["name"] . 'anzeigen</a>';  
}
?>
<a href="./index.php?show=all">Alle Vertr&auml;ge anzeigen</a>
<a href="./input.php?type=contract">Neuer Vertrag</a><br>
