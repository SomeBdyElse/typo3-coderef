<?php

declare(strict_types=1);

namespace SomeBdyElse\Coderef\PlaceholderProcessor;

use SomeBdyElse\Coderef\Service\CoderefLookupService;
use TYPO3\CMS\Core\Configuration\Processor\Placeholder\PlaceholderProcessorInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Coderef implements PlaceholderProcessorInterface
{
    public function canProcess(string $placeholder, array $referenceArray): bool
    {
        return is_string($placeholder) && (str_contains($placeholder, '%coderef('));
    }

    public function process(string $value, array $referenceArray)
    {
        [$table, $coderef] = explode(':', $value);

        $codeRefService = GeneralUtility::makeInstance(CoderefLookupService::class);
        $uid = $codeRefService->getUidForCoderef($table, $coderef);
        if (!isset($uid)) {
            throw new \UnexpectedValueException('Coderef not found: ' . $value, 1621033619105);
        }

        return $uid;
    }
}
