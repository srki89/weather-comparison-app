<?php
namespace App;


class Logger{



		private const CONFIG_PATH = 'App/config.php';
		private static $logsPath;
	  private static $filePath;


		private const INFO_LEVEL  = "INFO";
		private const ERROR_LEVEL = "ERROR";

		/**
		*	Make log file
		* @return boolean
		**/
		public static function make(){
				// get config from external file
				$config = include self::CONFIG_PATH;
				$logsPath = $config['LOGS_PATH'];
				self::$logsPath = $logsPath;
				$date = str_replace("-", "_", date("Y-m-d"));
				$file = $logsPath . $date . ".txt";

				if(!is_file($file)){
				    $content = '';
				    if(file_put_contents($file, $content) !== false){

								self::$filePath = $file;
								return true;
						}else{
								return false;
						}
				}else{
						self::$filePath = $file;
						return true;
				}
		}
		/**
		* write text in log file
		* @param string $level
		* @param string $className
		* @param string $methodName
		* @param string $message
		**/
		public static function write($level, $className, $methodName, $message){
				$text = date("Y-m-d H:i:s");
				switch($level){
						case self::INFO_LEVEL:
							$text .= "; " . self::INFO_LEVEL;
							break;
						case self::ERROR_LEVEL:
							$text .= "; " . self::ERROR_LEVEL;
							break;
						default:
							$text .= "; ?";
				}
				$text .= "; ClassName: " . $className . "; MethodName: " . $methodName . "; Msg: " . $message . "\r\n";
				$file = fopen(self::$filePath , "a");
				if($file){
						fwrite($file, $text);
				}
		}
		/**
		* Write info message
		* @param string $className
		* @param string $methodName
		* @param string $message
		**/
		public static function info($className, $methodName, $message){
				if(self::make()){
						self::write(self::INFO_LEVEL, $className, $methodName, $message);
				}
		}
		/**
		* Write error message
		* @param string $className
		* @param string $methodName
		* @param string $message
		**/
		public static function error($className, $methodName, $message){
				if(self::make()){
						self::write(self::ERROR_LEVEL, $className, $methodName, $message);
				}
		}



}

?>
