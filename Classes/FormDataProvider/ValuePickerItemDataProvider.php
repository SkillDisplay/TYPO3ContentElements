<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace SkillDisplay\SkilldisplayContent\FormDataProvider;

use SkillDisplay\PHPToolKit\Api\SkillSet;
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;

/**
 * Inject available skill sets a valuepicker form
 */
class ValuePickerItemDataProvider implements FormDataProviderInterface
{
    private SkillSet $skillSetApi;

    public function __construct(SkillSet $skillSetApi)
    {
        $this->skillSetApi = $skillSetApi;
    }

    /**
     * Add sys_domains into $result data array
     *
     * @param array $result Initialized result array
     * @return array Result filled with more data
     */
    public function addData(array $result): array
    {
        if ($result['tableName'] === 'tt_content' && isset($result['processedTca']['columns']['skilldisplay_skillset'])) {
            $skillSets = $this->skillSetApi->getAll();
            foreach ($skillSets as $skillSet) {
                $result['processedTca']['columns']['skilldisplay_skillset']['config']['valuePicker']['items'][] =
                    [
                        $skillSet->getName(),
                        $skillSet->getId(),
                    ];
            }
        }
        return $result;
    }
}
