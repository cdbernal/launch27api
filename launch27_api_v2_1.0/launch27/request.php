<?php

	namespace launch27;

	class request{

		public static function initRequest($action, $data=false, $user=null){
			$response=self::the_curl($action, $data, $user);
			return $response;
		}

		private static function the_curl($action, $data=false, $user=null){
			$method=self::get_method($action);
			$url=self::generate_url($action, $data);
			$headers=self::generate_headers($user);
			$content=self::generate_content($action, $data);

			$response=self::exe_curl( $method, $url, $headers, $content );
			return self::interpretResponse($response);
		}

		private static function get_method($action){
			$methods=array(
					'booking.create'=>'post',
					'booking.estimate'=>'post',
					'booking.update'=>'put',
					'booking.get'=>'get',
					'bookings.get'=>'get',
					'booking.cancel'=>'delete',


					'booking.frequencies.get'=>'get',
				'booking.services.get'=>'get',
				

					'frequencies.get'=>'get',
					'services.get'=>'get',
					'custom_fields'=>'get',
					'auth'=>'post',
					'custom_fields.get'=>'get',
					'states.get'=>'get',
					'spots.get'=>'get'
			);
			return $methods[$action];
		}

		private static function generate_url($action, $data=''){
			$urls=array(
					'booking.create'=>'api/bookings',
					'booking.estimate'=>'api/bookings/estimate',
					'booking.update'=>'api/bookings/'.@$data['booking']['id'],
					'booking.get'=>'api/bookings/'.@$data,
					'bookings.get'=>'api/bookings?'.@http_build_query($data),
					'booking.cancel'=>'api/bookings/'.@$data,


					'booking.frequencies.get'=>'api/bookings/'.$data.'/frequencies',
				'booking.services.get'=>'api/bookings/'.$data.'/services',
				
				

					'frequencies.get'=>'api/frequencies',
					'services.get'=>'api/services',
					'custom_fields'=>'api/custom_fields',
					'auth'=>'api/auth',
					'custom_fields.get'=>'api/custom_fields',
					'states.get'=>'api/states',
					'spots.get'=>'api/spots/'.date('Y-m-d', @strtotime($data))
			);

			return launch27::$apiBase.$urls[$action];die;
		}

		private static function generate_headers($user=null){
			$headers=array(
				'X-API-Key: '.launch27::$apiKey
			);

			//if(launch27::$apiVersion!=null){
				$headers[]='Accept: application/launch27'.(launch27::$apiVersion!=null ? '.v'.launch27::$apiVersion : '');
			//}

			if(!empty($user)){
				$headers[]='X-Authentication: '.$user['user']['email'].':'.$user['user']['single_access_token'];
			}
			//if($this->method=='post'){
			//	$headers[]='Content-Type: multipart/form-data';
			//}else{
				$headers[]='Content-Type: application/json';
			//}

			return $headers;
		}

		private static function generate_content($action, $data){

			switch ($action) {
				//case 'bookings.get':
				case 'booking.create':
				case 'booking.estimate':
				case 'booking.update':

					$content=$data;
					
					break;

				case 'auth':
					$content=array(
						'auth'=>array(
							'email'=>$data['email'],
							'password'=>$data['password']
						)
					);
					break;
				
				default:
					$content='';
					break;
			}

			return $content;

		}




		private static function exe_curl( $method, $url, $headers, $content ){
			$send_curlConn = curl_init($url);
			curl_setopt($send_curlConn, CURLOPT_HEADER, false);
			curl_setopt($send_curlConn, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($send_curlConn, CURLOPT_NOBODY, true);
			curl_setopt($send_curlConn, CURLINFO_HEADER_OUT, true);
			curl_setopt($send_curlConn, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($send_curlConn, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($send_curlConn, CURLOPT_TIMEOUT, 4);
			curl_setopt($send_curlConn, CURLOPT_SSL_VERIFYPEER, FALSE); // Validate SSL certificate
			curl_setopt($send_curlConn, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($send_curlConn, CURLOPT_USERAGENT, "Launch 27 API");

			switch ($method) {
				case 'get':
					curl_setopt($send_curlConn, CURLOPT_HTTPGET, true);
					break;

				case 'post':
					//curl_setopt($send_curlConn, CURLOPT_HTTPGET, false);
					curl_setopt($send_curlConn, CURLOPT_POST, true);
					curl_setopt($send_curlConn, CURLOPT_POSTFIELDS, json_encode($content));
					break;

				case 'delete':
					curl_setopt($send_curlConn, CURLOPT_CUSTOMREQUEST, 'DELETE');
					break;

				case 'put':
					curl_setopt($send_curlConn, CURLOPT_POST, true);
					curl_setopt($send_curlConn, CURLOPT_CUSTOMREQUEST, 'PUT');
					curl_setopt($send_curlConn, CURLOPT_POSTFIELDS, json_encode($content));
					break;
				
				default:
					break;
			}
			
			$result = curl_exec($send_curlConn);

			$status=curl_getinfo($send_curlConn, CURLINFO_HTTP_CODE);
			$header=curl_getinfo($send_curlConn, CURLINFO_HEADER_OUT);

			return array(
				'status'=>$status,
				'header'=>$header,
				'body'=>$result
			);
		}

		private static function interpretResponse($response){
			
			try {
				$resp=json_decode($response['body'], true);
			} catch (Exception $e) {
				$msg='Invalid response body from API: '.$response['body'].' (HTTP response code was '.$response['status'].')';
				throw new Error($msg, $response['status'], $response['body']);
			}

			if($response['status']<200 || $response['status']>=300){

				$message=isset($resp['message']) ? $resp['message'] : '';

				if(isset($resp['errors'])){
					$errors=$resp['errors'];
					if(is_array($errors)){
						$errorsmessage=' - ';
						foreach ($errors as $key => $value) {
							if(is_array($value)){
								$errorsmessage.=$key.': { ';
								foreach ($value as $key2 => $value2) {
									$errorsmessage.=$key2.': '.$value2.', ';
								}
								$errorsmessage.=' } ';
							}else{
								$errorsmessage.=$key.': '.$value.', ';
							}
						}
					}
				}else{
					$errorsmessage='';
				}

				$msg=$response['status'].' '.$message.$errorsmessage;

				throw new Error($msg, $response['status'], $resp, $response['body']);
				
			}

			return $resp;
		}
		


		

		

	}
?>