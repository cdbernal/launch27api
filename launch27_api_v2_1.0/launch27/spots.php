<?php
	
	namespace launch27;

	class spots{

		public static function get($date){
			$spots=request::initRequest('spots.get', $date);
			return $spots['spots'];
		}

	}

?>