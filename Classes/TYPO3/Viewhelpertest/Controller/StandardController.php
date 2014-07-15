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

/**
 * Viewhelpertest Default Controller
 */
class StandardController extends AbstractBaseController {

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
			$this->addFlashMessage('Error flash message with %s content.', 'Flash message title', Message::SEVERITY_ERROR, array('dynamic'), 123);
		}
		if (in_array('security.ifAccess', $selectedPartials) || in_array('security.ifAuthenticated', $selectedPartials) || in_array('security.ifHasRole', $selectedPartials)) {
			$this->loginTestAccount();
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

	/**
	 * @return array
	 */
	protected function createTestVariables() {
		$user1 = $this->createUser(1, 'Ingmar', 'Schlecht', TRUE);
		$user2 = $this->createUser(2, 'Sebastian', 'Kurfürst', FALSE);
		$user3 = $this->createUser(3, 'Robert', 'Lemke', TRUE);
		$user4 = $this->createUser(4, 'Kasper', 'Skårhøj', TRUE, array('TYPO3', 'Snowboarding', 'Architecture'));
		$user5 = $this->createUser(5, 'Xaver', 'Cross <b>Site-Scripting</b>');
		$user6 = $this->createUser(6, 'Ernest', '&lt;b&gt;Escape&lt;/b&gt;');
		$invoice1 = $this->createInvoice($user1, 'Invoice #1', new \DateTime('1980-12-13'));
		$invoice2 = $this->createInvoice($user2, 'Invoice #2', new \DateTime('2010-07-01'));
		$invoice3 = $this->createInvoice($user1, 'Invoice #3', new \DateTime('2010-07-04'));
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
			'user5' => $user5,
			'user6' => $user6,
			'users' => array($user1, $user2, $user3, $user4),
			'date' => new \DateTime(),
			'stdClass1' => new \stdClass(),
			'stdClass2' => new \stdClass(),
			'integer' => 1,
			'float' => 1.1,
			'largeNumber' => pow(1024, 8),
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