<?php

declare(strict_types=1);

namespace SkillDisplay\SkilldisplayContent;

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

use SkillDisplay\PHPToolKit\Configuration\Settings;
use TYPO3\CMS\Core\Http\ServerRequest;

class SettingsFactory
{
    public function createFromCurrentSiteConfiguration(): Settings
    {
        $site = $this->getRequest()->getAttribute('site');
        if ($site === null) {
            throw new \Exception('Could not determine current site.', 1599721652);
        }

        $config = $site->getConfiguration();

        return new Settings(
            $config['skilldisplay_api_key'] ?? '',
            (int)($config['skilldisplay_verifier_id'] ?? 0),
            $config['skilldisplay_user_secret'] ?? ''
        );
    }

    private function getRequest(): ServerRequest
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
