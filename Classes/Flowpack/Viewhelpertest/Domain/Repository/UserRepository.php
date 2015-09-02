<?php
namespace Flowpack\Viewhelpertest\Domain\Repository;

/*                                                                        *
 * This script belongs to the FLOW3 package "Flowpack.Viewhelpertest".    *
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
