<?php

namespace HVP\Html5videoplayer;

use \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use \TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use \TYPO3\CMS\Core\Database\ConnectionPool;
use \TYPO3\CMS\Core\DataHandling\DataHandler;
use \TYPO3\CMS\Core\Utility\GeneralUtility;

class Div
{

    /**
     * Hook for clear page caches on video change
     *
     * @param string $status
     * @param string $table
     * @param int $id
     * @param array $fieldArray
     * @param $obj
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    public function processDatamap_postProcessFieldArray($status, $table, $id, &$fieldArray, &$obj): void
    {
        if ($table !== 'tx_html5videoplayer_domain_model_video') {
            return;
        }

        /* Clear Cache */
        $table = 'tt_content';
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $res = $queryBuilder->select('content.uid', 'content.pid')
            ->from($table, 'content')
            ->leftJoin(
                'content',
                'tx_html5videoplayer_video_content',
                'video_content',
                $queryBuilder->expr()->eq('content.uid', $queryBuilder->quoteIdentifier('video_content.content_uid'))
            )
            ->where($queryBuilder->expr()->eq('video_content.video_uid',
                $queryBuilder->createNamedParameter($id, \PDO::PARAM_INT)))
            ->execute()->fetchAll();
        $pages = [];
        foreach ($res as $r) {
            $pages[] = $r['pid'];
        }
        /** @var DataHandler $cache */
        $cache = GeneralUtility::makeInstance(DataHandler::class);
        if (!is_object($cache->BE_USER)) {
            $cache->BE_USER = $GLOBALS['BE_USER'];
        }
        foreach ($pages as $pid) {
            $cache->clear_cacheCmd($pid);
        }

        /* General Storage Folder */
        if ($id = self::getGeneralStorageFolder()) {
            $fieldArray['pid'] = $id;
        }
    }

    /**
     * Get the general record storage ID
     *
     * @return int
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    public static function getGeneralStorageFolder()
    {
        return self::getConfigurationValue('generalStorageFolder');
    }

    /**
     * Get a configuration value
     *
     * @param $key
     * @return int
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    public static function getConfigurationValue($key)
    {
        $config = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('html5videoplayer');
        if (isset($config[$key])) {
            return (int)$config[$key];
        } else {
            return 0;
        }
    }

    /**
     * Check if a feature is enabled
     *
     * @param $feature
     * @return bool
     */
    public static function featureEnable($feature): bool
    {
        /** @var Div $div */
        $div = GeneralUtility::makeInstance(Div::class);
        return $div->featureEnableInternal($feature);
    }

    /**
     * Inernal Check if a feature is enabled
     * Note: You can hook/overwrite it ;)
     *
     * @param $feature
     * @return bool
     */
    public function featureEnableInternal($feature): bool
    {
        switch ($feature) {
            case 'vimeo':
                /* enable for dev preview */
                return true;
        }
        return false;
    }
}
