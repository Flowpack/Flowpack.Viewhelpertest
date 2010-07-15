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
 *
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 * @scope prototype
 */
class HighlightViewHelper extends \F3\Fluid\Core\ViewHelper\AbstractViewHelper implements \F3\Fluid\Core\ViewHelper\Facets\ChildNodeAccessInterface {

	/**
	 * An array of \F3\Fluid\Core\Parser\SyntaxTree\AbstractNode
	 * @var array
	 */
	protected $childNodes = array();

	/**
	 * @var F3\Fluid\Core\Rendering\RenderingContextInterface
	 */
	protected $renderingContext;

	/**
	 * Setter for ChildNodes - as defined in ChildNodeAccessInterface
	 *
	 * @param array $childNodes Child nodes of this syntax tree node
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @api
	 */
	public function setChildNodes(array $childNodes) {
		$this->childNodes = $childNodes;
	}

	/**
	 * Inject the template parser
	 *
	 * @param \F3\Fluid\Core\Parser\TemplateParser $templateParser The template parser
	 * @return void
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function injectTemplateParser(\F3\Fluid\Core\Parser\TemplateParser $templateParser) {
		$this->templateParser = $templateParser;
	}

	//PLACEHOLDER
	// Here, the backporter can insert a constructor method, which is needed for Fluid v4.

	/**
	 * @param string $expected
	 * @param string $expectedRegex
	 * @return string
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function render($expected = NULL, $expectedRegex = NULL) {
		$source = trim($this->renderChildren());
		$parsedTemplate = $this->templateParser->parse($source);
		$renderedSource = $parsedTemplate->render($this->getRenderingContext());
		$title = '';
		$className = '';
		if ($expected !== NULL) {
			$replacement = array(
				'\n' => "\n",
				'\t' => "\t"
			);
			$expected = strtr($expected, $replacement);
		}
		if ($expected !== NULL && trim($renderedSource) === html_entity_decode($expected)) {
			$title = 'successfully compared the rendered result with &quot;' . htmlspecialchars(html_entity_decode($expected)) . '&quot;';
			$className = 'success';
		} elseif ($expectedRegex !== NULL && preg_match(html_entity_decode($expectedRegex), $renderedSource) === 1) {
			$title = 'successfully compared the rendered result with RegEx &quot;' . htmlspecialchars(html_entity_decode($expectedRegex)) . '&quot;';
			$className = 'success';
		} elseif ($expected === NULL && $expectedRegex === NULL) {
			$className = 'default';
		} else {
			$className = 'failure';
			if ($expected !== NULL) {
				$title = 'expected &quot;' . htmlspecialchars(html_entity_decode($expected)) . '&quot;';
			} else {
				$title = 'expected RegEx &quot;' . htmlspecialchars($expectedRegex) . '&quot;';
			}
		}
		return '<div title="' . $title . '" class="' . $className . '">
			<h2>' . htmlspecialchars($source) . '</h2>
			<div>' . $renderedSource . '</div>
		</div>';
	}
}


?>
