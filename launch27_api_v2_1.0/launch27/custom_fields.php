<?php
	
	namespace launch27;

	class custom_fields{

		public static function get(){
			$cf=request::initRequest('custom_fields.get');
			return $cf['custom_fields'];
		}

	}

?>