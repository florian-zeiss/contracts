# contracts
Webbasierte Verwaltung der eigenen Verträge
Verträge werden dabei in einer MySQL-Datenbank gespeichert.
Die Datei contracts_warn.pl ist als Cronjob gedacht, um die DB regelmäßig zu scannen
und im Bedarfsfall eine Mail abzuschicken.
Neustrukturierung der functions.php um etwas mehr Uebersichtlichkeit zu bekommen.
Datenbankzugriff wird zentralisiert, so das auch nur an einer Stelle der Zugriff
parametrisiert werden muss.