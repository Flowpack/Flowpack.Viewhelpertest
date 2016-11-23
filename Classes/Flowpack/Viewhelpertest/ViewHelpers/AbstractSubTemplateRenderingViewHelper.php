<?php
namespace Flowpack\Viewhelpertest\ViewHelpers;

/*                                                                        *
 * This script belongs to the FLOW3 package "Flowpack.Viewhelpertest".    *
 *                                                                        */

use TYPO3\Flow\Mvc\Controller\ControllerContext;
use Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper;
use Neos\FluidAdaptor\View\TemplateView;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContext;

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
	 * @param $source
	 * @param ControllerContext $controllerContext
	 * @param RenderingContext $renderingContext
	 * @return mixed
	 */
	protected static function renderSource($source, ControllerContext $controllerContext, RenderingContext $renderingContext) {
		$templateView = new TemplateView();
		$templateView->setControllerContext($controllerContext);
		$templateView->getRenderingContext()->setViewHelperVariableContainer($renderingContext->getViewHelperVariableContainer());
		$templateView->getRenderingContext()->setVariableProvider($renderingContext->getVariableProvider());
		$templateView->getTemplatePaths()->setTemplateSource($source);

		return $templateView->render(md5($source));
	}
}

?>
