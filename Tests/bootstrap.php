<?php

/*
 * This file is part of the RebolonPagerBundle package.
 *
 * It has been freely inspired by FOSUserBundle
 */

if (file_exists($file = __DIR__.'/autoload.php')) {
    require_once $file;
} elseif (file_exists($file = __DIR__.'/autoload.php.dist')) {
    require_once $file;
}
