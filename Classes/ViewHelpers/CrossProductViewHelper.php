<?php
declare(ENCODING = 'utf-8');
namespace F3\Viewhelpertest\ViewHelpers;

/*                                                                        *
 * This script belongs to the FLOW3 package "Viewhelpertest".             *
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
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 * @scope prototype
 */
class CrossProductViewHelper extends AbstractSubTemplateRenderingViewHelper {

	/**
	 * @param array $values,
	 * @param string $expected
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function render($values, $expected=NULL) {
		if ($expected === NULL) {
			return $this->renderHelper($values);
		}

		// parse expected string
		$expected = trim($expected);
		$lines = explode(chr(10), $expected);
		$numberOfLines = count($lines);
		foreach ($lines as $key => $line) {
			$lines[$key] = explode(';', $line);
			if (count($lines[$key]) !== $numberOfLines) {
				throw new \Exception('The matrix must be symmetrical!');
			}
		}
		// Sanity check: First line must contain all columns in correct order
		$variableNames = array_keys($values);
		if (count($variableNames) !== count($lines[0]) - 1) {
			throw new \Exception('Number of columns not correct');
		}
		for ($i=1; $i < count($lines[0]); $i++) {
			if ($variableNames[$i-1] != trim($lines[0][$i])) {
				throw new \Exception('TODO: Header line not correct, ');
			}
		}
		// Sanity check: Column headers must have the right order and be correct
		if (count($variableNames) !== count($lines) - 1) {
			throw new \Exception('Number of rows not correct');
		}

		for ($i=1; $i < count($lines); $i++) {
			if ($variableNames[$i-1] != trim($lines[$i][0])) {
				throw new \Exception('TODO: header column not correct, ');
			}
		}

		for ($i=1; $i <= count($variableNames); $i++) {
			$rowVariableName = $variableNames[$i-1];
			for ($j=1; $j <= count($variableNames); $j++) {
				$columnVariableName = $variableNames[$j-1];
				$expectedResult = trim($lines[$i][$j]);
				
				/*$this->templateVariableContainer->add('row', $values[$rowVariableName]);
				$this->templateVariableContainer->add('column', $values[$columnVariableName]);
				$this->templateVariableContainer->add('expected', $expectedResult);
				$result = $this->renderChildren();
				$this->templateVariableContainer->remove('row');
				$this->templateVariableContainer->remove('column');
				$this->templateVariableContainer->remove('expected');*/
			}
		}
	}

	protected function renderHelper($values) {
		$output = '';

		$longestKeyLength = 0;
		foreach ($values as $key => $v) {
			if (strlen($key) > $longestKeyLength) {
				$longestKeyLength = strlen($key);
			}
		}

		// Header line
		$output .= str_repeat(' ', $longestKeyLength);
		foreach ($values as $key => $value) {
			$output .= ';' . str_pad($key, $longestKeyLength);
		}
		$output .= chr(10);

		foreach ($values as $key => $value) {
			$output .= rtrim(str_pad($key, $longestKeyLength) . ';' . str_repeat(str_pad('    0', $longestKeyLength) . ';', count($values)), ';').  chr(10);
		}
		return '<pre>' . $output . '</pre>';
	}
}
?>