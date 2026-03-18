<?php

namespace Debug;

class DebugTools {
    public static function log($object, $die = false): void {
        echo '<pre>';
        print_r($object);
        echo '</pre>';
        if ($die) die();
        return;
    }
}