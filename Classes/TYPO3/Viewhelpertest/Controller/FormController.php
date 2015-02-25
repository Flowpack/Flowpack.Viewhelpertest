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
use TYPO3\Flow\Error\Message;
use TYPO3\Viewhelpertest\Domain\Model\User;

/**
 * Viewhelpertest Form Controller
 */
class FormController extends AbstractBaseController {

	/**
	 * @param string $redirectAction
	 * @return void
	 */
	public function setupAction($redirectAction) {
		$this->createUsers();

		// This is needed in case the setup action is called with GET
		$this->persistenceManager->persistAll();

		$this->redirect($redirectAction);
	}

	/**
	 * @return void
	 */
	public function identityPropertiesAction() {
		$this->view->assign('user', $this->userRepository->findAll()->getFirst());
	}

	/**
	 * @param User $user
	 * @return void
	 */
	public function identityPropertiesValidateAction(User $user) {
		$this->userRepository->update($user);
		$this->addFlashMessage('Updated user "%s"', 'success', Message::SEVERITY_OK, array($user->getFirstName()));
		$this->redirect('identityProperties');
	}

	/**
	 * @return void
	 */
	public function nestedFormsAction() {
		$this->view->assign('user', $this->userRepository->findAll()->getFirst());
	}

	/**
	 * @param User $user
	 * @return void
	 */
	public function nestedFormsValidateAction(User $user) {
		$this->userRepository->update($user);
		$this->addFlashMessage('Updated user "%s"', 'success', Message::SEVERITY_OK, array($user->getFirstName()));
		$this->redirect('nestedForms');
	}

	/**
	 * @return void
	 */
	public function defaultValuesAction() {
		$this->view->assign('user', $this->userRepository->findAll()->getFirst());
	}

	/**
	 * @param User $user1
	 * @param User $user2
	 * @param User $user3
	 * @return void
	 */
	public function defaultValuesValidateAction(User $user1 = NULL, User $user2 = NULL, User $user3 = NULL) {
		if ($user1 !== NULL) {
			$user = $user1;
		} elseif ($user2 !== NULL) {
			$user = $user2;
		} else {
			$user = $user3;
		}
		if ($user === NULL) {
			$this->addFlashMessage('Missing user data', 'error', Message::SEVERITY_ERROR);
			$this->redirect('defaultValues');
		}
		if ($this->persistenceManager->isNewObject($user)) {
			$this->userRepository->add($user);
			$this->addFlashMessage('Added user "%s"', 'success', Message::SEVERITY_OK, array($user->getFirstName()));
		} else {
			$this->userRepository->update($user);
			$this->addFlashMessage('Updated user "%s"', 'success', Message::SEVERITY_OK, array($user->getFirstName()));
		}
		$this->redirect('defaultValues');
	}


	/**
	 * @return void
	 */
	protected function createUsers() {
		$this->userRepository->removeAll();
		$user = $this->createUser(1, 'John', 'Doe', TRUE, array('Flow', 'Neos', 'Music'), User::TITLE_MR);

		$this->createInvoice($user, 'invoice 01');
		$this->createInvoice($user, 'invoice 02');

		$this->userRepository->add($user);
	}
}