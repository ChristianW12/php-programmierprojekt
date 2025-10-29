-- 1. Neue Spalte 'mail' hinzufügen
ALTER TABLE bids
ADD COLUMN mail VARCHAR(255) NULL AFTER offer_id;

-- 2. Fremdschlüssel entfernen (genauer Name aus Dump!)
ALTER TABLE bids
DROP FOREIGN KEY bids_ibfk_2;

-- 3. Spalte 'user_id' komplett entfernen
ALTER TABLE bids
DROP COLUMN user_id;

-- 4. mail-Spalte als NOT NULL setzen
ALTER TABLE bids
MODIFY COLUMN mail VARCHAR(255) NOT NULL;
