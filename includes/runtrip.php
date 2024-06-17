<?php

function getLatestJournal(int $userId): array
{
    $journals = getJournals($userId);
    return $journals[0]['journal'];
}

function getNewJournals(int $userId, int $journalId): Generator
{
    $journals = getJournals($userId);

    $i = count($journals);
    $skip = true;

    while ($i) {
        $journal = $journals[--$i]['journal'];

        if (!$skip) {
            yield $journal;
        }

        if ($journal['id'] === $journalId) {
            $skip = false;
        }
    }
}

function getJournals(int $userId): array
{
    $html = file_get_contents('https://runtrip.jp/users/' . $userId);
    $json = substrBetween($html, '<script id="__NEXT_DATA__" type="application/json">', '</script>');
    $array = json_decode($json, true);

    $uri = 'https://api.runtrip.jp/v1/users/' . $userId . '/journals?pageNumber=0&pageSize=9:$get';
    return $array['props']['pageProps']['swr']['fallback'][$uri]['journals'];
}

function createTweetText(string $text, string $tags, string $url): string
{
    $suffix = '';
    if ($tags !== '') {
        $suffix .= "\n" . $tags;
    }

    if ($url !== '') {
        $suffix .= "\n" . $url;
    }

    return limitTweetText($text, suffix: $suffix);
}
