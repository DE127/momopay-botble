<?php

use Botble\Ecommerce\Models\Currency;

if (!defined('MOMOPAY_PAYMENT_METHOD_NAME')) {
    define('MOMOPAY_PAYMENT_METHOD_NAME', 'momopay');
}

if (!defined('MOMOPAY_PAYMENT_SLAT_HASHID')) {
    define('MOMOPAY_PAYMENT_SLAT_HASHID', '12345678987654321qwertyuioplkjhgfdcvgbtrds');
}

if (!defined('MOMOPAY_PAYMENT_ALPHABET_HASHID')) {
    define('MOMOPAY_PAYMENT_ALPHABET_HASHID', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
}

if (!function_exists('get_current_VND_exchange_rate')) {
    /**
     * @return int|null
     */
    function get_current_VND_exchange_rate()
    {
        $currencies = get_all_currencies();
        foreach ($currencies as $currency) {
            if ($currency->title == 'VND') {
                $exchange_rate = $currency->exchange_rate;
            }
        }

        return (int)$exchange_rate ?? 23203;
    }
}

if (!function_exists('get_custom_current_exchange_rate')) {
    /**
     * @param null $currency
     */
    function get_custom_current_exchange_rate($currency = null)
    {
        if (!$currency) {
            $currency = get_application_currency();
        } elseif ($currency != null && !($currency instanceof Currency)) {
            $currency = app(CurrencyInterface::class)->getFirstBy(['ec_currencies.id' => $currency]);
        }

        if ($currency->exchange_rate > 0) {
            return $currency->exchange_rate;
        }

        return 1;
    }
}

if (!function_exists('convert_amount_to_VND')) {
    /**
     * @return int|null
     */
    function convert_amount_to_VND($amount)
    {
        return (int)(($amount / get_custom_current_exchange_rate()) * get_current_VND_exchange_rate());
    }
}
