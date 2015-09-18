<?php
namespace Flowpack\Viewhelpertest\View;

/*                                                                        *
 * This script belongs to the FLOW3 package "Flowpack.Viewhelpertest".    *
 *                                                                        */

use TYPO3\Fluid\View\TemplatePaths;

class FakeTemplatePaths extends TemplatePaths {

	/**
	 * @var string
	 */
	protected $templateSource;

	/**
	 * @param string $templateSource
	 */
	public function setTemplateSource($templateSource) {
		$this->templateSource = $templateSource;
	}

	/**
	 * @param string $controller
	 * @param string $action
	 * @return string
	 */
	public function getTemplateSource($controller = 'Default', $action = 'Default') {
		return $this->templateSource;
	}

}