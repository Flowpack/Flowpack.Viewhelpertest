<?php
declare(ENCODING = 'utf-8');
namespace TYPO3\Viewhelpertest\Controller;

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
 * Viewhelpertest Default Controller
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class PerformanceController extends \TYPO3\FLOW3\MVC\Controller\ActionController {

	public function indexAction() {
		$this->startProfiler();
		$this->view->render();
		$this->stopProfiler();
	}

	protected function startProfiler() {
		if (!function_exists('xhprof_enable')) {
			return;
		}

		xhprof_enable();
	}

	protected function stopProfiler() {
		if (!function_exists('xhprof_enable')) {
			return;
		}

		$xhprofData = xhprof_disable();

		include_once $this->settings['xhprofRoot'] . '/xhprof_lib/utils/xhprof_lib.php';
		include_once $this->settings['xhprofRoot'] . '/xhprof_lib/utils/xhprof_runs.php';

		$xhprofRun = new \XHProfRuns_Default();

		$xhprofRun->save_run(
			$xhprofData,
			'xhprof',
			'vhtest_' .
			$this->settings['optimizationIdentifier'] . '_' .
			$this->request->getControllerActionName() . '_' .
			date('Y-m-d_H:m:s'));
	}
}
?>