<?php

$dsn = 'mysql:dbname=auktion;host=db;port=3306';

 try{
        $db = new Db($dsn, 'root', '');
    } catch (PDOException $e){
        echo 'Verbindungsfehler: ' . $e->getMessage();
        exit;
    }
?>