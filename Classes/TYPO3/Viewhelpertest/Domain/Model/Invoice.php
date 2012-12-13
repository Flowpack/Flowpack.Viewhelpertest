<?php
namespace TYPO3\Viewhelpertest\Domain\Model;

/*                                                                        *
 * This script belongs to the FLOW3 package "Viewhelpertest".             *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * An Invoice
 *
 * @Flow\Scope("prototype")
 * @Flow\Entity
 */
class Invoice {

	/**
	 * @var \DateTime
	 */
	protected $date;

	/**
	 * @ORM\ManyToOne
	 * @var \TYPO3\Viewhelpertest\Domain\Model\User
	 */
	protected $customer;

	/**
	 * @param \DateTime $date
	 * @return void
	 */
	public function setDate(\DateTime $date) {
		$this->date = $date;
	}

	/**
	 * @return \DateTime
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @param \TYPO3\Viewhelpertest\Domain\Model\User $customer
	 * @return void
	 */
	public function setCustomer(\TYPO3\Viewhelpertest\Domain\Model\User $customer) {
		$this->customer = $customer;
	}

	/**
	 * @return \TYPO3\Viewhelpertest\Domain\Model\User
	 */
	public function getCustomer() {
		return $this->customer;
	}

	/**
	 * @param \DateTime $date
	 * @param \TYPO3\Viewhelpertest\Domain\Model\User $customer
	 */
	public function __construct(\DateTime $date, \TYPO3\Viewhelpertest\Domain\Model\User $customer) {
		$this->date = $date;
		$this->customer = $customer;
	}
}
?>