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
		self::$executionCount++;

		if ($this->controllerContext->getRequest()->hasArgument('singleTestcase')
			&& $this->controllerContext->getRequest()->getArgument('singleTestcase') != self::$executionCount) {
				return '';
		}

		$caughtException = NULL;
		$source = trim($this->renderChildren());
		if ($expectedException !== NULL) {
			try {
				$renderedSource = $this->renderSource($source);
			} catch (\Exception $exception) {
				$caughtException = $exception;
				$renderedSource = htmlspecialchars(sprintf('EXCEPTION #%d: %s', $exception->getCode(), $exception->getMessage()));
			}
		} else {
			$renderedSource = $this->renderSource($source);
		}

		$title = '';
		if ($expected !== NULL) {
			$replacement = array(
				'\n' => "\n",
				'\t' => "\t"
			);
			$expected = strtr($expected, $replacement);
			$expected = html_entity_decode($expected);
		}
		if ($expectedRegex !== NULL) {
			$expectedRegex = html_entity_decode($expectedRegex);
		}

		if ($expected === NULL && $expectedRegex === NULL && $this->viewHelperVariableContainer->exists('TYPO3\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'source')) {
			$isRegex = $this->viewHelperVariableContainer->get('TYPO3\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'regex');
			if ($isRegex) {
				$expectedRegex = $this->viewHelperVariableContainer->get('TYPO3\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'source');
			} else {
				$expected = $this->viewHelperVariableContainer->get('TYPO3\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'source');
			}

			$this->viewHelperVariableContainer->remove('TYPO3\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'source');
			$this->viewHelperVariableContainer->remove('TYPO3\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'regex');
		}
		if ($expectedException !== NULL) {
			if ($caughtException === NULL) {
				$title = sprintf('expected exception "%s", but none was thrown', htmlspecialchars($expectedException));
				$className = 'failure';
			} elseif (!$caughtException instanceof $expectedException) {
				$title = sprintf('expected exception "%s", but exception of type "%s" was thrown', htmlspecialchars($expectedException), htmlspecialchars(get_class($caughtException)));
				$className = 'failure';
			} else {
				$title = sprintf('successfully validated that exception "%s" was thrown', htmlspecialchars($expectedException));
				$className = 'success';
			}
		} elseif ($expected !== NULL && trim($renderedSource) === $expected) {
			$title = 'successfully compared the rendered result with &quot;' . htmlspecialchars($expected) . '&quot;';
			$className = 'success';
		} elseif ($expectedRegex !== NULL && preg_match($expectedRegex, $renderedSource) === 1) {
			$title = 'successfully compared the rendered result with RegEx &quot;' . htmlspecialchars($expectedRegex) . '&quot;';
			$className = 'success';
		} elseif ($expectedType !== NULL && gettype($renderedSource) === $expectedType) {
			$title = 'successfully compared the rendered result type with &quot;' . htmlspecialchars($expectedType) . '&quot;';
			$className = 'success';
		} elseif ($expected === NULL && $expectedRegex === NULL && $expectedType === NULL) {
			$className = 'default';
		} else {
			$className = 'failure';
			if ($expected !== NULL) {
				$title = 'expected &quot;' . htmlspecialchars($expected) . '&quot; got &quot;' . htmlspecialchars($renderedSource) . '&quot;';
			} elseif ($expectedType !== NULL) {
				$title = 'expected type &quot;' . htmlspecialchars($expectedType) . '&quot; got &quot;' . htmlspecialchars(gettype($renderedSource)) . '&quot;';
			} else {
				$title = 'expected RegEx &quot;' . htmlspecialchars($expectedRegex) . '&quot;';
			}
		}

		if ($this->viewHelperVariableContainer->exists('TYPO3\Viewhelpertest\ViewHelpers\HighlightViewHelper', 'results')) {
			$results = $this->viewHelperVariableContainer->get('TYPO3\Viewhelpertest\ViewHelpers\HighlightViewHelper', 'results');
		} else {
			$results = array(
				'total' => 0,
				'failures' => 0
			);
		}

		$results['total']++;

		if ($className === 'failure') {
			$results['failures']++;
		}
		$this->viewHelperVariableContainer->addOrUpdate('TYPO3\Viewhelpertest\ViewHelpers\HighlightViewHelper', 'results', $results);

		$uriToThisTest = $this->controllerContext->getUriBuilder()
				->reset()
				->setAddQueryString(TRUE)
				->uriFor('index', array('singleTestcase' => self::$executionCount));
		return '<div class="testcase ' . $className . '">
			<h3>' . $title . '<a href="' . $uriToThisTest . '">Show only this test</a></h3>
			<div class="input">' . htmlspecialchars($source) . '</div>
			<div class="output">' . $renderedSource . '</div>
		</div>';
	}
}


?>
