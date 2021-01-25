<?php

declare(strict_types=1);

namespace SkillDisplay\SkilldisplayContent;

use SkillDisplay\PHPToolKit\Api\Campaigns;
use SkillDisplay\PHPToolKit\Entity\Campaign;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TcaEnhancer
{
    public function getCampaignsForTCA(array $params): void
    {
        $params['items'] = [
            ['', 0],
        ];

        $campaigns = GeneralUtility::makeInstance(Campaigns::class)->getForUser();
        /** @var Campaign $campaign */
        foreach ($campaigns as $campaign) {
            $params['items'][] = [
                $campaign->getTitle(),
                $campaign->getId(),
            ];
        }
    }
}
