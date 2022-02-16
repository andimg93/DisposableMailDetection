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
    'https://gist.githubusercontent.com/adamloving/4401361/raw/temporary-email-address-domains'         => TYPE_TEXT,
    'https://raw.githubusercontent.com/vboctor/disposable_email_checker/master/data/domains.txt'        => TYPE_TEXT,
    'https://gist.githubusercontent.com/tbrianjones/5992856/raw/free_email_provider_domains.txt'        => TYPE_TEXT,
    'https://raw.githubusercontent.com/Propaganistas/Laravel-Disposable-Email/master/domains.json'      => TYPE_JSON,
    'https://gist.githubusercontent.com/michenriksen/8710649/raw/disposable-email-provider-domains'     => TYPE_TEXT,
];

$disposableMailAddresses = include __DIR__ . '/disposable-mails-blacklist.inc.php';

require_once __DIR__ . '/Helper.php';
$helper = new Helper();

foreach ($sources as $source => $sourceType) {
    if ($sourceType === TYPE_PHP) {
        // We could just do an eval on the returned php file/array but this would be risky - Dont want to trust the source so use regex
        preg_match_all('/\'(.*)\',/', $helper->fetchContent($source), $matches, PREG_SET_ORDER, 0);
        $disposableMailAddresses = array_merge($disposableMailAddresses, array_column($matches, 1));

        continue;
    }

    if ($sourceType === TYPE_JSON) {
        $disposableMailAddresses = array_merge($disposableMailAddresses, json_decode($helper->fetchContent($source)));

        continue;
    }

    if ($sourceType === TYPE_TEXT) {
        $separator = '---#|#---';
        $disposableMailAddresses = array_merge($disposableMailAddresses, explode($separator, str_replace([
            "\r\n",
            "\r",
            "\n"
        ], $separator, $helper->fetchContent($source))));

        continue;
    }

    throw new \RuntimeException('Unknown source type: "' . $sourceType . '" for source: "' . $source . '"');
}

$helper->writeLookup($disposableMailAddresses);