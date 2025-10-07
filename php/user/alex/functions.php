<?php
    class Shout {
        public $user;
        public $content;

        public function __construct($user, $content) {
            $this->user = $user;
            $this->content = $content;
        }

        static public function listshouts() {
            echo '<table cellspacing="2" align="center" width="350">';
            $file = fopen('shoutbox.txt', 'r');
            if ($file) {
                while (!feof($file)) {
                    $zeile = fgets($file);
                    echo $zeile;
                }
                fclose($file);
            }
            echo '</table>';
        }

        public function save($user, $content) {
            switch (strtolower($user)) {
                case 'alex':
                    $bgColor = 'red';
                    break;
                case 'christian':
                    $bgColor = 'blue';
                    break;
                case 'sabrina':
                case 'elira':
                    $bgColor = 'pink';
                    break;
                default:
                    $bgColor = '#3399CC';
                    break;
            }
            $file = fopen('shoutbox.txt', 'a');
            if($file) {
                $zeile = '<tr>
                        <td style="background-color:' .$bgColor. '">'.$user.'</td>
                        <td style="background-color:' .$bgColor. '">'.$content.'</td>
                      </tr>';
                fwrite($file, $zeile);
                fclose($file);
            }
        }
    }

?>
