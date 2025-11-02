<?php
/**
 * Template für eine Angebotskarte.
 *
 * Erwartet eine Variable $angebot (array) im Scope mit den Keys:
 * title, beschreibung, startpreis, start, ende
 */

if (!isset($angebot)) {
    return;
}

$titel = htmlspecialchars($angebot['title'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$beschreibung = htmlspecialchars($angebot['beschreibung'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$startpreisWert = isset($angebot['startpreis']) ? (float) $angebot['startpreis'] : null;
$startpreis = $startpreisWert !== null
    ? number_format($startpreisWert, 2, ',', '.') . ' €'
    : 'Preis unbekannt';

$angebotId = isset($angebot['offer_id']) ? (int) $angebot['offer_id'] : null;

$startDatum = 'Startdatum offen';
if (!empty($angebot['start'])) {
    try {
        $startDatum = (new DateTime($angebot['start']))->format('d.m.Y H:i');
    } catch (Exception $exception) {
        $startDatum = htmlspecialchars($angebot['start'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

$endeDatum = 'Enddatum offen';
if (!empty($angebot['ende'])) {
    try {
        $endeDatum = (new DateTime($angebot['ende']))->format('d.m.Y H:i');
    } catch (Exception $exception) {
        $endeDatum = htmlspecialchars($angebot['ende'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
?>
<article class="angebot-card">
    <h3 class="angebot-title">
        <a href="aktuelles-angebot.php?id=<?= $angebotId ?>"><?= $titel ?></a>
    </h3>
    <dl class="angebot-meta">
        <div class="meta-item price-item">
            <dt>Startpreis</dt>
            <dd><?= $startpreis ?></dd>
        </div>
        <div class="meta-item date-item">
            <dt>Start</dt>
            <dd><?= $startDatum ?></dd>
        </div>
        <div class="meta-item date-item">
            <dt>Ende</dt>
            <dd><?= $endeDatum ?></dd>
        </div>
    </dl>
    <form method="get" action="angebot-bieten.php">
        <?php if ($angebotId !== null): ?>
            <input type="hidden" name="offer_id" value="<?= htmlspecialchars((string) $angebotId, ENT_QUOTES, 'UTF-8') ?>">
        <?php endif; ?>
        <?php if ($startpreisWert !== null): ?>
            <input type="hidden" name="startpreis" value="<?= htmlspecialchars(number_format($startpreisWert, 2, '.', ''), ENT_QUOTES, 'UTF-8') ?>">
        <?php endif; ?>
        <button type="submit" class="bieten-button">Jetzt bieten</button>
    </form>
</article>
