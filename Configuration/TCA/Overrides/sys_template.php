<?php

(function (string $extensionKey, string $tableName) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $extensionKey,
        'Configuration/TypoScript/',
        'SkillDisplay'
    );
})('skilldisplay_content', 'sys_template');
