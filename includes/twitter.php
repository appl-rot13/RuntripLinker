<?php

const MAX_TWEET_LENGTH = 280;

function sanitizeHashtag(string $hashtag): string
{
    $letters = '\p{L}\p{M}';
    $numerals = '\p{Nd}';
    $specialChars =
        '\x{005F}\x{200C}\x{200D}\x{A67E}\x{05BE}\x{05F3}\x{05F4}\x{FF5E}' .
        '\x{301C}\x{309B}\x{309C}\x{30A0}\x{30FB}\x{3003}\x{0F0B}\x{0F0C}\x{00B7}';

    $hashtag = preg_replace('/[^' . $letters . $numerals . $specialChars . ']/u', '', $hashtag);
    if (!preg_match('/[' . $letters . ']/u', $hashtag)) {
        return '';
    }

    return '#' . $hashtag;
}

function sanitizeTweetText(string $text): string
{
    $pattern = Twitter\Text\Regex::getInvalidCharactersMatcher();
    return preg_replace($pattern, '', $text);
}

function limitTweetText(string $text, string $ellipsis = '…', string $prefix = '', string $suffix = ''): string
{
    $str = sanitizeTweetText($prefix . $text . $suffix);

    $parser = new Twitter\Text\Parser();
    $result = $parser->parseTweet($str);
    if ($result->valid) {
        return $str;
    }

    $trimLength = floor((MAX_TWEET_LENGTH - $result->weightedLength) / 2) - mb_strlen($ellipsis);
    $text = mb_substr($text, 0, $trimLength);
    if ($text === '') {
        return '';
    }

    return limitTweetText($text . $ellipsis, $ellipsis, $prefix, $suffix);
}

function tweet(string $text, string $image, string $accessToken, string $accessTokenSecret): array
{
    $connection = new Abraham\TwitterOAuth\TwitterOAuth(
        $_ENV['TWITTER_API_KEY'],
        $_ENV['TWITTER_API_KEY_SECRET'],
        $accessToken,
        $accessTokenSecret);
    $connection->setDecodeJsonAsArray(true);

    $parameters = ['text' => $text];

    if ($image) {
        $connection->setApiVersion('1.1');
        $media = $connection->upload('media/upload', ['media' => $image]);
        if (!isset($media['media_id_string'])) {
            return $media;
        }

        $parameters['media'] = ['media_ids' => [$media['media_id_string']]];
    }

    $connection->setApiVersion('2');
    return $connection->post('tweets', $parameters, ['jsonPayload' => true]);
}
