<?php

/*
 * Beim Erstellen von einem Objekt dieser Klasse wird die Variable übergeben 
 * welche wir erstellen beim Verbinden mit der Datenbank
 * Bisher sah das wie folgt aus
 *    try{
        $db = new Db($dsn, 'root', '');
    } catch (PDOException $e){
        echo 'Verbindungsfehler: ' . $e->getMessage();
        exit;
    }

    * Dieses $db wird dann beim erstellen vom Objekt mit an die Klasse Filter übergeben damit wir auch hier Zugriffsmöglichkeiten auf di
    * Datenbank haben 
 */
class Filter {
    
    private $db-connection;

    public function __construct($db) {
    }

    public function nachNeuste() {
    }
    
    public function nachBliebteste() {
    }

    public function nachPreisspanne($anfang, $ende) {
    }

    public function nachTag() {
    }

    
}

