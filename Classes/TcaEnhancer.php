<?php

declare(strict_types=1);

namespace SkillDisplay\SkilldisplayContent;

use SkillDisplay\PHPToolKit\Entity\Campaign;
use TYPO3\CMS\Backend\Utility\BackendUtility;

class TcaEnhancer
{
    private CampaignsFactory $campaignsFactory;

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
            try {
                $campaigns = $this->campaignsFactory->createFromPageUid($pid)->getForUser();
            } catch (\InvalidArgumentException $e) {
                // ignore missing API key exception
                if ($e->getCode() !== 1688660942) {
                    throw $e;
                }
                return;
            }
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
