<?php
namespace Flowpack\Viewhelpertest\Domain\Model;

/*                                                                        *
 * This script belongs to the FLOW3 package "Flowpack.Viewhelpertest".    *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * An Invoice
 *
 * @Flow\Entity
 */
class Invoice {

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $subject;

	/**
	 * @var \DateTime
	 */
	protected $date;

	/**
	 * @ORM\ManyToOne(inversedBy="invoices")
	 * @var \Flowpack\Viewhelpertest\Domain\Model\User
	 */
	protected $customer;

	/**
	 * @param string $subject
	 * @return void
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}

	/**
	 * @return string
	 */
	public function getSubject() {
		return $this->subject;
	}

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
	 * @param \Flowpack\Viewhelpertest\Domain\Model\User $customer
	 * @return void
	 */
	public function setCustomer(\Flowpack\Viewhelpertest\Domain\Model\User $customer) {
		$this->customer = $customer;
	}

	/**
	 * @return \Flowpack\Viewhelpertest\Domain\Model\User
	 */
	public function getCustomer() {
		return $this->customer;
	}
}
?>