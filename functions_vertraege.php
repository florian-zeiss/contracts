<?php

function formSearchContracts () #liefert das Formular als String
{$retval = "<p> <h2> Suche &uuml;ber alle Vertr&auml;ge</h2></p>";
 $retval = $retval . "<form action=\"index.php\" method=\"get\" >";
 $retval = $retval . "<p>Name:";
 $retval = $retval . "<input type=\"text\" name=\"show\" />";
 $retval = $retval . "</p>";
 $retval = $retval . "<p><input type=\"submit\" value=\"absenden\" />";
 $retval = $retval . "</p> </form>";
 return $retval;
    
}

function formNewUser() #liefert das Formular als String
{
    $retval = "<p> <h2>Neuer Benutzer</h2></p>";
    $retval = $retval . "<form action=\"input.php\" method=\"post\" >";
    $retval = $retval . "<p>Name des Benutzers:";
    $retval = $retval . "<input type=\"text\" name=\"name\" />";
    $retval = $retval . "<p>Passwort:";
    $retval = $retval . "<input type=\"text\" name=\"pass1\" />";
    $retval = $retval . "<p>Best&auml;tigung des Passworts:";
    $retval = $retval . "<input type=\"text\" name=\"pass2\" />";
}
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
    
#Jetzt noch die Dateien...
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
function formNewContract() #liefert das Formular als String
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

function readFromDB ($sqlStmt)  //fuehrt ein select auf der DB aus und liefert 
{                              //das Ergebnis als Array
    $con = mysql_connect("localhost","username","password");
    if (!$con)
        {
        die('Could not connect: ' . mysql_error());
        }

    mysql_select_db("contracts", $con);

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

function getUser ($userName){   //liefert die UserID zu einem Usernamen
    $sql = 'select id from users where name = "' . $userName . '"';
    $answer = readFromDB($sql);
    $retVal = $answer[0][0];
    
    return $retVal;
}

function getContracts (){    //liefert alle Vertraege als Array
    $sql = 'select * from contracts';
    $answer = readFromDB($sql);
    #print "answer is: ";
    #print_r($answer);
    $retval = $answer;
    return $retval;
}

function showContract($exid){   //Bereitet einen Vertrag zur Ansicht auf
    $contractsDir= "/data/" . $exid;
    $CDir = getcwd() . $contractsDir;
    $sql = 'select * from contracts where name like "' . $exid .'"';
    #print $sql . "<br>";
    $answer = readFromDB($sql);
    #print_r($answer);
    #print "making table for $exid<br>";
    $retval = makeTable($answer, "contracts", "edit");
    #$media = $answer[0]["media"];
    #print "trying to get media...<br>";
    $media = getMedia($exid, $CDir);
    foreach ($media as $file) {
       
        $retval = $retval . '<a href=".' . $contractsDir. "/" . $file . '">' . $file . '</a><br>';        
    }
    $retval = $retval . '<form action="upload.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="CName" value="' . $exid . '"><br>
            <input type="file" name="datei"><br>
            <input type="submit" value="Datei Hochladen">
        </form>';
    #print_r($media);
    
    return $retval;
    
}

function getMedia($cname, $CDir){
    #print "getMedia called...<br>";
    $retval = array();
    #print $contractsDir . $cname;
    #print $CDir;
    if ($handle = opendir($CDir)) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            $retval[] = $file;
        }
    }
    closedir($handle);
}
   # $retval = $MList;
    return $retval;
}

function writeICS ($CName, $file)
{
    
}


function updateDB ($sqlStmt)   //fuehrt ein update auf der DB aus
{
   $con = mysql_connect("localhost","username","password");
    if (!$con)
        {
        die('Could not connect: ' . mysql_error());
        }

    mysql_select_db("contracts", $con);
    if (!mysql_query($sqlStmt,$con))
  {
  die('Error: ' . mysql_error());
  }
mysql_close($con); 
}

function insertToDB ($sqlStmt)   //fuehrt ein insert auf der DB aus
{
    $con = mysql_connect("localhost","username","password");
    if (!$con)
        {
        die('Could not connect: ' . mysql_error());
        }

    mysql_select_db("contracts", $con);
    if (!mysql_query($sqlStmt,$con))
  {
  die('Error: ' . mysql_error());
  }
mysql_close($con);
}

function makeTable ($array,$type, $link) {   //erzeugt aus einem Array eine Tabelle
                                      //ruft dazu getRow auf jeder Zeile auf
                                      //und unterscheidet nach Typ der Tabelle
   
    if ($type == "contracts")
    {
        
        $retval = '<table border="1">'; 
        $retval = $retval . "<tr><th>Name</th><th>Vertragsnummer</th><th>Beginn</th><th>Ende</th><th>K&uuml;ndigungsfrist</th><th>Monatliche Kosten</th>></tr>";         //Hier kommt der tableheader
    }
    
    $anzahl = count($array);
    #print "anzahl = $count<br>";
    for ($x = 0; $x < $anzahl; $x++){
        $retval = $retval . getRow($array[$x],"td", $link)."</tr>";
    }
    $retval = $retval . "</table>";
    //print "Anzahl: $anzahl<br>";
    return $retval;
}

function getRow ($DBRow,$typ, $link) {     //erzeugt aus einer DBZeile eine Tabellen-
    $retval ="<tr>";                    //zeile des gewuenschten Typs
    $count = count($DBRow);
    #print "count: $count<br>";
    #print_r($DBRow);
    for ($i = 0; $i < $count/2 ; $i++){
        if($i == 0){$retval = $retval . "<" . $typ . "><a href=\"./index.php?$link=" . $DBRow[0] . "\">" . htmlentities($DBRow[$i]) . "</a></" . $typ . ">";}
        else{$retval = $retval . "<" . $typ . ">" . htmlentities($DBRow[$i]) . "</" . $typ . ">";}
    }
    $retval = $retval . "</tr>";
    return $retval;
}

function evaluateForm(){         //Hier werden die Eingaben geprueft, damit
                                 //SQL-Injection verhindert werden kann
    $kategorie = $_POST[kategorie];
    $material = $_POST[material];
    $schlagwort = $_POST[schlagwort];
    
    
    $retval ="";
    
    return $retval;
}
?>
