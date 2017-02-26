<?php
#Liefert das Formular zur Suche nach einem Vertrag als String.
function formSearchContracts () 
{$retval = "<p> <h2> Suche &uuml;ber alle Vertr&auml;ge</h2></p>";
 $retval = $retval . "<form action=\"index.php\" method=\"get\" >";
 $retval = $retval . "<p>Name:";
 $retval = $retval . "<input type=\"text\" name=\"show\" />";
 $retval = $retval . "</p>";
 $retval = $retval . "<p><input type=\"submit\" value=\"absenden\" />";
 $retval = $retval . "</p> </form>";
 return $retval;
    
}

#Liefert das Formular zum aendern eines Vertrages als String
#Wobei die bisherigen Werte in die Felder gefuellt werden.
function formEditContract($contract){
    #Erst mal die Werte holen...
    $contractsDir= "/data/" . $contract;
    $CDir = getcwd() . $contractsDir;
    $sql = 'select * from contracts where name like "' . $contract .'"';
    $answer = readFromDB($sql);
    $name = $answer[0][0];
    $number = $answer[0][1];
    $start = $answer[0][2];
    $ende = $answer[0][3];
    $frist = $answer[0][4];
    $fee = $answer[0][5];
    
    $retval = "<p> <h2>Neuer Vertrag</h2></p>";
    $retval = $retval . "<form action=\"edit.php\" method=\"post\" >";
    $retval = $retval . "<p>Name des Vertrages:";
    $retval = $retval . "<input type=\"text\" name=\"name\" value=$name />";
    $retval = $retval . "<p>Nummer des Vertrages:";
    $retval = $retval . "<input type=\"text\" name=\"number\" value=$number/>";
    $retval = $retval . "<p>Anfangsdatum des Vertrages (YYYY.MM.DD):";
    $retval = $retval . "<input type=\"text\" name=\"start\" value=$start/>";
    $retval = $retval . "<p>Enddatum des Vertrages (YYYY.MM.DD):";
    $retval = $retval . "<input type=\"text\" name=\"end\" value=$ende/>";
    $retval = $retval . "<p>K&uuml;ndigungsfrist des Vertrages in Tagen:";
    $retval = $retval . "<input type=\"text\" name=\"frist\" value=$frist/>";
    $retval = $retval . "<p>Monatliche Kosten des Vertrages:";
    $retval = $retval . "<input type=\"text\" name=\"monthly\" value=$fee/>";
    $retval = $retval . "<p><input type=\"submit\" value=\"ändern\" />";
    $retval = $retval . "<p><input type=\"reset\" value=\"abbrechen\" />";
    $retval = $retval . "</p> </form>";
    
    //TODO//
#Jetzt noch die Dateien...bin mir nicht sicher, ob ich die noch brauche.
    $media = getMedia($contract, $CDir);
    foreach ($media as $file) {
       
        $retval = $retval . '<a href=".' . $contractsDir. "/" . $file . '">' . $file . '</a><br>';        
    }
    $retval = $retval . '<form action="upload.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="CName" value="' . $contract . '"><br>
            <input type="file" name="datei"><br>
            <input type="submit" value="Datei Hochladen">
        </form>';
    
    return $retval; 
}
#Liefert das Formular zum eintragen eines neuen Vertrages als String
function formNewContract() 
{
    $retval = "<p> <h2>Neuer Vertrag</h2></p>";
    $retval = $retval . "<form action=\"input.php\" method=\"post\" >";
    $retval = $retval . "<p>Name des Vertrages:";
    $retval = $retval . "<input type=\"text\" name=\"name\" />";
    $retval = $retval . "<p>Nummer des Vertrages:";
    $retval = $retval . "<input type=\"text\" name=\"number\" />";
    $retval = $retval . "<p>Anfangsdatum des Vertrages (YYYY.MM.DD):";
    $retval = $retval . "<input type=\"text\" name=\"start\" />";
    $retval = $retval . "<p>Enddatum des Vertrages (YYYY.MM.DD):";
    $retval = $retval . "<input type=\"text\" name=\"end\" />";
    $retval = $retval . "<p>K&uuml;ndigungsfrist des Vertrages in Tagen:";
    $retval = $retval . "<input type=\"text\" name=\"frist\" />";
    $retval = $retval . "<p>Monatliche Kosten des Vertrages:";
    $retval = $retval . "<input type=\"text\" name=\"monthly\" />";
    #$retval = $retval . "<input type=\"file\" name=\"datei\"><br>";
    $retval = $retval . "<p><input type=\"submit\" value=\"absenden\" />";
    $retval = $retval . "</p> </form>";
    return $retval;
}

#Liefert eine Datenbankverbindung auf die Vertragsdatenbank
function getDBCon ()
{
    #Festlegen des Datenbankzuganges
    $host = "localhost";
    $username = "username";
    $password = "password";
    $database = "contracts";
    #Aufbauen der Verbindung
    $con = mysql_connect($host, $username, $password);
    if (!$con)
        {
        die('Could not connect: ' . mysql_error());
        }
        #Auswaehlen der Datenbank
        mysql_select_db($database, $con);
    return $con;
}

//fuehrt ein select auf der DB aus und liefert 
//das Ergebnis als Array
function readFromDB ($sqlStmt)  {
    $con = getDBCon(); 
    $result = mysql_query($sqlStmt);
    $i = 0;
    $array[0] = "";
    while($row = mysql_fetch_array($result))
    {
        $array[$i] = $row;  
        $i = $i + 1;
    }
    mysql_close($con);
    #print_r($array);    
    return $array;
    
    
}

//liefert alle Vertraege als Array
function getContracts (){    
    $sql = 'select * from contracts';
    $answer = readFromDB($sql);
    #print "answer is: ";
    #print_r($answer);
    $retval = $answer;
    return $retval;
}

//Bereitet einen Vertrag zur Ansicht auf.
//Erwartet eine Vertrags-ID
function showContract($exid){   
    $contractsDir= "/data/" . $exid;
    $CDir = getcwd() . $contractsDir;
    $sql = 'select * from contracts where name like "' . $exid .'"';
    $answer = readFromDB($sql);
    $retval = makeTable($answer, "contracts", "edit");
   # $media = getMedia($exid, $CDir);
   # foreach ($media as $file) {
       
   #     $retval = $retval . '<a href=".' . $contractsDir. "/" . $file . '">' . $file . '</a><br>';        
   # }
    $retval = $retval . '<form action="upload.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="CName" value="' . $exid . '"><br>
            <input type="file" name="datei"><br>
            <input type="submit" value="Datei Hochladen">
        </form>';
    #print_r($media);
    
    return $retval;
    
}

#function getMedia($cname, $CDir){
    #print "getMedia called...<br>";
#    $retval = array();
    #print $contractsDir . $cname;
    #print $CDir;
#    if ($handle = opendir($CDir)) {
#    while (false !== ($file = readdir($handle))) {
#        if ($file != "." && $file != "..") {
#            $retval[] = $file;
#        }
#    }
#    closedir($handle);
#}
   # $retval = $MList;
#    return $retval;
#}

#function writeICS ($CName, $file)
#{
    
#}

//fuehrt ein update auf der DB aus
//Erwartet den vollständig vorbereiteten SQL-String
function updateDB ($sqlStmt)   
{
   $con = getDBCon();
    if (!mysql_query($sqlStmt,$con))
  {
  die('Error: ' . mysql_error());
  }
mysql_close($con); 
}
//fuehrt ein insert auf der DB aus
//Erwartet den vollständig vorbereiteten SQL-String
function insertToDB ($sqlStmt)   
{
    $con = getDBCon();
    if (!mysql_query($sqlStmt,$con))
  {
  die('Error: ' . mysql_error());
  }
mysql_close($con);
}

//erzeugt aus einem Array eine Tabelle
//ruft dazu getRow auf jeder Zeile auf
//und unterscheidet nach Typ der Tabelle, wobei es hier nur Vertraege gibt
function makeTable ($array,$type, $link) {   
   
    if ($type == "contracts")
    {
        
        $retval = '<table border="1">'; 
        $retval = $retval . "<tr><th>Name</th><th>Vertragsnummer</th><th>Beginn</th><th>Ende</th><th>K&uuml;ndigungsfrist</th><th>Monatliche Kosten</th>></tr>";         //Hier kommt der tableheader
    }
    
    $anzahl = count($array);
    for ($x = 0; $x < $anzahl; $x++){
        $retval = $retval . getRow($array[$x],"td", $link)."</tr>";
    }
    $retval = $retval . "</table>";
    return $retval;
}
//erzeugt aus einer DBZeile eine Tabellenzeile des gewuenschten Typs (td oder th)
function getRow ($DBRow,$typ, $link) {     
    $retval ="<tr>";                    
    $count = count($DBRow);
    for ($i = 0; $i < $count/2 ; $i++){
        if($i == 0){$retval = $retval . "<" . $typ . "><a href=\"./index.php?$link=" . $DBRow[0] . "\">" . htmlentities($DBRow[$i]) . "</a></" . $typ . ">";}
        else{$retval = $retval . "<" . $typ . ">" . htmlentities($DBRow[$i]) . "</" . $typ . ">";}
    }
    $retval = $retval . "</tr>";
    return $retval;
}
//TODO//
//Hier werden die Eingaben geprueft, damit
//SQL-Injection verhindert werden kann
function evaluateForm(){         
    $kategorie = $_POST[kategorie];
    $material = $_POST[material];
    $schlagwort = $_POST[schlagwort];
    
    
    $retval ="";
    
    return $retval;
}
?>
