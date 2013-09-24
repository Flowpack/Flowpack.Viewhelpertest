<?php
namespace TYPO3\Viewhelpertest\Controller;

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
use TYPO3\Flow\Mvc\Controller\ActionController;
use TYPO3\Flow\Security\Account;
use TYPO3\Flow\Security\Policy\Role;
use TYPO3\Viewhelpertest\Domain\Model\Invoice;
use TYPO3\Viewhelpertest\Domain\Model\User;

/**
 * Viewhelpertest common base Controller
 */
abstract class AbstractBaseController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Viewhelpertest\Domain\Repository\UserRepository
	 */
	protected $userRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
	 */
	protected $authenticationManager;

	/**
	 * @return void
	 */
	protected function loginTestAccount() {
		$account = new Account();
		$account->addRole(new Role('TYPO3.Viewhelpertest:TestRole'));

		/** @var $securityContext \TYPO3\Flow\Security\Context */
		$securityContext = $this->authenticationManager->getSecurityContext();

		$authenticationTokens = $securityContext->getAuthenticationTokensOfType('TYPO3\Flow\Security\Authentication\Token\UsernamePassword');
		$authenticationTokens[0]->setAccount($account);
		$authenticationTokens[0]->setAuthenticationStatus(\TYPO3\Flow\Security\Authentication\TokenInterface::AUTHENTICATION_SUCCESSFUL);
	}

	/**
	 * @param integer $id
	 * @param string $firstName
	 * @param string $lastName
	 * @param boolean $newsletter
	 * @param array $interests
	 * @return User
	 */
	protected function createUser($id, $firstName, $lastName, $newsletter = FALSE, array $interests = array()) {
		return new User($id, $firstName, $lastName, $newsletter, $interests);
	}

	/**
	 * @param integer $id
	 * @param string $firstName
	 * @param string $lastName
	 * @param boolean $newsletter
	 * @param array $interests
	 * @return User
	 */
	protected function addUser($id, $firstName, $lastName, $newsletter = FALSE, array $interests = array()) {
		$user = $this->createUser($id, $firstName, $lastName, $newsletter, $interests);
		$this->userRepository->add($user);
		return $user;
	}


	/**
	 * @param User $user
	 * @param string $subject
	 * @param \DateTime $date
	 * @return Invoice
	 */
	protected function createInvoice(User $user, $subject, $date = NULL) {
		$invoice = new Invoice();
		$invoice->setSubject($subject);
		$invoice->setDate($date !== NULL ? $date : new \DateTime());
		$user->addInvoice($invoice);
		return $invoice;
	}

	/**
	 * @return boolean
	 */
	protected function getErrorFlashMessage() {
		return FALSE;
	}
}
?>
