<?php
declare(ENCODING = 'utf-8');
namespace F3\Viewhelpertest\Controller;

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
 * @package Viewhelpertest
 * @subpackage Controller
 * @version $Id$
 */
/**
 * Viewhelpertest Default Controller
 *
 * @package Viewhelpertest
 * @subpackage Controller
 * @version $Id$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class StandardController extends \F3\FLOW3\MVC\Controller\ActionController {

	/**
	 * @inject
	 * @var \F3\FLOW3\Security\Authentication\AuthenticationManagerInterface
	 */
	protected $authenticationManager;

	/**
	 * @inject
	 * @var F3\FLOW3\Security\Context
	 */
	protected $securityContext;

	/**
	 * @param array $selectedPartials
	 * @return void
	 */
	public function indexAction(array $selectedPartials = array()) {
		$allowedPartials = $this->settings['includeViewHelpers'];
		if (isset($selectedPartials[0]) && strlen($selectedPartials[0]) === 0) {
			$selectedPartials = $allowedPartials;
		} else {
			$selectedPartials = array_intersect($allowedPartials, $selectedPartials);
		}
		$this->view->assign('allowedPartials', $allowedPartials);
		$this->view->assign('selectedPartials', $selectedPartials);
		if (in_array('flashMessages', $selectedPartials)) {
			$this->flashMessageContainer->add('Some dummy flash message at ' . date('H:i:s'));
			$this->flashMessageContainer->add('Another dummy flash message.');
		}
		if (in_array('security.ifAccess', $selectedPartials) || in_array('security.ifAuthenticated', $selectedPartials) || in_array('security.ifHasRole', $selectedPartials)) {
			$this->loginTestuser();
		}

		$this->view->assign('testVariables', $this->createTestVariables());
	}

	/**
	 * @return void
	 */
	public function allowedAction() {
	}
	public function deniedAction() {
	}

	protected function loginTestuser() {
		$account = $this->objectManager->create('F3\FLOW3\Security\Account');
		$roles = array(
			$this->objectManager->create('F3\FLOW3\Security\Policy\Role', 'TestRole'),
		);
		$account->setAuthenticationProviderName('DefaultProvider');
		$account->setRoles($roles);

		$authenticationTokens = $this->securityContext->getAuthenticationTokensOfType('F3\FLOW3\Security\Authentication\Token\UsernamePassword');
		if (count($authenticationTokens) === 1) {
			$authenticationTokens[0]->setAccount($account);
			$authenticationTokens[0]->setAuthenticationStatus(\F3\FLOW3\Security\Authentication\TokenInterface::AUTHENTICATION_SUCCESSFUL);
		}
	}

	/**
	 * @return array
	 */
	protected function createTestVariables() {
		$user1 = new User(1, 'Ingmar', 'Schlecht', TRUE);
		$user2 = new User(3, 'Sebastian', 'Kurfürst', FALSE);
		$user3 = new User(2, 'Robert', 'Lemke', TRUE);
		$userDomainObject = new \F3\Viewhelpertest\Domain\Model\User('Kasper', 'Skårhøj', TRUE, array('TYPO3', 'Snowboarding', 'Architecture'));
		$testVariables = array(
			'text' => 'this is some text with newlines' . chr(10) . 'and special characters: äöüß',
			'array' => array('a', 'b', 'c', 'd', 'e'),
			'fruits' => array(array('name' => 'blackberry', 'type' => 'berry'), array('name' => 'orange', 'type' => 'citrus fruit'), array('name' => 'cranberry', 'type' => 'berry'), array('name' => 'pear', 'type' => 'core'), array('name' => 'lemon', 'type' => 'citrus fruit'), array('name' => 'grape', 'type' => 'berry'), array('name' => 'apple', 'type' => 'core')),
			'emptyArray' => array(),
			'null' => NULL,
			'selected' => array(3, 2),
			'user1' => $user1,
			'user2' => $user2,
			'user3' => $user3,
			'users' => array($user1, $user2, $user3),
			'userDomainObject' => $userDomainObject,
			'date' => new \DateTime(),
			'htmlContent' => 'This should be <b>bold</b> and <i>italic</i>',
			'stdClass1' => new \stdClass(),
			'stdClass2' => new \stdClass(),
			'integer' => 1,
			'float' => 1.1,
			'string' => 'foo',
			'htmlContent' => 'This should be <b>bold</b> and <i>italic</i>',
			'boolean' => array('true' => TRUE, 'false' => FALSE),
			'number' => array('zero' => 0, 'one' => 1, 'minusOne' => -1),
		);
		return $testVariables;
	}
}

class User {
	protected $id;
	protected $firstName;
	protected $lastName;
	protected $newsletter;

	public function __construct($id, $firstName, $lastName, $newsletter) {
		$this->id = $id;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->newsletter = $newsletter;
	}
	public function getId() {
		return $this->id;
	}
	public function getFirstName() {
		return $this->firstName;
	}
	public function getLastName() {
		return $this->lastName;
	}
	public function getNewsletter() {
		return $this->newsletter;
	}
	public function __toString() {
		return $this->firstName . ' ' . $this->lastName;
	}
}
?>