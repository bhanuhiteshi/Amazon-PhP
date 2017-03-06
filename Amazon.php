<?php
	/**
	* Amazon PhP - A PhP Soap Library to Amazon Product Advertising API
	* https://github.com/psbhanu/Amazon-PhP
	*
	* The Amazon Soap Library for PHP makes it easy for developers to implement Amazon Product Advertising API in their PHP Application, 
	* and build robust applications including a e-commerce shop. You can get started in minutes by installing the Library through Composer 
	* or by downloading a single zip or phar file from our latest release.
	*
	* You can use it with your Core PhP application or you can implement it with your Framework like with CodeIgniter. 
	* To Use with CodeIgniter just copy Amazon.php to your library directory. To use with Core Application or with any other framework just 
	* include or require the file and start by instantiating the Amazon Class. 
	*
	*
	* Prerequisite	PhP extension - SOAP.
	*
	* @package      Amazon PhP
	* @license      https://opensource.org/licenses/MIT MIT
	* @version      1.0.0
	* @author       psbhanu <psbhanu@outlook.com> <psbhanu.com>
	* @link         https://github.com/psbhanu/Amazon-PhP/wiki Wiki
	* @link         https://github.com/psbhanu/Amazon-PhP Source
	*/
	
	class Amazon {
	
		const RETURN_TYPE_ARRAY  = 1;
		const RETURN_TYPE_OBJECT = 2;
		
		/**
		* Baseconfigurationstorage
		*
		* @var array
		*/
		private $requestConfig = array(
			'requestDelay' => false
		);
		
		/**
		* Responseconfigurationstorage
		*
		* @var array
		*/
		private $responseConfig = array(
			'returnType'          => self::RETURN_TYPE_OBJECT,
			'responseGroup'       => 'Small',
			'optionalParameters'  => array()
		);
		
		/**
		* All possible locations
		*
		* @var array
		*/
		private $possibleLocations = array('de', 'com', 'co.uk', 'ca', 'fr', 'co.jp', 'it', 'cn', 'es', 'in');
		
		/**
		* The WSDL File
		*
		* @var string
		*/
		protected $webserviceWsdl = 'http://webservices.amazon.com/AWSECommerceService/AWSECommerceService.wsdl';
		
		/**
		* The SOAP Endpoint
		*
		* @var string
		*/
		protected $webserviceEndpoint = 'https://webservices.amazon.%%COUNTRY%%/onca/soap?Service=AWSECommerceService';
		
		/**
		* @param string $accessKey
		* @param string $secretKey
		* @param string $country
		* @param string $associateTag
		*/
		public function __construct($params) {
			$accessKey 		= $params['AWS_API_KEY'];
			$secretKey 		= $params['AWS_API_SECRET_KEY'];
			$country 		= $params['AWS_API_LOCALE']; 
			$associateTag 	= $params['AWS_ASSOCIATE_TAG'];
			
			if (empty($accessKey) || empty($secretKey)) :
				throw new Exception('No Access Key or Secret Key has been set');
			endif;
			
			$this->requestConfig['accessKey']     = $accessKey;
			$this->requestConfig['secretKey']     = $secretKey;
			$this->associateTag($associateTag);
			$this->country($country);
		}
		
		/**
		* execute search
		*
		* @param string $pattern
		*
		* @return array|object return type depends on setting
		*
		* @see returnType()
		*/
		public function search($pattern, $nodeId = null) {
			if (false === isset($this->requestConfig['category'])) :
				throw new Exception('No Category given: Please set it up before');
			endif;
			
			$browseNode = array();
			if (null !== $nodeId && true === $this->validateNodeId($nodeId)) :
				$browseNode = array('BrowseNode' => $nodeId);
			endif;
			
			$params = $this->buildRequestParams('ItemSearch', array_merge(
				array(
					'Keywords' => $pattern,
					'SearchIndex' => $this->requestConfig['category']
				),
				$browseNode
			));
			
			return $this->returnData(
				$this->performSoapRequest("ItemSearch", $params)
			);
		}
		
		/**
		* execute ItemLookup request
		*
		* @param string $asin
		*
		* @return array|object return type depends on setting
		*
		* @see returnType()
		*/
		public function lookup($asin) {
			$params = $this->buildRequestParams('ItemLookup', array(
				'ItemId' => $asin,
			));
			
			return $this->returnData(
				$this->performSoapRequest("ItemLookup", $params)
			);
		}
		
		
		/**
		* Implementation of BrowseNodeLookup
		* This allows to fetch information about nodes (children anchestors, etc.)
		*
		* @param integer $nodeId
		*/
		public function browseNodeLookup($nodeId) {
			$this->validateNodeId($nodeId);
			
			$params = $this->buildRequestParams('BrowseNodeLookup', array(
				'BrowseNodeId' => $nodeId
			));
			
			return $this->returnData(
				$this->performSoapRequest("BrowseNodeLookup", $params)
			);
		}
		
		/**
		* Implementation of SimilarityLookup
		* This allows to fetch information about product related to the parameter product
		*
		* @param string $asin
		*/
		public function similarityLookup($asin) {
			$params = $this->buildRequestParams('SimilarityLookup', array(
				'ItemId' => $asin
			));
			
			return $this->returnData(
				$this->performSoapRequest("SimilarityLookup", $params)
			);
		}
		
		/**
		* Builds the request parameters
		*
		* @param string $function
		* @param array  $params
		*
		* @return array
		*/
		protected function buildRequestParams($function, array $params) {
			$associateTag = array();
			
			if(false === empty($this->requestConfig['associateTag'])) :
				$associateTag = array('AssociateTag' => $this->requestConfig['associateTag']);
			endif;
			
			return array_merge(
				$associateTag,
				array(
					'AWSAccessKeyId' => $this->requestConfig['accessKey'],
					'Request' => array_merge(
						array('Operation' => $function),
							$params,
							$this->responseConfig['optionalParameters'],
							array('ResponseGroup' => $this->prepareResponseGroup()
						)
					)
				)
			);
		}
		
		/**
		* Prepares the responsegroups and returns them as array
		*
		* @return array|prepared responsegroups
		*/
		protected function prepareResponseGroup() {
			if (false === strstr($this->responseConfig['responseGroup'], ','))
			return $this->responseConfig['responseGroup'];
			
			return explode(',', $this->responseConfig['responseGroup']);
		}
		
		/**
		* @param string $function Name of the function which should be called
		* @param array $params Requestparameters 'ParameterName' => 'ParameterValue'
		*
		* @return array The response as an array with stdClass objects
		*/
		protected function performSoapRequest($function, $params) {
			if (true ===  $this->requestConfig['requestDelay']) :
				sleep(1);
			endif;
			
			$soapClient = new SoapClient(
				$this->webserviceWsdl,
				array('exceptions' => 1)
			);
			
			$soapClient->__setLocation(
				str_replace(
					'%%COUNTRY%%',
					$this->responseConfig['country'],
					$this->webserviceEndpoint
				)
			);
			
			$soapClient->__setSoapHeaders($this->buildSoapHeader($function));
			
			return $soapClient->__soapCall($function, array($params));
		}
		
		/**
		* Provides some necessary soap headers
		*
		* @param string $function
		*
		* @return array Each element is a concrete SoapHeader object
		*/
		protected function buildSoapHeader($function) {
			$timeStamp = $this->getTimestamp();
			$signature = $this->buildSignature($function . $timeStamp);
			
			return array(
				new SoapHeader(
					'http://security.amazonaws.com/doc/2007-01-01/',
					'AWSAccessKeyId',
					$this->requestConfig['accessKey']
				),
				new SoapHeader(
					'http://security.amazonaws.com/doc/2007-01-01/',
					'Timestamp',
					$timeStamp
				),
				new SoapHeader(
					'http://security.amazonaws.com/doc/2007-01-01/',
					'Signature',
					$signature
				)
			);
		}
		
		/**
		* provides current gm date
		*
		* primary needed for the signature
		*
		* @return string
		*/
		final protected function getTimestamp() {
			return gmdate("Y-m-d\TH:i:s\Z");
		}
		
		/**
		* provides the signature
		*
		* @return string
		*/
		final protected function buildSignature($request) {
			return base64_encode(hash_hmac("sha256", $request, $this->requestConfig['secretKey'], true));
		}
		
		/**
		* Basic validation of the nodeId
		*
		* @param integer $nodeId
		*
		* @return boolean
		*/
		final protected function validateNodeId($nodeId) {
			if (false === is_numeric($nodeId) || $nodeId <= 0) :
				throw new InvalidArgumentException(sprintf('Node has to be a positive Integer.'));
			endif;
			
			return true;
		}
		
		/**
		* Returns the response either as Array or Array/Object
		*
		* @param object $object
		*
		* @return mixed
		*/
		protected function returnData($object) {
			switch ($this->responseConfig['returnType']) :
				case self::RETURN_TYPE_OBJECT:
				return $object;
				break;
				
				case self::RETURN_TYPE_ARRAY:
				return $this->objectToArray($object);
				break;
				
				default:
				throw new InvalidArgumentException(sprintf(
				"Unknwon return type %s", $this->responseConfig['returnType']
				));
				break;
			endswitch;
		}
		
		/**
		* Transforms the responseobject to an array
		*
		* @param object $object
		*
		* @return array An arrayrepresentation of the given object
		*/
		protected function objectToArray($object) {
			$out = array();
			foreach ($object as $key => $value) :
				switch (true) :
					case is_object($value):
					$out[$key] = $this->objectToArray($value);
					break;
					
					case is_array($value):
					$out[$key] = $this->objectToArray($value);
					break;
					
					default:
					$out[$key] = $value;
					break;
				endswitch;
			endforeach;
			
			return $out;
		}
		
		/**
		* set or get optional parameters
		*
		* if the argument params is null it will reutrn the current parameters,
		* otherwise it will set the params and return itself.
		*
		* @param array $params the optional parameters
		*
		* @return array|Amazon depends on params argument
		*/
		public function optionalParameters($params = null) {
			if (null === $params) :
				return $this->responseConfig['optionalParameters'];
			endif;
			
			if (false === is_array($params)) :
				throw new InvalidArgumentException(
					sprintf(
						"%s is no valid parameter: Use an array with Key => Value Pairs", 
						$params
					)
				);
			endif;
			
			$this->responseConfig['optionalParameters'] = $params;
			
			return $this;
		}
		
		/**
		* Set or get the country
		*
		* if the country argument is null it will return the current
		* country, otherwise it will set the country and return itself.
		*
		* @param string|null $country
		*
		* @return string|Amazon depends on country argument
		*/
		public function country($country = null) {
			if (null === $country) :
				return $this->responseConfig['country'];
			endif;
			
			if (false === in_array(strtolower($country), $this->possibleLocations)) :
				throw new InvalidArgumentException(
					sprintf(
						"Invalid Country-Code: %s! Possible Country-Codes: %s",
						$country,
						implode(', ', $this->possibleLocations)
					)
				);
			endif;
			
			$this->responseConfig['country'] = strtolower($country);
			
			return $this;
		}
		
		/**
		* Setting/Getting the amazon category
		*
		* @param string $category
		*
		* @return string|Amazon depends on category argument
		*/
		public function category($category = null) {
			if (null === $category) :
				return isset($this->requestConfig['category']) ? $this->requestConfig['category'] : null;
			endif;
			
			$this->requestConfig['category'] = $category;
			
			return $this;
		}
		
		/**
		* Setting/Getting the responsegroup
		*
		* @param string $responseGroup Comma separated groups
		*
		* @return string|Amazon depends on responseGroup argument
		*/
		public function responseGroup($responseGroup = null) {
			if (null === $responseGroup) :
				return $this->responseConfig['responseGroup'];
			endif;
			
			$this->responseConfig['responseGroup'] = $responseGroup;
			
			return $this;
		}
		
		/**
		* Setting/Getting the returntype
		* It can be an object or an array
		*
		* @param integer $type Use the constants RETURN_TYPE_ARRAY or RETURN_TYPE_OBJECT
		*
		* @return integer|Amazon depends on type argument
		*/
		public function returnType($type = null) {
			if (null === $type) :
				return $this->responseConfig['returnType'];
			endif;
			
			$this->responseConfig['returnType'] = $type;
			
			return $this;
		}
		
		/**
		* Setter/Getter of the AssociateTag.
		* This could be used for late bindings of this attribute
		*
		* @param string $associateTag
		*
		* @return string|Amazon depends on associateTag argument
		*/
		public function associateTag($associateTag = null) {
			if (null === $associateTag) :
				return $this->requestConfig['associateTag'];
			endif;
			
			$this->requestConfig['associateTag'] = $associateTag;
			
			return $this;
		}
		
		/**
		* @deprecated use returnType() instead
		*/
		public function setReturnType($type)
		{
			return $this->returnType($type);
		}
		
		/**
		* Setting the resultpage to a specified value.
		* Allows to browse resultsets which have more than one page.
		*
		* @param integer $page
		*
		* @return Amazon
		*/
		public function page($page) {
			if (false === is_numeric($page) || $page <= 0) :
				throw new InvalidArgumentException(
					sprintf(
						'%s is an invalid page value. It has to be numeric and positive',
						$page
					)
				);
			endif;
			
			$this->responseConfig['optionalParameters'] = array_merge(
				$this->responseConfig['optionalParameters'],
				array("ItemPage" => $page)
			);
			
			return $this;
		}
		
		/**
		* Enables or disables the request delay.
		* If it is enabled (true) every request is delayed one second to get rid of the api request limit.
		*
		* Reasons for this you can read on this site:
		* https://affiliate-program.amazon.com/gp/advertising/api/detail/faq.html
		*
		* By default the requestdelay is disabled
		*
		* @param boolean $enable true = enabled, false = disabled
		*
		* @return boolean|Amazon depends on enable argument
		*/
		public function requestDelay($enable = null) {
			if (false === is_null($enable) && true === is_bool($enable)) :
				$this->requestConfig['requestDelay'] = $enable;
				
				return $this;
			endif;
			
			return $this->requestConfig['requestDelay'];
		}

		/**
		* execute CartGet request
		*
		* @param string $hmac
		* @param string $cartId
		*
		* @return array|object return type depends on setting
		*
		* @see returnType()
		*/
		public function cartGet($hmac, $cartId) {
			$params = $this->buildRequestParams('CartGet', array(
					'HMAC' => $hmac,
					'CartId' => $cartId,
				)
			);
			
			return $this->returnData(
				$this->performSoapRequest("CartGet", $params)
			);
		} 
		
		/**
		* execute CartCreate request
		*
		* @param string $ASIN
		* @param string $qty
		*
		* @return array|object return type depends on setting
		*
		* @see returnType()
		*/		
		public function cartCreate($ASIN, $qty) {
			$params = $this->buildRequestParams('CartCreate', array(
				'Items' => array(
					array( 
						'ASIN' => $ASIN,
						'Quantity' => $qty
					)
				),				
			));
			return $this->returnData(
				$this->performSoapRequest("CartCreate", $params)
			);
		}	
		
		/**
		* execute CartAdd request
		*
		* @param string $ASIN
		* @param string $qty
		* @param string $hmac
		* @param string $cartId
		*
		* @return array|object return type depends on setting
		*
		* @see returnType()
		*/	
		public function cartAdd($ASIN, $qty, $hmac, $cartId) {
			$params = $this->buildRequestParams('CartAdd', array(
				'Items' => array(
					array( 
						'ASIN' => $ASIN,
						'Quantity' => $qty
					)
				),
				'HMAC'	=>$hmac,
				'CartId'=>$cartId,
			));
			
			return $this->returnData(
				$this->performSoapRequest("CartAdd", $params)
			);						
		}	
		
		/**
		* execute CartModify request
		*
		* @param string $cartItemId
		* @param string $qty
		* @param string $hmac
		* @param string $cartId
		*
		* @return array|object return type depends on setting
		*
		* @see returnType()
		*/	
		public function cartModify($cartItemId, $qty, $hmac, $cartId) {
			$params = $this->buildRequestParams('CartModify', array(
				'Items' => array(
					array( 
						'CartItemId' => $cartItemId,
						'Quantity' => $qty
					)
				),
				'HMAC'	=>$hmac,
				'CartId'=>$cartId,
			));
			
			return $this->returnData(
				$this->performSoapRequest("CartModify", $params)
			);
		}		

		/**
		* execute CartClear request
		*
		* @param string $hmac
		* @param string $cartId
		*
		* @return array|object return type depends on setting
		*
		* @see returnType()
		*/	
		public function cartClear($hmac, $cartId) {
			$params = $this->buildRequestParams('CartClear', array(
					'HMAC'	=>$hmac,
					'CartId'=>$cartId,
				)
			);
			
			return $this->returnData(
				$this->performSoapRequest("CartClear", $params)
			);
		}		
	}	