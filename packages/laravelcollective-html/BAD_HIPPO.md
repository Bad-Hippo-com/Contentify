# Bad-Hippo-Kompatibilitätskopie

Stand: 2026-09-07 12:10 CEST

Diese Kopie basiert unverändert auf `laravelcollective/html` v6.4.1,
Upstream-Commit `64ddfdcaeeb8d332bd98bef442bef81e39c3910b`. Das Paket steht unter der
beiliegenden MIT-Lizenz.

Bad Hippo führt Paketversion 6.4.3 ausschließlich als kontrollierte
Kompatibilitätsbrücke für PHP 8.5 und Laravel 12. Neben Composer-Metadaten und
dieser Dokumentation wurden genau zwei Konstruktorsignaturen an PHP 8.5
angepasst: Der tatsächlich erforderliche URL-Generator ist nicht mehr
scheinbar optional und der optionale Request ist explizit nullable. Das
Laufzeitverhalten wurde nicht verändert.

Die Brücke verhindert, dass die Laravel-12-Stufe gleichzeitig alle bestehenden
Formular- und HTML-Aufrufe umbauen muss. Das aufgegebene Paket soll später
getrennt durch eine gepflegte Lösung ersetzt werden.
