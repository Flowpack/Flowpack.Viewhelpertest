<?php
namespace Flowpack\Viewhelpertest\ViewHelpers;

/*                                                                        *
 * This script belongs to the FLOW3 package "Flowpack.Viewhelpertest".    *
 *                                                                        */

use Flowpack\Viewhelpertest\View\FakeTemplatePaths;
use TYPO3\Fluid\View\TemplateView;

/**
 * This is the TemplateView for the Highlight ViewHelper. To properly test
 * <f:render>, <f:section> and the like, we need a properly initialized
 * TemplateView, which is also fresh (== clean state)
 */
class TemplateViewForHighlightViewHelper extends TemplateView {

	/**
	 * The source of the template
	 * @var string
	 */
	protected $templateSource;

	/**
	 * The ViewHelperVariableContainer of the surrounding area,
	 * this makes it possible to test single form elements inside a surrounding
	 * <f:form>.
	 * @var \NamelessCoder\Fluid\Core\ViewHelper\ViewHelperVariableContainer
	 */
	protected $viewHelperVariableContainer;

	/**
	 * TemplateViewForHighlightViewHelper constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->templatePaths = new FakeTemplatePaths();
	}

	/**
	 * @param string $templateSource
	 * @return void
	 */
	public function setTemplateSource($templateSource) {
		$this->templatePaths->setTemplateSource($templateSource);
	}

	/**
	 * Set the current ViewHelperVariableContainer.
	 *
	 * @param \NamelessCoder\Fluid\Core\ViewHelper\ViewHelperVariableContainer $viewHelperVariableContainer
	 * @return void
	 */
	public function setViewHelperVariableContainer(\NamelessCoder\Fluid\Core\ViewHelper\ViewHelperVariableContainer $viewHelperVariableContainer) {
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