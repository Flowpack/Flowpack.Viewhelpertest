<?php
declare(ENCODING = 'utf-8');
namespace TYPO3\Viewhelpertest\Tests\Functional;

/*                                                                        *
 * This script belongs to the FLOW3 framework.                            *
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
 * Testcase for the "Rendering" view helper (calling the "Render" controller)
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class RenderingTest extends \TYPO3\FLOW3\Tests\FunctionalTestCase {

	/**
	 * Enable testable HTTP.
	 * @var boolean
	 */
	protected $testableHttpEnabled = TRUE;

	/**
	 * @test
	 */
	public function renderSectionInLayoutUsesTemplateVariables() {
		$this->callActionAndValidateResult('renderSectionInLayoutUsesTemplateVariables');
	}

	/**
	 * @test
	 */
	public function renderSectionInPartialNeedsArgumentsExplicitely() {
		$this->callActionAndValidateResult('renderSectionInPartialNeedsArgumentsExplicitely');
	}

	/**
	 * @test
	 */
	public function renderPartialNeedsArgumentsExplicitely() {
		$this->callActionAndValidateResult('renderPartialNeedsArgumentsExplicitely');
	}

	/**
	 * @test
	 */
	public function renderSectionInsidePartialNeedsArgumentsExplicitely() {
		$this->callActionAndValidateResult('renderSectionInsidePartialNeedsArgumentsExplicitely');
	}

	/**
	 * @test
	 */
	public function renderSectionInsideTemplateNeedsArgumentsExplicitely() {
		$this->callActionAndValidateResult('renderSectionInsideTemplateNeedsArgumentsExplicitely');
	}

	/**
	 * This helper method calls the given action on the Render contoller,
	 * and evaluates the result by checking the .expected-notEmpty and .expected-empty
	 * annotations.
	 *
	 * @param string $actionName The action to call on the Render Controller
	 * @return void
	 */
	protected function callActionAndValidateResult($actionName) {
		$result = $this->browser->request('http://localhost/typo3.viewhelpertest/render/' . $actionName)->getContent();

		// Check if there is a '.expected-notEmpty' area which only contains whitespace. If this is the case,
			// the assertion fails.
		$this->assertSelectRegExp('.expected-notEmpty', '/^\s*$/', FALSE, $result, 'A variable which should not be empty was found empty.');

			// Check if there is a '.expected-empty' area which contains content. If this is the case,
			// the assertion fails.
		$this->assertSelectRegExp('.expected-empty', '/^.+$/s', FALSE, $result, 'A variable which should be empty was not.');
	}

	/**
	 * @test
	 */
	public function recursiveSections() {
		$actual = $this->browser->request('http://localhost/typo3.viewhelpertest/render/recursivesections')->getContent();
		$expected = '
			<ul>
				<li>
					Item 1
					<ul>
						<li>
							Item 1.1
							<ul>
								<li>
									Item 1.1.1
								</li>
							</ul>
						</li>
						<li>
							Item 1.2
						</li>
					</ul>
				</li>
				<li>
					Item 2
				</li>
			</ul>';
		$expected = preg_replace('/\s+/', '', $expected);
		$actual = preg_replace('/\s+/', '', $actual);
		$this->assertXmlStringEqualsXmlString($expected, $actual);
	}
}
?>