<?php
declare(ENCODING = 'utf-8');
namespace F3\Viewhelpertest\ViewHelpers;

/* *
 * This script belongs to the FLOW3 framework.                            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * This is the TemplateView for the Highlight ViewHelper. To properly test
 * <f:render>, <f:section> and the like, we need a properly initialized
 * TemplateView, which is also fresh (== clean state)
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class TemplateViewForHighlightViewHelper extends \F3\Fluid\View\TemplateView {

	/**
	 * The source of the template
	 * @var string
	 */
	protected $templateSource;

	/**
	 * The ViewHelperVariableContainer of the surrounding area,
	 * this makes it possible to test single form elements inside a surrounding
	 * <f:form>.
	 * @var F3\Fluid\Core\ViewHelper\ViewHelperVariableContainer
	 */
	protected $viewHelperVariableContainer;

	/**
	 * Return the template sourc to the Parser.
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
	 * @param \F3\Fluid\Core\ViewHelper\ViewHelperVariableContainer $viewHelperVariableContainer
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function setViewHelperVariableContainer(\F3\Fluid\Core\ViewHelper\ViewHelperVariableContainer $viewHelperVariableContainer) {
		$this->viewHelperVariableContainer = clone $viewHelperVariableContainer;
	}

	/**
	 * Overridden Render method.
	 *
	 * @param string $actionName render the given action.
	 * @return string the rendered result
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function render($actionName = NULL) {
		$this->baseRenderingContext->injectViewHelperVariableContainer($this->viewHelperVariableContainer);
		$this->baseRenderingContext->getViewHelperVariableContainer()->setView($this);
		return parent::render($actionName);
	}
}
?>