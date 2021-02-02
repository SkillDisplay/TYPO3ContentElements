<?php

declare(strict_types=1);

namespace SkillDisplay\SkilldisplayContent\Tests\Unit;

/*
 * Copyright (C) 2021 Daniel Siepmann <coding@daniel-siepmann.de>
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

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use SkillDisplay\PHPToolKit\Api\Campaigns;
use SkillDisplay\PHPToolKit\Entity\Campaign;
use SkillDisplay\SkilldisplayContent\CampaignsFactory;
use SkillDisplay\SkilldisplayContent\TcaEnhancer;

/**
 * @covers SkillDisplay\SkilldisplayContent\TcaEnhancer
 */
class TcaEnhancerTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function instanceCanBeCreated(): void
    {
        /** @var CampaignsFactory|ObjectProphecy $campaignsFactory */
        $campaignsFactory = $this->prophesize(CampaignsFactory::class);
        $subject = new TcaEnhancer(
            $campaignsFactory->reveal()
        );

        self::assertInstanceOf(TcaEnhancer::class, $subject);
    }

    /**
     * @test
     */
    public function addsDefaultItemOnlyWhenNoCampaignsAreAvailable(): void
    {
        $parameters = [
            'row' => [
                'pid' => 10,
            ],
            'items' => [],
        ];

        /** @var Campaigns|ObjectProphecy $campaigns */
        $campaigns = $this->prophesize(Campaigns::class);
        $campaigns->getForUser()->willReturn([]);

        /** @var CampaignsFactory|ObjectProphecy $campaignsFactory */
        $campaignsFactory = $this->prophesize(CampaignsFactory::class);
        $campaignsFactory->createFromPageUid(10)->willReturn($campaigns->reveal());

        /** @var TcaEnhancer|MockObject $subject */
        $subject = $this->getMockBuilder(TcaEnhancer::class)
            ->onlyMethods(['resolvePid'])
            ->setConstructorArgs([$campaignsFactory->reveal()])
            ->getMock();
        $subject->expects(self::any())->method('resolvePid')->willReturn(10);
        $subject->getCampaignsForTCA($parameters);

        self::assertArrayHasKey('items', $parameters);
        self::assertSame([
            ['', 0],
        ], $parameters['items']);
    }

    /**
     * @test
     */
    public function addsFetchedCampaignsBesideDefault(): void
    {
        $parameters = [
            'row' => [
                'pid' => 10,
            ],
            'items' => [],
        ];

        /** @var Campaign|ObjectProphecy $campaign1 */
        $campaign1 = $this->prophesize(Campaign::class);
        $campaign1->getTitle()->willReturn('Campaign Title 1');
        $campaign1->getId()->willReturn(10);
        /** @var Campaign|ObjectProphecy $campaign2 */
        $campaign2 = $this->prophesize(Campaign::class);
        $campaign2->getTitle()->willReturn('Campaign Title 2');
        $campaign2->getId()->willReturn(20);

        /** @var Campaigns|ObjectProphecy $campaigns */
        $campaigns = $this->prophesize(Campaigns::class);
        $campaigns->getForUser()->willReturn([
            $campaign1->reveal(),
            $campaign2->reveal(),
        ]);

        /** @var CampaignsFactory|ObjectProphecy $campaignsFactory */
        $campaignsFactory = $this->prophesize(CampaignsFactory::class);
        $campaignsFactory->createFromPageUid(10)->willReturn($campaigns->reveal());

        /** @var TcaEnhancer|MockObject $subject */
        $subject = $this->getMockBuilder(TcaEnhancer::class)
            ->onlyMethods(['resolvePid'])
            ->setConstructorArgs([$campaignsFactory->reveal()])
            ->getMock();
        $subject->expects(self::any())->method('resolvePid')->willReturn(10);
        $subject->getCampaignsForTCA($parameters);

        self::assertArrayHasKey('items', $parameters);
        self::assertSame([
            ['', 0],
            ['Campaign Title 1', 10],
            ['Campaign Title 2', 20],
        ], $parameters['items']);
    }
}
