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

    // todo v12?
    // TYPO3\CMS\Backend\View\Event\PageContentPreviewRenderingEvent
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem'][$extKey] =
        \SkillDisplay\SkilldisplayContent\Backend\Preview::class;

    // Inject skillsets into valuepicker form
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord']
    [\SkillDisplay\SkilldisplayContent\FormDataProvider\ValuePickerItemDataProvider::class] = [
        'depends' => [
            \TYPO3\CMS\Backend\Form\FormDataProvider\TcaInputPlaceholders::class,
        ],
    ];


    $caches = [
        'sdcontent',
    ];
    foreach ($caches as $cacheName) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cacheName] = [
            'frontend' => \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class,
            'backend' => \TYPO3\CMS\Core\Cache\Backend\SimpleFileBackend::class,
            'options' => [
                'defaultLifetime' => 2592000, // 30 days
            ],
            'groups' => ['lowlevel']
        ];
    }

})('skilldisplay_content');
