# Unser Programmierprojekt

## Beschreibung

Auktify soll eine Möglichkeit geben, verschiedene Produkte anzubieten, darauf bieten und zu erwerben. Kunden/Benutzer haben die Möglichkeit durch Erstellung eines Kontos ebenfalls neue Angebote zu erstellen, eine Beschreibung mit optionalem Startpreis, sowie eine Dauer mit Start und Ende des Angebots. Angebote können ohne die Erstellung eines Kontos gegeben werden.

## TODO

- nicht alle Angebote anzeigen, dynamisch weitere mit Button laden (Christian)
- Artikel müssen absteigend nach dem Enddatum angezeigt werden, Neuste nicht mehr ausgewählt, Filterung nach Ende absteigend (Alex)
- Fehler bei Benachrichtungenen beheben, Preis stimmt nicht (Alex)
- Security testen (alle)
- Redirect anpassen (Christian) 
- Doku für Abgabe beginnen (Installationsanweisung, Voraussetzung, Erklärungen etc) (Melina)
<<<<<<< HEAD

---
=======
---

Bugs:

-

---

ausstehend:

- 
>>>>>>> 4c42c7741d59642cfb61e94fa8298f5ace409e34
## Obligatorische Features

- Profil mit Benachrichtungen und User-Verwaltung
- erweiterte Suchfunktion
- Filterfunktionen
- Adminrechte für Angebotsverwaltung
- Kategorien für Angebote
- Favoriten
- Kommentare und Rezension für Ersteller
- Hilfe- und Über Uns-Seite
- mehr als 1 Bild bei Angeboten
- Ebay-Bieten, es wird nur der zweithöchste Preis + 1 € gezahlt
- 

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

Für den Start der Anwendung wird Docker und Docker Compose benötigt.  
Alle notwendigen Komponenten (Webserver, PHP, MySQL, phpMyAdmin) sind bereits im Repository enthalten – es muss nichts zusätzlich installiert oder konfiguriert werden.

## Installationsanleitung

1. **Repository klonen**  
   Klone das Projekt auf deinen Rechner und öffne es anschließend im Terminal:

   ```bash
   git clone https://github.com/ChristianW12/php-programmierprojekt.git
   cd <repo-name>

   ```

2. **Container starten**  
   Stelle sicher, dass Docker auf deinem System läuft.
   Starte dann das Projekt mit:

   ```bash
   docker compose up -d

   ```

3. **Anwendung im Browser öffnen**  
   Die Webanwendung ist anschließend unter: http://localhost:8080  
   Die Datenbankoberfläche (phpMyAdmin) ist erreichbar unter: http://localhost:8081

4. **Datenbank importieren**  
   Im phpMyAdmin-Interface kann über den Reiter „Importieren“ die mitgelieferte .sql-Datei unter [SQL-Befehle](sql-befehle/create-befehle) hoch geladen werden, um die Beispieldaten in die Datenbank zu laden.

5. **Anwendung testen**  
   Prüfe, ob die Webanwendung korrekt läuft und die Datenbank verbunden ist.

6. **Docker stoppen**  
   Nach Beendigung der Arbeit, können alle laufenden Container wieder gestoppt werden:
   ```bash
   docker compose down
   ```
