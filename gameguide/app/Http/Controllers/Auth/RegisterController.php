<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Config;
use App\Models\Setting;
use App\Models\User;
use App\Models\EmailTemplate;
use Auth;
use App\Models\AuditLog;
use App\Models\UserCard;

use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\RegistersUsers;
use QrCode;
use Session;
use Illuminate\Auth\Events\Registered;
use Response;
use DB;
use DateTime;
// use App\Models\Plan;
use App\Http\Requests\CreateUserRequest;
use Carbon\Carbon;
use App\Models\PaymentMethod;
use App\Models\Plan;
use App\Models\UserProfile;
use App\Models\Subscription;
use App\Models\AccessCodes;
use Stripe;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    use RegistersUsers;	
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/account';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
		
	public function showRegistrationForm(Request $req)
	{
		$accessCode='';
		if($req->has('a'))
		{
			$accessCode=$req->a;

		}

		$paymentMethod = PaymentMethod::where('status',1)->get();
		$plan = Plan::get();
		return view('auth.register',compact('paymentMethod','plan','accessCode'));			
	}
	
	public function checkemail(Request $req)
	{
		$email = $req->email;
		$emailcheck = DB::table('users')->where('email',$email)->count();
		if($emailcheck > 0)
		{
		 $result =array('success' => false,'msg'=>'The email has already been taken.');	
		}else{
			$result =array('success' => true,'msg'=>'');	
		}

		return Response::json($result, 200);
	}
		
	protected function register(CreateUserRequest $request)
    {
		$acc_code_id = 0;
		$auto_code_id = 0;


    	$response = [];
        $response['success'] = false;
        $response['message'] = 'Something went wrong';
        $request_data = $request->all();
	    $token = getToken();
	    $planStart = Carbon::now()->format('Y-m-d');
	
		$a_code = $request->input('access_code');
	
		if(!empty($a_code)){

	
		  $code = DB::select('select * from add_access_codes where number = "'.$a_code.'" AND user_id > 0 ');
			
			if($code){
			
			  $response['msg'] = "Code Already Used";
			  return $response;
				
			}
			$code = DB::select('select * from add_access_codes where number = "'.$a_code.'" AND user_id = 0 ');
			
			
			if(!$code){
			
				$code = DB::select('select * from client_acess_log where access_code = "'.$a_code.'" AND redeemed_user_profile is null order by client_acess_log_id Desc limit 1');
			
		    $acccessCode = new AccessCodes();
			$acccessCode->number = $code[0]->access_code;
			$acccessCode->is_manual = 0;
			$acccessCode->save();
				
		$code = DB::select('select * from add_access_codes where number = "'.$a_code.'" AND user_id = 0 ');
			}


		
			if($code){
				
				$acc_code_id = $code[0]->serial_id;
				

			}
			else
			{
				try {
					$decode = gzinflate(base64_decode(strtr($a_code, '-_', '+/')));
				} 
				catch (\Exception $ex) {

					return $response;
				}
				
				if(!empty($decode)){

					$decode = explode(':', $decode);
		
					$co = $decode[0];
					$key = $decode[1];
		
					$keyExist = DB::table('settings')->where('secret_key', $key)->get();
				
					if($keyExist)
               		{
						$co_key = DB::select('select * from add_access_codes where number = "'.$co.'" AND user_id > 0 AND end_date = 0');
			
						if(count($co_key)>0)
						{

						return $response;
							
						}
						else{
							$auto_code = DB::table('add_access_codes')->insert(['number'=>$co, 'is_manual' => 0]);
							$auto_code = DB::table('add_access_codes')->select('serial_id')->where('number', $co)->get();
							$auto_code_id = $auto_code[0]->serial_id;
						}
                	}
                    else{
						return $response;	
               		}
				}	
				else{
					return $response;
				}		
		    }	
		}
		
		
					
			if(!empty($request_data['payment_method']) && !empty($request_data['plan'])){
				$payment_method = $request_data['payment_method'];
				$stripe = Stripe::make(env('STRIPE_SECRET'));
				$plan = Plan::where('id',$request_data['plan'])->first();
				/*If plan exist*/
				if($plan){
					/*Stripe Payment Method*/
					if($user && $payment_method == 2){
						try{
							$customer = $stripe->customers()->create([
							  'email' => $user->email,
							]);
	
							if(!empty($customer)){
								$customer_id = $customer['id'];
	
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
	
										$userProfile = [
											'subscription_id' => $subscription_id,
											'user_id'=>$user_id
										];
	
										$saveProfile = UserProfile::create($userProfile);
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
						//Save paypal data
						$new_Subscriber  = new Subscription;
						//SET DATA TO SAVE SUBSCRIPTION 
						$subscription_price = $plan->amount ;
						$subscribed_user = $request->user_id;
						
						$new_Subscriber->subscription_id   	= $request->subscription_id;
						$new_Subscriber->plan_id  			= $request_data['plan'];
						$new_Subscriber->user_id  			= $user_id;
						$new_Subscriber->payer_name   		= $request->PayerName;
						$new_Subscriber->payer_mail   		= $request->PayerMail;
						$new_Subscriber->payer_id   		= $request->payer_id;
						$new_Subscriber->plan_price   		= $subscription_price;
						$new_Subscriber->status   			= $request->status;
						
						$strtotime_subscription_start = strtotime($request->CreateTime);
						$subscription_start = date('Y-m-d H:i:s', $strtotime_subscription_start);
						$subscription_end = date('Y-m-d H:i:s', strtotime('1 month',$strtotime_subscription_start));
						
						
						$new_Subscriber->subscription_start   = $subscription_start;
						$new_Subscriber->subscription_end   = $subscription_end;
						$new_Subscriber->save();
	
						if($new_Subscriber){
							$subscription_id = $new_Subscriber->id;
	
							$userProfile = [
								'subscription_id' => $subscription_id,
								'user_id'=>$user_id
							];
	
							$saveProfile = UserProfile::create($userProfile);
						}
	
						$users = User::find($user_id);
						//save db
						$users->plan_id = $request_data['plan'];
						$users->save();
					}
				}	
			}
		
	    $user = User::create([
		   // 'name' => $data['name'],
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'email' => $request->email,
			'role_id' => 2,
			'status' => 1,
			'password' => Hash::make($request->password),
			'plan_start_on' => $planStart,
			'verify_token' => $token,
		]);

		$user_id = $user->id;

		    if ($auto_code_id){
				$using_at = Carbon::now()->format('Y-m-d H:i:s');
				$used_on = strtotime($using_at);
				DB::table('add_access_codes')->where('serial_id', $auto_code_id)->update(['user_id'=>$user_id, 'used_date' => $used_on]);
				$user->plan_id=2;
				$user->save();
				Session::flash('Success', 'Access Code Applied.');
			}
					
			if($acc_code_id){
				$using_at = Carbon::now()->format('Y-m-d H:i:s');
				$used_on = strtotime($using_at);
				DB::table('add_access_codes')->where('serial_id', $acc_code_id)->update(['user_id'=>$user_id, 'used_date' => $used_on]);
				$user->plan_id=2;
				$user->save();
				Session::flash('Success', 'Access Code Applied.');
			}

		
		  //SEND EMAIL TO REGISTER USER.
			$uname = $request->first_name .' '.$request->last_name;
			//$token = getToken();
			$logo = url('/frontend/images/logo.png');
			$link= url('verify/account/'.$token);
			$to = $request->email;
			//EMAIL REGISTER EMAIL TEMPLATE 
			$result = EmailTemplate::where('id',1)->get();
			$subject = $result[0]->subject;
      		$message_body = $result[0]->content;
      		
      		$list = Array
              ( 
                 '[NAME]' => $uname,
				 //'[USERNAME]' => $request->email,
				// '[PASSWORD]' => $request->password,
                 '[LINK]' => $link,
                 '[LOGO]' => $logo,
              );

      		$find = array_keys($list);
      		$replace = array_values($list);
      	    $message = str_ireplace($find, $replace, $message_body);
			
			//$mail = send_email($to, $subject, $message, $from, $fromname);
			
			//$mail = send_email($to, $subject, $message);
			
			//return redirect('/confirmation');

			//return redirect('/login');
			
			if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
				$response['success'] = true;
				$response['message'] = 'Successfully done payment';
				$response['redirect_url'] =  url('/user-profile');
				return $response;
			}	
	}
	
	//VERIFY ACCOUNT  
	public function verifyAccount($token)
    {
		
		$result = User::where('verify_token', '=' ,$token)->get();
		$notwork =false; 
		if(count($result)>0){
	    $userUpdate = User::where('email',$result[0]->email);
		$data['verify_token'] =NULL;					
		$data['status'] =1;					
		$userUpdate->update($data);	
		//$url_post = url('password/reset_new_user_password');
		//$notwork =true;  
		//Session::flash('success', 'Your Account has been verified.');
	  //  return redirect('login');
		return redirect()->route('login')
					->with('success','Your Account has been verified.');
			//return view('auth.passwords.reset',compact('token','notwork','url_post'));	
		}else{
			 return redirect('login')->with('error','Your link is not correct.');	;
		}
   	
    }
	
	//VERIFY ACCOUNT  
	public function Registeration_confirmation()
    {	
		return view('auth.verify');
    }
	
	
	
	/* public function register(Request $request)
	{
		
		$this->validator($request->all())->validate();

		event(new Registered($user = $this->create($request->all())));

		$this->guard()->login($user);

		return $this->registered($request, $user)
							?: redirect($this->redirectPath());
	 }  */
	 

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
	protected function validator(array $data)
    {
		
		$return = [];
		$return = [
            'first_name'     => [
                'required',
            ],
			'email' => [
				'required','unique:users'
			],
			'last_name'     => [
                'required',
            ],
			'login_password'    => [
				'required', 'regex:/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!$#%@]).*$/', 'min:6'
			],
			'login_password_confirmation'    => [
                'required','same:login_password',
            ],
			'email*' => [
				'unique:users'
			],
            'mobile_number'   => [
				'required','numeric','regex:/[0-9]{9}/',
             ],
			'terms_and_condtions'   => [
               'required',
            ],
		]; 
		return Validator::make($data,$return );
    } 

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
		
		$dat =  User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'mobile_number' => $data['mobile_number'],
            'email' => $data['email'],
            'password' => Hash::make($data['login_password']),
			'role_id' => 2,
			'status' => 1,
	
		]);
		
		if($dat){
			$user_data = User::where('id',$dat->id);
			//die;
			Session::flash('message', "Welcome to Bread and Beauty - Bigfoot. Please logged into your account and setup your profile.");
			return $dat;
		}

		return redirect()->route('account'); 
    }

    /*fetch plan related info*/
    // public function fetchPlanLockingPeriod($plan_id){
    //     $selectedPlanInfo = Plan::where('id',$plan_id)->first();
    //     $lockingPeriod = 0;
    //     if(!is_null($selectedPlanInfo) && ($selectedPlanInfo->count())>0){
    //         $lockingPeriod = $selectedPlanInfo->locking_period;
    //     }
    //     return $lockingPeriod;

    // }
}