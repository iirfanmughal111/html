<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Plan;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\UserCard;
use App\Models\UserProfile;
use App\Models\Subscription;
use Response;
use Stripe;
use Config;
use Auth;

class PlansController extends Controller
{
	public function __construct()
    {
    	if(Config::get('paypal.settings.mode') == 'live'){
			$this->client_id = Config::get('paypal.live_client_id');
			$this->secret  = Config::get('paypal.live_secret');
			$this->api_url = Config::get('paypal.live_api_url');
		} else {
			$this->client_id = Config::get('paypal.sandbox_client_id');
			$this->secret = Config::get('paypal.sandbox_secret');
			$this->api_url = Config::get('paypal.sandbax_api_url');
		}
    }

    
    public function index()
    {
    	$paymentMethod = PaymentMethod::where('status',1)->get();
		$plan = Plan::get();
       	return view('frontend.creaters.plans.index',compact('paymentMethod','plan'));
    }

    public function updatePlan(UpdatePlanRequest $request){
    	$data = [];
        $data['success'] = false;
        $data['message'] = 'Something went wrong';

    	if($request->ajax()){
    		$request_data = $request->all();
    		$user_id = auth::user()->id; 
    		$user = User::where('id',$user_id)->first();
    		if($user){
    			if(!empty($request_data['payment_method']) && !empty($request_data['plan'])){
		        	$payment_method = $request_data['payment_method'];
		        	$stripe = Stripe::make(env('STRIPE_SECRET'));
		        	$plan = Plan::where('id',$request_data['plan'])->first();
		        	/*If plan exist*/
		        	if($plan){
		        		//check
		        		$oldSubscription = UserProfile::where('user_id',$user_id)->first();
		        		/*Stripe Payment Method*/
			        	if($user && $payment_method == 2){
			        		try{
				        		$customer_id = $user->stripe_customer_id;
				        		if(empty($customer_id)){
				        			$customer = $stripe->customers()->create([
						                'email' => $user->email,
						            ]);

						            if(!empty($customer)){
						            	$customer_id = $customer['id'];
						            }
				        		}

				        		if(!empty($customer_id))
				        		{
					        		//check token exist
				                	$cardTokenId = $request->get('stripeToken');
				                	$card = $stripe->cards()->create($customer_id, $cardTokenId);

				                	$cardId = $card['id'];

					                $userCard = [
					                    'stripe_card_id' => $card['id'],
					                    'last4' => $card['last4'],
					                    'user_id'=>$user_id
					                ];

					                //Save to db
					                $saveCard = UserCard::create($userCard);

					                //check if already subscription created
					                if($oldSubscription && !empty($oldSubscription->subscription_id)){
					                	$destroySubscription = $this->cancelSubscription($oldSubscription->subscription_id);
					                }

					                /*Create New Subscription*/
				                    $createSubscription = $stripe->subscriptions()->create($customer_id, [
									    'plan' => $plan->stripe_plan_id,
									]);

									$createSubscriptionId = $createSubscription['id'];

				                    $users = User::find($user_id);
				                    //save db
				                    $users->stripe_customer_id = $customer_id;
				                    $users->plan_id = $request_data['plan'];
				                    $users->save();

				                    if(!empty($createSubscriptionId)){
					                	/*Save data to subscription table*/
					                	$subscription = [
					                		'user_id'=>$user_id,
					                		'payment_method_id' => $payment_method,
					                		'status' => 1,
					                		'subscription_id' => $createSubscriptionId,
					                		'plan_id' => $request_data['plan'],
					                		'plan_price' => $plan->amount
					                	];

					                	$saveSubscription = Subscription::create($subscription);

					                	if($saveSubscription){
					                		$subscription_id = $saveSubscription->id;

					                		if($oldSubscription && !empty($oldSubscription->id)){
					                			
							                	$saveProfile = UserProfile::find($oldSubscription->id);
							                	$saveProfile->subscription_id = $subscription_id;
							                	$saveProfile->save();
					                		}else{

						                		$userProfile = [
							                		'subscription_id' => $subscription_id,
							                		'user_id'=>$user_id
							                	];

						                		$saveProfile = UserProfile::create($userProfile);
						                	}
					                		$data['success'] = true;
					                		$data['message'] = 'Your Plan is successfully activated';
					                	}
					                }
				                }
				        	}catch (Exception $e) {
				                //Session::put('error',$e->getMessage());
				                $response['msg'] = $e->getMessage();
				                return $response;
				            } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {

				                $response['msg'] = $e->getMessage();
				                return $response;
				             } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
				                 $response['msg'] = $e->getMessage();
				                 return $response;
				             }
			        	}elseif ($user && $payment_method == 1){
			        		//check if already subscription created
			                if($oldSubscription && !empty($oldSubscription->subscription_id)){
			                	$destroySubscription = $this->cancelSubscription($oldSubscription->subscription_id);
			                }
			                $new_Subscriber  = new Subscription;
							//SET DATA TO SAVE SUBSCRIPTION 
							
							
							$new_Subscriber->subscription_id   	= $request->subscription_id;
							$new_Subscriber->plan_id  			= $request->plan_id;
							$new_Subscriber->user_id  			= $user_id;
							$new_Subscriber->payer_name   		= $request->PayerName;
							$new_Subscriber->payer_mail   		= $request->PayerMail;
							$new_Subscriber->payer_id   		= $request->payer_id;
							$new_Subscriber->plan_price   		= $plan->amount;
							$new_Subscriber->status   			= $request->status;
							
							$strtotime_subscription_start = strtotime($request->CreateTime);
							$subscription_start = date('Y-m-d H:i:s', $strtotime_subscription_start);
							$subscription_end = date('Y-m-d H:i:s', strtotime('1 month',$strtotime_subscription_start));
							
							
							$new_Subscriber->subscription_start   = $subscription_start;
							$new_Subscriber->subscription_end   = $subscription_end;
							$new_Subscriber->save();

							if($new_Subscriber){
		                		$subscription_id = $new_Subscriber->id;

		                		$userProfile = UserProfile::where('user_id',$user_id)->first();
		                		if($userProfile){
		                			$uProfile =array();
									$uProfile['subscription_id']=$subscription_id;
									$userProfile->update($uProfile);

		                		}else{
		                			$userProfile = [
				                		'subscription_id' => $subscription_id,
				                		'user_id'=>$user_id
				                	];

			                		$saveProfile = UserProfile::create($userProfile);
		                		}

		                		
		                	}

		                	$users = User::find($user_id);
		                    //save db
		                    $users->plan_id = $request_data['plan'];
		                    $users->save();

		                    $data['success'] = true;
		                    $data['message'] = 'Your Plan is successfully activated';
			        	}
			        }
    		}
    	}
    	return Response::json($data, 200);
    }

    }

    public function cancelSubscription($subscription_id){
    	$subscription = Subscription::where('id',$subscription_id)->first();
    	if($subscription){
    		//check paymethod 
    		if($subscription->payment_method_id == 1){
    			$cancelStatus = $this->cancelPaypalSubscription($subscription);
    		}else{
    			$cancelStatus = $this->cancelStripeSubscription($subscription);
    		}
    	}
		$updateSubscriber = Subscription::where('id',$subscription_id)->first();
		$Subscriber =array();
		$Subscriber['status']='CANCELLED';
		$updateSubscriber->update($Subscriber);
    	return $cancelStatus;
    }

    public function cancelStripeSubscription($subscription){
    	$response['success'] = false;
    	$stripe = Stripe::make(env('STRIPE_SECRET'));
    	if(!empty($subscription)){
    		//get customer id
    		$user = User::where('id',$subscription->user_id)->first();
    		if($user && !empty($user->stripe_customer_id) && !empty($subscription->subscription_id)){
    			$retrivesSbscription = $stripe->subscriptions()->find($user->stripe_customer_id, $subscription->subscription_id);
    			/*Check if subscribtion exist*/
    			if(!empty($retrivesSbscription['id'])){
    				$cancelSubscription = $stripe->subscriptions()->cancel($user->stripe_customer_id, $subscription->subscription_id);
    			}
    			$response['success'] = true;
    		}

    	}
    	return $response;
    }

    public function cancelPaypalSubscription($subscription){
    	$response = [];
    	$response['success'] = false;
    	$response['msg'] = 'Something went wrong';
    	$getToken = $this->getPaypalAccessToken();
		//IF TOKEN NOT RETURN THEN SHOW ERROR 
		if(!$getToken['success']){
			//$result = array('success'=>false,'msg'=>$getToken['msg']);
			$response['msg'] = $getToken['msg'];	
			return $response;
		}
		$accessToken=$getToken['access_token']; ;
		$headers = array(
				'Content-Type: application/json',
				 'Authorization: Bearer ' . $accessToken
		);
		
		$subscription_id= $subscription->subscription_id;
		$urln = $this->api_url .'/v1/billing/subscriptions/'.$subscription_id;
		$curl = curl_init($urln);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		$response1 = curl_exec($curl);
		//$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$SubsResponse = json_decode($response1);
		//echo 'hfghgf';
		//pr($SubsResponse);
		if(curl_errno($curl)){
					//If an error occured, throw an Exception.
					 $result['success']=false;
					 $result['msg']='Request Error:' . curl_error($curl);
					 $result['access_token']='';
					 return $result;
		}
		
		//IF SUBSCRIPTION IS ACTIVE ON PAYPAL THEN CANCEL SUBSCRIPTION
		if($SubsResponse->status=='ACTIVE'){			

			$urln1 = $this->api_url .'/v1/billing/subscriptions/'.$subscription_id.'/cancel';
			$curl1 = curl_init($urln1);
			curl_setopt($curl1, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl1, CURLOPT_POST, true);
			curl_setopt($curl1, CURLOPT_HTTPHEADER, $headers);
			$response2 = curl_exec($curl1);
			$http_code = curl_getinfo($curl1, CURLINFO_HTTP_CODE);
			if(curl_errno($curl1)){
						//If an error occured, throw an Exception.
						 $result['success']=false;
						 $result['msg']='Request Error:' . curl_error($curl1);
						 $result['access_token']='';
						 return Response::json($result,200);
			}
			if($http_code==204){
				$updateSubscriber = Subscription::where('id',$subscription_id)->where('status','ACTIVE');
				$Subscriber =array();
				$Subscriber['status']='CANCELLED';
				$updateSubscriber->update($Subscriber);

				$result['success']=false;
				return $result;
				//$updateSubscriber->delete();
				// $result['success']=true;
				// $result['msg']='Subscription Cancel SuccessFully';
				// return Response::json($result,200);			
			}	
			
		}else{
					$result['success']=false;
					$result['msg']='Something went wrong.';
					return $result;		
		} 
    }

    /* ==================================
* GET ACCESS TOKEN FROM  PAYPAL 
*  RETURN  access_token
===========================================*/

	public function  getPaypalAccessToken(){
	
		$url = $this->api_url . '/v1/oauth2/token'; 
		$username = $this->client_id;   //Client ID
		$password = $this->secret;     //secret ID
		$headers = array(
				'Content-Type: application/x-www-form-urlencoded',
				'Authorization: Basic '. base64_encode("$username:$password")
			);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS , rawurldecode(http_build_query(array(
			'grant_type' => 'client_credentials'
		  ))));
		
		 $response = curl_exec($ch);
		 $result =array();
		 if(curl_errno($ch)){
			//If an error occured, throw an Exception.
			 $result['success']=false;
			 $result['msg']='Request Error:' . curl_error($ch);
			 $result['access_token']='';
		}else{
			$json = json_decode($response);
			$accessToken = $json->access_token;
			 $result['success']=true;
			 $result['msg']='';
			 $result['access_token']=$accessToken;
		}
		//pr($response)
		return $result;
	}


}
