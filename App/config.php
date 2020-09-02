<?php

	return [




		// Home URL
		'HOME_URL' => 'https://weather-comparison-app.herokuapp.com',

		// Custom css
		"CUSTOM_CSS_PATH" => 'css/customStyle.css',

		// JSON path
		"JSON_MESSAGES_PATH" => 'App/messages.json',
		"TEMPLATES_CONFIG_PATH" => 'App/templates.php',

		// API routes

		// Replace !LATITUDE! with real latitude
		// Replace !LONGITUDE! with real longitude
		"GET_WEATHER_API" => "http://api.met.no/weatherapi/locationforecast/1.9/?lat=!LATITUDE!;lon=!LONGITUDE!",

		// Replace !ADDRESS! with real address
		"GEOCODING_ADDRESS" => "http://api.positionstack.com/v1/forward?access_key=0d82025c81770dfb9b294f27340ac2c9&query=!ADDRESS!",






	];

?>
