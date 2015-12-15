<?php
	
	namespace launch27;
	
	class customer{

		public static function login($email, $password){
			return request::initRequest('auth', array('email'=>$email, 'password'=>$password));
		}

	}

?>