<?php
declare(ENCODING = 'utf-8');
namespace F3\Viewhelpertest\Tests\Functional;

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
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function runViewHelperTest() {
		$this->authenticateRoles(array('TestRole'));

		$result = $this->sendWebRequest('Standard', 'Viewhelpertest', 'index', array('selectedPartial' => 'all'));

		$resultsWithFailures = \PHPUnit_Util_XML::cssSelect('.failure', TRUE, $result);

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
}
?>