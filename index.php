<?php

require_once __DIR__ . '/includes/common.php';

$userId = $_ENV['RUNTRIP_USER_ID'];
$journalId = getLatestJournalId($userId);

logging('Polling started.');
while (true) {
    try {
        $journals = getNewJournals($userId, $journalId);
        foreach ($journals as $journal) {
            $result = tweetJournal($journal);
            logging($result);

            $journalId = $journal['id'];
            setLatestJournalId($userId, $journalId);
        }
    } catch (Throwable $t) {
        logging($t);
    } catch (Exception $e) {
        logging($e);
    }

    sleep($_ENV['CHECK_INTERVAL']);
}
