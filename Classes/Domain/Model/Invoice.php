<?php
declare(ENCODING = 'utf-8');
namespace F3\Viewhelpertest\Domain\Model;

/*                                                                        *
 * This script is part of the TYPO3 project - inspiring people to share!  *
 *                                                                        *
 * TYPO3 is free software; you can redistribute it and/or modify it under *
 * the terms of the GNU General Public License version 2 as published by  *
 * the Free Software Foundation.                                          *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        */

/**
 * An Invoice
 *
 * @package Viewhelpertest
 * @subpackage Domain
 * @version $Id: User.php 4833 2010-07-12 16:49:13Z bwaidelich $
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @scope prototype
 * @entity
 */
class Invoice {

	/**
	 * @var \DateTime
	 */
	protected $date;

	/**
	 * @var \F3\Viewhelpertest\Domain\Model\User
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
	 * @param \F3\Viewhelpertest\Domain\Model\User $customer
	 * @return void
	 */
	public function setCustomer(\F3\Viewhelpertest\Domain\Model\User $customer) {
		$this->customer = $customer;
	}

	/**
	 * @return \F3\Viewhelpertest\Domain\Model\User
	 */
	public function getCustomer() {
		return $this->customer;
	}

	/**
	 * @param \DateTime $date
	 * @param \F3\Viewhelpertest\Domain\Model\User $customer
	 */
	public function __construct(\DateTime $date, \F3\Viewhelpertest\Domain\Model\User $customer) {
		$this->date = $date;
		$this->customer = $customer;
	}
}
?>