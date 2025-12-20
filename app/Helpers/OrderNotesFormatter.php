<?php

namespace App\Helpers;

class OrderNotesFormatter
{
    /**
     * Formatea las notas del cliente (JSON) en un formato legible
     */
    public static function formatNotes($notes)
    {
        if (!$notes) {
            return null;
        }

        try {
            $data = json_decode($notes, true);
            
            if (!is_array($data)) {
                return $notes;
            }

            $formatted = [];

            // Información de Facturación
            if (isset($data['billing']) && is_array($data['billing'])) {
                $formatted['Información de Facturación'] = self::formatAddressBlock($data['billing']);
            }

            // Información de Envío
            if (isset($data['shipping']) && is_array($data['shipping'])) {
                $formatted['Información de Envío'] = self::formatAddressBlock($data['shipping']);
            }

            // Método de pago
            if (isset($data['payment_method'])) {
                $paymentMethod = $data['payment_method'];
                $paymentTranslations = [
                    'cash' => 'Efectivo',
                    'credit_card' => 'Tarjeta de Crédito',
                    'debit_card' => 'Tarjeta de Débito',
                    'paypal' => 'PayPal',
                    'bank_transfer' => 'Transferencia Bancaria',
                ];
                
                $formatted['Método de Pago'] = $paymentTranslations[strtolower($paymentMethod)] ?? ucfirst(str_replace('_', ' ', $paymentMethod));
            }

            return $formatted;
        } catch (\Exception $e) {
            return $notes;
        }
    }

    /**
     * Formatea un bloque de dirección
     */
    private static function formatAddressBlock($address)
    {
        $fields = [
            'first_name' => 'Nombre',
            'last_name' => 'Apellido',
            'email' => 'Email',
            'phone' => 'Teléfono',
            'address' => 'Dirección',
            'city' => 'Ciudad',
            'state' => 'Estado/Departamento',
            'postal_code' => 'Código Postal',
            'country' => 'País',
        ];

        $formatted = [];
        foreach ($fields as $key => $label) {
            if (isset($address[$key]) && !empty($address[$key])) {
                $formatted[$label] = $address[$key];
            }
        }

        return $formatted;
    }

    /**
     * Verifica si las notas son JSON
     */
    public static function isJsonNotes($notes)
    {
        if (!$notes) {
            return false;
        }

        try {
            $decoded = json_decode($notes, true);
            return is_array($decoded) && (isset($decoded['billing']) || isset($decoded['shipping']));
        } catch (\Exception $e) {
            return false;
        }
    }
}
