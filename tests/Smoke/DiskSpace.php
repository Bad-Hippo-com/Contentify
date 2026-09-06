<?php

use Contentify\DiskSpace;

require __DIR__.'/../../vendor/autoload.php';

$readable = DiskSpace::freeBytes(__DIR__);
if (! is_float($readable) || $readable < 0) {
    throw new RuntimeException('Freier Speicher wurde für einen lesbaren Pfad nicht ermittelt.');
}

$missing = __DIR__.DIRECTORY_SEPARATOR.'missing-'.uniqid('', true);
if (DiskSpace::freeBytes($missing) !== null) {
    throw new RuntimeException('Ein nicht lesbarer Pfad wurde nicht als unbekannt behandelt.');
}

echo "OK: Freier Speicher wird ermittelt; nicht lesbare Pfade werden ohne Fehler als unbekannt behandelt.\n";
