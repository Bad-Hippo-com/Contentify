# Bad-Hippo-Kompatibilitätskopie

Ausgangsbasis ist `caffeinated/modules` v6.3.1, Commit
`aa19e0c8b4ba49edef936eb58c63cd4898ca3d31`, unter der beiliegenden MIT-Lizenz.

Die Originalveröffentlichung erlaubt Illuminate nur bis Version 8 und wird seit
2021 nicht mehr veröffentlicht. Contentify benötigt deren bestehende Modul-API
für seine 44 Module. Die lokale Version 6.3.2 erweiterte die Composer-Anforderung
zunächst auf Illuminate 9 und PHP 8.5. Version 6.3.3 führt denselben
unveränderten Paketcode auf Illuminate 10 weiter. Syntax-, Unit-, Modul-,
Datenbank- und Browserprüfungen entscheiden über die tatsächliche
Kompatibilität. Version 6.3.4 setzt diese kontrollierte Brücke mit unverändertem
Paketcode für Illuminate 11 fort.

Die lokale Kopie ist eine kontrollierte Brücke. Ein späterer Austausch des
Modulsystems bleibt eine eigene, deutlich größere Aufgabe.
