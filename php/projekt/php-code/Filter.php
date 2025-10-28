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
    
    private $dbconnection;
    private $data;

    public function __construct($db) {
        $this->dbconnection = $db;
        $this->data = [];
    }
    public function getData() {
        $query = 'SELECT offer_id, user_id, title, beschreibung, startpreis, start, ende FROM offers';
        $result = $this->dbconnection->query($query);

        if($result !== false && $result->rowCount() > 0){
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            $this->data = $rows;
            return $this->data;
        }
    }
    public function nachNeuste() {
        if(empty($this->data)) {
            $this->getData();
        }
        if(!empty($this->data)){
            usort($this->data, function($a, $b) {
                return strtotime($b['start']) - strtotime($a['start']);
            });
        }
        return $this->data;
    }

    public function nachPreisspanne($anfang,$ende) {
        if(empty($this->data)) {
            $this->getData();
        }
        if(!empty($this->data)){
            $this->data = array_filter($this->data, function($item) use ($anfang, $ende) {
                return $item['startpreis'] >= $anfang && $item['startpreis'] <= $ende;
            });
        }
        return $this->data;
    }

    public function nachSuche($suchbegriff) {
        if(empty($this->data)) {
            $this->getData();
        }
        if(!empty($this->data)){
            $this->data = array_filter($this->data, function($item) use ($suchbegriff) {
                return stripos($item['title'], $suchbegriff) !== false || stripos($item['beschreibung'], $suchbegriff) !== false;
            });
        }
        return $this->data;

    }

    public function nachBeliebteste() {
        $query = 'SELECT o.*, COUNT(b.bid_id) AS bid_count FROM offers o LEFT JOIN bids b ON o.offer_id = b.offer_id GROUP BY o.offer_id ORDER BY bid_count DESC';
        $result = $this->dbconnection->query($query);

        if($result !== false && $result->rowCount() > 0){
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            $this->data = $rows;
            return $this->data;
        }
        return [];
    }
}
