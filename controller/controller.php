<?php

/**
 * ownCloud - App Framework
 *
 * @author Bernhard Posselt
 * @copyright 2012 Bernhard Posselt nukeawhale@gmail.com
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


namespace OCA\AppFramework\Controller;

use OCA\AppFramework\Http\JSONResponse;
use OCA\AppFramework\Http\TemplateResponse;
use OCA\AppFramework\Http\Request;
use OCA\AppFramework\Core\API;


/**
 * Baseclass to inherit your controllers from
 */
abstract class Controller {

        /**
         * @var API instance of the api layer
         */
        protected $api;

        private $request;

        /**
         * @param API $api an api wrapper instance
         * @param Request $request an instance of the request
         */
        public function __construct(API $api, Request $request){
                $this->api = $api;
                $this->request = $request;
        }


        /**
         * Lets you access post and get parameters by the index
         * @param string $key the key which you want to access in the URL Parameter
         *                     placeholder, $_POST or $_GET array.
         *                     The priority how they're returned is the following:
         *                     1. URL parameters
         *                     2. POST parameters
         *                     3. GET parameters
         * @param mixed $default If the key is not found, this value will be returned
         * @return mixed the content of the array
         */
        public function params($key, $default=null){
                $postValue = $this->request->getPOST($key);
                $getValue = $this->request->getGET($key);
                $urlValue = $this->request->getURLParams($key);

                if($urlValue !== null){
                        return $urlValue;
                }

                if($postValue !== null){
                        return $postValue;
                }

                if($getValue !== null){
                        return $getValue;
                }

                return $default;
        }


        /**
         * Returns all params that were received, be it from the request
         * (as GET or POST) or throuh the URL by the route
         * @return array the array with all parameters
         */
        public function getParams() {
                return $this->request->getRequestParams();
        }


        /**
         * Returns the method of the request
         * @return string the method of the request (POST, GET, etc)
         */
        public function method() {
                return $this->request->getMethod();
        }


        /**
         * Shortcut for accessing an uploaded file through the $_FILES array
         * @param string $key the key that will be taken from the $_FILES array
         * @return array the file in the $_FILES element
         */
        public function getUploadedFile($key){
                return $this->request->getFILES($key);
        }


        /**
         * Shortcut for getting env variables
         * @param string $key the key that will be taken from the $_ENV array
         * @return array the value in the $_ENV element
         */
        public function env($key){
                return $this->request->getENV($key);
        }


        /**
         * Shortcut for getting and setting session variables
         * @param string $key the key that will be taken from the $_SESSION array
         * @param string $value if given sets a new session variable
         * @return array the value in the $_SESSION element
         */
        public function session($key, $value=null){
                if($value !== null) {
                        $this->request->setSESSION($key, $value);
                }
                return $this->request->getSESSION($key);
        }


        /**
         * Shortcut for getting and setting cookie variables
         * @param string $key the key that will be taken from the $_COOKIE array
         * @return array the value in the $_COOKIE element
         */
        public function cookie($key){
                return $this->request->getCOOKIE($key);
        }


        /**
         * Shortcut for rendering a template
         * @param string $templateName the name of the template
         * @param array $params the template parameters in key => value structure
         * @param string $renderAs user renders a full page, blank only your template
         *                          admin an entry in the admin settings
         * @param array $headers set additional headers
         * @return \OCA\AppFramework\Http\TemplateResponse containing the page
         */
        public function render($templateName, array $params=array(),
                                                        $renderAs='user', array $headers=array()){
                $response = new TemplateResponse($this->api, $templateName);
                $response->setParams($params);
                $response->renderAs($renderAs);

                foreach($headers as $header){
                        $response->addHeader($header);
                }

                return $response;
        }


        /**
         * Shortcut for rendering a JSON response
         * @param array $data the PHP array that will be put into the JSON data index
         * @param string $errorMsg If you want to return an error message, pass one
         * @return \OCA\AppFramework\Http\JSONResponse containing the values
         */
        public function renderJSON(array $data, $errorMsg=null){
                $response = new JSONResponse();
                $response->setParams($data);

                if($errorMsg !== null){
                        $response->setErrorMessage($errorMsg);
                }

                return $response;
        }

}