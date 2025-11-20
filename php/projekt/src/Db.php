<?php
class Db extends PDO {
    /**
     * Führt eine Query aus und fängt PDO-Exceptions ab, um Fehlermeldungen auszugeben.
     *
     * @param string $statement
     * @param int|null $mode
     * @param mixed ...$fetch_mode_args
     * @return PDOStatement|false
     */
    public function query(
        string $statement,
        ?int $mode = PDO::FETCH_ASSOC,
        mixed ...$fetch_mode_args
    ): PDOStatement|false
    {
        try {
            return parent::query($statement, $mode, ...$fetch_mode_args);
        } catch (PDOException $e) {
            error_log(sprintf('DB ERROR: %s | Query: %s', $e->getMessage(), $statement));
            throw new RuntimeException('Ein Datenbankfehler ist aufgetreten. Bitte versuchen Sie es später erneut.', 0, $e);
        }
    }
}
