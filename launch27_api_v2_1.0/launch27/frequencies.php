<?php
	
	namespace launch27;

	class frequencies{
		
		/*protected static function initRequest($action, $data=null, $user=null){
			$request=new request;
			$response=$request->the_curl($action, $data, $user);
			return $response;
		}*/

		public static function get(){
			$frequencies=request::initRequest('frequencies.get');
			return $frequencies['frequencies'];
		}

	}

?>