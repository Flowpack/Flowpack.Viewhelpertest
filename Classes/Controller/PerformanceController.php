<?php
declare(ENCODING = 'utf-8');
namespace TYPO3\Viewhelpertest\Controller;

/*                                                                        *
 * This script is part of the TYPO3 project - inspiring people to share!  *
 *                                                                        *
 * TYPO3 is free software; you can redistribute it and/or modify it under *
 * the terms of the GNU General Public License version 2 as published by  *
 * the Free Software Foundation.                                          *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        */

/**
 * Viewhelpertest Default Controller
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class PerformanceController extends \TYPO3\FLOW3\MVC\Controller\ActionController {

	/**
	 * @var array
	 */
	protected $setup;

	/**
	 * @var TYPO3\FLOW3\Utility\Environment
	 * @inject
	 */
	protected $environment;

	/**
	 * @return void
	 */
	public function indexAction() {
	}

	/**
	 * @return void
	 */
	public function testAction() {
		$this->view->assign("foo", array('baz', 'buz', 'x', 'y', 'z'));
		$this->view->assign('simpleOneValue', '1');
	}

	/**
	 * @param array $setup
	 * @return void
	 */
	public function profileAction(array $setup) {
		$this->setup = $setup;

		$this->view->setTemplatePathAndFilename($this->createTemplateFileFromSetup());

		$this->view->assign('setup', $setup);
		$this->view->assign('profilingData', $this->createProfilingDataFromSetup());
		$startTime = microtime(TRUE);
		$this->startProfiler();
		$output = $this->view->render();
		$endTime = microtime(TRUE);
		$timeInMilliseconds = (integer)(($endTime - $startTime) * 1000);
		$this->stopProfiler($timeInMilliseconds);
		$output = str_replace('###RENDERING_TIME###', number_format($timeInMilliseconds, 2, ',', '.') , $output);
		return $output;
	}

	/**
	 * Creates Fluid template (if it does not already exist) and returns the path and filename
	 *
	 * @return string template path and filename
	 */
	protected function createTemplateFileFromSetup() {
		$templateFilename = $this->createTemplateFilenameFromSetup();
		$templatePathAndFilename = \TYPO3\FLOW3\Utility\Files::concatenatePaths(array($this->settings['profilingTemplatesDirectory'], $templateFilename));
		if (!file_exists($templatePathAndFilename)) {
			$templateCode = $this->createTemplateFromSetup();
			\TYPO3\FLOW3\Utility\Files::createDirectoryRecursively($this->settings['profilingTemplatesDirectory']);
			file_put_contents($templatePathAndFilename, $templateCode);
		}
		return $templatePathAndFilename;
	}

	/**
	 * @return string
	 */
	protected function createTemplateFilenameFromSetup() {
		return sprintf('%d-%d-%d-%d-%d-%d.html',
					$this->setup['layout'],
					$this->setup['objects'],
					$this->setup['arrays'],
					$this->setup['forms'],
					$this->setup['nestingLevels'],
					$this->setup['partials']
		);
	}

	/**
	 * @return string
	 */
	protected function createTemplateFromSetup() {
		$templateCode = '';
		if ($this->setup['layout']) {
			$templateCode .= '<f:layout name="PerformanceTest" /><f:section name="content">';
		} else {
			$templateCode .= <<<EOT
				<?xml version="1.0" encoding="utf-8" ?>
				<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>Fluid - Viewhelpertest (Profiling)</title>

				<f:base />
				<link href="{f:uri.resource(path: 'styles.css')}" rel="stylesheet" type="text/css" />
				</head>
				<body>

EOT;
		}

		$templateCode .= '<h1>Rendering time: ###RENDERING_TIME### ms</h1>';

		if ($this->setup['objects']) {
			$templateCode .= '<h2>Objects (' . (integer)$this->setup['objects'] . ')</h2>';
			$templateCode .= <<<EOT
	<div class="profilingBox">
		<f:for each="{profilingData.objects.users}" as="user">
			{user.firstName} {user.lastName}<br />
		</f:for>
	</div>

EOT;
		}

		if ($this->setup['arrays']) {
			$templateCode .= '<h2>Arrays (' . (integer)$this->setup['arrays'] . ')</h2>';
			$templateCode .= <<<EOT
	<div class="profilingBox">
		<f:for each="{profilingData.arrays.names}" as="name">
			{name}<br />
		</f:for>
	</div>

EOT;
		}

		if ($this->setup['forms']) {
			$templateCode .= '<h2>Forms (' . (integer)$this->setup['forms'] . ')</h2>' . chr(10);
			$templateCode .= '<div class="profilingBox">' . chr(10);
			for ($i = 0; $i < (integer)$this->setup['forms']; $i ++) {
				$templateCode .= <<<EOT
				<f:form action="profile" object="{profilingData.forms.users.$i}" objectName="user$i">
					<f:form.checkbox property="newsletter" value="1" /><br />
					<f:form.hidden property="firstName" /><br />
					<f:form.password property="lastName" /><br />
					<f:form.radio property="newsletter" value="1" /><br />
					<f:form.select property="interests" options="{interest1: 'interest 01', interest2: 'interest 02'}" /><br />
					<f:form.textarea property="firstName" rows="2" cols="5" /><br />
					<f:form.textfield property="firstName" /><br />
					<f:form.submit value="submit" /><br />
				</f:form>

EOT;
			}
			$templateCode .= '</div>' .chr(10);
		}

		if ($this->setup['nestingLevels']) {
			$templateCode .= '<h2>Nesting levels (' . (integer)$this->setup['nestingLevels'] . ')</h2>';
			$forLoops = $this->forLoopRecursively((integer)$this->setup['nestingLevels']);
			$templateCode .= <<<EOT
	<div class="profilingBox">
		{profilingData.nestingLevels.person.name}
		<f:for each="{profilingData.nestingLevels.person.children}" as="child1">
			$forLoops;
		</f:for>
	</div>

EOT;
		}

		if ($this->setup['partials']) {
			$templateCode .= '<h2>Partials (' . (integer)$this->setup['partials'] . ')</h2>';
			$templateCode .= <<<EOT
	<div class="profilingBox">
		<f:for each="{profilingData.partials}" as="partialData">
			<f:render partial="Performance/Dummy" arguments="{data: partialData}" />
		</f:for>
	</div>

EOT;
		}

		if ($this->setup['sections']) {
			$templateCode .= '<h2>Sections (' . (integer)$this->setup['sections'] . ')</h2>';
			$templateCode .= <<<EOT
	<div class="profilingBox">
		<f:section name="testSection">
			This is content from section #{data.index}
		</f:section>
		<f:for each="{profilingData.sections}" as="sectionData">
			<f:render section="testSection" arguments="{data: sectionData}" />
		</f:for>
	</div>

EOT;
		}

		$templateCode .= '<p><f:link.action action="index">BACK</f:link.action></p>';
		if ($this->setup['layout']) {
			$templateCode .= '</f:section>';
		} else {
			$templateCode .= '</body></html>';
		}
		return $templateCode;
	}

	/**
	 * @param  $totalLevels
	 * @param int $currentLevel
	 * @return string
	 */
	protected function forLoopRecursively($totalLevels, $currentLevel = 1) {
		$indentation = str_repeat("\t", $currentLevel + 2);
		$nextLevel = $currentLevel + 1;
		$content = $indentation . '<f:for each="{child' . $currentLevel . '.children}" as="child' . $nextLevel . '">' . chr(10);
		$content .= $indentation . '{child' . $nextLevel . '.name}<br />' . chr(10);
		if ($nextLevel < $totalLevels) {
			$content .= $this->forLoopRecursively($totalLevels, $nextLevel);
		}
		$content .= $indentation . '</f:for>' . chr(10);
		return $content;
	}

	/**
	 * @return array
	 */
	protected function createProfilingDataFromSetup() {
		$profilingData = array();
		for ($i = 0; $i < (integer)$this->setup['objects']; $i ++) {
			$profilingData['objects']['users'][] = new \TYPO3\Viewhelpertest\Domain\Model\User($i + 1, \TYPO3\Faker\Name::firstName(), \TYPO3\Faker\Name::lastName(), TRUE);
		}
		for ($i = 0; $i < (integer)$this->setup['arrays']; $i ++) {
			$profilingData['arrays']['names'][] = \TYPO3\Faker\Name::fullName();
		}
		for ($i = 0; $i < (integer)$this->setup['forms']; $i ++) {
			$profilingData['forms']['users'][] = new \TYPO3\Viewhelpertest\Domain\Model\User($i + 1, \TYPO3\Faker\Name::firstName(), \TYPO3\Faker\Name::lastName(), TRUE);
		}
		if ((integer)$this->setup['nestingLevels'] > 0) {
			$profilingData['nestingLevels']['person'] = $this->createDataRecursively((integer)$this->setup['nestingLevels']);
		}
		for ($i = 0; $i < (integer)$this->setup['partials']; $i ++) {
			$profilingData['partials'][] = array('index' => $i + 1);
		}
		for ($i = 0; $i < (integer)$this->setup['sections']; $i ++) {
			$profilingData['sections'][] = array('index' => $i + 1);
		}
		return $profilingData;
	}

	/**
	 * @param integer $totalLevels
	 * @param integer $currentLevel
	 * @return array
	 */
	protected function createDataRecursively($totalLevels, $currentLevel = 0) {
		$user = array('name' => \TYPO3\Faker\Name::fullName());
		if ($currentLevel < $totalLevels) {
			$user['children'] = array();
			$user['children'][] = $this->createDataRecursively($totalLevels, $currentLevel + 1);
			$user['children'][] = $this->createDataRecursively($totalLevels, $currentLevel + 1);
			$user['children'][] = $this->createDataRecursively($totalLevels, $currentLevel + 1);
			$user['children'][] = $this->createDataRecursively($totalLevels, $currentLevel + 1);
		}
		return $user;
	}

	/**
	 * @return void
	 */
	protected function startProfiler() {
		if (!function_exists('xhprof_enable')) {
			return;
		}

		xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
	}

	/**
	 * @param float $timeInMilliseconds
	 * @return void
	 */
	protected function stopProfiler($timeInMilliseconds) {
		if (!function_exists('xhprof_enable')) {
			return;
		}

		$xhprofData = xhprof_disable();

		require_once \TYPO3\FLOW3\Utility\Files::concatenatePaths(array($this->settings['xhprof']['rootDirectory'], '/xhprof_lib/utils/xhprof_lib.php'));
		require_once \TYPO3\FLOW3\Utility\Files::concatenatePaths(array($this->settings['xhprof']['rootDirectory'], '/xhprof_lib/utils/xhprof_runs.php'));

		\TYPO3\FLOW3\Utility\Files::createDirectoryRecursively($this->settings['xhprof']['outputDirectory']);
		$xhprofRun = new \XHProfRuns_Default($this->settings['xhprof']['outputDirectory']);

		$rawGetArguments = $this->environment->getRawGetArguments();
		$optimizationIdentifier = isset($rawGetArguments['optimizationIdentifier']) ? $rawGetArguments['optimizationIdentifier'] : $this->settings['xhprof']['optimizationIdentifier'];
		$outputFilename = sprintf('vhtest_%s_%s_%s', $optimizationIdentifier, $this->request->getControllerActionName(), date('Y-m-d_H-i-s'));
		$xhprofRun->save_run(
			$xhprofData,
			'xhprof',
			$outputFilename
		);
		$settingsOutputFilename = $outputFilename . '.settings';
		$data = $this->setup;
		$data['time'] = $timeInMilliseconds * 1000;
		file_put_contents(\TYPO3\FLOW3\Utility\Files::concatenatePaths(array($this->settings['xhprof']['outputDirectory'], $settingsOutputFilename)), serialize($data));
	}
}
?>