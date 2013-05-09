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

abstract class AbstractSubTemplateRenderingViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @var \TYPO3\Viewhelpertest\ViewHelpers\TemplateViewForHighlightViewHelper
	 */
	protected $templateView;

	/**
	 * @param \TYPO3\Viewhelpertest\ViewHelpers\TemplateViewForHighlightViewHelper $templateView
	 * @return void
	 */
	public function injectTemplateView(\TYPO3\Viewhelpertest\ViewHelpers\TemplateViewForHighlightViewHelper $templateView) {
		$this->templateView = $templateView;
	}

	/**
	 * @param $source
	 * @return mixed
	 */
	protected function renderSource($source) {
		$this->templateView->setTemplateSource($source);
		$this->templateView->setControllerContext($this->renderingContext->getControllerContext());
		$this->templateView->setViewHelperVariableContainer($this->viewHelperVariableContainer);

		$this->templateView->assign('testVariables', $this->templateVariableContainer->get('testVariables'));
		$this->templateView->assign('settings', $this->templateVariableContainer->get('settings'));

		return $this->templateView->render();
	}
}

?>