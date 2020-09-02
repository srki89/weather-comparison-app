<?php
namespace App;


class Validation{



		private const LON_LAT_ROLE     = 'lon_lat';
		private const STRING_ROLE      = 'string';
		private const NO_EMPTY_ROLE    = 'no_empty';



		/**
		*	Check value over roles
		* @param mixed $value$email   = trim($_POST['email']);
		*	@param array $roles
		*	@param int $lenght
		* @return boolean
		**/
		public static function check($value, $roles){
				$validation = true;
				foreach($roles as $role){
						switch($role){
								case self::LON_LAT_ROLE:
										if( !self::checkLonLat($value) ){
												$validation = false;
										}
										break;
								case self::STRING_ROLE:
										if( !self::checkString($value) ){
												$validation = false;
										}
										break;
								case self::NO_EMPTY_ROLE:
										if( !self::checkEmptyValue($value) ){
												$validation = false;
										}
										break;
						}
				}

				return $validation;
		}
		/**
		*	check value is valid longitude or latidude string
		*	@param mixed $value
		*	@param boolean
		**/
		private static function checkLonLat($value){
				if( !preg_match ("/^[.0-9\-]+$/", $value) ) {
						return false;
				}
				return true;
		}
		/**
		*	check value is string, only letters and space will be allowed
		*	@param mixed $value
		*	@param boolean
		**/
		private static function checkString($value){
				if( !preg_match ("/^[SšđčćžŠĐČĆŽa-zA-Z0-9\s,]*$/", $value) ) {
						return false;
				}
				return true;
		}
		/**
		*	check value is non empty
		*	@param mixed $value
		*	@param boolean
		**/
		private static function checkEmptyValue($value){
				return empty($value) ? false : true;
		}



}

?>
