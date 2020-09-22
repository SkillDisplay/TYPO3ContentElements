<?php

(function (string $extensionKey, string $tableName) {
    $languagePath = 'LLL:EXT:' . $extensionKey . '/Resources/Private/Language/locallang_tca.xlf:' . $tableName . '.';

    \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($GLOBALS['TCA'][$tableName], [
        'columns' => [
            'CType' => [
                'config' => [
                    'itemGroups' => [
                        'skilldisplay' => $languagePath . 'CType.itemGroups.skilldisplay',
                    ],
                ],
            ],
            'skilldisplay_skills' => [
                'exclude' => 1,
                'label' => $languagePath . 'skilldisplay_skills',
                'description' => $languagePath . 'skilldisplay_skills.description',
                'config' => [
                    'type' => 'input',
                    'eval' => 'required',
                    'size' => 10,
                ],
            ],
            'skilldisplay_skillset' => [
                'exclude' => 1,
                'label' => $languagePath . 'skilldisplay_skillset',
                'description' => $languagePath . 'skilldisplay_skillset.description',
                'config' => [
                    'type' => 'input',
                    'eval' => 'int,required',
                    'size' => 10,
                ],
            ],
        ],
    ]);
})('skilldisplay', 'tt_content');
