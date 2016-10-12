<?php
namespace Flowpack\Viewhelpertest\ViewHelpers;

/*                                                                        *
 * This script belongs to the FLOW3 package "Flowpack.Viewhelpertest".    *
 *                                                                        */

/**
 * This is the TemplateView for the Highlight ViewHelper. To properly test
 * <f:render>, <f:section> and the like, we need a properly initialized
 * TemplateView, which is also fresh (== clean state)
 */
class TemplateViewForHighlightViewHelper extends \Neos\FluidAdaptor\View\TemplateView {

	/**
	 * @param string $templateSource
	 * @return void
	 */
	public function setTemplateSource($templateSource) {
		$this->getTemplatePaths()->setTemplateSource($templateSource);
	}

	/**
	 * Overridden Render method.
	 *
	 * @param string $actionName render the given action.
	 * @return string the rendered result
	 */
	public function render($actionName = NULL) {
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
