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
	 * @var \F3\Viewhelpertest\Domain\Repository\UserRepository
	 */
	protected $userRepository;

	public function indexAction() {
		$this->view->assign('text', 'this is some text with newlines' . chr(10) . 'and special characters: äöüß');
		$this->view->assign('array', array('a', 'b', 'c', 'd', 'e'));
		$this->view->assign('fruits', array(array('name' => 'blackberry', 'type' => 'berry'), array('name' => 'orange', 'type' => 'citrus fruit'), array('name' => 'cranberry', 'type' => 'berry'), array('name' => 'pear', 'type' => 'core'), array('name' => 'lemon', 'type' => 'citrus fruit'), array('name' => 'grape', 'type' => 'berry'), array('name' => 'apple', 'type' => 'core')));
		$this->view->assign('emptyArray', array());
		$this->view->assign('null', NULL);
		$this->view->assign('selected', array(3, 2));
		$user1 = new User(1, 'Ingmar', 'Schlecht', TRUE);
		$user2 = new User(3, 'Sebastian', 'Kurfuerst', FALSE);
		$user3 = new User(2, 'Robert', 'Lemke', TRUE);
		$this->view->assign('user1', $user1);
		$this->view->assign('user2', $user2);
		$this->view->assign('user3', $user3);
		$this->view->assign('users', array($user1, $user2, $user3));
		$userDomainObject = $this->userRepository->getOne();
		$this->view->assign('userDomainObject', $userDomainObject);
		$this->view->assign('date', new \DateTime());
	}

	public function setupAction() {
		$this->userRepository->removeAll();


		$user = $this->objectFactory->create('F3\Viewhelpertest\Domain\Model\User');
		$user->setFirstName('Kasper');
		$user->setLastName('Skårhøj');
		$user->setNewsletter(TRUE);
		$user->setInterests(array('TYPO3', 'Snowboarding', 'Architecture'));
		$role = $this->objectFactory->create('F3\Viewhelpertest\Domain\Model\Role');
		$role->setName('Friendly Ghost');
		$user->setRole($role);
		$this->userRepository->add($user);
		$this->redirect('index');
	}

	public function flashMessagesAction() {
		$this->flashMessageContainer->add('Some dummy flash message at ' . date('H:i:s'));
		$this->flashMessageContainer->add('Another dummy flash message.');
		$this->redirect('index');
	}

	/**
	 * @param \F3\Viewhelpertest\Domain\Model\User $user
	 * @return void
	 */
	public function validateAction(\F3\Viewhelpertest\Domain\Model\User $user) {
		var_dump($user);
	}

	/**
	 * Save the updated user
	 *
	 * @param F3\Viewhelpertest\Domain\Model\User $user The user to save
	 */
	public function saveAction(\F3\Viewhelpertest\Domain\Model\User $user) {
		$this->userRepository->update($user);
		//return "Hallo";
		$this->redirect('index');
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