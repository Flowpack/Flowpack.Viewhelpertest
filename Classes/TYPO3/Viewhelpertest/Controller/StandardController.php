<?php
namespace TYPO3\Viewhelpertest\Controller;

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

/**
 * Viewhelpertest Default Controller
 */
class StandardController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\AccountFactory
	 */
	protected $accountFactory;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Session\SessionInterface
	 */
	protected $session;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
	 */
	protected $authenticationManager;

	/**
	 * @param string $selectedPartial
	 * @return void
	 */
	public function indexAction($selectedPartial = NULL) {
		$allowedPartials = $this->settings['includeViewHelpers'];

		if ($selectedPartial === 'all') {
			$selectedPartials = $allowedPartials;
		} elseif (in_array($selectedPartial, $allowedPartials)) {
			$selectedPartials = array($selectedPartial);
		} else {
			$selectedPartials = array();
		}
		$this->view->assign('allowedPartials', $allowedPartials);
		$this->view->assign('selectedPartials', $selectedPartials);
		if (in_array('flashMessages', $selectedPartials)) {
			$this->addFlashMessage('Some dummy flash message at ' . date('H:i:s'));
			$this->addFlashMessage('Error flash message with %s content.', 'Flash message title', \TYPO3\Flow\Error\Message::SEVERITY_ERROR, array('dynamic'), 123);
		}
		if (in_array('security.ifAccess', $selectedPartials) || in_array('security.ifAuthenticated', $selectedPartials) || in_array('security.ifHasRole', $selectedPartials)) {
			$this->loginTestAccount();
		}

		$this->view->assign('testVariables', $this->createTestVariables());
	}

	/**
	 * @return void
	 */
	protected function loginTestAccount() {
		$account = new \TYPO3\Flow\Security\Account();
		$account->addRole(new \TYPO3\Flow\Security\Policy\Role('TYPO3.Viewhelpertest:TestRole'));

		/** @var $securityContext \TYPO3\Flow\Security\Context */
		$securityContext = $this->authenticationManager->getSecurityContext();

		$authenticationTokens = $securityContext->getAuthenticationTokensOfType('TYPO3\Flow\Security\Authentication\Token\UsernamePassword');
		$authenticationTokens[0]->setAccount($account);
		$authenticationTokens[0]->setAuthenticationStatus(\TYPO3\Flow\Security\Authentication\TokenInterface::AUTHENTICATION_SUCCESSFUL);
	}

	/**
	 * @return void
	 */
	public function allowedAction() {
	}
	public function deniedAction() {
	}

	/**
	 * @return array
	 */
	protected function createTestVariables() {
		$user1 = new \TYPO3\Viewhelpertest\Domain\Model\User(1, 'Ingmar', 'Schlecht', TRUE);
		$user2 = new \TYPO3\Viewhelpertest\Domain\Model\User(2, 'Sebastian', 'Kurfürst', FALSE);
		$user3 = new \TYPO3\Viewhelpertest\Domain\Model\User(3, 'Robert', 'Lemke', TRUE);
		$user4 = new \TYPO3\Viewhelpertest\Domain\Model\User(4, 'Kasper', 'Skårhøj', TRUE, array('TYPO3', 'Snowboarding', 'Architecture'));
		$invoice1 = new \TYPO3\Viewhelpertest\Domain\Model\Invoice(new \DateTime('1980-12-13'), $user1);
		$invoice2 = new \TYPO3\Viewhelpertest\Domain\Model\Invoice(new \DateTime('2010-07-01'), $user2);
		$invoice3 = new \TYPO3\Viewhelpertest\Domain\Model\Invoice(new \DateTime('2010-07-04'), $user1);
		$testVariables = array(
			'simpleText' => 'Hello world!',
			'text' => 'this is some text with newlines' . chr(10) . 'and special characters: äöüß <script>alert(\'this should never be executed!!\')</script>',
			'array' => array('a', 'b', 'c', 'd', 'e'),
			'fruits' => array(array('name' => 'blackberry', 'type' => 'berry'), array('name' => 'orange', 'type' => 'citrus fruit'), array('name' => 'cranberry', 'type' => 'berry'), array('name' => 'pear', 'type' => 'core'), array('name' => 'lemon', 'type' => 'citrus fruit'), array('name' => 'grape', 'type' => 'berry'), array('name' => 'apple', 'type' => 'core')),
			'emptyArray' => array(),
			'null' => NULL,
			'selected' => array(3, 2),
			'user1' => $user1,
			'user2' => $user2,
			'user3' => $user3,
			'user4' => $user4,
			'users' => array($user1, $user2, $user3, $user4),
			'date' => new \DateTime(),
			'stdClass1' => new \stdClass(),
			'stdClass2' => new \stdClass(),
			'integer' => 1,
			'float' => 1.1,
			'string' => 'foo',
			'htmlContent' => 'This should be <b>bold</b> and <i>italic</i>',
			'boolean' => array('true' => TRUE, 'false' => FALSE),
			'number' => array('zero' => 0, 'one' => 1, 'minusOne' => -1, 'onePointOne' => 1.1, 'minusOnePointOne' => -1.1),
			'invoices' => new \ArrayObject(array('invoice1' => $invoice1, 'invoice2' => $invoice2, 'invoice3' => $invoice3)),
		);
		return $testVariables;
	}
}
?>