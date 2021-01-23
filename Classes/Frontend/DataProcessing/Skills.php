<?php

declare(strict_types=1);

namespace SkillDisplay\Typo3Extension\Frontend\DataProcessing;

/*
 * Copyright (C) 2020 Daniel Siepmann <coding@daniel-siepmann.de>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301, USA.
 */

use SkillDisplay\PHPToolKit\Api\Skill;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class Skills implements DataProcessorInterface
{
    /**
     * @var Skill
     */
    protected $skillApi;

    public function __construct(
        Skill $skillApi
    ) {
        $this->skillApi = $skillApi;
    }

    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        $as = $cObj->stdWrapValue('as', $processorConfiguration, 'skills');
        $skillIds = GeneralUtility::intExplode(
            ',',
            $cObj->stdWrapValue('skills', $processorConfiguration, ''),
            true
        );
        $skills = [];

        foreach ($skillIds as $skillId) {
            try {
                $skills[] = $this->skillApi->getById($skillId);
            } catch (\Exception $e) {
                continue;
            }
        }

        $processedData[$as] = $skills;
        return $processedData;
    }
}
