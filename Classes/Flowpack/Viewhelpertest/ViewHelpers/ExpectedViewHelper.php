<?php
namespace Flowpack\Viewhelpertest\ViewHelpers;

/*                                                                        *
 * This script belongs to the FLOW3 package "Flowpack.Viewhelpertest".    *
 *                                                                        */

use Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper;

/**
 *
 */
class ExpectedViewHelper extends AbstractViewHelper {

	/**
	 * @param boolean $regex
	 * @return void
	 */
	public function render($regex = FALSE) {
		$source = trim($this->renderChildren());
		$this->viewHelperVariableContainer->addOrUpdate('Flowpack\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'source', $source);
		$this->viewHelperVariableContainer->addOrUpdate('Flowpack\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'regex', $regex);
	}
}


?>
