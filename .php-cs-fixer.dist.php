<?php
require_once __DIR__ . '/vendor/autoload.php';

$config = \TYPO3\CodingStandards\CsFixerConfig::create();
$config->getFinder()
    ->in(__DIR__)
;

return $config;
