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
use SkillDisplay\PHPToolKit\Api\Skill;
use SkillDisplay\PHPToolKit\Entity\Skill as SkillEntity;
use SkillDisplay\SkilldisplayContent\Frontend\DataProcessing\Skills;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase as TestCase;

/**
 * @covers SkillDisplay\SkilldisplayContent\Frontend\DataProcessing\Skills
 */
class SkillsTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function instanceCanBeCreated(): void
    {
        $skillApi = $this->prophesize(Skill::class);
        $subject = new Skills(
            $skillApi->reveal()
        );

        static::assertInstanceOf(Skills::class, $subject);
    }

    /**
     * @test
     */
    public function addsEmptyArrayIfNoSkillsAreProvided(): void
    {
        $skillApi = $this->prophesize(Skill::class);
        $cObj = $this->prophesize(ContentObjectRenderer::class);
        $cObj->stdWrapValue('as', [], 'skills')->willReturn('skills');
        $cObj->stdWrapValue('skills', [], '')->willReturn('');

        $subject = new Skills(
            $skillApi->reveal()
        );

        $processedData = $subject->process(
            $cObj->reveal(),
            [],
            [],
            []
        );

        static::assertEquals([
            'skills' => [],
        ], $processedData);
    }

    /**
     * @test
     */
    public function addsSkillsAccordinglyToProvidedIds(): void
    {
        $skillApi = $this->prophesize(Skill::class);
        $skill10 = $this->prophesize(SkillEntity::class);
        $skillApi->getById(10)->willReturn($skill10->reveal());
        $skill20 = $this->prophesize(SkillEntity::class);
        $skillApi->getById(20)->willReturn($skill20->reveal());

        $cObj = $this->prophesize(ContentObjectRenderer::class);
        $cObj->stdWrapValue('as', [], 'skills')->willReturn('skills');
        $cObj->stdWrapValue('skills', [], '')->willReturn('10, 20,,');

        $subject = new Skills(
            $skillApi->reveal()
        );

        $processedData = $subject->process(
            $cObj->reveal(),
            [],
            [],
            [
                'skills' => '10, 20,,',
            ]
        );

        static::assertEquals([
            'skills' => [
                $skill10->reveal(),
                $skill20->reveal(),
            ],
        ], $processedData);
    }

    /**
     * @test
     */
    public function skillsSkillSetInCaseOfException(): void
    {
        $skillApi = $this->prophesize(Skill::class);
        $skill10 = $this->prophesize(SkillEntity::class);
        $skillApi->getById(10)->willReturn($skill10->reveal());
        $skill20 = $this->prophesize(SkillEntity::class);
        $skillApi->getById(20)->willThrow(new \Exception());

        $cObj = $this->prophesize(ContentObjectRenderer::class);
        $cObj->stdWrapValue('as', [], 'skills')->willReturn('skills');
        $cObj->stdWrapValue('skills', [], '')->willReturn('10, 20,,');

        $subject = new Skills(
            $skillApi->reveal()
        );

        $processedData = $subject->process(
            $cObj->reveal(),
            [],
            [],
            [
                'skills' => '10, 20,,',
            ]
        );

        static::assertEquals([
            'skills' => [
                $skill10->reveal(),
            ],
        ], $processedData);
    }
}
