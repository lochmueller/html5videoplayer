<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c)  Tim Lochmueller
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

namespace HVP\Html5videoplayer\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Repository for Tx_Hettich_Domain_Repository_Exposition
 *
 * @author     Michael Feinbier
 * @subpackage Repository
 * @license    http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class VideoRepository extends Repository {

	/**
	 * All Queries withoud storagePID
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
	 */
	public function createQuery() {
		$query = parent::createQuery();
		$query->getQuerySettings()
		      ->setRespectStoragePage(FALSE);
		return $query;
	}

	/**
	 * Find Video and respect hidden
	 *
	 * @param $uid
	 *
	 * @return object
	 */
	public function findByUidHidden($uid) {
		$query = $this->createQuery();
		return $query->matching($query->equals('uid', intval($uid)))
		             ->execute()
		             ->getFirst();
	}

	/**
	 *
	 * @param array $uids
	 *
	 * @return array
	 */
	public function findByUids(array $uids = array()) {
		$objects = array();
		foreach ($uids as $u) {
			$object = $this->findByUid((int)$u);
			if ($object !== NULL) {
				$objects[] = $object;
			}
		}
		return $objects;
	}

}