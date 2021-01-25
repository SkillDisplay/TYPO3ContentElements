<?php

declare(strict_types=1);

namespace SkillDisplay\SkilldisplayContent\Tests\Unit;

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
use SkillDisplay\PHPToolKit\Configuration\Settings;
use SkillDisplay\SkilldisplayContent\SettingsFactory;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase as TestCase;

/**
 * @covers SkillDisplay\SkilldisplayContent\SettingsFactory
 */
class SettingsFactoryTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function instanceCanBeCreated(): void
    {
        $subject = new SettingsFactory();
        static::assertInstanceOf(SettingsFactory::class, $subject);
    }

    /**
     * @test
     */
    public function returnsDefaultSettingsIfNothingIsConfigured(): void
    {
        $site = $this->prophesize(Site::class);
        $site->getConfiguration()->willReturn([]);

        $request = $this->prophesize(ServerRequest::class);
        $request->getAttribute('site')->willReturn($site->reveal());

        $GLOBALS['TYPO3_REQUEST'] = $request->reveal();
        $subject = new SettingsFactory();

        $settings = $subject->createFromCurrentSiteConfiguration();
        static::assertInstanceOf(Settings::class, $settings);
        static::assertSame(0, $settings->getVerifierID());
        static::assertSame('', $settings->getUserSecret());
        static::assertSame('', $settings->getApiKey());
        static::assertSame('https://www.skilldisplay.eu', $settings->getAPIUrl());
        static::assertSame('https://my.skilldisplay.eu', $settings->getMySkillDisplayUrl());
    }

    /**
     * @test
     */
    public function returnsSettingsFromCurrentSite(): void
    {
        $site = $this->prophesize(Site::class);
        $site->getConfiguration()->willReturn([
            'skilldisplay_api_key' => '---YOUR-API-KEY---',
            'skilldisplay_verifier_id' => 10,
            'skilldisplay_user_secret' => '---USER-SECRET---',
        ]);

        $request = $this->prophesize(ServerRequest::class);
        $request->getAttribute('site')->willReturn($site->reveal());

        $GLOBALS['TYPO3_REQUEST'] = $request->reveal();
        $subject = new SettingsFactory();

        $settings = $subject->createFromCurrentSiteConfiguration();
        static::assertInstanceOf(Settings::class, $settings);
        static::assertSame(10, $settings->getVerifierID());
        static::assertSame('---USER-SECRET---', $settings->getUserSecret());
        static::assertSame('---YOUR-API-KEY---', $settings->getApiKey());
        static::assertSame('https://www.skilldisplay.eu', $settings->getAPIUrl());
        static::assertSame('https://my.skilldisplay.eu', $settings->getMySkillDisplayUrl());
    }

    /**
     * @test
     */
    public function throwsExceptionIfCurrentSiteCanNotBeFetched(): void
    {
        $request = $this->prophesize(ServerRequest::class);
        $request->getAttribute('site')->willReturn(null);

        $GLOBALS['TYPO3_REQUEST'] = $request->reveal();
        $subject = new SettingsFactory();

        $this->expectExceptionMessage('Could not determine current site.');
        $this->expectExceptionCode(1599721652);
        $subject->createFromCurrentSiteConfiguration();
    }
}
