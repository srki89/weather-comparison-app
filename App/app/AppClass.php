<?php


require_once 'App/app/ValidationClass.php';



use App\Validation;
use App\Content;
use App\Subscriber;


class App{



	private $configPath = 'App/config.php';

	private $templates;
	private $homeUrl;
	private $customCSSpath;
	private $jsonPath;
	private $apiWeather;
	private $apiGoecoding;




	/**
	* Construct function ...
	**/
	public function __construct(){
			//construct
			// Start session
			session_start();

			// get config from external file
			$config = include $this->configPath;
			$this->homeUrl = $config['HOME_URL'];
			$this->customCSSpath = $config['CUSTOM_CSS_PATH'];
			$this->jsonPath = $config['JSON_MESSAGES_PATH'];
			$this->templatesPath = $config['TEMPLATES_CONFIG_PATH'];
			$this->apiWeather = $config['GET_WEATHER_API'];
			$this->apiGoecoding = $config['GEOCODING_ADDRESS'];
	}

	/**
	*	Function load template and configuration
	* Check for submit form
	* @return include TEMPLATE OR REDIRECT TO HOME URL
	**/
	public function load(){



			$request_url = $this->getUrl();

			if($request_url === $this->homeUrl){
					// Check submit form on index page
					if(isset($_POST['submit'])){
						$this->post();
					}else{
						$this->getTemplate('INDEX');
					}
			}else{
					$this->getTemplate('404');
			}

			session_destroy();
	}

	/**
	* Function set variables for index template
	* Clear all session
	*	@return include index template
	**/
	private function getTemplate($templateName){
			//get templates from external file
			$templates = include $this->templatesPath;
			// include page template
			$template = $templates[$templateName];
			if(file_exists($template)){
				include $template;
			}
	}

	/**
	* get request url
	* @return string URL
	**/
	private function getUrl(){
			return 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}

	/**
	* get messages from json file
	* @return array or false
	**/
	private function getJsonMessages(){
			//get json messages
			if(file_exists($this->jsonPath)){
					$json = file_get_contents($this->jsonPath);
					return json_decode($json);
			}else{
					return false;
			}
	}

	/**
	* Catch data from form and validation ..
	* @return redirect to home page
	**/
	private function post(){
			$errorMessages = $this->getJsonMessages();

			$dep_sel = (integer)$_POST['use_dep'];
			$des_sel = (integer)$_POST['use_des'];

			// Validation in Departure form
			if($dep_sel == 1){
					$latitude = trim($_POST['latitude_dep']);
					$validationLat = Validation::check($latitude, ['lon_lat', 'no_empty']);
					if( !$validationLat ){
							if($errorMessages){
									$_SESSION['VALIDATION_ERROR']["LAT_DEP_ERROR"] = $errorMessages->LAT_DEP_ERROR;
							}
					}
					$longitude = trim($_POST['longitude_dep']);
					$validationLon = Validation::check($longitude, ['lon_lat', 'no_empty']);
					if( !$validationLon ){
							if($errorMessages){
									$_SESSION['VALIDATION_ERROR']["LON_DEP_ERROR"] = $errorMessages->LON_DEP_ERROR;
							}
					}
					$_SESSION['use_dep'] = $dep_sel;
					$_SESSION['latitude_dep'] = $latitude;
					$_SESSION['longitude_dep'] = $longitude;
			}else{
					$addressDep = trim($_POST['address_dep']);
					$validationCity = Validation::check($addressDep, ['string', 'no_empty']);
					if( !$validationCity ){
							if($errorMessages){
									$_SESSION['VALIDATION_ERROR']["ADDRESS_DEP_ERROR"] = $errorMessages->ADDRESS_DEP_ERROR;
							}
					}

					$_SESSION['use_dep'] = $dep_sel;
					$_SESSION['address_dep'] = $addressDep;

			}
			// Validation in Destination form
			if($des_sel == 1){
					$latitude = trim($_POST['latitude_des']);
					$validationLat = Validation::check($latitude, ['lon_lat', 'no_empty']);
					if( !$validationLat ){
							if($errorMessages){
									$_SESSION['VALIDATION_ERROR']["LAT_DES_ERROR"] = $errorMessages->LAT_DES_ERROR;
							}
					}
					$longitude = trim($_POST['longitude_des']);
					$validationLon = Validation::check($longitude, ['lon_lat', 'no_empty']);
					if( !$validationLon ){
							if($errorMessages){
									$_SESSION['VALIDATION_ERROR']["LON_DES_ERROR"] = $errorMessages->LON_DES_ERROR;
							}
					}
					$_SESSION['use_des'] = $des_sel;
					$_SESSION['latitude_des'] = $latitude;
					$_SESSION['longitude_des'] = $longitude;
			}else{
					$addressDes = trim($_POST['address_des']);
					$validationCity = Validation::check($addressDes, ['string', 'no_empty']);
					if( !$validationCity ){
							if($errorMessages){
									$_SESSION['VALIDATION_ERROR']["ADDRESS_DES_ERROR"] = $errorMessages->ADDRESS_DES_ERROR;
							}
					}

					$_SESSION['use_des'] = $des_sel;
					$_SESSION['address_des'] = $addressDes;

			}

			if(!isset($_SESSION['VALIDATION_ERROR'])){
					// Departure
					if($dep_sel == 1){
							// Departure geolocation
							// User use latitude and longitude
							$departureData = $this->getAPIweather($this->apiWeather, $_SESSION['latitude_dep'], $_SESSION['longitude_dep']);
							if($departureData["dewPointTemp"]){
									$_SESSION['depData'] = $departureData;
							}else{
									$_SESSION['GET_DATA_ERROR'][] = $errorMessages->DEPARTURE_GEO_ERROR;
							}
					}else{
							// Departure address
							// User use address
							$geolocationDep = $this->getAPIgeocoding($this->apiGoecoding, $_SESSION['address_dep']);
							if($geolocationDep){
									$departureData = $this->getAPIweather($this->apiWeather, $geolocationDep['latitude'], $geolocationDep['longitude']);
									if(!empty($departureData["dewPointTemp"])){
											$_SESSION['depData'] = $departureData;
									}else{
											$_SESSION['GET_DATA_ERROR'][] = $errorMessages->DEPARTURE_GEO_ERROR;
									}
							}else{
									$_SESSION['GEOCODING_DATA_ERROR'][] = $errorMessages->GEOCODING_DEP_DATA_ERROR;
							}
					}
					// Destination
					if($des_sel == 1){
							// Destination geolocation
							// User use latitude and longitude
							$destinationData = $this->getAPIweather($this->apiWeather, $_SESSION['latitude_des'], $_SESSION['longitude_des']);
							if(!empty($destinationData["dewPointTemp"])){
									$_SESSION['desData'] = $destinationData;
							}else{
									$_SESSION['GET_DATA_ERROR'][] = $errorMessages->DESTINATION_GEO_ERROR;
							}
					}else{
							// Destination address
							// User use address
							$geolocationDes = $this->getAPIgeocoding($this->apiGoecoding, $_SESSION['address_des']);
							if($geolocationDes){
									$destinationData = $this->getAPIweather($this->apiWeather, $geolocationDes['latitude'], $geolocationDes['longitude']);
									if(!empty($destinationData["dewPointTemp"])){
											$_SESSION['desData'] = $destinationData;
									}else{
											$_SESSION['GET_DATA_ERROR'][] = $errorMessages->DESTINATION_GEO_ERROR;
									}
							}else{
									$_SESSION['GEOCODING_DATA_ERROR'][] = $errorMessages->GEOCODING_DES_DATA_ERROR;
							}
					}

			}

		  header('Location: ' . $this->homeUrl);
			exit;
	}
	/**
	* get API result for goecoding over API route and address
	* @param string $apiRoute
	* @param string $address
	* @return array or false
	**/
	private function getAPIgeocoding($apiRoute, $address){
			$result = false;
			$address = urlencode($address);
			$apiRoute = str_replace("!ADDRESS!", $address, $apiRoute);
			$geolocationJsonContent = file_get_contents($apiRoute);
			$geolocation = json_decode($geolocationJsonContent);
			$latitude = isset($geolocation->data[0]->latitude) ? $geolocation->data[0]->latitude : null;
			$longitude = isset($geolocation->data[0]->longitude) ? $geolocation->data[0]->longitude : null;
			if(!empty($latitude) and !empty($longitude)){
					$result["latitude"] = $latitude;
					$result["longitude"] = $longitude;
			}

			return $result;

	}
	/**
	* get api result for weather over API route and latitude/longitude
	* @param string $apiRoute
	* @param string $latitude
	* @param string $longitude
	* @return array $data
	**/
	private function getAPIweather($apiRoute, $latitude, $longitude){
			// replace !LATITUDE!
			$apiRoute = str_replace("!LATITUDE!", $latitude, $apiRoute);
			// replace !LONGITUDE!
			$apiRoute = str_replace("!LONGITUDE!", $longitude, $apiRoute);

			$result = [];
			$weatherXmlContent = file_get_contents($apiRoute);
			$weatherString = simplexml_load_string($weatherXmlContent);
			$result["dewPointTemp"]              = json_decode($weatherString->product->time->location->dewpointTemperature["value"]);
			$result["humidity"]                  = json_decode($weatherString->product->time->location->humidity["value"]);
			$result["temp"]                      = json_decode($weatherString->product->time->location->temperature["value"]);
			$result["fog"]["value"]              = json_decode($weatherString->product->time->location->fog["percent"]);
			$result["lowClouds"]["value"]        = json_decode($weatherString->product->time->location->lowClouds["percent"]);
			$result["mediumClouds"]["value"]     = json_decode($weatherString->product->time->location->mediumClouds["percent"]);
			$result["highClouds"]["value"]       = json_decode($weatherString->product->time->location->highClouds["percent"]);

			/**
			* each of blocks(fog, low clouds, medium clouds and high clouds) have tree elements in representation
			* Elements: sun, fog, cloud
			* Each of elements have two dinamic style parameters: bottom-pading and opacity
			**/
			$result["fog"]["styleParameters"]          = $this->prepareStyleParams("fog", $result["fog"]["value"]);
			$result["lowClouds"]["styleParameters"]    = $this->prepareStyleParams("lowClouds", $result["lowClouds"]["value"]);
			$result["mediumClouds"]["styleParameters"] = $this->prepareStyleParams("mediumClouds", $result["mediumClouds"]["value"]);
			$result["highClouds"]["styleParameters"]   = $this->prepareStyleParams("highClouds", $result["highClouds"]["value"]);

			return $result;
	}

	/**
	* preprare style for representation block (bottom-padding and opacity) for the tree primary elements (sun, fog, cloud)
	* @param string $block
	* @param string $value
	* @return array
	**/
	private function prepareStyleParams($block, $value){
			$value = (float)$value;
			$styleParameters = [];
			switch($block){
					case "fog":
						if($value == 0){
								$styleParameters["sun"]["padding-bottom"]     = "75";
								$styleParameters["sun"]["opacity"]            = "1.0";
								$styleParameters["fog"]["padding-bottom"]     = "75";
								$styleParameters["fog"]["opacity"]            = "0.0";
								$styleParameters["cloud"]["padding-bottom"]   = "25";
								$styleParameters["cloud"]["opacity"]          = "0.0";
						}else if($value < 21){
								$styleParameters["sun"]["padding-bottom"]     = "75";
								$styleParameters["sun"]["opacity"]            = "0.7";
								$styleParameters["fog"]["padding-bottom"]     = "75";
								$styleParameters["fog"]["opacity"]            = "0.2";
								$styleParameters["cloud"]["padding-bottom"]   = "25";
								$styleParameters["cloud"]["opacity"]          = "0.0";
						}else if($value < 66){
								$styleParameters["sun"]["padding-bottom"]     = "75";
								$styleParameters["sun"]["opacity"]            = "0.4";
								$styleParameters["fog"]["padding-bottom"]     = "75";
								$styleParameters["fog"]["opacity"]            = "0.7";
								$styleParameters["cloud"]["padding-bottom"]   = "25";
								$styleParameters["cloud"]["opacity"]          = "0.0";
						}else{
								$styleParameters["sun"]["padding-bottom"]     = "75";
								$styleParameters["sun"]["opacity"]            = "0.2";
								$styleParameters["fog"]["padding-bottom"]     = "75";
								$styleParameters["fog"]["opacity"]            = "1.0";
								$styleParameters["cloud"]["padding-bottom"]   = "25";
								$styleParameters["cloud"]["opacity"]          = "0.0";
						}
						break;
					case "lowClouds":
						if($value == 0){
								$styleParameters["sun"]["padding-bottom"]     = "75";
								$styleParameters["sun"]["opacity"]            = "0.0";
								$styleParameters["fog"]["padding-bottom"]     = "75";
								$styleParameters["fog"]["opacity"]            = "0.0";
								$styleParameters["cloud"]["padding-bottom"]   = "35";
								$styleParameters["cloud"]["opacity"]          = "0.0";
						}else if($value < 21){
								$styleParameters["sun"]["padding-bottom"]     = "75";
								$styleParameters["sun"]["opacity"]            = "0.0";
								$styleParameters["fog"]["padding-bottom"]     = "75";
								$styleParameters["fog"]["opacity"]            = "0.0";
								$styleParameters["cloud"]["padding-bottom"]   = "35";
								$styleParameters["cloud"]["opacity"]          = "0.2";
						}else if($value < 66){
								$styleParameters["sun"]["padding-bottom"]     = "75";
								$styleParameters["sun"]["opacity"]            = "0.0";
								$styleParameters["fog"]["padding-bottom"]     = "75";
								$styleParameters["fog"]["opacity"]            = "0.0";
								$styleParameters["cloud"]["padding-bottom"]   = "35";
								$styleParameters["cloud"]["opacity"]          = "0.5";
						}else{
								$styleParameters["sun"]["padding-bottom"]     = "75";
								$styleParameters["sun"]["opacity"]            = "0.0";
								$styleParameters["fog"]["padding-bottom"]     = "75";
								$styleParameters["fog"]["opacity"]            = "0.0";
								$styleParameters["cloud"]["padding-bottom"]   = "35";
								$styleParameters["cloud"]["opacity"]          = "1.0";
						}
						break;
					case "mediumClouds":
						if($value == 0){
								$styleParameters["sun"]["padding-bottom"]     = "75";
								$styleParameters["sun"]["opacity"]            = "0.0";
								$styleParameters["fog"]["padding-bottom"]     = "75";
								$styleParameters["fog"]["opacity"]            = "0.0";
								$styleParameters["cloud"]["padding-bottom"]   = "80";
								$styleParameters["cloud"]["opacity"]          = "0.0";
						}else if($value < 21){
								$styleParameters["sun"]["padding-bottom"]     = "75";
								$styleParameters["sun"]["opacity"]            = "0.0";
								$styleParameters["fog"]["padding-bottom"]     = "75";
								$styleParameters["fog"]["opacity"]            = "0.0";
								$styleParameters["cloud"]["padding-bottom"]   = "80";
								$styleParameters["cloud"]["opacity"]          = "0.2";
						}else if($value < 66){
								$styleParameters["sun"]["padding-bottom"]     = "75";
								$styleParameters["sun"]["opacity"]            = "0.0";
								$styleParameters["fog"]["padding-bottom"]     = "75";
								$styleParameters["fog"]["opacity"]            = "0.0";
								$styleParameters["cloud"]["padding-bottom"]   = "80";
								$styleParameters["cloud"]["opacity"]          = "0.5";
						}else{
								$styleParameters["sun"]["padding-bottom"]     = "75";
								$styleParameters["sun"]["opacity"]            = "0.0";
								$styleParameters["fog"]["padding-bottom"]     = "75";
								$styleParameters["fog"]["opacity"]            = "0.0";
								$styleParameters["cloud"]["padding-bottom"]   = "80";
								$styleParameters["cloud"]["opacity"]          = "1.0";
						}
						break;
					case "highClouds":
						if($value == 0){
								$styleParameters["sun"]["padding-bottom"]     = "75";
								$styleParameters["sun"]["opacity"]            = "0.0";
								$styleParameters["fog"]["padding-bottom"]     = "75";
								$styleParameters["fog"]["opacity"]            = "0.0";
								$styleParameters["cloud"]["padding-bottom"]   = "125";
								$styleParameters["cloud"]["opacity"]          = "0.0";
						}else if($value < 21){
								$styleParameters["sun"]["padding-bottom"]     = "75";
								$styleParameters["sun"]["opacity"]            = "0.0";
								$styleParameters["fog"]["padding-bottom"]     = "75";
								$styleParameters["fog"]["opacity"]            = "0.0";
								$styleParameters["cloud"]["padding-bottom"]   = "125";
								$styleParameters["cloud"]["opacity"]          = "0.2";
						}else if($value < 66){
								$styleParameters["sun"]["padding-bottom"]     = "75";
								$styleParameters["sun"]["opacity"]            = "0.0";
								$styleParameters["fog"]["padding-bottom"]     = "75";
								$styleParameters["fog"]["opacity"]            = "0.0";
								$styleParameters["cloud"]["padding-bottom"]   = "125";
								$styleParameters["cloud"]["opacity"]          = "0.5";
						}else{
								$styleParameters["sun"]["padding-bottom"]     = "75";
								$styleParameters["sun"]["opacity"]            = "0.0";
								$styleParameters["fog"]["padding-bottom"]     = "75";
								$styleParameters["fog"]["opacity"]            = "0.0";
								$styleParameters["cloud"]["padding-bottom"]   = "125";
								$styleParameters["cloud"]["opacity"]          = "1.0";
						}
						break;
					default:
						$styleParameters["sun"]["padding-bottom"]     = "75";
						$styleParameters["sun"]["opacity"]            = "0.0";
						$styleParameters["fog"]["padding-bottom"]     = "75";
						$styleParameters["fog"]["opacity"]            = "0.0";
						$styleParameters["cloud"]["padding-bottom"]   = "75";
						$styleParameters["cloud"]["opacity"]          = "0.0";
			}

			return $styleParameters;

	}



}

?>
