<?php

declare(strict_types=1);

namespace SomeBdyElse\Coderef\Database;

use SomeBdyElse\Coderef\Configuration\CoderefConfigurationService;
use TYPO3\CMS\Core\Database\Event\AlterTableDefinitionStatementsEvent;

class DatabaseSchemaService
{
    protected CoderefConfigurationService $coderefConfigurationService;

    public const SQL_CREATE_TABLE_TEMPLATE = '
        CREATE TABLE %s (
            %s varchar(255)
        );
    ';

    public function __construct(CoderefConfigurationService $coderefConfigurationService)
    {
        $this->coderefConfigurationService = $coderefConfigurationService;
    }

    public function addCoderefDatabaseFields(AlterTableDefinitionStatementsEvent $event): void
    {
        $tables = $this->coderefConfigurationService->getTables();

        $sql = '';
        foreach ($tables as $table) {
            $sql .= sprintf(self::SQL_CREATE_TABLE_TEMPLATE, $table, 'tx_coderef_identifier');
        }

        $event->addSqlData($sql);
    }
}
