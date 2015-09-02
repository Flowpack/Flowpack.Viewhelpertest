<?php
namespace Flowpack\Viewhelpertest\ViewHelpers\Widget;

/*                                                                        *
 * This script belongs to the FLOW3 package "Flowpack.Viewhelpertest".    *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Fluid\Core\Widget\AbstractWidgetViewHelper;

class RedirectViewHelper extends AbstractWidgetViewHelper {

	/**
	 * @Flow\Inject
	 * @var \Flowpack\Viewhelpertest\ViewHelpers\Widget\Controller\RedirectController
	 */
	protected $controller;

	/**
	 * @return string
	 */
	public function render() {
		return $this->initiateSubRequest();
	}
}