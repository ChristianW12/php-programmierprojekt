<?php
    class Shout_klasse {
        public $user;
        public $content;
        public $db;


        public function __construct($user, $content, $db) {
            $this->user = $user;
            $this->content = $content;
            $this->db = $db;
        }

        static public function listshouts($db)
        {
            $query = 'SELECT * FROM shout ORDER BY shout_id DESC LIMIT 10';
            $res = $db->query($query);

            $res->setFetchMode(PDO::FETCH_OBJ);
            echo '<table cellspacing="2" align="center" width="350">';
            foreach ($res as $row) {
                switch (strtolower($row->user)) {
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
            echo'
            <tr> 
            <td style="background-color: '.$bgColor.'" > '.$row->shout_id.' </td>
            <td style="background-color: '.$bgColor.'" > '.$row->user.'    </td>
            <td style="background-color: '.$bgColor.'" > '.$row->shout_text.' </td>
            <td style="background-color: '.$bgColor.'" > '.$row->created.'    </td>       
            </tr >';
            }
            echo '</table>';


            unset($db);
        }

        public function save($user, $content, $db) {
            $query = 'INSERT INTO shout(user, shout_text) VALUES (?, ?)';
            $stmt = $db->prepare($query);

            $stmt->bindParam(1, $user, PDO::PARAM_STR);
            $stmt->bindParam(2, $content, PDO::PARAM_STR);
            $stmt->execute();
            unset($db);

            }

    }

?>
