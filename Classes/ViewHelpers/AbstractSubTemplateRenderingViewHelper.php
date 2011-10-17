<?php
namespace TYPO3\Viewhelpertest\ViewHelpers;

/*                                                                        *
 * This script belongs to the FLOW3 package "Viewhelpertest".             *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

abstract class AbstractSubTemplateRenderingViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper implements \TYPO3\Fluid\Core\ViewHelper\Facets\ChildNodeAccessInterface {

	/**
	 * @var TYPO3\Viewhelpertest\ViewHelpers\TemplateViewForHighlightViewHelper
	 */
	protected $templateView;

	/**
	 * @param \TYPO3\Viewhelpertest\ViewHelpers\TemplateViewForHighlightViewHelper $templateView
	 * @return void
	 * @author Sebastian Kurf√ºrst <sebastian@typo3.org>
	 */
	public function injectTemplateView(\TYPO3\Viewhelpertest\ViewHelpers\TemplateViewForHighlightViewHelper $templateView) {
		$this->templateView = $templateView;
	}

	/**
	 * We only need to implement this method because we want to call $this->getRenderingContext(), and for that, we need
	 * to implement ChildNodeAccessInterface, which in turn requires this method to exist.
	 *
	 * @param array $childNodes
	 * @return void
	 * @author Sebastian Kurf√ºrst <sebastian@typo3.org>
	 */
	public function setChildNodes(array $childNodes) {
	}


	protected function renderSource($source) {
		$this->templateView->setTemplateSource($source);
		$this->templateView->setControllerContext($this->getRenderingContext()->getControllerContext());
		$this->templateView->setViewHelperVariableContainer($this->viewHelperVariableContainer);

		$this->templateView->assign('testVariables', $this->templateVariableContainer->get('testVariables'));
		$this->templateView->assign('settings', $this->templateVariableContainer->get('settings'));

		return $this->templateView->render();
	}
}

?>