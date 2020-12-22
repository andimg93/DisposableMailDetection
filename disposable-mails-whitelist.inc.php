<?php

// Here, mail addresses are entered that are not disposable mail addresses but are incorrectly identified as such by the defined sources.
// Performance is not an issue while generating the lookup, so it is sufficient to just define the providers as value based and thus perform an in_array check
return [
    'gmx.es',
    'gmx.us',
];