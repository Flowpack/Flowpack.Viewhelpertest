<?php
namespace TYPO3\Viewhelpertest\ViewHelpers\Widget;

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
use TYPO3\Fluid\Core\Widget\AbstractWidgetViewHelper;

class RedirectViewHelper extends AbstractWidgetViewHelper {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Viewhelpertest\ViewHelpers\Widget\Controller\RedirectController
	 */
	protected $controller;

	/**
	 * @return string
	 */
	public function render() {
		return $this->initiateSubRequest();
	}
}