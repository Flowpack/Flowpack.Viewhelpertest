<?php
namespace TYPO3\Viewhelpertest\Domain\Model;

/*                                                                        *
 * This script belongs to the FLOW3 package "TYPO3.Viewhelpertest".       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A User
 *
 * @Flow\Entity
 */
class User {

	const TITLE_MR = 'mr';
	const TITLE_MRS = 'mrs';

	/**
	 * @var integer
	 */
	protected $id;

	/**
	 * @ORM\Column(nullable=true)
	 * @var string one of the TITLE_* constants
	 */
	protected $title;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $firstName;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $lastName;

	/**
	 * @var boolean
	 */
	protected $newsletter = FALSE;

	/**
	 * @var array<string>
	 * @Flow\Validate(type="Count")
	 */
	protected $interests = array();

	/**
	 * @var \Doctrine\Common\Collections\Collection<\TYPO3\Viewhelpertest\Domain\Model\Invoice>
	 * @ORM\OneToMany(mappedBy="customer", cascade={"all"})
	 */
	protected $invoices;

	/**
	 * @param integer $id
	 * @param string $firstName
	 * @param string $lastName
	 * @param boolean $newsletter
	 * @param array $interests
	 */
	public function __construct($id, $firstName = '', $lastName = '', $newsletter = FALSE, array $interests = NULL, $title = NULL) {
		$this->id = $id;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->newsletter = (boolean)$newsletter;
		if ($interests !== NULL) {
			$this->interests = $interests;
		}
		$this->title = $title;
		$this->invoices = new ArrayCollection();
	}

	/**
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
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
	 * @param \Doctrine\Common\Collections\Collection<Invoice> $invoices
	 * @return void
	 */
	public function setInvoices(Collection $invoices) {
		$this->invoices = $invoices;
	}

	/**
	 * @param Invoice $invoice
	 * @return void
	 */
	public function addInvoice(Invoice $invoice) {
		$invoice->setCustomer($this);
		$this->invoices->add($invoice);
	}

	/**
	 * @return Collection
	 */
	public function getInvoices() {
		return $this->invoices;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return sprintf('%s %s', $this->firstName, $this->lastName);
	}
}
?>