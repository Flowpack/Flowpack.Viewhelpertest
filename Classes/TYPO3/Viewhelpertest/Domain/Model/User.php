<?php
namespace TYPO3\Viewhelpertest\Domain\Model;

/*                                                                        *
 * This script belongs to the FLOW3 package "Viewhelpertest".             *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * A User
 *
 * @Flow\Scope("prototype")
 * @Flow\Entity
 */
class User {

	/**
	 * @var integer
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $firstName;

	/**
	 * @var string
	 */
	protected $lastName;

	/**
	 * @var boolean
	 */
	protected $newsletter = FALSE;

	/**
	 * @var array
	 */
	protected $interests = array();

	/**
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param string $firstName
	 * @return void
	 */
	public function setFirstName($firstName) {
		$this->firstName = $firstName;
	}
	/**
	 * @return string
	 */
	public function getFirstName() {
		return $this->firstName;
	}

	/**
	 * @param string $lastName
	 * @return void
	 */
	public function setLastName($lastName) {
		$this->lastName = $lastName;
	}
	/**
	 * @return string
	 */
	public function getLastName() {
		return $this->lastName;
	}

	/**
	 * @param boolean $newsletter
	 * @return void
	 */
	public function setNewsletter($newsletter) {
		$this->newsletter = $newsletter;
	}
	/**
	 * @return boolean
	 */
	public function getNewsletter() {
		return $this->newsletter;
	}

	/**
	 * @param array $interests
	 * @return void
	 */
	public function setInterests(array $interests) {
		$this->interests = $interests;
	}
	/**
	 * @return array
	 */
	public function getInterests() {
		return $this->interests;
	}

	/**
	 * @param integer $id
	 * @param string $firstName
	 * @param string $lastName
	 * @param boolean $newsletter
	 * @param array $interests
	 */
	public function __construct($id, $firstName = '', $lastName = '', $newsletter = FALSE, array $interests = NULL) {
		$this->id = $id;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->newsletter = (boolean)$newsletter;
		if ($interests !== NULL) {
			$this->interests = $interests;
		}
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return sprintf('%s %s', $this->firstName, $this->lastName);
	}
}
?>