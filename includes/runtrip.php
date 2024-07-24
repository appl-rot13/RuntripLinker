<?php

function getCacheFilename(int $userId): string
{
    return $userId . '.cache';
}

function getLatestJournalId(int $userId): int
{
    $filename = getCacheFilename($userId);
    $journalId = unserializeFile($filename);
    if ($journalId === null) {
        $journalId = getLatestJournal($userId)['id'];
        setLatestJournalId($userId, $journalId);
    }

    return $journalId;
}

function setLatestJournalId(int $userId, int $journalId): void
{
    $filename = getCacheFilename($userId);
    serializeFile($filename, $journalId);
}

function getLatestJournal(int $userId): array
{
    $journals = getJournals($userId);
    return $journals[0]['journal'];
}

function getNewJournals(int $userId, int $journalId): Generator
{
    $journals = getJournals($userId);

    $i = count($journals);
    $take = false;

    while ($i) {
        $journal = $journals[--$i]['journal'];

        if ($take) {
            yield $journal;
        }

        if ($journal['id'] === $journalId) {
            $take = true;
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

function tweetJournal(array $journal): array
{
    $tags = implode(' ', array_filter(array_map('sanitizeHashtag', $journal['tags'])));
    $journalUrl = 'https://runtrip.jp/journals/' . $journal['id'];
    $text = createTweetText($journal['description'], $tags, $journalUrl);
    if ($text === '') {
        return array();
    }

    $imageUrl = $journal['imageUrls'][0];
    return tweet($text, $imageUrl, $_ENV['TWITTER_ACCESS_TOKEN'], $_ENV['TWITTER_ACCESS_TOKEN_SECRET']);
}
