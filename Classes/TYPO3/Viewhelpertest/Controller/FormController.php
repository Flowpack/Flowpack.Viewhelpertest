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
use TYPO3\Viewhelpertest\Domain\Model\Invoice;
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
	 * @param \TYPO3\Viewhelpertest\Domain\Model\User $user
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
	 * @param \TYPO3\Viewhelpertest\Domain\Model\User $user
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
	public function createUsers() {
		$this->userRepository->removeAll();
		$user = $this->createUser(1, 'John', 'Doe');

		$this->createInvoice($user, 'invoice 01');
		$this->createInvoice($user, 'invoice 02');

		$this->userRepository->add($user);
	}
}
?>
