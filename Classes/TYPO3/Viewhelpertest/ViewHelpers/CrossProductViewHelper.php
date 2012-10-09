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

class CrossProductViewHelper extends \TYPO3\Viewhelpertest\ViewHelpers\AbstractSubTemplateRenderingViewHelper {

	/**
	 * @param array $values,
	 * @param string $matrixMode
	 * @return string
	 * @throws \Exception
	 */
	public function render($values, $matrixMode = NULL) {
		if ($matrixMode !== NULL && $matrixMode !== 'outputRaw' && $matrixMode !== 'symmetric') {
			throw new \Exception('TODO: Matrix mode must be either "outputRaw" or "symmetric"');
		}
		$variableNames = array_keys($values);

		$expected = NULL;
		if ($this->viewHelperVariableContainer->exists('TYPO3\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'source')) {
			$expected = $this->viewHelperVariableContainer->get('TYPO3\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'source');
			$this->viewHelperVariableContainer->remove('TYPO3\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'source');
			$this->viewHelperVariableContainer->remove('TYPO3\Viewhelpertest\ViewHelpers\ExpectedViewHelper', 'regex');
		}

		if ($expected === NULL) {
			return $this->renderHelper($variableNames);
		}
		if ($matrixMode === 'outputRaw') {
			$expectedResultsTable = $this->parseExpectedResults($expected);
			return $this->renderHelper($variableNames, $expectedResultsTable);
		}

		$expectedResultsTable = $this->parseExpectedResults($expected);
		$this->sanityCheckExpectedResultsArray($expectedResultsTable);

		$output = '<table class="crossProduct">';
		$output .= '<tr><th> </th>';

		foreach ($variableNames as $variableName) {
			$output .= '<th>' . $variableName . '</th>';
		}

		$output .= '</tr>';
			// TODO: add header column
		for ($i = 1; $i <= count($variableNames); $i++) {
			$rowVariableName = $variableNames[$i - 1];
			$output .= '<tr>';
			$output .= '<th>' . $rowVariableName . '</th>';
			for ($j = 1; $j <= count($variableNames); $j++) {
				$columnVariableName = $variableNames[$j - 1];
				if ($i > $j && $matrixMode === 'symmetric') {
					$expectedResult = trim($expectedResultsTable[$j][$i]);
				} else {
					$expectedResult = trim($expectedResultsTable[$i][$j]);
				}

				if ($this->viewHelperVariableContainer->exists('TYPO3\Viewhelpertest\ViewHelpers\HighlightViewHelper', 'results')) {
					$this->viewHelperVariableContainer->remove('TYPO3\Viewhelpertest\ViewHelpers\HighlightViewHelper', 'results');
				}

				$this->templateVariableContainer->add('rowValue', $values[$rowVariableName]);
				$this->templateVariableContainer->add('columnValue', $values[$columnVariableName]);
				$this->templateVariableContainer->add('expectedResult', $expectedResult);
				$testExecutionHtml = $this->renderChildren();
				$this->templateVariableContainer->remove('rowValue');
				$this->templateVariableContainer->remove('columnValue');
				$this->templateVariableContainer->remove('expectedResult');

				$status = 'default';
				if ($this->viewHelperVariableContainer->exists('TYPO3\Viewhelpertest\ViewHelpers\HighlightViewHelper', 'results')) {
					$results = $this->viewHelperVariableContainer->get('TYPO3\Viewhelpertest\ViewHelpers\HighlightViewHelper', 'results');
					if ($results['failures'] > 0) {
						$status = 'failure';
					} elseif ($results['total'] > 0) {
						$status = 'success';
					}
				}

				$output .= '<td class="' . $status . '" ext:qtip="' . htmlspecialchars($testExecutionHtml) . '">' . $expectedResult . ' <span class="numberOfTests">(' . $results['total'] . ' Tests)</span></td>';

			}
			$output .= '</tr>';
		}
		$output .= '</table>';
		return $output;
	}

	/**
	 * @param string $expected
	 * @return array
	 * @throws \Exception
	 */
	protected function parseExpectedResults($expected) {
			// parse expected string
		$expected = trim($expected);
		$lines = explode(chr(10), $expected);
		$numberOfLines = count($lines);
		foreach ($lines as $key => $line) {
			$lines[$key] = explode(';', $line);
			if (count($lines[$key]) !== $numberOfLines) {
				throw new \Exception('The matrix must be quadratic!');
			}
		}
		return $lines;
	}

	/**
	 * @param array $lines
	 * @throws \Exception
	 */
	protected function sanityCheckExpectedResultsArray(array $lines) {

			// Sanity check: First line must contain all columns in correct order
		$variableNames = array_keys($this->arguments['values']);

		if (count($variableNames) !== count($lines[0]) - 1) {
			throw new \Exception('Number of columns not correct. There are ' . count($variableNames) . ' variables but ' . (count($lines[0]) - 1) . ' columns. Use matrixMode="outputRaw" for correcting.');
		}
		for ($i = 1; $i < count($lines[0]); $i++) {
			if ($variableNames[$i - 1] != trim($lines[0][$i])) {
				throw new \Exception('TODO: Header line not correct,  Use matrixMode="outputRaw" for correcting.');
			}
		}
			// Sanity check: Column headers must have the right order and be correct
		if (count($variableNames) !== count($lines) - 1) {
			throw new \Exception('Number of rows not correct.  Use matrixMode="outputRaw" for correcting.');
		}

		for ($i = 1; $i < count($lines); $i++) {
			if ($variableNames[$i - 1] != trim($lines[$i][0])) {
				throw new \Exception('TODO: header column not correct.  Use matrixMode="outputRaw" for correcting. ');
			}
		}
	}

	/**
	 * @param array $variableNames
	 * @param array $inputValues
	 * @return string
	 */
	protected function renderHelper(array $variableNames, array $inputValues = NULL) {
		$output = '';

		$lengthOfLongestVariable = 0;
		foreach ($variableNames as $variableName) {
			if (strlen($variableName) > $lengthOfLongestVariable) {
				$lengthOfLongestVariable = strlen($variableName);
			}
		}

			// Header line
		$output .= str_repeat(' ', $lengthOfLongestVariable);
		foreach ($variableNames as $variableName) {
			$output .= ';' . str_pad($variableName, $lengthOfLongestVariable);
		}
		$output .= chr(10);

		foreach ($variableNames as $rowVariableName) {
			$output .= str_pad($rowVariableName, $lengthOfLongestVariable);
			foreach ($variableNames as $columnVariableName) {
				$value = $this->findExistingValueInTable($rowVariableName, $columnVariableName, $inputValues);
				$output .= ';' . str_pad('    ' . $value, $lengthOfLongestVariable);
			}
			$output .= chr(10);
		}
		return '<pre>' . $output . '</pre>';
	}

	/**
	 * @param string $rowVariableName
	 * @param string $columnVariableName
	 * @param array $inputValues
	 * @return string
	 */
	protected function findExistingValueInTable($rowVariableName, $columnVariableName, array $inputValues = NULL) {
		if (!is_array($inputValues)) {
			return 'X';
		}

		$row = NULL;
		for ($i = 1; $i < count($inputValues); $i++) {
			if (trim($inputValues[$i][0]) === $rowVariableName) {
				$row = $i;
				break;
			}
		}
		$column = NULL;
		for ($i = 1; $i < count($inputValues[0]); $i++) {
			if (trim($inputValues[0][$i]) === $columnVariableName) {
				$column = $i;
				break;
			}
		}
		if ($row === NULL || $column === NULL) {
			return 'X';
		} else {
			return trim($inputValues[$row][$column]);
		}

	}
}
?>