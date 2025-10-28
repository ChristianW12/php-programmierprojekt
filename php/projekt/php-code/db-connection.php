<?php
require_once 'Db.php'; // Db-Klasse einbinden
 function mitDBverbinden() {
    # code...
    $dsn = 'mysql:dbname=auktion;host=db;port=3306';
    try{
       return new Db($dsn, 'root', '');
    } catch (PDOException $e){
        echo 'Verbindungsfehler: ' . $e->getMessage();
        exit;
    }
}
?>