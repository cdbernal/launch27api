<?php
	
	namespace launch27;

	class services{

		public static function get(){
			$services=request::initRequest('services.get');
			return $services['services'];
		}

	}

?>