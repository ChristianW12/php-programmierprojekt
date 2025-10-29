<?php

class neuesAngebotEdit
{
    public $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function angebotErstellen($user_id, $titel, $beschreibung, $startpreis, $ende, $db)
    {
        $query = 'INSERT INTO offers (user_id, title, beschreibung, startpreis, ende) VALUES(?,?,?,?,?)';
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->bindParam(2, $titel, PDO::PARAM_STR);
        $stmt->bindParam(3, $beschreibung, PDO::PARAM_STR);
        $stmt->bindParam(4, $startpreis, PDO::PARAM_STR);
        $stmt->bindParam(5, $ende, PDO::PARAM_STR);
        $stmt->execute();
    }
}

?>