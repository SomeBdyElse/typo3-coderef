<?php

declare(strict_types=1);

namespace SomeBdyElse\Coderef\Configuration;

use TYPO3\CMS\Core\Configuration\Event\AfterTcaCompilationEvent;

class TcaService
{
    protected CoderefConfigurationService $coderefConfigurationService;

    public function __construct(CoderefConfigurationService $coderefConfigurationService)
    {
        $this->coderefConfigurationService = $coderefConfigurationService;
    }

    public function addCoderefTcaFields(AfterTcaCompilationEvent $event): void
    {
        $tca = $event->getTca();

        $tables = $this->coderefConfigurationService->getTables();
        foreach ($tables as $table) {
            $this->addColumns($tca, $table);
            $this->addColumnsToTypes($tca, $table);
        }
        $event->setTca($tca);
    }

    protected function addColumns(array &$tca, string $table)
    {
        $tca[$table]['columns']['tx_coderef_identifier'] = [
            'label' => 'LLL:EXT:coderef/Resources/Private/Language/locallang_backend.xlf:tx_coderef_identifier',
            'l10n_mode' => 'exclude',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
            ],
        ];
    }

    protected function addColumnsToTypes(array &$tca, string $table)
    {
        foreach ($tca[$table]['types'] as $type => &$typeDetails) {
            if (!isset($typeDetails['showitem'])) {
                continue;
            }
            $typeDetails['showitem'] .= ',tx_coderef_identifier';
        }
    }
}
