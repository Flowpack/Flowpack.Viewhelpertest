<?php
namespace Flowpack\Viewhelpertest\Controller;

/*                                                                        *
 * This script belongs to the FLOW3 package "Flowpack.Viewhelpertest".    *
 *                                                                        */

/**
 * Viewhelpertest Render Controller
 */
class RenderController extends AbstractBaseController {

	public function optionalSectionsAction() {
		// TODO
	}

	public function renderSectionInLayoutUsesTemplateVariablesAction() {
		$this->view->assign('variable', 'This variable is set from within the controller.');
	}

	public function renderSectionInPartialNeedsArgumentsExplicitelyAction() {
		$this->view->assign('variable', 'This variable is set from within the controller.');
	}

	public function renderPartialNeedsArgumentsExplicitelyAction() {
		$this->view->assign('variable', 'This variable is set from within the controller.');
	}

	public function renderSectionInsidePartialNeedsArgumentsExplicitelyAction() {
		$this->view->assign('variable', 'This variable is set from within the controller.');
	}

	public function renderSectionInsideTemplateNeedsArgumentsExplicitelyAction() {
		$this->view->assign('variable', 'This variable is set from within the controller.');
	}

	public function recursiveSectionsAction() {
		$this->view->assign('testVariables', array('menu' => array(
			array(
				'text' => 'Item 1',
				'subItems' => array(
					array(
						'text' => 'Item 1.1',
						'subItems' => array(
							array('text' => 'Item 1.1.1')
						)
					),
					array('text' => 'Item 1.2')
				)
			),
			array('text' => 'Item 2'),
		)));
	}
}
?>