<?php
namespace TYPO3\Viewhelpertest\ViewHelpers;

/*                                                                        *
 * This script belongs to the FLOW3 package "TYPO3.Viewhelpertest".       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

class ExpectedViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @param boolean $regex
	 * @return void
	 */
	public function render($regex = FALSE) {
		$source = trim($this->renderChildren());
		$this->viewHelperVariableContainer->addOrUpdate('TYPO3\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'source', $source);
		$this->viewHelperVariableContainer->addOrUpdate('TYPO3\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'regex', $regex);
	}
}


?>
