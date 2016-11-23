<?php
namespace Flowpack\Viewhelpertest\ViewHelpers;

/*                                                                        *
 * This script belongs to the FLOW3 package "Flowpack.Viewhelpertest".    *
 *                                                                        */

class IssetViewHelper extends \Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @return string
	 */
	public function render() {
		return $this->renderChildren() !== NULL;
	}
}


?>
