<?php

function substrBetween(string $str, string $start, string $end)
{
    $startIndex = mb_strrpos($str, $start);
    if ($startIndex === false) {
        return '';
    }

    $startIndex += mb_strlen($start);
    $endIndex = mb_strrpos($str, $end, $startIndex);
    if ($endIndex === false) {
        return '';
    }

    return mb_substr($str, $startIndex, $endIndex - $startIndex);
}

function logging(mixed $value): void
{
    $message = date('Y/m/d H:i:s') . "\n" . print_r($value, true) . "\n";
    file_put_contents('debug.log', $message, FILE_APPEND);
}
