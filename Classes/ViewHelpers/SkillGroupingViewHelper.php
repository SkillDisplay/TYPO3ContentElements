<?php

declare(strict_types=1);

namespace SkillDisplay\SkilldisplayContent\ViewHelpers;

/*
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

use Closure;
use SkillDisplay\PHPToolKit\Entity\Skill;
use SkillDisplay\PHPToolKit\Entity\SkillSet;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Usage:
 *
 * <sd:skillGrouping skillSet="{skillSet}" as="domainGroup">
 *   {domainGroup.title}<br />
 *   <f:for each="{domainGroup.skills}" as="skill">
 *     {skill.name}<br />
 *   </f:for>
 * </sd>
 */
class SkillGroupingViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    protected $escapeOutput = false;

    public function initializeArguments()
    {
        $this->registerArgument('skills', 'array', 'An array of skills to group', false, []);
        $this->registerArgument('skillSet', SkillSet::class, 'The skills of this skill set will be grouped', false, []);
        $this->registerArgument('as', 'string', 'The name of the iteration variable', true);
    }

    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        /** @var Skill[] $skills */
        $skills = $arguments['skills'];
        /** @var SkillSet $skillSet */
        $skillSet = $arguments['skillSet'];
        $as = $arguments['as'];

        if ($skillSet) {
            $groups = self::group($skillSet->getSkills());
        } else {
            $groups = self::group($skills);
        }

        $templateVariableContainer = $renderingContext->getVariableProvider();
        $output = '';
        foreach ($groups as $group) {
            $templateVariableContainer->add($as, $group);
            $output .= $renderChildrenClosure();
            $templateVariableContainer->remove($as);
        }
        return $output;
    }

    /**
     * @param Skill[] $skills
     * @return array
     */
    protected static function group(array $skills): array
    {
        $result = [
            /*
             * [
             *   'title' => 'Domain 1',
             *   'skills' => [...skills]
             * ]
             */
        ];

        // todo group skills based on domainTag|title
        return $result;
    }
}
