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

/**
 * Viewhelpertest Render Controller
 */
class WidgetController extends AbstractBaseController {

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
	public function ajaxWidgetContextResetAction() {
		$this->view->assign('testData', array(
			$this->userRepository->findByFirstName('Sebastian'),
			$this->userRepository->findByFirstName('Robert')
		));
	}

	/**
	 * @return void
	 */
	public function paginateWidgetAction() {
		$this->view->assign('users', $this->userRepository->findAll());
	}

	/**
	 * @return void
	 */
	public function redirectAction() {
	}

	/**
	 * @return void
	 */
	public function createUsers() {
		$this->userRepository->removeAll();
		$this->addUser(1, 'Rens', 'Admiraal');
		$this->addUser(2, 'Karsten', 'Dambekalns');
		$this->addUser(3, 'Aske', 'Ertmann');
		$this->addUser(4, 'Adrian', 'Föder');
		$this->addUser(5, 'Andreas', 'Förthner');
		$this->addUser(6, 'Berit', 'Hlubek');
		$this->addUser(7, 'Christopher', 'Hlubek');
		$this->addUser(8, 'Julle', 'Jensen');
		$this->addUser(9, 'Sebastian', 'Kurfürst');
		$this->addUser(10, 'Robert', 'Lemke');
		$this->addUser(11, 'Christian', 'Müller');
		$this->addUser(12, 'Bastian', 'Waidelich');
	}

}
?>
