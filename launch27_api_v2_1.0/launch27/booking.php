<?php
	
	namespace launch27;

	class booking{

		private $data=array();

		private function setLogin($customer){
			if(isset($this->data['login'])){
				return $this->data['login'];
			}else{
				throw new Error('This call requires authentication');
			}
		}

		/*
			*	get single booking details by booking ID
			*	returns response array
			*	https://bitbucket.org/awoo23/api-2.0/wiki/Get_booking
		*/
		public function getBooking(){
			$user=$this->setLogin();
			$response=request::initRequest('booking.get', $this->data['booking']['id'], $user);
			return $response['booking'];
		}

		/*
			*	get list of bookings for a customer
			*	returns response array
			*	https://bitbucket.org/awoo23/api-2.0/wiki/Get_booking
		*/
		public function getBookings(){
			$user=$this->setLogin();
			$data=utils::prepareBookingData('getBookings', $this->data);
			$response=request::initRequest('bookings.get', $data, $user);
			return $response['bookings'];
		}

		/*
			*	list frequencies available for selected booking by booking ID
			*	returns response array
			*	https://bitbucket.org/awoo23/api-2.0/wiki/Get_booking_frequencies
		*/
		public function getBookingFrequencies(){
			$user=$this->setLogin();
			$response=request::initRequest('booking.frequencies.get', $this->data['booking']['id'], $user);
			return $response['frequencies'];
		}

		/*
			*	list services available for selected booking by booking ID
			*	returns response array
			*	https://bitbucket.org/awoo23/api-2.0/wiki/Get_booking_services
		*/
		public function getBookingServices(){
			$user=$this->setLogin();
			$response=request::initRequest('booking.services.get', $this->data['booking']['id'], $user);
			return $response['services'];
		}

		/*
			*	Cancel existing booking
			*	returns response array
			*	Check: https://bitbucket.org/awoo23/api-2.0/wiki/Cancel_booking
		*/
		public function cancelBooking(){
			$user=$this->setLogin();
			$response=request::initRequest('booking.cancel', $this->data['booking']['id'], $user);
			return $response;

			//return request::initRequest('booking.cancel', array('id'=>$id), $user);
		}






		/*
			*	update existing booking details or re-schedule booking
			*	returns response array
			*	Check: https://bitbucket.org/awoo23/api-2.0/wiki/Update_booking
		*/
		public function update(){ //add the id inside
			if(isset($this->data['login'])){
				$user=$this->data['login'];
				$this->data['booking']['user_id']=$this->data['login']['user']['id'];
			}else{
				throw new Error('This call requires authentication');
			}
			$data=utils::prepareBookingData('update', $this->data);

			$response=request::initRequest('booking.update', $data, $user);
			return $response['booking'];
		}
	
		/*
			*	Create new booking
			*	returns response array
			*	$user is array response from launch27_login->login() or null for new user
			*	Check: https://bitbucket.org/awoo23/api-2.0/wiki/Create_booking
		*/
		public function create(){
			$data=utils::prepareBookingData('create', $this->data);

			if(isset($this->data['login'])){
				$user=$this->data['login'];
			}else{
				$user=null;
				$data['user']=$this->data['user'];
			}

			$response=request::initRequest('booking.create', $data, $user);
			return $response['booking'];
		}

		public function estimate(){
			$data=utils::prepareBookingData('estimate', $this->data);

			if(isset($this->data['login'])){
				$user=$this->data['login'];
			}else{
				$user=null;
				$data['user']=$this->data['user'];
			}

			$response=request::initRequest('booking.estimate', $data, $user);
			return $response['estimate'];
		}
		



	/* getBookings() FUNCTIONS*/
		public function setDateFrom($date){
			$this->data['booking']['from']=date('Y-m-d', strtotime(utils::sanitize($date)));
		}

		public function setDateTo($date){
			$this->data['booking']['to']=date('Y-m-d', strtotime(utils::sanitize($date)));
		}

		public function setLimit($limit){
			$this->data['booking']['limit']=utils::sanitize($limit);
		}

		public function setOffset($offset){
			$this->data['booking']['offset']=utils::sanitize($offset);
		}

	/* COMMON FUNCTIONS */
		public function setID($id){
			$this->data['booking']['id']=$id;
		}

		public function setDate($date){
			$this->data['booking']['service_date']=date('Y-m-d\TH:i', strtotime(utils::sanitize($date)));
		}

		// 1 flexible, 0 not flexible
		public function setFlexibility($flexibility){
			$this->data['booking']['flexibility']=intval(utils::sanitize($flexibility));
		}

		public function setAddress($address){
			$this->data['booking']['address']=utils::sanitize($address);
		}

		public function setCity($city){
			$this->data['booking']['city']=utils::sanitize($city);
		}

		public function setState($state){
			$this->data['booking']['state']=utils::sanitize($state);
		}

		public function setZip($zip){
			$this->data['booking']['zip']=utils::sanitize($zip);
		}

		public function setPhone($phone){
			$this->data['booking']['phone']=utils::sanitize($phone);
		}

		public function setPaymentMethod($payment_method){
			$this->data['booking']['payment_method']=utils::sanitize($payment_method);
		}

		public function setDiscountCode($discount_code){
			$this->data['booking']['discount_code']=utils::sanitize($discount_code);
		}

		public function setComments($customer_comments){
			$this->data['booking']['customer_comments']=utils::sanitize($customer_comments);
		}

		//true or false
		public function setSms($sms_notifications){
			$this->data['booking']['sms_notifications']=$sms_notifications;
		}

		/*public function setServiceID($service_id){
			$this->data['booking']['service_id']=intval(utils::sanitize($service_id));
		}*/

		public function addService($service_id){
			$service_id=intval(utils::sanitize($service_id));
			$this->data['booking']['services'][]=array(
					'id'=>$service_id,
					'extras'=>array(),
					'pricing_parameters'=>array()
				);
		}
		public function addExtra($service_id, $extra_id, $quantity=null){
			$id=utils::get_arrayKey(array('id'=>$service_id), $this->data['booking']['services']);
			array_push($this->data['booking']['services'][$id]['extras'], array('extra_id'=>intval($extra_id), 'quantity'=>intval($quantity)));
		}
		public function addPricingParameter($service_id, $pp_id, $quantity){
			$id=utils::get_arrayKey(array('id'=>$service_id), $this->data['booking']['services']);
			array_push($this->data['booking']['services'][$id]['pricing_parameters'], array('pricing_parameter_id'=>intval($pp_id), 'quantity'=>intval($quantity)));
		}




		public function setMaids($service_id, $maids){
			$id=utils::get_arrayKey(array('id'=>$service_id), $this->data['booking']['services']);
			array_push($this->data['booking']['services'][$id]['service_maids'], $maids);
		}

		public function setHours($service_id, $hours){
			$id=utils::get_arrayKey(array('id'=>$service_id), $this->data['booking']['services']);
			array_push($this->data['booking']['services'][$id]['service_maids'], $maids);
		}

		public function setFrequency($frequency_id){
			$this->data['booking']['frequency_id']=intval(utils::sanitize($frequency_id));
		}

		

		

		public function addCustomField($cf_id, $value){
			if(!isset($this->data['booking']['custom_fields'])){
				$this->data['booking']['custom_fields']=array();
			}
			$this->data['booking']['custom_fields'][$cf_id]=utils::sanitize($value);
		}

		public function setCustomer($email, $first_name, $last_name){
			$this->data['user']=array(
				'email'=>utils::sanitize($email),
				'first_name'=>utils::sanitize($first_name),
				'last_name'=>utils::sanitize($last_name)
			);
		}

		public function setCustomerLogin($user){
			$this->data['login']=$user;
		}

		public function setStripeToken($token){
			$this->data['stripe']=array('token'=>trim($token));
		}

	/* END CREATE BOOKING FUNCTIONS */

		public function getData(){
			return $this->data;
		}


	}

?>