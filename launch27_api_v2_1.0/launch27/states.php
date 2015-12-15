<?php
	
	namespace launch27;

	class states{

		public static function get(){
			$states=request::initRequest('states.get');
			return $states['states'];
		}

	}

?>