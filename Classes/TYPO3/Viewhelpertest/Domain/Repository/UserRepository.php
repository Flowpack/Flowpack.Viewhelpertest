<?php
namespace TYPO3\Viewhelpertest\Domain\Repository;

/*                                                                        *
 * This script belongs to the FLOW3 package "TYPO3.Viewhelpertest".       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class UserRepository extends \TYPO3\Flow\Persistence\Repository {

	/**
	 * @var array
	 */
	protected $defaultOrderings = array(
		'lastName' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_ASCENDING,
		'firstName' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_ASCENDING
	);
}
?>
