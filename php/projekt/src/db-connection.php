<?php
/**
 * Stellt die Verbindung zur Datenbank her
 */
require_once __DIR__ . '/Db.php';

if (!function_exists('mitDBverbinden')) {
    /**
     * Bei einem Verbindungsfehler wird eine Fehlermeldung ausgegeben und das Skript beendet
     */
    function mitDBverbinden() {
        $dsn = 'mysql:dbname=auktion;host=db;port=3306';
        try{
            return new Db($dsn, 'root', '');
        } catch (PDOException $e){
            echo 'Verbindungsfehler: ' . $e->getMessage();
            exit;
        }
    }
}
?>