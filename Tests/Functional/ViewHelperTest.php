<?php
namespace TYPO3\Viewhelpertest\Tests\Functional;

/*                                                                        *
 * This script belongs to the FLOW3 framework.                            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Mvc\Routing\Route;

/**
 * Testcase for all view helpers
 */
class ViewHelperTest extends \TYPO3\Flow\Tests\FunctionalTestCase {

	/**
	 * Enable testable security (and HTTP as well).
	 * @var boolean
	 */
	protected $testableSecurityEnabled = TRUE;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$this->registerRoute('Viewhelpertest', '{@package}/{@controller}(/{@action})', array(
			'@package' => 'TYPO3.Viewhelpertest',
			'@controller' => 'Standard',
			'@action' => 'index',
			'@format' =>'html'
		), TRUE);
	}

	/**
	 * @test
	 */
	public function runViewHelperTest() {
		$this->authenticateRoles(array('TYPO3.Viewhelpertest:TestRole'));

		$result = $this->browser->request('http://localhost/typo3.viewhelpertest/standard/index?selectedPartial=all')->getContent();

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
			$this->fail('It seems that the tests were not executed!' . PHP_EOL . PHP_EOL . $result);
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
		$newHeader .= '<style>' . file_get_contents(FLOW_PATH_ROOT . 'Packages/Application/TYPO3.Viewhelpertest/Resources/Public/styles.css') . '</style>';
		$newHeader .= '<link rel="stylesheet" type="text/css" href="http://extjs.cachefly.net/ext-3.2.1/resources/css/ext-all.css" />
			<script type="text/javascript" src="http://extjs.cachefly.net/ext-3.2.1/adapter/ext/ext-base.js"> </script>
			<script type="text/javascript" src="http://extjs.cachefly.net/ext-3.2.1/ext-all.js"> </script>';

		$newHeader .= '<script type="text/javascript">' . file_get_contents(FLOW_PATH_ROOT . 'Packages/Application/TYPO3.Viewhelpertest/Resources/Public/javascript.js') . '</script>';

		$newHeader .= '<!--';


		$result = preg_replace('/BEGIN_HEADERS.*?END_HEADERS/s', $newHeader, $result);

		file_put_contents(FLOW_PATH_ROOT . 'Build/Reports/Viewhelpertest.html', $result);
	}
}
?>