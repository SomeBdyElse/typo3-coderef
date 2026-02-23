<?php

namespace SomeBdyElse\Coderef\Service;

use Doctrine\DBAL\Exception\TableNotFoundException;
use TYPO3\CMS\Core\Database\ConnectionPool;

class CoderefLookupService
{
    public function __construct(
        protected ConnectionPool $connectionPool
    ) {}

    public function getUidForCoderef(string $table, string $coderef): ?int
    {
        try {
            $queryBuilder = $this->connectionPool->getQueryBuilderForTable($table);
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
            return null;
        }

        return $uid;
    }
}
