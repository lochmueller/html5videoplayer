<?php

namespace HVP\Html5videoplayer\Domain\Repository;

use \TYPO3\CMS\Extbase\Persistence\QueryInterface;
use \TYPO3\CMS\Extbase\Persistence\Repository;

class VideoRepository extends Repository
{

    /**
     * All Queries withoud storagePID
     *
     * @return QueryInterface
     */
    public function createQuery(): QueryInterface
    {
        $query = parent::createQuery();
        $query->getQuerySettings()
            ->setRespectStoragePage(false)
            ->setRespectSysLanguage(false);
        return $query;
    }

    /**
     * Find Video and respect hidden
     *
     * @param $uid
     * @return object
     */
    public function findByUidHidden($uid)
    {
        $query = $this->createQuery();
        return $query->matching($query->equals('uid', intval($uid)))
            ->execute()
            ->getFirst();
    }

    /**
     * @param array $uids
     * @return array
     */
    public function findByUids(array $uids = [])
    {
        $objects = [];
        foreach ($uids as $u) {
            $object = $this->findByUid((int)$u);
            if ($object !== null) {
                $objects[] = $object;
            }
        }
        return $objects;
    }
}
