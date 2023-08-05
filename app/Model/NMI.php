<?php 
namespace App\Model; 
define("APPROVED", 1);
define("DECLINED", 2);
define("ERROR", 3); 
class NMI{
	// https://secure.networkmerchants.com/gw/merchants/resources/integration/integration_portal.php#transaction_variables
	public function setLogin($security_key) {
	    $this->login['security_key'] = $security_key;
	} 
	public function setOrder($setting) {
	    $this->order['orderid']          = $setting['refId'];
	    $this->order['orderdescription'] = $setting['description'];
	    $this->order['tax']              = $setting['tax_amount'];
        $this->order['ipaddress']        = $setting['ip_address'];
        $this->order['shipping']         = $setting['shipping'];
	}

	public function setBilling($setting){ 
		

	    $this->billing['firstname'] = $setting['first_name'];
	    $this->billing['lastname']  = $setting['last_name'];

		$this->billing['address1']  = $setting['address1'];
	    $this->billing['address2']  = $setting['address2'];
	    $this->billing['city']      = $setting['city'];
	    $this->billing['state']     = $setting['state'];
	    $this->billing['zip']       = $setting['zip'];
	    $this->billing['country']   = $setting['country'];
		$this->billing['email']     = $setting['email'];
		$this->billing['phone']     = $setting['phone'];
 
  	}
    
  	public function setShipping($firstname, $lastname,	$company,	$address1, $address2, $city, $state, $zip,	$country, $email) {
	    $this->shipping['firstname'] = $firstname;
	    $this->shipping['lastname']  = $lastname;
	    $this->shipping['company']   = $company;
	    $this->shipping['address1']  = $address1;
	    $this->shipping['address2']  = $address2;
	    $this->shipping['city']      = $city;
	    $this->shipping['state']     = $state;
	    $this->shipping['zip']       = $zip;
	    $this->shipping['country']   = $country;
	    $this->shipping['email']     = $email;
	}
	public function doSale($amount, $ccnumber, $ccexp, $cvv = "546") {
	    $query  = ""; 
	    $query .= "security_key=" . urlencode($this->login['security_key']) . "&"; 
	    // Sales Information
	    $query .= "ccnumber=" . urlencode($ccnumber) . "&";
	    $query .= "ccexp=" . urlencode($ccexp) . "&";
	    $query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
	    $query .= "cvv=" . urlencode($cvv) . "&";
	    // Order Information
	    $query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
	    $query .= "orderid=" . urlencode($this->order['orderid']) . "&";
	    $query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
	    $query .= "tax=" . urlencode(number_format($this->order['tax'], 2, ".","")) . "&";
	    $query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&"; 
	    // Billing Information
	    $query .= "first_name=" . urlencode($this->billing['firstname']) . "&";
	    $query .= "last_name=" . urlencode($this->billing['lastname']) . "&";  

		// Billing Information  
	    $query .= "address1=" . urlencode($this->billing['address1']) . "&";
	    $query .= "address2=" . urlencode($this->billing['address2']) . "&";
	    $query .= "city=" . urlencode($this->billing['city']) . "&";
	    $query .= "state=" . urlencode($this->billing['state']) . "&";
	    $query .= "zip=" . urlencode($this->billing['zip']) . "&";
	    $query .= "country=" . urlencode($this->billing['country']) . "&";
	    $query .= "phone=" . urlencode($this->billing['phone']) . "&"; 
	    $query .= "email=" . urlencode($this->billing['email']) . "&";
	    
	    $query .= 	"type=sale"; 
	    return $this->_doPost($query);
  	}
    
  	public function doAuth($amount, $ccnumber, $ccexp, $cvv=""){
	    $query  = "";
	    // Login Information
	    $query .= "security_key=" . urlencode($this->login['security_key']) . "&";
	    // Sales Information
	    $query .= "ccnumber=" . urlencode($ccnumber) . "&";
	    $query .= "ccexp=" . urlencode($ccexp) . "&";
	    $query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
	    $query .= "cvv=" . urlencode($cvv) . "&";
	    // Order Information
	    $query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
	    $query .= "orderid=" . urlencode($this->order['orderid']) . "&";
	    $query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
	    $query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
	    $query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
	    $query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
	    // Billing Information
	    $query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
	    $query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
	    $query .= "company=" . urlencode($this->billing['company']) . "&";
	    $query .= "address1=" . urlencode($this->billing['address1']) . "&";
	    $query .= "address2=" . urlencode($this->billing['address2']) . "&";
	    $query .= "city=" . urlencode($this->billing['city']) . "&";
	    $query .= "state=" . urlencode($this->billing['state']) . "&";
	    $query .= "zip=" . urlencode($this->billing['zip']) . "&";
	    $query .= "country=" . urlencode($this->billing['country']) . "&";
	    $query .= "phone=" . urlencode($this->billing['phone']) . "&";
	    $query .= "fax=" . urlencode($this->billing['fax']) . "&";
	    $query .= "email=" . urlencode($this->billing['email']) . "&";
	    $query .= "website=" . urlencode($this->billing['website']) . "&";

	    // Shipping Information
	    if(isset($this->shipping)){
		    $query .= "shipping_firstname=" . urlencode($this->shipping['firstname']) . "&";
		    $query .= "shipping_lastname=" . urlencode($this->shipping['lastname']) . "&";
		    $query .= "shipping_company=" . urlencode($this->shipping['company']) . "&";
		    $query .= "shipping_address1=" . urlencode($this->shipping['address1']) . "&";
		    $query .= "shipping_address2=" . urlencode($this->shipping['address2']) . "&";
		    $query .= "shipping_city=" . urlencode($this->shipping['city']) . "&";
		    $query .= "shipping_state=" . urlencode($this->shipping['state']) . "&";
		    $query .= "shipping_zip=" . urlencode($this->shipping['zip']) . "&";
		    $query .= "shipping_country=" . urlencode($this->shipping['country']) . "&";
		    $query .= "shipping_email=" . urlencode($this->shipping['email']) . "&";
		}

	    $query .= "type=auth";

	    return $this->_doPost($query);
	} 
  	public function doCredit($amount, $ccnumber, $ccexp){
	    $query  = "";
	    // Login Information
	    $query .= "security_key=" . urlencode($this->login['security_key']) . "&";
	    // Sales Information
	    $query .= "ccnumber=" . urlencode($ccnumber) . "&";
	    $query .= "ccexp=" . urlencode($ccexp) . "&";
	    $query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
	    // Order Information
	    $query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
	    $query .= "orderid=" . urlencode($this->order['orderid']) . "&";
	    $query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
	    $query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
	    $query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
	    $query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
	    // Billing Information
	    $query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
	    $query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
	    $query .= "company=" . urlencode($this->billing['company']) . "&";
	    $query .= "address1=" . urlencode($this->billing['address1']) . "&";
	    $query .= "address2=" . urlencode($this->billing['address2']) . "&";
	    $query .= "city=" . urlencode($this->billing['city']) . "&";
	    $query .= "state=" . urlencode($this->billing['state']) . "&";
	    $query .= "zip=" . urlencode($this->billing['zip']) . "&";
	    $query .= "country=" . urlencode($this->billing['country']) . "&";
	    $query .= "phone=" . urlencode($this->billing['phone']) . "&";
	    $query .= "fax=" . urlencode($this->billing['fax']) . "&";
	    $query .= "email=" . urlencode($this->billing['email']) . "&";
	    $query .= "website=" . urlencode($this->billing['website']) . "&";
	    $query .= "type=credit";
	    return $this->_doPost($query);
  	} 
  	public function doOffline($authorizationcode, $amount, $ccnumber, $ccexp) {
	    $query  = "";
	    // Login Information
	    $query .= "security_key=" . urlencode($this->login['security_key']) . "&";
	    // Sales Information
	    $query .= "ccnumber=" . urlencode($ccnumber) . "&";
	    $query .= "ccexp=" . urlencode($ccexp) . "&";
	    $query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
	    $query .= "authorizationcode=" . urlencode($authorizationcode) . "&";
	    // Order Information
	    $query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
	    $query .= "orderid=" . urlencode($this->order['orderid']) . "&";
	    $query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
	    $query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
	    $query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
	    $query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
	    // Billing Information
	    $query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
	    $query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
	    $query .= "company=" . urlencode($this->billing['company']) . "&";
	    $query .= "address1=" . urlencode($this->billing['address1']) . "&";
	    $query .= "address2=" . urlencode($this->billing['address2']) . "&";
	    $query .= "city=" . urlencode($this->billing['city']) . "&";
	    $query .= "state=" . urlencode($this->billing['state']) . "&";
	    $query .= "zip=" . urlencode($this->billing['zip']) . "&";
	    $query .= "country=" . urlencode($this->billing['country']) . "&";
	    $query .= "phone=" . urlencode($this->billing['phone']) . "&";
	    $query .= "fax=" . urlencode($this->billing['fax']) . "&";
	    $query .= "email=" . urlencode($this->billing['email']) . "&";
	    $query .= "website=" . urlencode($this->billing['website']) . "&";
	    // Shipping Information 
	    if(isset($this->shipping)){
		    $query .= "shipping_firstname=" . urlencode($this->shipping['firstname']) . "&";
		    $query .= "shipping_lastname=" . urlencode($this->shipping['lastname']) . "&";
		    $query .= "shipping_company=" . urlencode($this->shipping['company']) . "&";
		    $query .= "shipping_address1=" . urlencode($this->shipping['address1']) . "&";
		    $query .= "shipping_address2=" . urlencode($this->shipping['address2']) . "&";
		    $query .= "shipping_city=" . urlencode($this->shipping['city']) . "&";
		    $query .= "shipping_state=" . urlencode($this->shipping['state']) . "&";
		    $query .= "shipping_zip=" . urlencode($this->shipping['zip']) . "&";
		    $query .= "shipping_country=" . urlencode($this->shipping['country']) . "&";
		    $query .= "shipping_email=" . urlencode($this->shipping['email']) . "&";
	    } 
	    $query .= "type=offline";
	    return $this->_doPost($query);
  	} 
  	public function AddOrUpdateCustomerRecord($ccnumber, $ccexp, $customer_vault_id = ""){ 
  		$query  = "";
	    $query .= "security_key=" . urlencode($this->login['security_key']) . "&";  
	    $query .= "ccnumber=" . urlencode($ccnumber) . "&";
	    $query .= "ccexp=" . urlencode($ccexp) . "&";  
	    if($customer_vault_id == ""){
	    	$query .= "customer_vault=add_customer&"; 
	    }
	    else{
	    	$query .= "customer_vault=update_customer&";
	    	$query .= "customer_vault_id=" . urlencode($customer_vault_id) . "&"; 
	    }
	    $query .= "first_name=" . urlencode($this->billing['firstname']) . "&";
	    $query .= "last_name=" . urlencode($this->billing['lastname']) . "&"; 
	    return $this->_doPost($query); 
  	} 
  	public function PaywithProfile($amount,  $customer_vault_id, $orderid){
  		$query  = "";
  		$query .= "security_key=" . urlencode($this->login['security_key']) . "&";
  		$query .= "customer_vault_id=" . urlencode($customer_vault_id) . "&"; 

  		$query .= "amount=" . urlencode($amount) . "&";
  		$query .= "currency=usd&"; 
  		$query .= "orderid=" . urlencode($orderid);
  		return $this->_doPost($query);  
  	} 
	public function doCapture($transactionid, $amount = 0) { 
	    $query  = ""; 
	    $query .= "security_key=" . urlencode($this->login['security_key']) . "&"; 
	    $query .= "transactionid=" . urlencode($transactionid) . "&";
	    if ($amount>0) {
	        $query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
	    }
	    $query .= "type=capture";
	    return $this->_doPost($query);
	} 
	public function doVoid($transactionid) { 
	    $query  = ""; 
	    $query .= "security_key=" . urlencode($this->login['security_key']) . "&"; 
	    $query .= "transactionid=" . urlencode($transactionid) . "&";
	    $query .= "type=void";
	    return $this->_doPost($query);
	}  
  	public function doRefund($transactionid, $amount = 0) {
	    $query  = ""; 
	    $query .= "security_key=" . urlencode($this->login['security_key']) . "&"; 
	    $query .= "transactionid=" . urlencode($transactionid) . "&";
	    if ($amount>0) {
	        $query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
	    }
	    $query .= "type=refund";
	    return $this->_doPost($query);
	} 
	public function _doPost($query){
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, "https://secure.networkmerchants.com/api/transact.php");
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
	    curl_setopt($ch, CURLOPT_POST, 1); 
	    if (!($data = curl_exec($ch))) {
	        return ERROR;
	    } 
	    curl_close($ch);
	    unset($ch); 
	    $data = explode("&",$data);
	    for($i = 0;	$i < count($data);	$i++) {
	        $rdata = explode("=",$data[$i]);
	        $this->responses[$rdata[0]] = $rdata[1];
	    }
	    return $this->responses['response'];
	}  
}