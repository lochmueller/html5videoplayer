<?php

namespace HVP\Html5videoplayer\Hooks;

use \TYPO3\CMS\Core\Database\ConnectionPool;
use \TYPO3\CMS\Core\Utility\GeneralUtility;

class CmsLayout
{

    /**
     * @var array
     */
    protected $flexformData;

    /**
     * @param array $params
     * @return string
     */
    public function getExtensionSummary(array $params)
    {
        $result = '<strong><cite>&rarr; HTML5 Video Player</cite></strong>';
        $this->flexformData = GeneralUtility::xml2array($params['row']['pi_flexform']);

        if ($this->getFieldFromFlexform('settings.ff.videowidth')) {
            $result .= '<br /><strong>Width:</strong>: ' . $this->getFieldFromFlexform('settings.ff.videowidth');
        }
        if ($this->getFieldFromFlexform('settings.ff.videoheight')) {
            $result .= '<br /><strong>Height</strong>: ' . $this->getFieldFromFlexform('settings.ff.videoheight');
        }
        if ($this->getFieldFromFlexform('switchableControllerActions')) {
            $result .= '<br /><strong>Mode</strong>:';
            if ($this->getFieldFromFlexform('switchableControllerActions') == 'Videoplayer->list') {
                $result .= 'Movies';
            } else {
                $result .= 'Gallery';
            }
        }

        $result .= '<br /><ul style="margin-bottom: 0;">';
        $videos = $this->getVideosByContentUid($params['row']['uid']);
        foreach ($videos as $video) {
            $result .= '<li>' . $video['title'] . '</li>';
        }
        $result .= '</ul>';

        return $result;
    }

    /**
     * @param $uid
     * @return array|NULL
     */
    protected function getVideosByContentUid($uid)
    {
        $table = 'tx_html5videoplayer_domain_model_video';
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        return $queryBuilder->select('video.*')
            ->from($table, 'video')
            ->leftJoin(
                'video',
                'tx_html5videoplayer_video_content',
                'content',
                $queryBuilder->expr()->eq('video.uid', $queryBuilder->quoteIdentifier('content.video_uid'))
            )
            ->where($queryBuilder->expr()->eq('content.content_uid',
                $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT)))
            ->orderBy('content.sorting')
            ->execute()->fetchAll();
    }

    /**
     * Get field value from flexform configuration,
     * including checks if flexform configuration is available
     *
     * @param string $key name of the key
     * @param string $sheet name of the sheet
     * @return string|NULL if nothing found, value if found
     */
    protected function getFieldFromFlexform($key, $sheet = 'sDEF')
    {
        $flexform = $this->flexformData;
        if (isset($flexform['data'])) {
            $flexform = $flexform['data'];
            if (is_array($flexform) && is_array($flexform[$sheet]) && is_array($flexform[$sheet]['lDEF']) && is_array($flexform[$sheet]['lDEF'][$key]) && isset($flexform[$sheet]['lDEF'][$key]['vDEF'])) {
                return $flexform[$sheet]['lDEF'][$key]['vDEF'];
            }
        }
        return null;
    }
}
