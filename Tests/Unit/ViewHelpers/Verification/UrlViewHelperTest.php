<?php

declare(strict_types=1);

namespace SkillDisplay\Typo3Extension\Tests\Unit\ViewHelpers\Verification;

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

use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use SkillDisplay\PHPToolKit\Verification\Link;
use SkillDisplay\Typo3Extension\ViewHelpers\Verification\UrlViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase as TestCase;

/**
 * @covers SkillDisplay\Typo3Extension\ViewHelpers\Verification\UrlViewHelper
 * @covers SkillDisplay\Typo3Extension\ViewHelpers\VerificationViewHelper
 */
class UrlViewHelperTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function throwsExceptionIfSkillAndSkillSetIsProvided(): void
    {
        $renderingContext = $this->prophesize(RenderingContextInterface::class);

        $this->expectExceptionMessage('Can only handle skill or skillSet not both.');
        $this->expectExceptionCode(1600775604);

        UrlViewHelper::renderStatic(
            [
                'skill' => 10,
                'skillSet' => 10,
                'campaign' => 0,
            ],
            function () {
            },
            $renderingContext->reveal()
        );
    }

    /**
     * @test
     */
    public function throwsExceptionIfNeitherSkillNorSkillSetIsProvided(): void
    {
        $renderingContext = $this->prophesize(RenderingContextInterface::class);

        $this->expectExceptionMessage('Either needs skill or skillSet, none given.');
        $this->expectExceptionCode(1600775604);

        UrlViewHelper::renderStatic(
            [
            ],
            function () {
            },
            $renderingContext->reveal()
        );
    }

    /**
     * @test
     */
    public function returnsRenderedUrlForSkill(): void
    {
        $renderingContext = $this->prophesize(RenderingContextInterface::class);
        /** @var Link|ObjectProphecy $link */
        $link = $this->prophesize(Link::class);
        $link->getVerificationLink('self', 10, Link::SKILL, 0)
            ->willReturn('https://example.com/path/to/verification');
        GeneralUtility::addInstance(Link::class, $link->reveal());

        $result = UrlViewHelper::renderStatic(
            [
                'skill' => 10,
                'type' => 'self',
                'campaign' => 0,
            ],
            function () {
            },
            $renderingContext->reveal()
        );
        static::assertSame('https://example.com/path/to/verification', $result);
    }

    /**
     * @test
     */
    public function returnsRenderedUrlForSkillSet(): void
    {
        $renderingContext = $this->prophesize(RenderingContextInterface::class);
        /** @var Link|ObjectProphecy $link */
        $link = $this->prophesize(Link::class);
        $link->getVerificationLink('self', 10, Link::SKILL_SET, 0)
            ->willReturn('https://example.com/path/to/verification');
        GeneralUtility::addInstance(Link::class, $link->reveal());

        $result = UrlViewHelper::renderStatic(
            [
                'skillSet' => 10,
                'type' => 'self',
                'campaign' => 0,
            ],
            function () {
            },
            $renderingContext->reveal()
        );
        static::assertSame('https://example.com/path/to/verification', $result);
    }
}
