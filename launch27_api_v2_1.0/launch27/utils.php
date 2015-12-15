<?php
	
	namespace launch27;

	abstract class utils{


		public static function sanitize($field){
			$field=stripcslashes(trim($field));

			return $field;
		}

		public static function prepareBookingData($call, $datas){
			$prepared=array();
			switch ($call) {
				
				case 'create':
				case 'estimate':
					$data=$datas['booking'];
					$prepared=array(
						'service_date'=>(isset($data['service_date']) ? $data['service_date'] : null), //"2015-02-05T13:00"
						'flexibility'=>(isset($data['flexibility']) ? $data['flexibility'] : 0),
						'address'=>(isset($data['address']) ? $data['address'] : null),
						'city'=>(isset($data['city']) ? $data['city'] : null),
						'state'=>(isset($data['state']) ? $data['state'] : null),
						'zip'=>(isset($data['zip']) ? $data['zip'] : null),
						'phone'=>(isset($data['phone']) ? $data['phone'] : null),
						'payment_method'=>(isset($data['payment_method']) ? $data['payment_method'] : null),
						'discount_code'=>(isset($data['discount_code']) ? $data['discount_code'] : null),
						'customer_comments'=>(isset($data['customer_comments']) ? $data['customer_comments'] : null),
						'sms_notifications'=>(isset($data['sms_notifications']) ? $data['sms_notifications'] : false),
						'services'=>(isset($data['services']) ? $data['services'] : null),
						//'service_maids'=>(isset($data['service_maids']) ? $data['service_maids'] : null),
						//'service_hours'=>(isset($data['service_hours']) ? $data['service_hours'] : null),
						'frequency_id'=>(isset($data['frequency_id']) ? $data['frequency_id'] : null),
						//'extras'=>(isset($data['extras']) ? $data['extras'] : null),
						//'pricing_parameters'=>(isset($data['pricing_parameters']) ? $data['pricing_parameters'] : null),
						'custom_fields'=>(isset($data['custom_fields']) ? $data['custom_fields'] : null)
					);

					$return=array(
							'booking'=>$prepared
						);
					(isset($datas['stripe']) ? $return['stripe']=$datas['stripe'] : null );

					break;


				case 'update':
					$data=$datas['booking'];
					(isset($data['id']) ? $prepared['id']=$data['id'] : null );
					(isset($data['user_id']) ? $prepared['user_id']=$data['user_id'] : null );
					(isset($data['service_date']) ? $prepared['service_date']=$data['service_date'] : null );
					(isset($data['flexibility']) ? $prepared['flexibility']=$data['flexibility'] : null );
					(isset($data['address']) ? $prepared['address']=$data['address'] : null );
					(isset($data['city']) ? $prepared['city']=$data['city'] : null );
					(isset($data['state']) ? $prepared['state']=$data['state'] : null );
					(isset($data['zip']) ? $prepared['zip']=$data['zip'] : null );
					(isset($data['phone']) ? $prepared['phone']=$data['phone'] : null );
					(isset($data['payment_method']) ? $prepared['payment_method']=$data['payment_method'] : null );
					(isset($data['discount_code']) ? $prepared['discount_code']=$data['discount_code'] : null );
					(isset($data['customer_comments']) ? $prepared['customer_comments']=$data['customer_comments'] : null );
					(isset($data['sms_notifications']) ? $prepared['sms_notifications']=$data['sms_notifications'] : null );
					(isset($data['services']) ? $prepared['services']=$data['services'] : null );
					//(isset($data['service_maids']) ? $prepared['service_maids']=$data['service_maids'] : null );
					//(isset($data['service_hours']) ? $prepared['service_hours']=$data['service_hours'] : null );
					(isset($data['frequency_id']) ? $prepared['frequency_id']=$data['frequency_id'] : null );
					//(isset($data['extras']) ? $prepared['extras']=$data['extras'] : null );
					//(isset($data['pricing_parameters']) ? $prepared['pricing_parameters']=$data['pricing_parameters'] : null );
					(isset($data['custom_fields']) ? $prepared['custom_fields']=$data['custom_fields'] : null );

					$return=array(
							'booking'=>$prepared
						);
					(isset($datas['stripe']) ? $return['stripe']=$datas['stripe'] : null );

					break;


				case 'getBookings':
					$data=$datas['booking'];
					(isset($data['from']) ? $prepared['from']=$data['from'] : null );
					(isset($data['to']) ? $prepared['to']=$data['to'] : null );
					(isset($data['limit']) ? $prepared['limit']=$data['limit'] : null );
					(isset($data['offset']) ? $prepared['offset']=$data['offset'] : null );

					$return=$data;

					break;
				
				default:
					
					break;
			}
			return $return;
		}

		public static function get_arrayKey($tosearch, $array){
			$k=array_keys($tosearch);
			$v=array_values($tosearch);

			//PHP >5.5
			//$key = array_search($v[0], array_column($array, $k[0]));

			$ids=array_map(create_function('$ar', 'return $ar["'.$k[0].'"];'), $array);


			$key=array_search($v[0], $ids);

			return $key;
		}


	}
	
?>