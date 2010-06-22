<?php
declare(ENCODING = 'utf-8');
namespace F3\Viewhelpertest;

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

class RenderTest extends \F3\Viewhelpertest\SeleniumBaseTestCase {

	// TODO: Test for optional sections
	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function renderSectionInLayoutUsesTemplateVariables() {
		$this->navigateToControllerAndAction('Render', 'renderSectionInLayoutUsesTemplateVariables');
		$this->assertText('//span[@class="variableOutput"]', 'This variable is set from within the controller.');
	}

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function renderPartialNeedsArgumentsExplicitely() {
		$this->navigateToControllerAndAction('Render', 'renderPartialNeedsArgumentsExplicitely');
		$this->assertNotText('original', '.+');
		$this->assertText('renamed', 'This variable is set from within the controller.');
		
		$this->assertText('variableInOriginalTemplate', 'This variable is set from within the controller.');
	}

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function renderSectionInPartialNeedsArgumentsExplicitely() {
		$this->navigateToControllerAndAction('Render', 'renderSectionInPartialNeedsArgumentsExplicitely');
		$this->assertNotText('original', '.+');
		$this->assertNotText('empty', '.+');
		$this->assertText('renamed', 'This variable is set from within the controller.');
	}

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function renderSectionInsidePartialNeedsArgumentsExplicitely() {
		$this->navigateToControllerAndAction('Render', 'renderSectionInsidePartialNeedsArgumentsExplicitely');
		$this->assertNotText('original', '.+');
		$this->assertText('renamed', 'This variable is set from within the controller.');
		$this->assertTextNotPresent('This is in the partial main file.');
	}

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function renderSectionInsideTemplateNeedsArgumentsExplicitely() {
		$this->navigateToControllerAndAction('Render', 'renderSectionInsideTemplateNeedsArgumentsExplicitely');
		$this->assertNotText('original', '.+');
		$this->assertText('renamed', 'This variable is set from within the controller.');

		$this->assertText('variableInOriginalTemplate', 'This variable is set from within the controller.');
	}
}
?>