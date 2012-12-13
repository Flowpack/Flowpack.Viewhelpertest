<?php
namespace TYPO3\Viewhelpertest\ViewHelpers;

/*                                                                        *
 * This script belongs to the FLOW3 package "Viewhelpertest".             *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * This is the TemplateView for the Highlight ViewHelper. To properly test
 * <f:render>, <f:section> and the like, we need a properly initialized
 * TemplateView, which is also fresh (== clean state)
 */
class TemplateViewForHighlightViewHelper extends \TYPO3\Fluid\View\TemplateView {

	/**
	 * The source of the template
	 * @var string
	 */
	protected $templateSource;

	/**
	 * The ViewHelperVariableContainer of the surrounding area,
	 * this makes it possible to test single form elements inside a surrounding
	 * <f:form>.
	 * @var \TYPO3\Fluid\Core\ViewHelper\ViewHelperVariableContainer
	 */
	protected $viewHelperVariableContainer;

	/**
	 * Return the template source to the Parser.
	 *
	 * @param string $actionName the name of the action to render.
	 * @return string
	 */
	protected function getTemplateSource($actionName = NULL) {
		return $this->templateSource;
	}

	/**
	 * @param string $templateSource
	 * @return void
	 */
	public function setTemplateSource($templateSource) {
		$this->templateSource = $templateSource;
	}

	/**
	 * Set the current ViewHelperVariableContainer.
	 *
	 * @param \TYPO3\Fluid\Core\ViewHelper\ViewHelperVariableContainer $viewHelperVariableContainer
	 * @return void
	 */
	public function setViewHelperVariableContainer(\TYPO3\Fluid\Core\ViewHelper\ViewHelperVariableContainer $viewHelperVariableContainer) {
		$this->viewHelperVariableContainer = clone $viewHelperVariableContainer;
	}

	/**
	 * Overridden Render method.
	 *
	 * @param string $actionName render the given action.
	 * @return string the rendered result
	 */
	public function render($actionName = NULL) {
		$this->baseRenderingContext->injectViewHelperVariableContainer($this->viewHelperVariableContainer);
		$this->baseRenderingContext->getViewHelperVariableContainer()->setView($this);
		return parent::render($actionName);
	}

	/**
	 * We use a checksum of the template source as the template identifier
	 *
	 * @param string $actionName
	 * @return string
	 */
	protected function getTemplateIdentifier($actionName = NULL) {
		return 'viewhelpertest_' . sha1($this->templateSource);
	}
}
?>