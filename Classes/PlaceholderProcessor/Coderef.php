<?php

declare(strict_types=1);

namespace SomeBdyElse\Coderef\PlaceholderProcessor;

use Doctrine\DBAL\Exception\TableNotFoundException;
use TYPO3\CMS\Core\Configuration\Processor\Placeholder\PlaceholderProcessorInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Coderef implements PlaceholderProcessorInterface
{
    public function canProcess(string $placeholder, array $referenceArray): bool
    {
        return is_string($placeholder) && (strpos($placeholder, '%coderef(') !== false);
    }

    public function process(string $value, array $referenceArray)
    {
        [$table, $coderef] = explode(':', $value);

        try {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
            $uid = $queryBuilder
                ->select('uid')
                ->from($table)
                ->where(
                    $queryBuilder->expr()->eq('tx_coderef_identifier', $queryBuilder->createNamedParameter($coderef))
                )
                ->execute()->fetchOne();
        } catch (TableNotFoundException $tableNotFoundException) {
            throw new \UnexpectedValueException('Codref table not found: ' . $table, 1713194130145);
        }

        if ($uid === false) {
            throw new \UnexpectedValueException('Coderef not found: ' . $value, 1621033619105);
        }

        return $uid;
    }
}
