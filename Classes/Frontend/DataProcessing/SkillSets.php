<?php

declare(strict_types=1);

namespace SkillDisplay\SkilldisplayContent\Frontend\DataProcessing;

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

use Exception;
use SkillDisplay\PHPToolKit\Api\SkillSet;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class SkillSets implements DataProcessorInterface
{
    protected SkillSet $skillSetApi;

    public function __construct(
        SkillSet $skillSetApi
    ) {
        $this->skillSetApi = $skillSetApi;
    }

    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {

        $as = $cObj->stdWrapValue('as', $processorConfiguration, 'skillSets');
        $skillSetsIdsRaw = (string)$cObj->stdWrapValue('skillSets', $processorConfiguration);
        $skillSetIds = GeneralUtility::intExplode(
            ',',
            $skillSetsIdsRaw,
            true
        );

        $enableCache = $processorConfiguration['cache'] ?? false;
        if ($enableCache) {
            $cacheKey = md5($skillSetsIdsRaw) . $as;
            /** @var VariableFrontend $cache */
            $cache = GeneralUtility::makeInstance(CacheManager::class)->getCache('sdcontent');
            if ($cache->has($cacheKey)) {
                $data = $cache->get($cacheKey);
            } else {
                $data = $this->generateData($skillSetIds);
                $cache->set($cacheKey, $data);
            }
        } else {
            $data = $this->generateData($skillSetIds);
        }
        $processedData[$as] = $data;
        return $processedData;
    }

    protected function generateData(array $skillSetIds): array
    {
        $skillSets = [];
        foreach ($skillSetIds as $skillSetId) {
            try {
                $skillSets[] = $this->skillSetApi->getById($skillSetId, true);
            } catch (Exception $e) {
                continue;
            }
        }
        return $skillSets;
    }
}
