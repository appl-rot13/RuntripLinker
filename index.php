<?php

require_once __DIR__ . '/includes/common.php';

$userId = $_ENV['RUNTRIP_USER_ID'];
$journalId = getLatestJournal($userId)['id'];

while (true) {
    $journals = getNewJournals($userId, $journalId);
    foreach ($journals as $journal) {
        $journalId = $journal['id'];

        $tags = implode(' ', array_filter(array_map('sanitizeHashtag', $journal['tags'])));
        $journalUrl = 'https://runtrip.jp/journals/' . $journalId;
        $text = createTweetText($journal['description'], $tags, $journalUrl);
        if ($text === '') {
            continue;
        }

        $imageUrl = $journal['imageUrls'][0];
        $result = tweet($text, $imageUrl, $_ENV['TWITTER_ACCESS_TOKEN'], $_ENV['TWITTER_ACCESS_TOKEN_SECRET']);
        logging($result);
    }

    sleep($_ENV['CHECK_INTERVAL']);
}
