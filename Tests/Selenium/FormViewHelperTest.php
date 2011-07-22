<?php
declare(ENCODING = 'utf-8');
namespace TYPO3\Viewhelpertest;

/*
 * This script belongs to the TYPO3 project.                              *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License as published by the Free   *
 * Software Foundation, either version 3 of the License, or (at your      *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        *
 * You should have received a copy of the GNU General Public License      *
 * along with the script.                                                 *
 * If not, see http://www.gnu.org/licenses/gpl.html                       *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

require_once(__DIR__ . '/SeleniumBaseTestCase.php');

class FormViewHelperTest extends \TYPO3\Viewhelpertest\SeleniumBaseTestCase {

	/**
	 * @test
	 * @author Sebastian Kurf√ºrst <sebastian@typo3.org>
	 */
	public function itIsPossibleToPutFormFieldsIntoPartial() {
		$this->navigateToViewHelper('form.withFieldsInPartial');
		$this->assertValue('firstName', 'Ingmar');
	}
}
?>