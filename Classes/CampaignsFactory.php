<?php

declare(strict_types=1);

namespace SkillDisplay\SkilldisplayContent;

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
use SkillDisplay\PHPToolKit\Api\Campaigns;

class CampaignsFactory
{
    /**
     * @var SettingsFactory
     */
    private $settingsFactory;

    /**
     * @var Client
     */
    private $client;

    public function __construct(
        SettingsFactory $settingsFactory,
        Client $client
    ) {
        $this->settingsFactory = $settingsFactory;
        $this->client = $client;
    }

    public function createFromPageUid(int $pageUid): Campaigns
    {
        return new Campaigns(
            $this->settingsFactory->createFromPageUid($pageUid),
            $this->client
        );
    }
}
