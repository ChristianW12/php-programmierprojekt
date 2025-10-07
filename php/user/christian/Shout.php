<?php
class Shout{

    public static function shoutAusgeben(){
        // Ausgabe der Inhalte der Datei
        $file = fopen('shouts.txt', 'r');
        if ($file) {
            while (!feof($file)) {
                $zeile = fgets($file);
                echo $zeile;
            }
            fclose($file);
        } else {
            echo 'Datei nicht lesbar!';
        }
    }

    public static function save($user, $content)
    {
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
}