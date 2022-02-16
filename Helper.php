<?php

namespace AndreasMaximilianGerum\DisposableMailDetection;

class Helper
{
    private const DISPOSABLE_MAILS_LOOKUP_PATH = __DIR__ . '/generated/disposable-mails-lookup.inc.php';
    private const INDENT_STRING = '    ';

    /**
     * @param array $disposableMailAddresses
     */
    public function writeLookup(array $disposableMailAddresses): void
    {
        if (file_exists(self::DISPOSABLE_MAILS_LOOKUP_PATH)) {
            $alreadyExistingLookup = include self::DISPOSABLE_MAILS_LOOKUP_PATH;
            $disposableMailAddresses = array_merge($disposableMailAddresses, array_keys($alreadyExistingLookup));
        }

        file_put_contents(
            self::DISPOSABLE_MAILS_LOOKUP_PATH,
            '<?php'
            . PHP_EOL . '// !!! CAUTION !!!'
            . PHP_EOL . '// >>> This file was automatized generated and should not be, in any case, manually modified! <<<'
            . PHP_EOL . 'return ['
            . PHP_EOL . $this->processIndented(
                $this->prepareDisposableMailLookup($disposableMailAddresses)
            ) . '];'
        );
    }

    /**
     * @param array $disposableMailAddresses
     *
     * @return array
     */
    private function prepareDisposableMailLookup(array $disposableMailAddresses): array
    {
        $whitelistedMailProviders = include __DIR__ . '/disposable-mails-whitelist.inc.php';

        $preparedLookup = [];
        foreach ($disposableMailAddresses as $disposableMailAddress) {
            $disposableMailAddress = strtolower(trim($disposableMailAddress));
            $disposableMailAddress = trim($disposableMailAddress, '@');

            // Performance is not an issue while generating the lookup, so it is sufficient to just define the providers as value based and thus perform an in_array check
            if (!in_array($disposableMailAddress, $whitelistedMailProviders, true)) {
                $preparedLookup[$disposableMailAddress] = true;
            }
        }

        ksort($preparedLookup);

        return $preparedLookup;
    }

    /**
     * @param array $config
     * @param int $indentLevel
     *
     * @return string
     */
    private function processIndented(array $config, &$indentLevel = 1): string
    {
        $arrayString = '';

        foreach ($config as $key => $value) {
            $arrayString .= str_repeat(self::INDENT_STRING, $indentLevel);
            $arrayString .= (is_int($key) ? $key : "'" . addslashes($key) . "'") . ' => ';

            if (is_array($value)) {
                if ($value === []) {
                    $arrayString .= '[]' . ",\n";
                } else {
                    $indentLevel++;
                    $arrayString .= '[' . "\n" . $this->processIndented($value, $indentLevel) . str_repeat(self::INDENT_STRING, --$indentLevel) . "],\n";
                }
            } else if (is_object($value) || is_string($value)) {
                $arrayString .= var_export($value, true) . ",\n";
            } else if (is_bool($value)) {
                $arrayString .= ($value ? 'true' : 'false') . ",\n";
            } else if ($value === null) {
                $arrayString .= "null,\n";
            } else {
                $arrayString .= $value . ",\n";
            }
        }

        return $arrayString;
    }

    public function fetchContent($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
}