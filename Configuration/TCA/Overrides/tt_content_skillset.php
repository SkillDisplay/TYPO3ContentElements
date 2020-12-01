<?php

(function (string $extensionKey, string $tableName, string $contentType) {
    $languagePath = 'LLL:EXT:' . $extensionKey . '/Resources/Private/Language/locallang_tca.xlf:' . $tableName . '.';

    \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($GLOBALS['TCA'][$tableName], [
        'ctrl' => [
            'typeicon_classes' => [
                $contentType => 'skilldisplay-skillset',
            ],
        ],
        'types' => [
            $contentType => [
                'showitem' => implode(',', [
                    '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general',
                    '--palette--;;general',
                    'skilldisplay_skillset',
                    'skilldisplay_campaign',
                    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance',
                    '--palette--;;frames',
                    '--palette--;;appearanceLinks',
                    '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language',
                    '--palette--;;language',
                    '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access',
                    '--palette--;;hidden',
                    '--palette--;;access',
                    '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories',
                    'categories',
                    '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes',
                    'rowDescription',
                    '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended',
                ]),
            ],
        ],
    ]);

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
        $tableName,
        'CType',
        [
            $languagePath . $contentType,
            $contentType,
            'skilldisplay-skillset',
            'skilldisplay'
        ]
    );
})('skilldisplay', 'tt_content', 'skilldisplay_skillset');
