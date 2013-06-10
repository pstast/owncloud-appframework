<?php
/**
 * ownCloud - App Framework
 *
 * @author Thomas Müller
 * @copyright 2013 Thomas Müller thomas.mueller@tmit.eu
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\AppFramework\routing;

use \OCA\AppFramework\App;
use \OCA\Mail\DependencyInjection\DIContainer;

class RouteActionHandler {
	private $controllerName;
	private $actionName;

	public function __construct($controllerName, $actionName) {
		$this->controllerName = $controllerName;
		$this->actionName = $actionName;
	}

	public function __invoke($params) {
		App::main($this->controllerName, $this->actionName, $params, new DIContainer());
	}
}
