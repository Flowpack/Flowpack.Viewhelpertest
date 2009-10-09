<?php
declare(ENCODING = 'utf-8');
namespace F3\Viewhelpertest\Domain\Model;

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
 * @package Viewhelpertest
 * @subpackage Domain
 * @version $Id: User.php 2613 2009-06-15 14:23:39Z bwaidelich $
 */

/**
 * A User
 *
 * @package Viewhelpertest
 * @subpackage Domain
 * @version $Id: User.php 2613 2009-06-15 14:23:39Z bwaidelich $
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @scope prototype
 * @entity
 */

class Role {
	
	/**
	 * @var string
	 */
	protected $name;
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
}
?>
