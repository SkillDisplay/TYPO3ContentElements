<?php

declare(strict_types=1);

namespace SkillDisplay\SkilldisplayContent\Tests\Unit\Frontend\DataProcessing;

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
use SkillDisplay\PHPToolKit\Api\SkillSet;
use SkillDisplay\PHPToolKit\Entity\SkillSet as SkillSetEntity;
use SkillDisplay\SkilldisplayContent\Frontend\DataProcessing\SkillSets;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase as TestCase;

/**
 * @covers SkillDisplay\SkilldisplayContent\Frontend\DataProcessing\SkillSets
 */
class SkillSetsTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function instanceCanBeCreated(): void
    {
        $skillApi = $this->prophesize(SkillSet::class);
        $subject = new SkillSets(
            $skillApi->reveal()
        );

        static::assertInstanceOf(SkillSets::class, $subject);
    }

    /**
     * @test
     */
    public function addsEmptyArrayIfNoSkillSetsAreProvided(): void
    {
        $skillApi = $this->prophesize(SkillSet::class);
        $cObj = $this->prophesize(ContentObjectRenderer::class);
        $cObj->stdWrapValue('as', [], 'skillSets')->willReturn('skillSets');
        $cObj->stdWrapValue('skillSets', [], '')->willReturn('');

        $subject = new SkillSets(
            $skillApi->reveal()
        );

        $processedData = $subject->process(
            $cObj->reveal(),
            [],
            [],
            []
        );

        static::assertEquals([
            'skillSets' => [],
        ], $processedData);
    }

    /**
     * @test
     */
    public function addsSkillSetsAccordinglyToProvidedIds(): void
    {
        $skillApi = $this->prophesize(SkillSet::class);
        $skillSet10 = $this->prophesize(SkillSetEntity::class);
        $skillApi->getById(10)->willReturn($skillSet10->reveal());
        $skillSet20 = $this->prophesize(SkillSetEntity::class);
        $skillApi->getById(20)->willReturn($skillSet20->reveal());

        $cObj = $this->prophesize(ContentObjectRenderer::class);
        $cObj->stdWrapValue('as', [], 'skillSets')->willReturn('skillSets');
        $cObj->stdWrapValue('skillSets', [], '')->willReturn('10, 20,,');

        $subject = new SkillSets(
            $skillApi->reveal()
        );

        $processedData = $subject->process(
            $cObj->reveal(),
            [],
            [],
            [
                'skillSets' => '10, 20,,',
            ]
        );

        static::assertEquals([
            'skillSets' => [
                $skillSet10->reveal(),
                $skillSet20->reveal(),
            ],
        ], $processedData);
    }

    /**
     * @test
     */
    public function skillsSkillSetInCaseOfException(): void
    {
        $skillApi = $this->prophesize(SkillSet::class);
        $skillSet10 = $this->prophesize(SkillSetEntity::class);
        $skillApi->getById(10)->willReturn($skillSet10->reveal());
        $skillSet20 = $this->prophesize(SkillSetEntity::class);
        $skillApi->getById(20)->willThrow(new \Exception());

        $cObj = $this->prophesize(ContentObjectRenderer::class);
        $cObj->stdWrapValue('as', [], 'skillSets')->willReturn('skillSets');
        $cObj->stdWrapValue('skillSets', [], '')->willReturn('10, 20,,');

        $subject = new SkillSets(
            $skillApi->reveal()
        );

        $processedData = $subject->process(
            $cObj->reveal(),
            [],
            [],
            [
                'skillSets' => '10, 20,,',
            ]
        );

        static::assertEquals([
            'skillSets' => [
                $skillSet10->reveal(),
            ],
        ], $processedData);
    }
}
