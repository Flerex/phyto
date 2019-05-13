<?php

function str_begins_with($needle, $haystack) {
    return substr($haystack, 0, strlen($needle)) === $needle;
}
