<?php

namespace App\Helpers;

class ArabicShaper
{
    private static $map = [
        // Char => [Isolated, Final, Medial, Initial]
        0x0621 => [0xFE80, 0xFE80, 0xFE80, 0xFE80], // ء
        0x0622 => [0xFE81, 0xFE82, 0xFE82, 0xFE81], // آ
        0x0623 => [0xFE83, 0xFE84, 0xFE84, 0xFE83], // أ
        0x0624 => [0xFE85, 0xFE86, 0xFE86, 0xFE85], // ؤ
        0x0625 => [0xFE87, 0xFE88, 0xFE88, 0xFE87], // إ
        0x0626 => [0xFE89, 0xFE8A, 0xFE8C, 0xFE8B], // ئ
        0x0627 => [0xFE8D, 0xFE8E, 0xFE8E, 0xFE8D], // ا
        0x0628 => [0xFE8F, 0xFE90, 0xFE92, 0xFE91], // ب
        0x0629 => [0xFE93, 0xFE94, 0xFE94, 0xFE93], // ة
        0x062A => [0xFE95, 0xFE96, 0xFE98, 0xFE97], // ت
        0x062B => [0xFE99, 0xFE9A, 0xFE9C, 0xFE9B], // ث
        0x062C => [0xFE9D, 0xFE9E, 0xFEA0, 0xFE9F], // ج
        0x062D => [0xFEA1, 0xFEA2, 0xFEA4, 0xFEA3], // ح
        0x062E => [0xFEA5, 0xFEA6, 0xFEA8, 0xFEA7], // خ
        0x062F => [0xFEA9, 0xFEAA, 0xFEAA, 0xFEA9], // د
        0x0630 => [0xFEAB, 0xFEAC, 0xFEAC, 0xFEAB], // ذ
        0x0631 => [0xFEAD, 0xFEAE, 0xFEAE, 0xFEAD], // ر
        0x0632 => [0xFEAF, 0xFEB0, 0xFEB0, 0xFEAF], // ز
        0x0633 => [0xFEB1, 0xFEB2, 0xFEB4, 0xFEB3], // س
        0x0634 => [0xFEB5, 0xFEB6, 0xFEB8, 0xFEB7], // ش
        0x0635 => [0xFEB9, 0xFEBA, 0xFEBC, 0xFEBB], // ص
        0x0636 => [0xFEBD, 0xFEBE, 0xFEC0, 0xFEBF], // ض
        0x0637 => [0xFEC1, 0xFEC2, 0xFEC4, 0xFEC3], // ط
        0x0638 => [0xFEC5, 0xFEC6, 0xFEC8, 0xFEC7], // ظ
        0x0639 => [0xFEC9, 0xFECA, 0xFECC, 0xFECB], // ع
        0x063A => [0xFECD, 0xFECE, 0xFED0, 0xFECF], // غ
        0x0641 => [0xFED1, 0xFED2, 0xFED4, 0xFED3], // ف
        0x0642 => [0xFED5, 0xFED6, 0xFED8, 0xFED7], // ق
        0x0643 => [0xFED9, 0xFEDA, 0xFEDC, 0xFEDB], // ك
        0x0644 => [0xFEDD, 0xFEDE, 0xFEE0, 0xFEDF], // ل
        0x0645 => [0xFEE1, 0xFEE2, 0xFEE4, 0xFEE3], // م
        0x0646 => [0xFEE5, 0xFEE6, 0xFEE8, 0xFEE7], // ن
        0x0647 => [0xFEE9, 0xFEEA, 0xFEEC, 0xFEEB], // ه
        0x0648 => [0xFEED, 0xFEEE, 0xFEEE, 0xFEED], // و
        0x0649 => [0xFEEF, 0xFEF0, 0xFEF0, 0xFEEF], // ى
        0x064A => [0xFEF1, 0xFEF2, 0xFEF4, 0xFEF3], // ي
    ];

    // Letters that do not connect to the next character (to their left)
    private static $nonConnecting = [
        0x0621, 0x0622, 0x0623, 0x0624, 0x0625, 0x0627, 0x062F, 0x0630, 0x0631, 0x0632, 0x0648, 0x0649, 0x0629
    ];

    /**
     * Shape Arabic string (join glyphs and reverse direction)
     */
    public static function shape($text)
    {
        if (empty($text)) {
            return '';
        }

        // Split text into array of Unicode code points
        $chars = [];
        $len = mb_strlen($text, 'UTF-8');
        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($text, $i, 1, 'UTF-8');
            $chars[] = mb_ord($char, 'UTF-8');
        }

        $shapedChars = [];
        $count = count($chars);

        for ($i = 0; $i < $count; $i++) {
            $curr = $chars[$i];

            if (!isset(self::$map[$curr])) {
                // Not an Arabic letter, keep as is
                $shapedChars[] = $curr;
                continue;
            }

            // Check for Lam-Alef ligature
            if ($curr == 0x0644 && $i + 1 < $count) {
                $next = $chars[$i + 1];
                $ligature = null;
                if ($next == 0x0622) $ligature = [0xFEF5, 0xFEF6]; // لآ
                elseif ($next == 0x0623) $ligature = [0xFEF7, 0xFEF8]; // لأ
                elseif ($next == 0x0625) $ligature = [0xFEF9, 0xFEFA]; // لإ
                elseif ($next == 0x0627) $ligature = [0xFEFB, 0xFEFC]; // لا

                if ($ligature !== null) {
                    $prev = ($i > 0) ? $chars[$i - 1] : null;
                    $connectsPrev = ($prev !== null && isset(self::$map[$prev]) && !in_array($prev, self::$nonConnecting));
                    
                    $shapedChars[] = $connectsPrev ? $ligature[1] : $ligature[0];
                    $i++; // skip next char
                    continue;
                }
            }

            // Determine connection states
            $prev = ($i > 0) ? $chars[$i - 1] : null;
            $next = ($i + 1 < $count) ? $chars[$i + 1] : null;

            $connectsPrev = ($prev !== null && isset(self::$map[$prev]) && !in_array($prev, self::$nonConnecting));
            $connectsNext = ($next !== null && isset(self::$map[$next]) && !in_array($curr, self::$nonConnecting));

            if ($connectsPrev && $connectsNext) {
                $form = 2; // medial
            } elseif ($connectsPrev) {
                $form = 1; // final
            } elseif ($connectsNext) {
                $form = 3; // initial
            } else {
                $form = 0; // isolated
            }

            $shapedChars[] = self::$map[$curr][$form];
        }

        // Convert back to UTF-8
        $shapedText = '';
        foreach ($shapedChars as $code) {
            $shapedText .= mb_chr($code, 'UTF-8');
        }

        return self::reverseRtl($shapedText);
    }

    /**
     * Reverse Arabic text for correct RTL visual display while keeping LTR text (English & numbers) intact
     */
    private static function reverseRtl($text)
    {
        $lines = explode("\n", $text);
        $reversedLines = [];

        foreach ($lines as $line) {
            // Regex splits line into Arabic character runs and non-Arabic runs
            preg_match_all('/[\x{0600}-\x{06FF}\x{FE70}-\x{FEFF}]+|[^[\x{0600}-\x{06FF}\x{FE70}-\x{FEFF}]+/u', $line, $matches);
            $parts = $matches[0];
            
            $reversedParts = [];
            foreach ($parts as $part) {
                if (preg_match('/[\x{0600}-\x{06FF}\x{FE70}-\x{FEFF}]/u', $part)) {
                    // Arabic segment: reverse characters
                    $reversedParts[] = self::utf8Strrev($part);
                } else {
                    // English/Number segment: preserve order
                    $reversedParts[] = $part;
                }
            }

            // Reverse overall segment ordering to handle right-to-left layout flow
            $reversedLines[] = implode('', array_reverse($reversedParts));
        }

        return implode("\n", $reversedLines);
    }

    private static function utf8Strrev($str)
    {
        $rev = '';
        $len = mb_strlen($str, 'UTF-8');
        for ($i = $len - 1; $i >= 0; $i--) {
            $rev .= mb_substr($str, $i, 1, 'UTF-8');
        }
        return $rev;
    }
}
