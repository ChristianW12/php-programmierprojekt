<?php

class Helper
{
    /**
     * Konvertiert Datumsstrings in ein deutsches Anzeigeformat.
     *
     * @param string|null $dateString
     * @return string
     */
    public static function formatDate(?string $dateString): string
    {
        if (empty($dateString)) {
            return 'N/A';
        }

        try {
            return (new DateTime($dateString))->format('d.m.Y H:i') . ' Uhr';
        } catch (Exception $e) {
            return 'Ungültiges Datum';
        }
    }

    /**
     * Formatiert numerische Preise als Euro-Betrag.
     *
     * @param mixed $price
     * @return string
     */
    public static function formatPrice(mixed $price): string
    {
        if ($price === null || $price === '') {
            return 'N/A';
        }

        return number_format((float) $price, 2, ',', '.') . ' €';
    }
}
