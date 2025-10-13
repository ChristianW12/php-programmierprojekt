<?php
class Shout{
    public  function shoutAusgebenTxt(){
        // Ausgabe der Inhalte der Datei
        $file = fopen('shouts.txt', 'r');
        if ($file)              {
            while (!feof($file)) {
                $zeile = fgets($file);
                echo $zeile;
            }
            fclose($file);
        } else {
            echo 'Datei nicht lesbar!';
        }
    }
    public function saveInTxt($user, $content){
        $file = fopen('shouts.txt', 'a');
        switch (strtolower($user)) {
        case 'christian':
            $bgColor = '#95dea8';
            break;
        case 'melina':
            $bgColor = '#ca6fd6';
            break;
        case 'lars':
            $bgColor = '#92f0ed';
            break;       
        case 'kathrin':
            $bgColor = '#cf5be3';
            break;
        default:
            $bgColor = 'beige';
            break;
        }
        if ($file) {
            $zeile = ' 
                <table cellspacing="2" align="center" width="350"> 
                <tr> 
                <td bgcolor="' . $bgColor . '">' . $user . '</td> 
                <td bgcolor="' . $bgColor . '">' . $content . '</td> 
                </tr> 
                </table>';
            fwrite($file, $zeile);
            fclose($file);
        } else {
            echo 'Datei nicht schreibbar!';
        }
    }

    public function saveInDB( $db, $user, $content ){
        // Insert Statement vorbereiten
        $query = 'INSERT INTO shout (user, shout_text) VALUES (?,?)';
        $stmt = $db->prepare( $query );
        $stmt->bindParam( 1, $user, PDO::PARAM_STR );
        $stmt->bindParam( 2, $content, PDO::PARAM_STR );
        // Insert Statement ausführen
        $stmt->execute();
    }

    public function outputShoutDB($db){
        $query = "select user, shout_text 
            from shout
            ";
        $ausgabe = $db->query($query);

        foreach ($ausgabe as $reihe) {
            echo '<br/>'.$reihe['user'].','.$reihe['shout_text']; 
        }
        unset($ausgabe);
    }
}
