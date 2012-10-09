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

class HighlightViewHelper extends \TYPO3\Viewhelpertest\ViewHelpers\AbstractSubTemplateRenderingViewHelper {

	protected static $executionCount = 0;

	/**
	 * @param string $expected
	 * @param string $expectedRegex
	 * @return string
	 */
	public function render($expected = NULL, $expectedRegex = NULL) {
		self::$executionCount++;

		if ($this->controllerContext->getRequest()->hasArgument('singleTestcase')
			&& $this->controllerContext->getRequest()->getArgument('singleTestcase') != self::$executionCount) {
				return '';
		}

		$source = trim($this->renderChildren());
		$renderedSource = $this->renderSource($source);

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
		if ($expected !== NULL && trim($renderedSource) === $expected) {
			$title = 'successfully compared the rendered result with &quot;' . htmlspecialchars($expected) . '&quot;';
			$className = 'success';
		} elseif ($expectedRegex !== NULL && preg_match($expectedRegex, $renderedSource) === 1) {
			$title = 'successfully compared the rendered result with RegEx &quot;' . htmlspecialchars($expectedRegex) . '&quot;';
			$className = 'success';
		} elseif ($expected === NULL && $expectedRegex === NULL) {
			$className = 'default';
		} else {
			$className = 'failure';
			if ($expected !== NULL) {
				$title = 'expected &quot;' . htmlspecialchars($expected) . '&quot;';
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
				->uriFor(NULL, array('singleTestcase' => self::$executionCount));
		return '<div class="testcase ' . $className . '">
			<h3>' . $title . '<a href="' . $uriToThisTest . '">Show only this test</a></h3>
			<div class="input">' . htmlspecialchars($source) . '</div>
			<div class="output">' . $renderedSource . '</div>
		</div>';
	}
}


?>
