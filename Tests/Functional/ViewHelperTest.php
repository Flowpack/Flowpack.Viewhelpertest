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
 * Testcase for all view helpers
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class ViewHelperTest extends \F3\FLOW3\Tests\FunctionalTestCase {

	protected $testableSecurityEnabled = TRUE;

	/**
	 * @test
	 * @author Sebastian Kurf√ºrst <sebastian@typo3.org>
	 */
	public function runViewHelperTest() {
		$this->authenticateRoles(array('TestRole'));

		$result = $this->sendWebRequest('Standard', 'Viewhelpertest', 'index', array('selectedPartial' => 'all'));

		$resultsWithFailures = \PHPUnit_Util_XML::cssSelect('.failure', TRUE, $result);
		$successfulResultList = \PHPUnit_Util_XML::cssSelect('.success', TRUE, $result);

		$numberOfSuccessfulTests = 0;
		if (is_array($successfulResultList)) {
			$numberOfSuccessfulTests = count($successfulResultList);
		}

		$numberOfFailedTests = 0;
		if (is_array($resultsWithFailures)) {
			$numberOfFailedTests = count($resultsWithFailures);
		}

		// Outputting some statistics for the Jenkins Measurement Plots plugin:
		// https://wiki.jenkins-ci.org/display/JENKINS/Measurement+Plots+Plugin
		echo '<measurement><name>Number of successful ViewHelper Tests</name><value>' . $numberOfSuccessfulTests . '</value></measurement>';
		echo '<measurement><name>Number of failed ViewHelper Tests</name><value>' . $numberOfFailedTests . '</value></measurement>';

		if (strpos($result, '___VIEWHELPERTEST_EXECUTED___') === FALSE) {
			$this->fail('It seems that the tests were not executed!');
		}

		$this->writeResultToFile($result);

		$errors = array();
		if (is_array($resultsWithFailures) && count($resultsWithFailures) > 0) {
			foreach ($resultsWithFailures as $singleFailureDomElement) {
				$document = new \DOMDocument();
				$document->appendChild($document->importNode($singleFailureDomElement, true));
				$errors[] = $document->saveXML();
			}
			if (count($errors) > 0) {
				$this->fail('Some failures occured: ' . implode("\n\n==================\n\n", $errors));
			}
		}
	}

	/**
	 * Write the Viewhelpertest-Result to a file (Build/Reports/Viewhelpertest.html)
	 *
	 * @param string $result
	 * @return void
	 */
	protected function writeResultToFile($result) {
		$newHeader =' -->';
		$newHeader .= '<style>' . file_get_contents(FLOW3_PATH_ROOT . 'Packages/Application/Viewhelpertest/Resources/Public/styles.css') . '</style>';
		$newHeader .= '<link rel="stylesheet" type="text/css" href="http://extjs.cachefly.net/ext-3.2.1/resources/css/ext-all.css" />
			<script type="text/javascript" src="http://extjs.cachefly.net/ext-3.2.1/adapter/ext/ext-base.js"> </script>
			<script type="text/javascript" src="http://extjs.cachefly.net/ext-3.2.1/ext-all.js"> </script>';

		$newHeader .= '<script type="text/javascript">' . file_get_contents(FLOW3_PATH_ROOT . 'Packages/Application/Viewhelpertest/Resources/Public/javascript.js') . '</script>';

		$newHeader .= '<!--';


		$result = preg_replace('/BEGIN_HEADERS.*?END_HEADERS/s', $newHeader, $result);

		file_put_contents(FLOW3_PATH_ROOT . 'Build/Reports/Viewhelpertest.html', $result);
	}
}
?>