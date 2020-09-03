<?php

	return [




		// Home URL
		'HOME_URL' => 'http://weather.test/',

		// Custom css
		"CUSTOM_CSS_PATH" => 'css/customStyle.css',

		// Logs path
		"LOGS_PATH" => 'logs/',

		// JSON path
		"JSON_MESSAGES_PATH" => 'App/messages.json',
		"TEMPLATES_CONFIG_PATH" => 'App/templates.php',

		// API routes

		// Replace !LATITUDE! with real latitude
		// Replace !LONGITUDE! with real longitude
		"GET_WEATHER_API" => "http://api.met.no/weatherapi/locationforecast/1.9/?lat=!LATITUDE!;lon=!LONGITUDE!",

		// Replace !ADDRESS! with real address
		"GEOCODING_ADDRESS" => "http://api.positionstack.com/v1/forward?access_key=YOUR_API_KEY&query=!ADDRESS!",






	];

?>
