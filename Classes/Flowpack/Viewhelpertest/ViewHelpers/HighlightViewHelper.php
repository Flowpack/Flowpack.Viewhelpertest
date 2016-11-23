<?php
namespace Flowpack\Viewhelpertest\ViewHelpers;

/*                                                                        *
 * This script belongs to the FLOW3 package "Flowpack.Viewhelpertest".    *
 *                                                                        */

use Neos\FluidAdaptor\Core\Rendering\RenderingContext;
use Neos\FluidAdaptor\Core\ViewHelper\ViewHelperResolver;
use TYPO3Fluid\Fluid\Core\Parser\Patterns;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

class HighlightViewHelper extends AbstractSubTemplateRenderingViewHelper {

	/**
	 * @var boolean
	 */
	protected $escapeChildren = FALSE;

	protected static $executionCount = 0;

	/**
	 * @param string $expected
	 * @param string $expectedRegex
	 * @param string $expectedException
	 * @param string $expectedType
	 * @return string
	 */
	public function render($expected = NULL, $expectedRegex = NULL, $expectedException = NULL, $expectedType = NULL) {
		return self::renderStatic($this->arguments, $this->buildRenderChildrenClosure(), $this->renderingContext);
	}

	/**
	 * @param array $arguments
	 * @param \Closure $renderChildrenClosure
	 * @param RenderingContextInterface $renderingContext
	 * @return string
	 * @throws ViewHelper\Exception
	 */
	public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
	{
		self::$executionCount++;

		$expectedException = isset($arguments['expectedException']) ? $arguments['expectedException'] : null;
		$expectedRegex = isset($arguments['expectedRegex']) ? $arguments['expectedRegex'] : null;
		$expected = isset($arguments['expected']) ? $arguments['expected'] : null;
		$expectedType = isset($arguments['expectedType']) ? $arguments['expectedType'] : null;

		if ($renderingContext instanceof RenderingContext) {
			$controllerContext = $renderingContext->getControllerContext();
		}

		if ($renderingContext->getViewHelperResolver() instanceof ViewHelperResolver) {
			$renderingContext->getViewHelperResolver()->setNamespaces($renderingContext->getViewHelperResolver()->getDefaultNamespaces());
		}

		$viewHelperVariableContainer = $renderingContext->getViewHelperVariableContainer();

		if ($controllerContext->getRequest()->hasArgument('singleTestcase')
			&& $controllerContext->getRequest()->getArgument('singleTestcase') != self::$executionCount
		) {
			return '';
		}

		$caughtException = null;
		$source = trim($renderChildrenClosure());

		$source = preg_replace('/(\<\!\[CDATA\[|\]\]\>)/', '', $source);
		$source = trim($source);

		if ($expectedException !== null) {
			try {
				$renderedSource = self::renderSource($source, $controllerContext, $renderingContext);
			} catch (\Exception $exception) {
				$caughtException = $exception;
				$renderedSource = htmlspecialchars(sprintf('EXCEPTION #%d: %s', $exception->getCode(), $exception->getMessage()));
			}
		} else {
			$renderedSource = self::renderSource($source, $controllerContext, $renderingContext);
		}
		$title = '';
		if ($expected !== null) {
			$replacement = [
				'\n' => "\n",
				'\t' => "\t"
			];
			$expected = strtr($expected, $replacement);
			$expected = html_entity_decode($expected);
		}
		if ($expectedRegex !== null) {
			$expectedRegex = html_entity_decode($expectedRegex);
		}

		if ($expected === null && $expectedRegex === null && $viewHelperVariableContainer->exists('Flowpack\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'source')) {
			$isRegex = $viewHelperVariableContainer->get('Flowpack\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'regex');
			if ($isRegex) {
				$expectedRegex = $viewHelperVariableContainer->get('Flowpack\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'source');
			} else {
				$expected = $viewHelperVariableContainer->get('Flowpack\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'source');
			}

			$viewHelperVariableContainer->remove('Flowpack\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'source');
			$viewHelperVariableContainer->remove('Flowpack\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'regex');
		}
		if ($expectedException !== null) {
			if ($caughtException === null) {
				$title = sprintf('expected exception "%s", but none was thrown', htmlspecialchars($expectedException));
				$className = 'failure';
			} elseif (!$caughtException instanceof $expectedException) {
				$title = sprintf('expected exception "%s", but exception of type "%s" was thrown', htmlspecialchars($expectedException), htmlspecialchars(get_class($caughtException)));
				$className = 'failure';
			} else {
				$title = sprintf('successfully validated that exception "%s" was thrown', htmlspecialchars($expectedException));
				$className = 'success';
			}
		} elseif ($expected !== null && trim($renderedSource) === trim($expected)) {
			$title = 'successfully compared the rendered result with &quot;' . htmlspecialchars($expected) . '&quot;';
			$className = 'success';
		} elseif ($expectedRegex !== null && preg_match($expectedRegex, $renderedSource) === 1) {
			$title = 'successfully compared the rendered result with RegEx &quot;' . htmlspecialchars($expectedRegex) . '&quot;';
			$className = 'success';
		} elseif ($expectedType !== null && gettype($renderedSource) === $expectedType) {
			$title = 'successfully compared the rendered result type with &quot;' . htmlspecialchars($expectedType) . '&quot;';
			$className = 'success';
		} elseif ($expected === null && $expectedRegex === null && $expectedType === null) {
			$className = 'default';
		} else {
			$className = 'failure';
			if ($expected !== null) {
				$title = 'expected &quot;' . htmlspecialchars($expected) . '&quot; got &quot;' . htmlspecialchars($renderedSource) . '&quot;';
			} elseif ($expectedType !== null) {
				$title = 'expected type &quot;' . htmlspecialchars($expectedType) . '&quot; got &quot;' . htmlspecialchars(gettype($renderedSource)) . '&quot;';
			} else {
				$title = 'expected RegEx &quot;' . htmlspecialchars($expectedRegex) . '&quot;';
			}
		}

		if ($viewHelperVariableContainer->exists('Flowpack\Viewhelpertest\ViewHelpers\HighlightViewHelper', 'results')) {
			$results = $viewHelperVariableContainer->get('Flowpack\Viewhelpertest\ViewHelpers\HighlightViewHelper', 'results');
		} else {
			$results = [
				'total' => 0,
				'failures' => 0
			];
		}

		$results['total']++;

		if ($className === 'failure') {
			$results['failures']++;
		}
		$viewHelperVariableContainer->addOrUpdate('Flowpack\Viewhelpertest\ViewHelpers\HighlightViewHelper', 'results', $results);

		$uriToThisTest = $controllerContext->getUriBuilder()
			->reset()
			->setAddQueryString(true)
			->setSection('test' . self::$executionCount)
			->uriFor('index', ['singleTestcase' => self::$executionCount]);

		return '<div class="testcase ' . htmlspecialchars($className) . '" id="test' . self::$executionCount . '">
			<h3>' . $title . '<a href="' . $uriToThisTest . '">Show only this test</a></h3>
			<div class="input">' . htmlspecialchars($source) . '</div>
			<div class="output">' . $renderedSource . '</div>
		</div>';
	}
}

?>
