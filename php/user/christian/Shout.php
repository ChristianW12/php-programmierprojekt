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
        $query = "SELECT user, shout_text FROM shout";
        $ausgabe = $db->query($query);

       echo '<table style="width: 80%; margin: 20px auto; border-collapse: collapse; box-shadow: 0 2px 8px rgba(0,0,0,0.1); font-family: Arial, sans-serif;">';
       echo '<thead><tr style="background-color: #f8f9fa;"><th style="padding: 12px 15px; text-align: left; border-bottom: 2px solid #dee2e6;">User</th><th style="padding: 12px 15px; text-align: left; border-bottom: 2px solid #dee2e6;">Shout</th></tr></thead>';
       echo '<tbody>';
       foreach ($ausgabe as $reihe) {
           $userColor = $this->color($reihe['user']);
           echo '<tr>';
           echo '<td style="padding: 10px 15px; border-bottom: 1px solid #e9ecef; background-color: ' . $userColor . '; opacity: 0.85;">' . htmlspecialchars($reihe['user']) . '</td>';
           echo '<td style="padding: 10px 15px; border-bottom: 1px solid #e9ecef; background-color: ' . $userColor . '; opacity: 0.85;">' . htmlspecialchars($reihe['shout_text']) . '</td>';
           echo '</tr>';
       }
       echo '</tbody>';
       echo '</table>';
        unset($ausgabe);
    }

    public function color($user): string{
        switch (strtolower(trim($user))) {
            case 'christian':
                $bgColor = 'lightblue';
                break;
            case 'melina':
                $bgColor = 'pink';
                break;
            case 'lars':
                $bgColor = 'red';
                break;
            case 'kathrin':
                $bgColor = 'purple';
                break;
            default:
                $bgColor = 'beige';
                break;
        }
        return $bgColor;
    }
}
