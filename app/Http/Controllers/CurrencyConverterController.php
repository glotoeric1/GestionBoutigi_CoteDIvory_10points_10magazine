<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurrencyConverterController extends Controller
{
    private $numberWords = [
        0 => 'zéro',
        1 => 'un',
        2 => 'deux',
        3 => 'trois',
        4 => 'quatre',
        5 => 'cinq',
        6 => 'six',
        7 => 'sept',
        8 => 'huit',
        9 => 'neuf',
        10 => 'dix',
        11 => 'onze',
        12 => 'douze',
        13 => 'treize',
        14 => 'quatorze',
        15 => 'quinze',
        16 => 'seize',
        17 => 'dix-sept',
        18 => 'dix-huit',
        19 => 'dix-neuf',
        20 => 'vingt',
        30 => 'trente',
        40 => 'quarante',
        50 => 'cinquante',
        60 => 'soixante',
        70 => 'soixante-dix',
        80 => 'quatre-vingts',
        90 => 'quatre-vingt-dix'
    ];

    private $currencyName = 'franc CFA';

    public function convertAmountToWords($amount)
    {
        $amount = round($amount, 2);

        $integerPart = (int) floor($amount);
        $centimes = (int) round(($amount - $integerPart) * 100);

        $integerWords = $this->convertNumberToWords($integerPart);
        $centimesWords = $this->convertNumberToWords($centimes);

        $result = $integerWords . ' ' . $this->currencyName;

        if ($centimes > 0) {
            $result .= ' et ' . $centimesWords . ' centime' . ($centimes > 1 ? 's' : '');
        }

        return $result;
    }

    private function convertNumberToWords($number)
    {
        if ($number == 0)
            return 'zéro';

        if ($number < 20) {
            return $this->numberWords[$number];
        }

        if ($number < 100) {
            return $this->convertTwoDigits($number);
        }

        if ($number < 1000) {
            return $this->convertHundreds($number);
        }

        if ($number < 1000000) {
            return $this->convertThousands($number);
        }

        if ($number < 1000000000) {
            return $this->convertMillions($number);
        }

        return '';
    }

    private function convertTwoDigits($number)
    {
        if (isset($this->numberWords[$number])) {
            return $this->numberWords[$number];
        }

        $tens = (int) floor($number / 10) * 10;
        $units = $number % 10;

        // special 70-99 logic
        if ($number < 80) {
            if ($units == 1 && $tens != 80) {
                return $this->numberWords[$tens] . ' et un';
            }
            return $this->numberWords[$tens] . '-' . $this->numberWords[$units];
        }

        if ($number < 100) {
            $base = 80;
            $rest = $number - $base;

            if ($rest == 0) {
                return 'quatre-vingts';
            }

            if ($rest == 1) {
                return 'quatre-vingt-un';
            }

            return 'quatre-vingt-' . $this->convertNumberToWords($rest);
        }

        return '';
    }

    private function convertHundreds($number)
    {
        $hundreds = (int) floor($number / 100);
        $rest = $number % 100;

        $result = '';

        if ($hundreds == 1) {
            $result = 'cent';
        } else {
            $result = $this->numberWords[$hundreds] . ' cent';
        }

        if ($rest == 0 && $hundreds > 1) {
            $result .= 's';
        }

        if ($rest > 0) {
            $result .= ' ' . $this->convertNumberToWords($rest);
        }

        return $result;
    }

    private function convertThousands($number)
    {
        $thousands = (int) floor($number / 1000);
        $rest = $number % 1000;

        if ($thousands == 1) {
            $result = 'mille';
        } else {
            $result = $this->convertNumberToWords($thousands) . ' mille';
        }

        if ($rest > 0) {
            $result .= ' ' . $this->convertNumberToWords($rest);
        }

        return $result;
    }

    private function convertMillions($number)
    {
        $millions = (int) floor($number / 1000000);
        $rest = $number % 1000000;

        $result = $this->convertNumberToWords($millions) . ' million';

        if ($millions > 1) {
            $result .= 's';
        }

        if ($rest > 0) {
            $result .= ' ' . $this->convertNumberToWords($rest);
        }

        return $result;
    }
}

/*
class CurrencyConverterController extends Controller
{
    private $numberWords = [
        0 => 'zéro',
        1 => 'un',
        2 => 'deux',
        3 => 'trois',
        4 => 'quatre',
        5 => 'cinq',
        6 => 'six',
        7 => 'sept',
        8 => 'huit',
        9 => 'neuf',
        10 => 'dix',
        11 => 'onze',
        12 => 'douze',
        13 => 'treize',
        14 => 'quatorze',
        15 => 'quinze',
        16 => 'seize',
        17 => 'dix-sept',
        18 => 'dix-huit',
        19 => 'dix-neuf',
        20 => 'vingt',
        21 => 'vingt et un',
        22 => 'vingt-deux',
        23 => 'vingt-trois',
        24 => 'vingt-quatre',
        25 => 'vingt-cinq',
        26 => 'vingt-six',
        27 => 'vingt-sept',
        28 => 'vingt-huit',
        29 => 'vingt-neuf',
        30 => 'trente',
        31 => 'trente et un',
        32 => 'trente-deux',
        33 => 'trente-trois',
        34 => 'trente-quatre',
        35 => 'trente-cinq',
        36 => 'trente-six',
        37 => 'trente-sept',
        38 => 'trente-huit',
        39 => 'trente-neuf',
        40 => 'quarante',
        41 => 'quarante et un',
        42 => 'quarante-deux',
        43 => 'quarante-trois',
        44 => 'quarante-quatre',
        45 => 'quarante-cinq',
        46 => 'quarante-six',
        47 => 'quarante-sept',
        48 => 'quarante-huit',
        49 => 'quarante-neuf',
        50 => 'cinquante',
        51 => 'cinquante et un',
        52 => 'cinquante-deux',
        53 => 'cinquante-trois',
        54 => 'cinquante-quatre',
        55 => 'cinquante-cinq',
        56 => 'cinquante-six',
        57 => 'cinquante-sept',
        58 => 'cinquante-huit',
        59 => 'cinquante-neuf',
        60 => 'soixante',
        61 => 'soixante et un',
        62 => 'soixante-deux',
        63 => 'soixante-trois',
        64 => 'soixante-quatre',
        65 => 'soixante-cinq',
        66 => 'soixante-six',
        67 => 'soixante-sept',
        68 => 'soixante-huit',
        69 => 'soixante-neuf',
        70 => 'soixante-dix',
        71 => 'soixante et onze',
        72 => 'soixante-douze',
        73 => 'soixante-treize',
        74 => 'soixante-quatorze',
        75 => 'soixante-quinze',
        76 => 'soixante-seize',
        77 => 'soixante-dix-sept',
        78 => 'soixante-dix-huit',
        79 => 'soixante-dix-neuf',
        80 => 'quatre-vingts',
        81 => 'quatre-vingt-un',
        82 => 'quatre-vingt-deux',
        83 => 'quatre-vingt-trois',
        84 => 'quatre-vingt-quatre',
        85 => 'quatre-vingt-cinq',
        86 => 'quatre-vingt-six',
        87 => 'quatre-vingt-sept',
        88 => 'quatre-vingt-huit',
        89 => 'quatre-vingt-neuf',
        90 => 'quatre-vingt-dix',
        91 => 'quatre-vingt-onze',
        92 => 'quatre-vingt-douze',
        93 => 'quatre-vingt-treize',
        94 => 'quatre-vingt-quatorze',
        95 => 'quatre-vingt-quinze',
        96 => 'quatre-vingt-seize',
        97 => 'quatre-vingt-dix-sept',
        98 => 'quatre-vingt-dix-huit',
        99 => 'quatre-vingt-dix-neuf'
    ];

    private $currencyName = 'franc CFA';

    public function convertAmountToWords($amount)
    {
        $integerPart = floor($amount);
        $decimalPart = $amount - $integerPart;

        $integerPartInWords = $this->convertNumberToWords($integerPart);
        $decimalPartInWords = $this->convertNumberToWords($decimalPart * 100);

        $integerPartWord = $integerPartInWords . ' ' . $this->currencyName;
        $decimalPartWord = $decimalPartInWords . ' centimes';

        if ($integerPartInWords == 'un') {
            $integerPartWord = 'un ' . $this->currencyName;
        }

        if ($decimalPartInWords == 'un') {
            $decimalPartWord = 'un centime';
        }

        if ($decimalPart == 0) {
            return $integerPartWord;
        }

        return $integerPartWord . ' ' . $decimalPartWord;
    }

    private function convertNumberToWords($number)
    {
        if ($number < 0) {
            return 'moins ' . $this->convertNumberToWords(abs($number));
        }

        if ($number < 21) {
            return $this->numberWords[$number];
        }

        if ($number < 100) {
            $tens = (int) floor($number / 10) * 10;
            $units = $number % 10;
            if ($units > 0) {
                if ($tens == 60 || $tens == 80) {
                    $units += 10;
                }
                return $this->numberWords[$tens] . '-' . $this->numberWords[$units];
            }
            return $this->numberWords[$tens];
        }

        if ($number < 1000) {
            $hundreds = (int) floor($number / 100);
            $remainder = $number % 100;
            if ($remainder > 0) {
                if ($remainder < 10) {
                    $remainderWord = '0' . $this->numberWords[$remainder];
                } else {
                    $remainderWord = $this->convertNumberToWords($remainder);
                }
                return $this->numberWords[$hundreds] . ' cent ' . $remainderWord;
            }
            return $this->numberWords[$hundreds] . ' cents';
        }

        if ($number < 1000000) {
            $thousands = (int) floor($number / 1000);
            $remainder = $number % 1000;
            $thousandsWord = $this->convertNumberToWords($thousands) . ' mille';
            if ($remainder > 0) {
                if ($remainder < 100) {
                    $remainderWord = ' et ' . $this->convertNumberToWords($remainder);
                } else {
                    $remainderWord = ', ' . $this->convertNumberToWords($remainder);
                }
                return $thousandsWord . $remainderWord;
            }
            return $thousandsWord;
        }

        if ($number < 1000000000) {
            $millions = (int) floor($number / 1000000);
            $remainder = $number % 1000000;
            $millionsWord = $this->convertNumberToWords($millions) . ' million';
            if ($remainder > 0) {
                if ($remainder < 1000) {
                    $remainderWord = ' et ' . $this->convertNumberToWords($remainder);
                } else {
                    $remainderWord = ', ' . $this->convertNumberToWords($remainder);
                }
                return $millionsWord . $remainderWord;
            }
            return $millionsWord;
        }

        return '';
    }

}
*/