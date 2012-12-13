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

/**
 * Viewhelpertest Render Controller
 */
class WidgetController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @var \TYPO3\Viewhelpertest\Domain\Repository\UserRepository
	 */
	protected $userRepository;

	/**
	 * @param \TYPO3\Viewhelpertest\Domain\Repository\UserRepository $userRepository
	 */
	public function injectUserRepository(\TYPO3\Viewhelpertest\Domain\Repository\UserRepository $userRepository) {
		$this->userRepository = $userRepository;
	}

	public function setupTestDataAction() {
		$this->userRepository->removeAll();
		$user1 = new \TYPO3\Viewhelpertest\Domain\Model\User(1, 'Sebastian', 'Kurfuerst');
		$user2 = new \TYPO3\Viewhelpertest\Domain\Model\User(2, 'Robert', 'Lemke');
		$this->userRepository->add($user1);
		$this->userRepository->add($user2);

		$this->redirect('ajaxWidgetContextReset');
	}

	public function ajaxWidgetContextResetAction() {
		$this->view->assign('testData', array(
			$this->userRepository->findByFirstName('Sebastian'),
			$this->userRepository->findByFirstName('Robert')
		));
	}
}
?>