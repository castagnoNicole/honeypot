<?php
function is_challenge_xss($string): bool
{
    libxml_use_internal_errors(true);
    if ($xml = simplexml_load_string("<root>$string</root>")){
        return $xml->children()->count() !== 0;
    }
    return false;
}
