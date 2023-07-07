<?php

(function (string $extensionKey) {
    $languagePath = 'LLL:EXT:' . $extensionKey . '/Resources/Private/Language/locallang_tca.xlf:tt_content.';

    \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($GLOBALS['TCA']['tt_content'], [
        'columns' => [
            'CType' => [
                'config' => [
                    'itemGroups' => [
                        'skilldisplay' => $languagePath . 'CType.itemGroups.skilldisplay',
                    ],
                ],
            ],
            'skilldisplay_skills' => [
                'exclude' => true,
                'label' => $languagePath . 'skilldisplay_skills',
                'description' => $languagePath . 'skilldisplay_skills.description',
                'config' => [
                    'type' => 'input',
                    'eval' => 'required',
                    'size' => 10,
                ],
            ],
            'skilldisplay_skillset' => [
                'exclude' => true,
                'label' => $languagePath . 'skilldisplay_skillset',
                'description' => $languagePath . 'skilldisplay_skillset.description',
                'config' => [
                    'type' => 'input',
                    'eval' => 'required',
                    'size' => 10,
                    'valuePicker' => [
                        'mode' => 'append',
                        // filled by ValuePickerItemDataProvider
                        'items' => [],
                    ],
                ],
            ],
            'skilldisplay_campaign' => [
                'exclude' => true,
                'label' => $languagePath . 'skilldisplay_campaign',
                'description' => $languagePath . 'skilldisplay_campaign.description',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'itemsProcFunc' => \SkillDisplay\SkilldisplayContent\TcaEnhancer::class
                        . '->' . 'getCampaignsForTCA',
                    'items' => []
                ]
            ],
        ],
    ]);
})('skilldisplay_content');
