<?php
require_once __DIR__ . '/db-connection.php';

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

    * Dieses $db wird dann beim erstellen vom Objekt mit an die Klasse Filter übergeben damit wir auch hier Zugriffsmöglichkeiten auf die
    * Datenbank haben
 */

class Filter
{
    private $dbconnection;
    private $queryParams = [];
    private $whereClauses = ['o.ende > NOW()'];
    private $joins = '';
    private $orderBy = 'o.ende DESC';

    public function __construct()
    {
        $this->dbconnection = mitDBverbinden();
    }

    /**
     * Sortiert nach Startdatum (neueste zuerst)
     *
     * @return array<int, array<string, mixed>>
     */
    public function nachNeuste()
    {
        $this->orderBy = 'start DESC';
        return $this;
    }

    /**
     * Sortiert nach Enddatum (spätestes zuerst)
     *
     * @return array<int, array<string, mixed>>
     */
    public function nachEndeBald()
    {
        $this->orderBy = 'o.ende DESC';
        return $this;
    }

    /**
     * Filtert Angebote innerhalb einer Startpreis-Spanne.
     *
     * @param float|int $anfang
     * @param float|int $ende
     * @return array<int, array<string, mixed>>
     */
    public function nachPreisspanne($anfang, $ende)
    {
        $this->whereClauses[] = 'startpreis BETWEEN :min_preis AND :max_preis';
        $this->queryParams[':min_preis'] = $anfang;
        $this->queryParams[':max_preis'] = $ende;
        return $this;
    }

    /**
     * Filtert nach Textsuche inkl. toleranter Levenshtein-Prüfung.
     *
     * @param string $suchbegriff
     * @return array<int, array<string, mixed>>
     */
    public function nachSuche($suchbegriff)
    {
        $this->whereClauses[] = '(title LIKE :search OR beschreibung LIKE :search)';
        $this->queryParams[':search'] = '%' . $suchbegriff . '%';
        return $this;
    }

    /**
     * Liefert Angebote sortiert nach Anzahl der Gebote.
     *
     * @return array<int, array<string, mixed>>
     */
    public function nachBeliebteste()
    {
        $this->joins .= ' LEFT JOIN bids b ON o.offer_id = b.offer_id';
        $this->orderBy = 'COUNT(b.bid_id) DESC';
        return $this;
    }

    /**
     * Gibt alle Angebote eines Nutzers zurück.
     *
     * @param int $userId
     * @return array<int, array<string, mixed>>
     */
    public function nachMeineAngebote($userId)
    {
        $this->whereClauses[] = 'o.user_id = :user_id';
        $this->queryParams[':user_id'] = $userId;
        return $this;
    }

    /**
     * Ermittelt alle Favoriten eines Nutzers.
     *
     * @param int $userId
     * @return array<int, array<string, mixed>>
     */
    public function nachFavoriten($userId)
    {
        $this->joins .= ' JOIN favourites f ON o.offer_id = f.offer_id';
        $this->whereClauses[] = 'f.user_id = :user_id';
        $this->queryParams[':user_id'] = $userId;
        return $this;
    }

    /**
     * Setzt einen Kategorie-Filter für die Abfrage
     *
     * @param string $kategorie Kategorie-Name
     * @return array<int, array<string, mixed>>
     */
    public function nachKategorie(string $kategorie)
    {
        $this->whereClauses[] = 'kategorie = :kategorie';
        $this->queryParams[':kategorie'] = $kategorie;
        return $this;
    }

    /**
     * Baut die Abfrage und liefert die Ergebnisse als Array von assoziativen Arrays
     *
     * @return array<int, array<string, mixed>>
     */
    public function getResults()
    {
        $sql = 'SELECT o.* FROM offers AS o ' . $this->joins;

        if (!empty($this->whereClauses)) {
            $sql .= ' WHERE ' . implode(' AND ', $this->whereClauses);
        }

        $sql .= ' GROUP BY o.offer_id';
        $sql .= ' ORDER BY ' . $this->orderBy;

        $stmt = $this->dbconnection->prepare($sql);
        $stmt->execute($this->queryParams);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
