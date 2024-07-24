<?php

function substrBetween(string $str, string $start, string $end): string
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

function serializeFile(string $filename, mixed $data): void
{
    file_put_contents($filename, serialize($data));
}

function unserializeFile(string $filename): mixed
{
    if (!file_exists($filename)) {
        return null;
    }

    return unserialize(file_get_contents($filename));
}

function logging(mixed $value): void
{
    $message = date('Y/m/d H:i:s') . "\n" . print_r($value, true) . "\n";
    file_put_contents(__DIR__ . '/../debug.log', $message, FILE_APPEND);
}
