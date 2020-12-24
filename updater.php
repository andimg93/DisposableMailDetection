<?php
ini_set('memory_limit', '1G');

use AndreasMaximilianGerum\DisposableMailDetection\Helper;

const TYPE_PHP = 'PHP';
const TYPE_JSON = 'JSON';
const TYPE_TEXT = 'TEXT';

$sources = [
    'https://raw.githubusercontent.com/StephaneBour/disposable-email-domains/master/config/domains.php' => TYPE_PHP,
    'https://raw.githubusercontent.com/ivolo/disposable-email-domains/master/index.json'                => TYPE_JSON,
    'https://raw.githubusercontent.com/FGRibreau/mailchecker/master/list.txt'                           => TYPE_TEXT,
    'https://raw.githubusercontent.com/nojacko/email-data-disposable/master/data/disposable.txt'        => TYPE_TEXT,
    'https://raw.githubusercontent.com/MattKetmo/EmailChecker/master/res/throwaway_domains.txt'         => TYPE_TEXT,
    'https://raw.githubusercontent.com/disposable/disposable-email-domains/master/domains.txt'          => TYPE_TEXT,
    'https://raw.githubusercontent.com/nojacko/email-data-disposable/master/bin/disposable.txt'         => TYPE_TEXT,
    'https://gist.githubusercontent.com/adamloving/4401361/raw/temporary-email-address-domains'         => TYPE_TEXT,
];

$disposableMailAddresses = include __DIR__ . '/disposable-mails-blacklist.inc.php';
foreach ($sources as $source => $sourceType) {
    if ($sourceType === TYPE_PHP) {
        // We could just do an eval on the returned php file/array but this would be risky - Dont want to trust the source so use regex
        preg_match_all('/\'(.*)\',/', file_get_contents($source), $matches, PREG_SET_ORDER, 0);
        $disposableMailAddresses = array_merge($disposableMailAddresses, array_column($matches, 1));

        continue;
    }

    if ($sourceType === TYPE_JSON) {
        $disposableMailAddresses = array_merge($disposableMailAddresses, json_decode(file_get_contents($source)));

        continue;
    }

    if ($sourceType === TYPE_TEXT) {
        $disposableMailAddresses = array_merge($disposableMailAddresses, file($source, FILE_IGNORE_NEW_LINES));

        continue;
    }

    throw new \RuntimeException('Unknown source type: "' . $sourceType . '" for source: "' . $source . '"');
}

require_once __DIR__ . '/Helper.php';
$helper = new Helper();
$helper->writeLookup($disposableMailAddresses);