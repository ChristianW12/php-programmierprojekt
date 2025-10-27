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
$startpreis = isset($angebot['startpreis'])
    ? number_format((float) $angebot['startpreis'], 2, ',', '.') . ' €'
    : 'Preis unbekannt';

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
    <h3 class="angebot-title"><?= $titel ?></h3>
    <p class="angebot-description"><?= $beschreibung ?></p>
    <dl class="angebot-meta">
        <div>
            <dt>Startpreis</dt>
            <dd><?= $startpreis ?></dd>
        </div>
        <div>
            <dt>Start</dt>
            <dd><?= $startDatum ?></dd>
        </div>
        <div>
            <dt>Ende</dt>
            <dd><?= $endeDatum ?></dd>
        </div>
    </dl>
</article>
