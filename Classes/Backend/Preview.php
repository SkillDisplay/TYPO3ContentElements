<?php

namespace SkillDisplay\Typo3Extension\Backend;

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
use SkillDisplay\PHPToolKit\Api\SkillSet;
use TYPO3\CMS\Backend\View\PageLayoutView;
use TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Preview implements PageLayoutViewDrawItemHookInterface
{
    /**
     * @var Skill
     */
    protected $skillApi;

    /**
     * @var SkillSet
     */
    private $skillSetApi;

    public function __construct(
        Skill $skillApi,
        SkillSet $skillSetApi
    ) {
        $this->skillApi = $skillApi;
        $this->skillSetApi = $skillSetApi;
    }

    /**
     * @return void
     */
    public function preProcess(
        PageLayoutView &$parentObject,
        &$drawItem,
        &$headerContent,
        &$itemContent,
        array &$row
    ) {
        $row['skills'] = [];
        $row['skillSets'] = [];

        if ($row['skilldisplay_skills'] != '') {
            $row = $this->addSkills($row);
        }

        if ($row['skilldisplay_skillset'] > 0) {
            $row = $this->addSkillSets($row);
        }
    }

    private function addSkills(array $row): array
    {
        $skills = GeneralUtility::intExplode(',', $row['skilldisplay_skills'], true);

        foreach ($skills as $skillId) {
            try {
                $row['skills'][] = $this->skillApi->getById($skillId);
            } catch (\Exception $e) {
                $row['skills'][]['error'] = $e->getMessage();
            }
        }

        return $row;
    }

    private function addSkillSets(array $row): array
    {
        $skillSets = GeneralUtility::intExplode(',', $row['skilldisplay_skillset'], true);

        foreach ($skillSets as $skillSetId) {
            try {
                $row['skillSets'][] = $this->skillSetApi->getById($skillSetId);
            } catch (\Exception $e) {
                $row['skillSets'][]['error'] = $e->getMessage();
            }
        }

        return $row;
    }
}
