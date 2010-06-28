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
 * @version $Id: StandardController.php 4596 2010-06-18 09:13:48Z sebastian $
 */
/**
 * Viewhelpertest Default Controller
 *
 * @package Viewhelpertest
 * @subpackage Controller
 * @version $Id: StandardController.php 4596 2010-06-18 09:13:48Z sebastian $
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class RenderController extends \F3\FLOW3\MVC\Controller\ActionController {

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