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

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use SkillDisplay\PHPToolKit\Api\Campaigns;
use SkillDisplay\PHPToolKit\Configuration\Settings;
use SkillDisplay\SkilldisplayContent\CampaignsFactory;
use SkillDisplay\SkilldisplayContent\SettingsFactory;

/**
 * @covers SkillDisplay\SkilldisplayContent\CampaignsFactory
 */
class CampaignsFactoryTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function instanceCanBeCreated(): void
    {
        $settingsFactory = $this->prophesize(SettingsFactory::class);
        $client = $this->prophesize(Client::class);

        $subject = new CampaignsFactory(
            $settingsFactory->reveal(),
            $client->reveal()
        );

        self::assertInstanceOf(CampaignsFactory::class, $subject);
    }

    /**
     * @test
     */
    public function returnsCampaignsFromPageUid(): void
    {
        $settingsFactory = $this->prophesize(SettingsFactory::class);
        $settings = $this->prophesize(Settings::class);
        $client = $this->prophesize(Client::class);

        $settingsFactory->createFromPageUid(10)->willReturn($settings->reveal());

        $subject = new CampaignsFactory(
            $settingsFactory->reveal(),
            $client->reveal()
        );

        $result = $subject->createFromPageUid(10);

        self::assertInstanceOf(Campaigns::class, $result);
    }
}
