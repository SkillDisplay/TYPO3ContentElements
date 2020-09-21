<?php

(function (string $extensionKey, string $tableName) {
    $languagePath = 'LLL:EXT:' . $extensionKey . '/Resources/Private/Language/locallang_be.xlf:' . $tableName . '.';

    \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($GLOBALS['SiteConfiguration']['site'], [
        'columns' => [
            'skilldisplay_user_secret' => [
                'label' => $languagePath . 'skilldisplay_user_secret',
                'config' => [
                    'type' => 'input',
                ],
            ],
            'skilldisplay_api_key' => [
                'label' => $languagePath . 'skilldisplay_api_key',
                'config' => [
                    'type' => 'input',
                ],
            ],
            'skilldisplay_verifier_id' => [
                'label' => $languagePath . 'skilldisplay_verifier_id',
                'config' => [
                    'type' => 'input',
                ],
            ],
        ],
        'types' => [
            '0' => [
                'showitem' => $GLOBALS['SiteConfiguration']['site']['types']['0']['showitem']
                . ', ' . implode(',', [
                    '--div--;' . $languagePath . 'div.skilldisplay',
                    'skilldisplay_user_secret',
                    'skilldisplay_api_key',
                    'skilldisplay_verifier_id',
                ]),
            ],
        ],
    ]);
})('skilldisplay', 'site');
