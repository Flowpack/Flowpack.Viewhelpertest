<?php
namespace Flowpack\Viewhelpertest\Tests\Functional;

/*                                                                        *
 * This script belongs to the FLOW3 package "Flowpack.Viewhelpertest".    *
 *                                                                        */

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
			'@package' => 'Flowpack.Viewhelpertest',
			'@controller' => 'Standard',
			'@action' => 'index',
			'@format' =>'html'
		), TRUE);
	}

	/**
	 * @test
	 */
	public function runViewHelperTest() {
		$this->authenticateRoles(array('Flowpack.Viewhelpertest:TestRole1'));

		$result = $this->browser->request('http://localhost/flowpack.viewhelpertest/standard/index?selectedPartial=all')->getContent();

        $resultsWithFailures = $this->browser->getCrawler()->filter('.failure');
        $successfulResultList = $this->browser->getCrawler()->filter('.success');

        $numberOfSuccessfulTests = $successfulResultList->count();
		$numberOfFailedTests = $resultsWithFailures->count();

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
		$newHeader .= '<style>' . file_get_contents(FLOW_PATH_ROOT . 'Packages/Application/Flowpack.Viewhelpertest/Resources/Public/Styles/app.css') . '</style>';

		$newHeader .= '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
		$newHeader .= '<script>' . file_get_contents(FLOW_PATH_ROOT . 'Packages/Application/Flowpack.Viewhelpertest/Resources/Public/Scripts/app.js') . '</script>';

		$newHeader .= '<!--';


		$result = preg_replace('/BEGIN_HEADERS.*?END_HEADERS/s', $newHeader, $result);

		file_put_contents(FLOW_PATH_ROOT . 'Build/Reports/Viewhelpertest.html', $result);
	}

	/**
	 * Logout any authenticated accounts
	 *
	 * @return void
	 */
	protected function tearDownSecurity() {
		$this->authenticationManager->logout();
		parent::tearDownSecurity();
	}

}
