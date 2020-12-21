##  PHP - Disposable Email Addresses Detection

After I had to realize that there is no good, fast and well maintained package for the detection of disposable mail addresses - I have now created one myself. 

About **130k disposable mail addresses** are currently stored. Not a single address exists twice - **No duplicates**!
PHP related there is no better performing package for validation. This is based on a simple isset check for the relevant mail part, which would be much faster than an in_array one - Particularly with such a large lookup/array.
The advantage of isset compared to in_array is briefly explained as follows:
- It uses an O(1) hash search on the key whereas in_array must check every value until it finds a match
- Being an opcode, it has less overhead than calling the in_array built-in function

The advantages of this package are therefore obvious:
- Outstanding performance
- The package with presumably the most deposited disposable mail addresses
- No dependencies to various other packages

Use it via composer:
```bash
composer require andimg93/disposable-mail-detection
```

**Usage**
```php
<?php

use AndreasMaximilianGerum\DisposableMailDetection\Verifier;

if (Verifier::isDisposableMail($emailToValidate)) {
    // It's a disposable mail address!
    // Throw exception or stop processing, nobody want fake mail providers.
}

// Else it is a proper mail address - Do what you want to do in this case.
```

### Your help is wanted
Regarding the sources for all the fake mail providers, have a look at [updater.php](https://github.com/andimg93/DisposableMailDetection/blob/main/updater.php "updater.php")

If you can remember any other good source of fake mail providers, feel free to add them there and post a PR.

Should you know no other sources, but got fake mail providers that are still missing? Then please add them to the list in [disposable-mails-blacklist.inc.php](https://github.com/andimg93/DisposableMailDetection/blob/main/disposable-mails-blacklist.inc.php "disposable-mails-blacklist.inc.php"). Just post a PR for it, I will look at it as soon as possible - Thanks for your help!
