<?php

declare(strict_types=1);

namespace SkillDisplay\SkilldisplayContent;

use SkillDisplay\PHPToolKit\Entity\Campaign;
use TYPO3\CMS\Backend\Utility\BackendUtility;

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

        $pid = $this->resolvePid($params['row']);
        if ($pid > 0) {
            $campaigns = $this->campaignsFactory->createFromPageUid($pid)->getForUser();
            /** @var Campaign $campaign */
            foreach ($campaigns as $campaign) {
                $params['items'][] = [
                    $campaign->getTitle(),
                    $campaign->getId(),
                ];
            }
        }
    }

    protected function resolvePid(array $row): ?int
    {
        return BackendUtility::getTSconfig_pidValue('tt_content', $row['uid'], $row['pid']);
    }
}
