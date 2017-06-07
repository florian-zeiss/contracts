# contracts
Webbasierte Verwaltung der eigenen Verträge
Verträge werden dabei in einer MySQL-Datenbank gespeichert.
Die Datei contracts_warn.pl ist als Cronjob gedacht, um die DB regelmäßig zu scannen
und im Bedarfsfall eine Mail abzuschicken.
#Datenbankschema:
# id		INT		Primary Key		ID des Vertrages, interner Wert
# name		varchar					Worum geht es bei diesem Vertrag?
# firma		varchar					Bei welcher Firma ist er abgeschlossen
# nummer	varchar					Welche Vertragsnummer/Kundennummer haben wir
# beginn	date					Wann hat der Vertrag begonnen
# ende		date					Wann endet der Vertrag theoretisch
# frist		INT						Wie lang ist die Kündigungsfrist
# kosten	float					Wie hoch sind die Kosten im Monat