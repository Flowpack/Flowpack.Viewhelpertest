<?php
namespace Flowpack\Viewhelpertest\ViewHelpers\Widget\Controller;

/*                                                                        *
 * This script belongs to the FLOW3 package "Flowpack.Viewhelpertest".    *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Fluid\Core\Widget\AbstractWidgetController;

class RedirectController extends AbstractWidgetController {

	/**
	 * Initial action (showing different links)
	 *
	 * @return void
	 */
	public function indexAction() {
	}

	/**
	 * The target action for redirects/forwards
	 *
	 * @param string $parameter
	 * @return void
	 */
	public function targetAction($parameter = NULL) {
		$this->view->assign('parameter', $parameter);
	}

	/**
	 * @param integer $delay
	 * @param string $parameter
	 * @param boolean $otherController
	 * @return void
	 */
	public function redirectTestAction($delay = 0, $parameter = NULL, $otherController = FALSE) {
		$this->addFlashMessage('Redirection triggered!');
		$arguments = array();
		if ($parameter !== NULL) {
			$arguments['parameter'] = $parameter . ', via redirect';
		}
		$action = $otherController ? 'index' : 'target';
		$controller = $otherController ? 'Paginate' : NULL;
		$package = $otherController ? 'TYPO3.Fluid\ViewHelpers\Widget' : NULL;
		$this->redirect($action, $controller, $package, $arguments, $delay);
	}

	/**
	 * @param string $parameter
	 * @param boolean $otherController
	 * @return void
	 */
	public function forwardTestAction($parameter = NULL, $otherController = FALSE) {
		$this->addFlashMessage('Forward triggered!');
		$arguments = array();
		if ($parameter !== NULL) {
			$arguments['parameter'] = $parameter . ', via forward';
		}
		$action = $otherController ? 'index' : 'target';
		$controller = $otherController ? 'Standard' : NULL;
		$package = $otherController ? 'TYPO3.Flow\Mvc' : NULL;
		$this->forward($action, $controller, $package, $arguments);
	}
}