<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

use DateTime;
use Session;
use Config;

class AuthorizePaymentModel extends Model
{
   
    public static function createrecurring($expiry,$amount,$invoice,$request){
    	$amount = 115 ;
       
        $intervalLength = 1;
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName('4N22gkWe');
        $merchantAuthentication->setTransactionKey('8Q2ucQ6645TLxdWT');
        
    
        
        $refId = 'ref' . time();
        // Subscription Type Info
        $subscription = new AnetAPI\ARBSubscriptionType();
        $subscription->setName(Config('APP_NAME').$request->username);
        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
        $interval->setLength($intervalLength);
        $interval->setUnit("months");
        $paymentSchedule = new AnetAPI\PaymentScheduleType();
        $paymentSchedule->setInterval($interval);
        $paymentSchedule->setStartDate(new DateTime(date('Y-m-d')));
        $paymentSchedule->setTotalOccurrences("9999");
        $paymentSchedule->setTrialOccurrences("1");
        $subscription->setPaymentSchedule($paymentSchedule);
        $subscription->setAmount($amount);
        $subscription->setTrialAmount("0.00");
        
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($request->card_number);
        $creditCard->setExpirationDate($expiry);
        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);
        $subscription->setPayment($payment);
        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber( $invoice);        
        $order->setDescription(Config('APP_NAME') ." subscription"); 
        $subscription->setOrder($order); 
        
        $billTo = new AnetAPI\NameAndAddressType();
        $billTo->setFirstName($request->firstname);
        $billTo->setLastName($request->lastname);
        $subscription->setBillTo($billTo);
        $request = new AnetAPI\ARBCreateSubscriptionRequest();
        $request->setmerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscription($subscription);
        $controller = new AnetController\ARBCreateSubscriptionController($request);
        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);

        return $response ;
        
    }

    public static function chargeCreditCard($expiry,$amount,$invoice,$request){

     

	    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
	    $merchantAuthentication->setName('4N22gkWe');
	    $merchantAuthentication->setTransactionKey('8Q2ucQ6645TLxdWT');
	    $refId = 'ref' . time();

	    $creditCard = new AnetAPI\CreditCardType();
	    $creditCard->setCardNumber($request->card_number);
	    $creditCard->setExpirationDate($expiry);
	    $creditCard->setCardCode($request->cvv);

	    // Add the payment data to a paymentType object
	    $paymentOne = new AnetAPI\PaymentType();
	    $paymentOne->setCreditCard($creditCard);

	    // Create order information
	    $order = new AnetAPI\OrderType();
	    $order->setInvoiceNumber($invoice);
	    $order->setDescription(Config('APP_NAME') ." registration");

	    // Set the customer's Bill To address
	    $customerAddress = new AnetAPI\CustomerAddressType();
	    $customerAddress->setFirstName($request->firstname);
	    $customerAddress->setLastName($request->lastname);
	    $customerAddress->setCompany($request->company_name);
	    $customerAddress->setAddress($request->address);
	    $customerAddress->setCity($request->city);
	    // $customerAddress->setState("TX");
	    // $customerAddress->setZip("44628");
	    // $customerAddress->setCountry("USA");

	    // Set the customer's identifying information
	    $customerData = new AnetAPI\CustomerDataType();
	    $customerData->setType("individual");
	    $customerData->setId($request->username);
	    $customerData->setEmail($request->email);

	    // Add values for transaction settings
	    $duplicateWindowSetting = new AnetAPI\SettingType();
	    $duplicateWindowSetting->setSettingName("duplicateWindow");
	    $duplicateWindowSetting->setSettingValue("60");

	    // Add some merchant defined fields. These fields won't be stored with the transaction,
	    // but will be echoed back in the response.
	    $merchantDefinedField1 = new AnetAPI\UserFieldType();
	    $merchantDefinedField1->setName("customerLoyaltyNum");
	    $merchantDefinedField1->setValue("1128836273");

	    // $merchantDefinedField2 = new AnetAPI\UserFieldType();
	    // $merchantDefinedField2->setName("favoriteColor");
	    // $merchantDefinedField2->setValue("blue");

	    // Create a TransactionRequestType object and add the previous objects to it
	    $transactionRequestType = new AnetAPI\TransactionRequestType();
	    $transactionRequestType->setTransactionType("authCaptureTransaction");
	    $transactionRequestType->setAmount($amount);
	    $transactionRequestType->setOrder($order);
	    $transactionRequestType->setPayment($paymentOne);
	    $transactionRequestType->setBillTo($customerAddress);
	    $transactionRequestType->setCustomer($customerData);
	    $transactionRequestType->addToTransactionSettings($duplicateWindowSetting);
	    $transactionRequestType->addToUserFields($merchantDefinedField1);
	    // $transactionRequestType->addToUserFields($merchantDefinedField2);

	    // Assemble the complete transaction request
	    $request = new AnetAPI\CreateTransactionRequest();
	    $request->setMerchantAuthentication($merchantAuthentication);
	    $request->setRefId($refId);
	    $request->setTransactionRequest($transactionRequestType);

	    // Create the controller and get the response
	    $controller = new AnetController\CreateTransactionController($request);
	    $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
	    

	    if ($response != null) {
	        // Check to see if the API request was successfully received and acted upon
	        if ($response->getMessages()->getResultCode() == "Ok") {
	            // Since the API request was successful, look for a transaction response
	            // and parse it to display the results of authorizing the card
	            $tresponse = $response->getTransactionResponse();
	        
	            if ($tresponse != null && $tresponse->getMessages() != null) {
	              return $tresponse->getTransId() ;
	            } else {               
	                if ($tresponse->getErrors() != null) {
	                    Session::flash('flash_notification',array('level'=>'warning','message'=> $tresponse->getErrors()[0]->getErrorText()));
	                }
	                return false; 
	            }
	           
	        } else {
	             
	            $tresponse = $response->getTransactionResponse();
	        
	            if ($tresponse != null && $tresponse->getErrors() != null) {                 
	                Session::flash('flash_notification',array('level'=>'warning','message'=>  $tresponse->getErrors()[0]->getErrorText()));
	            } else {                 
	                Session::flash('flash_notification',array('level'=>'warning','message'=>  $response->getMessages()->getMessage()[0]->getText()  ));
	            }
	          return false; 
	        }
	    } else {
	        
	        Session::flash('flash_notification',array('level'=>'warning','message'=>  'No response returned'  ));
	        return false; 
	    } 
    
	}
}
