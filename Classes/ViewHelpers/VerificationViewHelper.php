<?php

declare(strict_types=1);

namespace SkillDisplay\SkilldisplayContent\ViewHelpers;

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

use Closure;
use Exception;
use SkillDisplay\PHPToolKit\Verification\Link;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

abstract class VerificationViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    protected $escapeOutput = false;

    public function initializeArguments()
    {
        $this->registerArgument('skill', 'integer', 'ID of the Skill.');
        $this->registerArgument('skillSet', 'integer', 'ID of the SkillSet.');
        $this->registerArgument('type', 'string', 'Type of verification', false, 'self');
        $this->registerArgument('campaign', 'int', 'ID of campaign', false, 0);
    }

    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        try {
            static::validateIds($arguments);
        } catch (Exception $e) {
            return '';
        }

        return static::verificationHtml($arguments);
    }

    abstract protected static function verificationHtml(array $arguments): string;

    /**
     * @param array $arguments
     * @return void
     * @throws Exception
     */
    protected static function validateIds(array $arguments): void
    {
        if (
            isset($arguments['skill']) && $arguments['skill'] !== ''
            && isset($arguments['skillSet']) && $arguments['skillSet'] !== ''
        ) {
            throw new Exception('Can only handle skill or skillSet not both.', 1600775604);
        }

        if (
            (isset($arguments['skill']) === false || $arguments['skill'] === '')
            && (isset($arguments['skillSet']) === false || $arguments['skillSet'] === '')
        ) {
            throw new Exception('Either needs skill or skillSet, none given.', 1600775604);
        }
    }

    protected static function getId(array $arguments): int
    {
        if (static::getType($arguments) === Link::SKILL) {
            return $arguments['skill'];
        }

        return $arguments['skillSet'];
    }

    protected static function getType(array $arguments): string
    {
        if (isset($arguments['skill']) && $arguments['skill'] !== '') {
            return Link::SKILL;
        }

        return Link::SKILL_SET;
    }
}
