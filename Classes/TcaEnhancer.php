<?php

declare(strict_types=1);

namespace SkillDisplay\SkilldisplayContent;

use SkillDisplay\PHPToolKit\Entity\Campaign;

class TcaEnhancer
{
    /**
     * @var CampaignsFactory
     */
    private $campaignsFactory;

    public function __construct(
        CampaignsFactory $campaignsFactory
    ) {
        $this->campaignsFactory = $campaignsFactory;
    }

    public function getCampaignsForTCA(array &$params): void
    {
        $params['items'] = [
            ['', 0],
        ];

        $campaigns = $this->campaignsFactory
            ->createFromPageUid(abs($params['row']['pid']))
            ->getForUser();
        /** @var Campaign $campaign */
        foreach ($campaigns as $campaign) {
            $params['items'][] = [
                $campaign->getTitle(),
                $campaign->getId(),
            ];
        }
    }
}
