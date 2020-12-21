<?php

namespace AndreasMaximilianGerum\DisposableMailDetection;

class Verifier
{
    /**
     * Checks whether the mail address is a disposable one
     * Consider that this method will also return true (disposable mail) while an invalid mail address was provided!
     *
     * @param string $emailToValidate
     *
     * @return bool
     */
    public static function isDisposableMail(string $emailToValidate): bool
    {
        $disposableMailsLookup = include __DIR__ . '/generated/disposable-mails-lookup.inc.php';

        return filter_var($emailToValidate, FILTER_VALIDATE_EMAIL) === false
            || isset($disposableMailsLookup[substr(strrchr($emailToValidate, '@'), 1)]);
    }
}