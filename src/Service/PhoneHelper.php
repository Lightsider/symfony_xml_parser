<?php

namespace App\Service;

class PhoneHelper
{
    /**
     *
     * @param string $phone
     * @param string|array $format
     * @param string $mask
     * @return string
     */
    static function phoneFormat(
        $phone,
        $format = [
            '7' => '###-##-##',
            '10' => '+7-###-###-####',
            '11' => '#-###-###-####'
        ],
        $mask = '#'
    ) {

        if (empty($phone)) {
            return '';
        }

        $phone = trim($phone);
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (is_array($format)) {
            if (array_key_exists(strlen($phone), $format)) {
                $format = $format[strlen($phone)];
            } else {
                return $phone;
            }
        }

        $pattern = '/' . str_repeat('([0-9])?', substr_count($format, $mask)) . '(.*)/';

        $format = preg_replace_callback(
            str_replace('#', $mask, '/([#])/'),
            function () use (&$counter) {
                return '${' . (++$counter) . '}';
            },
            $format
        );

        return ($phone) ? trim(preg_replace($pattern, $format, $phone, 1)) : false;
    }
}