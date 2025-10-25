<?php
class Db extends PDO {
    public function query(
        string $statement,
        ?int $mode = PDO::FETCH_ASSOC,
        mixed ...$fetch_mode_args
    ): PDOStatement|false
    {
        try {
            return parent::query($statement, $mode, ...$fetch_mode_args);
        } catch (PDOException $e) {
            echo '<div style="color: red">DB ERROR: ' . $e->getMessage();
            echo '<br/>Query: ' . $statement . '</div>';
            return false;
        }
    }
}