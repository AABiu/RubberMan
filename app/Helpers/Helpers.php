<?php

if (!function_exists('time_format')) {

    /**
     * @param $str
     * @return bool
     */
    function time_format($time = 0)
    {
        $tmp = time() - $time;
        if ($tmp && $tmp <= 60 * 60) {
            return ceil($tmp / 60) + 1 . '分钟前';
        } elseif ($tmp > 60 * 60 && $tmp < 60 * 60 * 24) {
            return ceil($tmp / 60 / 60) + 1 . '小时前';
        } elseif ($tmp > 60 * 60 * 24 && $tmp < 60 * 60 * 24 * 10) {
            return ceil($tmp / 60 / 60 / 24) + 1 . '天前';
        } else {
            return date('Y-m-d', $time);
        }
    }
}

if (!function_exists('distanceByLngAndLat')) {
    function distanceByLngAndLat($point1, $point2)
    {
        $earthRadius = 6367000;
        $lat1 = ($point1['lat'] * pi()) / 180;
        $lng1 = ($point1['lng'] * pi()) / 180;
        $lat2 = ($point2['lat'] * pi()) / 180;
        $lng2 = ($point2['lng'] * pi()) / 180;

        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        return round($calculatedDistance);
    }
}