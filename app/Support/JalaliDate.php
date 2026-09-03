<?php

declare(strict_types=1);

namespace App\Support;

use Carbon\CarbonInterface;

/**
 * Gregorian → Jalali (Persian solar calendar) conversion for `fa` display
 * dates — Figma's post-meta strings ("۲۰ اردیبهشت ۱۴۰۳") are Jalali, not the
 * Gregorian `Y/m/d` the app previously rendered even on `fa`.
 *
 * The conversion is the standard public-domain algorithm (as shipped by most
 * small Jalali libraries, e.g. jdf.scr.ir), reimplemented here rather than
 * pulled in as a Composer dependency for one call site. `Numerals::localise`
 * still handles digit transliteration, so this class only produces the ASCII
 * day/year and the Persian month name.
 */
final class JalaliDate
{
    private const MONTHS = [
        1 => 'فروردین',
        2 => 'اردیبهشت',
        3 => 'خرداد',
        4 => 'تیر',
        5 => 'مرداد',
        6 => 'شهریور',
        7 => 'مهر',
        8 => 'آبان',
        9 => 'آذر',
        10 => 'دی',
        11 => 'بهمن',
        12 => 'اسفند',
    ];

    /**
     * "۲۰ اردیبهشت ۱۴۰۳" — day, Persian month name, year, digits localised.
     */
    public static function format(CarbonInterface $date): string
    {
        [$year, $month, $day] = self::toJalaliParts($date);

        return Numerals::localise("{$day} ".self::MONTHS[$month]." {$year}");
    }

    /**
     * @return array{0: int, 1: int, 2: int} [jalaliYear, jalaliMonth, jalaliDay]
     */
    private static function toJalaliParts(CarbonInterface $date): array
    {
        return self::gregorianToJalali((int) $date->format('Y'), (int) $date->format('n'), (int) $date->format('j'));
    }

    /**
     * @return array{0: int, 1: int, 2: int}
     */
    private static function gregorianToJalali(int $gy, int $gm, int $gd): array
    {
        $gDaysInMonth = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];

        $gy2 = $gm > 2 ? $gy + 1 : $gy;

        $days = 355666
            + (365 * $gy)
            + intdiv($gy2 + 3, 4)
            - intdiv($gy2 + 99, 100)
            + intdiv($gy2 + 399, 400)
            + $gd
            + $gDaysInMonth[$gm - 1];

        $jy = -1595 + (33 * intdiv($days, 12053));
        $days %= 12053;

        $jy += 4 * intdiv($days, 1461);
        $days %= 1461;

        if ($days > 365) {
            $jy += intdiv($days - 1, 365);
            $days = ($days - 1) % 365;
        }

        if ($days < 186) {
            $jm = 1 + intdiv($days, 31);
            $jd = 1 + ($days % 31);
        } else {
            $jm = 7 + intdiv($days - 186, 30);
            $jd = 1 + (($days - 186) % 30);
        }

        return [$jy, $jm, $jd];
    }
}
