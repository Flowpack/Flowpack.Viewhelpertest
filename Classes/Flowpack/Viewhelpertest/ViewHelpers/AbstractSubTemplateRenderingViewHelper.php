<?php
namespace Flowpack\Viewhelpertest\ViewHelpers;

/*                                                                        *
 * This script belongs to the FLOW3 package "Flowpack.Viewhelpertest".    *
 *                                                                        */

use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

abstract class AbstractSubTemplateRenderingViewHelper extends AbstractViewHelper {

	/**
	 * @var boolean
	 */
	protected $escapeOutput = FALSE;

	/**
	 * @var \Flowpack\Viewhelpertest\ViewHelpers\TemplateViewForHighlightViewHelper
	 */
	protected $templateView;

	/**
	 * @param \Flowpack\Viewhelpertest\ViewHelpers\TemplateViewForHighlightViewHelper $templateView
	 * @return void
	 */
	public function injectTemplateView(\Flowpack\Viewhelpertest\ViewHelpers\TemplateViewForHighlightViewHelper $templateView) {
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