# Unser Programmierprojekt

## Beschreibung

Auktify soll eine Möglichkeit geben, verschiedene Produkte anzubieten, darauf bieten und zu erwerben. Kunden/Benutzer haben die Möglichkeit durch Erstellung eines Kontos ebenfalls neue Angebote zu erstellen, eine Beschreibung mit optionalem Startpreis, sowie eine Dauer mit Start und Ende des Angebots. Angebote können ohne die Erstellung eines Kontos gegeben werden.

## TODO

✅ Datenbank anlegen
✅ Account löschen/erstellen (Melina)

Neue Aufgaben

- angebote.php Seite umschreiben
  Es sollen die Angebote in kleinem Format angezeigt werden, durch Klicken auf ein Angebot soll dieses Angebot vergrößert werden, damit alle Einzelheiten angezeigt werden können.
  Dort soll es möglich sein, dass der Ersteller das Angebot bearbeiten oder löschen kann und ein x-beliebiger Nutzer kann dann dort auf Bieten klicken.
  Dort sollen dann auch alle Bilder angezeigt werden, die vom Ersteller hochgeladen wurden


========================================

- Funktionalität "Jetzt loslegen" (Alex)
- Neues Angebot hinzufügen (Alex)
- Jetzt Bieten (Luca)
- Feature für Bilder (Christian)
- Änderungen am Passwort vornehmen können, falls vergessen o.Ä.
- Wenn falsche Daten eingegeben werden, dann Warnhinweis o.Ä. (Aktuell: fehlerhafte DB wird angezeigt)

## Obligatorische Features

- Artikel als Hauptentität: Titel, Text (HTML/Markdown), Bild, Beginn- und Endzeitpunkt der Auktion
- CRUD-Funktionalität für Artikel: Erstellen, Anzeigen, Bearbeiten, Löschen
- Login-geschützte Verwaltung: Nur angemeldete Benutzer können Artikel anlegen, ändern oder löschen
- Anzeigen für Besucher: Artikel werden absteigend nach Endzeitpunkt angezeigt
- Gebote für Besucher: Höchstpreis + E-Mail-Adresse können abgegeben werden
- Admins können jedes Angebot bearbeiten

## Optionale Features / Erweiterungen

- Ebay-Gebote: Höchstbietender zahlt nur 1 € mehr als zweithöchster Bieter; Anpassung bei neuen Geboten
- Weitere Artikelattribute: Kategorie, mehrere Bilder, Notizen
- Erweiterte Suche: Fuzzy Search für unscharfe Treffer
- E-Mail-Benachrichtigung am Ende der Auktion an Bieter und Käufer
- Benutzerverwaltung & Kommentare: Bewertungen, Feedback, Dashboard
- Filterung: Seitenleiste um nach gewünschten Anforderungen zu sortieren

## Voraussetzungen

- wip

## Installationsanleitung

- wip
