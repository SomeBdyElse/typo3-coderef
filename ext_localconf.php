<?php

defined('TYPO3') or die();

// Add processor to replace dynamic database record uid in the site configuration
$GLOBALS['TYPO3_CONF_VARS']['SYS']['yamlLoader']['placeholderProcessors'][\SomeBdyElse\Coderef\PlaceholderProcessor\Coderef::class] = [];
