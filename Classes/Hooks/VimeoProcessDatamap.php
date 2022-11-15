<?php

namespace HVP\Html5videoplayer\Hooks;

use TYPO3\CMS\Core\Http\RequestFactory;
use \TYPO3\CMS\Core\Database\ConnectionPool;
use \TYPO3\CMS\Core\Resource\ResourceFactory;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException;
use \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException;

/**
 * Class VimeoProcessDatamap
 *
 * @package HVP\Html5videoplayer\Hooks
 */
class VimeoProcessDatamap
{

    /**
     * @var string
     */
    protected $defaultUploadFolder = '1:/';

    /**
     * @param $status
     * @param $table
     * @param $id
     * @param $fieldArray
     * @param $self
     * @throws InvalidSlotException
     * @throws InvalidSlotReturnException
     */
    public function processDatamap_postProcessFieldArray($status, $table, $id, &$fieldArray, &$self): void
    {

        if ($table === 'tx_html5videoplayer_domain_model_video') {
            $data = $fieldArray;
            if ($status === 'update') {
                $table = 'tx_html5videoplayer_domain_model_video';
                $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
                $res = $queryBuilder->select('*')
                    ->from($table, 'video')
                    ->where($queryBuilder->expr()->eq('video.uid',
                        $queryBuilder->createNamedParameter($id, \PDO::PARAM_INT)))
                    ->execute()->fetch();
                $data = array_merge($res, $data);
            }
            $vimeoUrl = $data['vimeo'];
            if (($status === 'update' || $status === 'new') && $vimeoUrl !== '' && GeneralUtility::isValidUrl($vimeoUrl)) {
                if (preg_match(
                    '/https?:\\/\\/(?:www\\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/i',
                    $vimeoUrl,
                    $matches
                )) {
                    $videoId = $matches[3];
                    $url = GeneralUtility::makeInstance(RequestFactory::class)->request('https://vimeo.com/api/v2/video/' . $videoId . '.php')->getBody()->getContents();
                    $videoData = unserialize($url, ['allowed_classes' => true]);

                    if (is_array($videoData)) {
                        // We're only interested in index zero.
                        $videoData = $videoData[0];

                        if (!isset($data['title']) || trim($data['title']) === '') {
                            $fieldArray['title'] = $videoData['title'];
                        }

                        if (!isset($data['description']) || trim($data['description']) === '') {
                            $fieldArray['description'] = $videoData['description'];
                        }

                        if (!isset($data['posterimage']) || trim($data['posterimage']) === '') {
                            $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
                            $folder = $resourceFactory->retrieveFileOrFolderObject($this->getUploadFolder());
                            $thumbnailData = GeneralUtility::getUrl($videoData['thumbnail_large']);
                            $file = $folder->createFile(basename($videoData['thumbnail_large']) . '.jpg');
                            $file->setContents($thumbnailData);
                            $fieldArray['posterimage'] = 'file:' . $file->getUid();
                        }
                    }
                }
            }
        }
    }

    /**
     * Get the upload folder
     * Use the Signal to override the default folder
     *
     * @return string
     * @throws InvalidSlotException
     * @throws InvalidSlotReturnException
     */
    protected function getUploadFolder(): string
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = GeneralUtility::makeInstance(Dispatcher::class);
        $arguments = [
            $this->defaultUploadFolder,
        ];
        $arguments = $dispatcher->dispatch(self::class, __METHOD__, $arguments);
        return $arguments[0];
    }
}
