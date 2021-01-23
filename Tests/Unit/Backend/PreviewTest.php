<?php

declare(strict_types=1);

namespace SkillDisplay\Typo3Extension\Tests\Unit\Backend;

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
use SkillDisplay\PHPToolKit\Api\SkillSet;
use SkillDisplay\PHPToolKit\Entity\Skill as SkillEntity;
use SkillDisplay\PHPToolKit\Entity\SkillSet as SkillSetEntity;
use SkillDisplay\Typo3Extension\Backend\Preview;
use TYPO3\CMS\Backend\View\PageLayoutView;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase as TestCase;

/**
 * @covers SkillDisplay\Typo3Extension\Backend\Preview
 */
class PreviewTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function instanceCanBeCreated(): void
    {
        $skillApi = $this->prophesize(Skill::class);
        $skillSetApi = $this->prophesize(SkillSet::class);

        $subject = new Preview(
            $skillApi->reveal(),
            $skillSetApi->reveal()
        );

        static::assertInstanceOf(Preview::class, $subject);
    }

    /**
     * @test
     */
    public function doesReturnEmptyResultsIfNoIds(): void
    {
        $skillApi = $this->prophesize(Skill::class);
        $skillSetApi = $this->prophesize(SkillSet::class);
        $pageLayoutView = $this->prophesize(PageLayoutView::class);

        $subject = new Preview(
            $skillApi->reveal(),
            $skillSetApi->reveal()
        );

        $revealedPageLayoutView = $pageLayoutView->reveal();
        $drawItem = false;
        $headerContent = '';
        $itemContent = '';
        $row = [
            'skilldisplay_skills' => '',
            'skilldisplay_skillset' => '0',
        ];

        $subject->preProcess(
            $revealedPageLayoutView,
            $drawItem,
            $headerContent,
            $itemContent,
            $row
        );

        static::assertFalse($drawItem);
        static::assertEmpty($headerContent);
        static::assertEmpty($itemContent);
        static::assertSame([
            'skilldisplay_skills' => '',
            'skilldisplay_skillset' => '0',
            'skills' => [],
            'skillSets' => [],
        ], $row);
    }

    /**
     * @test
     */
    public function addsSkillsBasedOnIds(): void
    {
        $skillApi = $this->prophesize(Skill::class);
        $skillSetApi = $this->prophesize(SkillSet::class);
        $pageLayoutView = $this->prophesize(PageLayoutView::class);

        $skill10 = $this->prophesize(SkillEntity::class);
        $skillApi->getById(10)->willReturn($skill10->reveal());
        $skill20 = $this->prophesize(SkillEntity::class);
        $skillApi->getById(20)->willReturn($skill20->reveal());

        $subject = new Preview(
            $skillApi->reveal(),
            $skillSetApi->reveal()
        );

        $revealedPageLayoutView = $pageLayoutView->reveal();
        $drawItem = false;
        $headerContent = '';
        $itemContent = '';
        $row = [
            'skilldisplay_skills' => '10, 20,,',
            'skilldisplay_skillset' => '0',
        ];

        $subject->preProcess(
            $revealedPageLayoutView,
            $drawItem,
            $headerContent,
            $itemContent,
            $row
        );

        static::assertFalse($drawItem);
        static::assertEmpty($headerContent);
        static::assertEmpty($itemContent);
        static::assertSame([
            'skilldisplay_skills' => '10, 20,,',
            'skilldisplay_skillset' => '0',
            'skills' => [
                $skill10->reveal(),
                $skill20->reveal(),
            ],
            'skillSets' => [],
        ], $row);
    }

    /**
     * @test
     */
    public function addsSkillSetsBasedOnIds(): void
    {
        $skillApi = $this->prophesize(Skill::class);
        $skillSetApi = $this->prophesize(SkillSet::class);
        $pageLayoutView = $this->prophesize(PageLayoutView::class);

        $skillSet10 = $this->prophesize(SkillSetEntity::class);
        $skillSetApi->getById(10)->willReturn($skillSet10->reveal());

        $subject = new Preview(
            $skillApi->reveal(),
            $skillSetApi->reveal()
        );

        $revealedPageLayoutView = $pageLayoutView->reveal();
        $drawItem = false;
        $headerContent = '';
        $itemContent = '';
        $row = [
            'skilldisplay_skills' => '',
            'skilldisplay_skillset' => '10',
        ];

        $subject->preProcess(
            $revealedPageLayoutView,
            $drawItem,
            $headerContent,
            $itemContent,
            $row
        );

        static::assertFalse($drawItem);
        static::assertEmpty($headerContent);
        static::assertEmpty($itemContent);
        static::assertSame([
            'skilldisplay_skills' => '',
            'skilldisplay_skillset' => '10',
            'skills' => [],
            'skillSets' => [
                $skillSet10->reveal(),
            ],
        ], $row);
    }

    /**
     * @test
     */
    public function addsExceptionMessageForSkills(): void
    {
        $skillApi = $this->prophesize(Skill::class);
        $skillSetApi = $this->prophesize(SkillSet::class);
        $pageLayoutView = $this->prophesize(PageLayoutView::class);
        $exception = new \Exception('Some helpfull message');

        $skill10 = $this->prophesize(SkillEntity::class);
        $skillApi->getById(10)->willThrow($exception);
        $skill20 = $this->prophesize(SkillEntity::class);
        $skillApi->getById(20)->willReturn($skill20->reveal());

        $subject = new Preview(
            $skillApi->reveal(),
            $skillSetApi->reveal()
        );

        $revealedPageLayoutView = $pageLayoutView->reveal();
        $drawItem = false;
        $headerContent = '';
        $itemContent = '';
        $row = [
            'skilldisplay_skills' => '10, 20,,',
            'skilldisplay_skillset' => '0',
        ];

        $subject->preProcess(
            $revealedPageLayoutView,
            $drawItem,
            $headerContent,
            $itemContent,
            $row
        );

        static::assertFalse($drawItem);
        static::assertEmpty($headerContent);
        static::assertEmpty($itemContent);
        static::assertSame([
            'skilldisplay_skills' => '10, 20,,',
            'skilldisplay_skillset' => '0',
            'skills' => [
                [
                    'error' => 'Some helpfull message',
                ],
                $skill20->reveal(),
            ],
            'skillSets' => [],
        ], $row);
    }

    /**
     * @test
     */
    public function addsExceptionMessageForSkillSets(): void
    {
        $skillApi = $this->prophesize(Skill::class);
        $skillSetApi = $this->prophesize(SkillSet::class);
        $pageLayoutView = $this->prophesize(PageLayoutView::class);
        $exception = new \Exception('Some helpfull message');

        $skillSetApi->getById(10)->willThrow($exception);

        $subject = new Preview(
            $skillApi->reveal(),
            $skillSetApi->reveal()
        );

        $revealedPageLayoutView = $pageLayoutView->reveal();
        $drawItem = false;
        $headerContent = '';
        $itemContent = '';
        $row = [
            'skilldisplay_skills' => '',
            'skilldisplay_skillset' => '10',
        ];

        $subject->preProcess(
            $revealedPageLayoutView,
            $drawItem,
            $headerContent,
            $itemContent,
            $row
        );

        static::assertFalse($drawItem);
        static::assertEmpty($headerContent);
        static::assertEmpty($itemContent);
        static::assertSame([
            'skilldisplay_skills' => '',
            'skilldisplay_skillset' => '10',
            'skills' => [],
            'skillSets' => [
                [
                    'error' => 'Some helpfull message',
                ],
            ],
        ], $row);
    }
}
