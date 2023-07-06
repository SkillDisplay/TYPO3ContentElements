<?php

(function (string $extKey) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        "@import 'EXT:" . $extKey . "/Configuration/PageTSconfig/*.tsconfig'"
    );

    $icons = [
        'skill',
        'skillset',
    ];
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Imaging\IconRegistry::class
    );
    foreach ($icons as $icon) {
        $iconRegistry->registerIcon(
            'skilldisplay-' . $icon,
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            [
                'source' => 'EXT:' . $extKey . '/Resources/Public/Icons/' . $icon . '.png',
            ]
        );
    }

    // todo v11?
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem'][$extKey] =
        \SkillDisplay\SkilldisplayContent\Backend\Preview::class;
})('skilldisplay_content');
