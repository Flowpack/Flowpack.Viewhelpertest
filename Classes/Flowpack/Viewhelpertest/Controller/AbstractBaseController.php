<?php
namespace Flowpack\Viewhelpertest\Controller;

/*                                                                        *
 * This script belongs to the FLOW3 package "Flowpack.Viewhelpertest".    *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use TYPO3\Flow\Security\Account;
use TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface;
use TYPO3\Flow\Security\Authentication\TokenInterface;
use TYPO3\Flow\Security\Policy\PolicyService;
use Flowpack\Viewhelpertest\Domain\Model\Invoice;
use Flowpack\Viewhelpertest\Domain\Model\User;
use Flowpack\Viewhelpertest\Domain\Repository\UserRepository;

/**
 * Viewhelpertest common base Controller
 */
abstract class AbstractBaseController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var UserRepository
	 */
	protected $userRepository;

	/**
	 * @Flow\Inject
	 * @var AuthenticationManagerInterface
	 */
	protected $authenticationManager;

	/**
	 * @Flow\Inject
	 * @var PolicyService
	 */
	protected $policyService;

	/**
	 * @return void
	 */
	protected function loginTestAccount() {
		$account = $this->getAccount('TestAccount', array('Flowpack.Viewhelpertest:TestRole1'));

		$securityContext = $this->authenticationManager->getSecurityContext();

		/** @var TokenInterface $authenticationToken */
		foreach ($securityContext->getAuthenticationTokensOfType('TYPO3\Flow\Security\Authentication\Token\UsernamePassword') as $authenticationToken) {
			$authenticationToken->setAccount($account);
			$authenticationToken->setAuthenticationStatus(TokenInterface::AUTHENTICATION_SUCCESSFUL);
		}
		$securityContext->refreshTokens();
 		$this->authenticationManager->authenticate();
	}

	/**
	 * @param string $accountIdentifier
	 * @param array $roleIdentifiers
	 * @return Account
	 */
	protected function getAccount($accountIdentifier, array $roleIdentifiers) {
		$account = new Account();
		$account->setAccountIdentifier($accountIdentifier);
		foreach ($roleIdentifiers as $roleIdentifier) {
			$account->addRole($this->policyService->getRole($roleIdentifier));
		}
		return $account;
	}


	/**
	 * @param integer $id
	 * @param string $firstName
	 * @param string $lastName
	 * @param boolean $newsletter
	 * @param array $interests
	 * @param string $title
	 * @return User
	 */
	protected function createUser($id, $firstName, $lastName, $newsletter = FALSE, array $interests = array(), $title = NULL) {
		return new User($id, $firstName, $lastName, $newsletter, $interests, $title);
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
