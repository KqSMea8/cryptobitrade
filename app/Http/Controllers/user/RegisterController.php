<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Country;
use App\Voucher;
use App\Settings;
use App\Tree_Table;
use App\Sponsortree;
use App\PointTable;
use App\Ranksetting;
use App\Commission;
use App\Packages;
use App\PointHistory;
use App\Sales;
use App\Emails;
use App\AppSettings;
use App\Balance;
use App\UserDebit;
use App\RsHistory;
use App\LeadershipBonus;
use App\DirectSposnor;
use App\Codes;
use App\TempDetails;
use App\PaymentType;
use App\ProfileInfo;
use App\PurchaseHistory;

use App\Paypalcustomers;
use Response;

use Mail;
use DB;
use Crypt;
use Session;
use Validator;
use Auth;
use App\MenuSettings;
use Redirect;
use CountryState;
use App\Activity;
use App\Http\Controllers\user\UserAdminController;

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Account;
use Stripe\Token;


use Srmklive\PayPal\Services\ExpressCheckout;

class RegisterController extends UserAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

      protected static  $provider ;

    public function __construct()
    {
        
        self::$provider = new ExpressCheckout;    
    }

    public function index($placement_id = null)
    {

        $title     = trans('all.register');
        $sub_title = trans('all.register');
        $base   = trans('all.register');
        $method = trans('all.register');
            /**
             * Get Countries from mmdb
             * @var [collection]
             */
            $countries = CountryState::getCountries();
            /**
             * [Get States from mmdb]
             * @var [collection]
             */
            $states = CountryState::getStates('US');

        $status=MenuSettings::find(1);
        if($status->status=="no"){
            Session::flash('flash_notification', array('level' => 'danger', 'message' => 'Permission Denied'));
            return Redirect::to('user\dashboard');
        }else{
            if($placement_id){
                $placement_id = Crypt::decrypt($placement_id);
                $place_details = Tree_Table::find($placement_id);
                if($place_details->type != 'vaccant'){
                    return redirect()->back();
                }
                $placement_user = User::where('id',$place_details->placement_id)->pluck('username');
                $leg = $place_details->leg;
            }else{
                $leg = NULL;
                $placement_user = NULL;
            }
        $user_details = array();
        $user_details = User::where('username',Auth::user()->username)->get();
        $title = "Register new member";
        $rules = ['sponsor' => 'required|min:5','username' => 'unique:users,username|required|min:5','email' => 'unique:users,email|required','password' => 'required|same:password_confirmation'];
        $country = Country::all();
        $package = Packages::all();
        $joiningfee = Settings::pluck('joinfee');
        $voucher_code=Voucher::pluck('voucher_code');
        $payment_type=PaymentType::where('status','yes')->get();
        $transaction_pass=self::RandomString();

        $joiningfee = Settings::value('joinfee');

        $default_plan_amount = Packages::where('id',1)->value('amount');

        $joiningfee = $joiningfee + $default_plan_amount;
        return view('app.user.register.index',compact('title','sub_title','base','method','requests','rules','country','countries','states','user_details','package','placement_user','leg','joiningfee','voucher_code','payment_type','transaction_pass','joiningfee'));
        }
    }
    public function register(Request $request){
        $data = array();
        $data['reg_by']=$request->payment;
        $data['firstname'] = $request->firstname;
        $data['lastname'] = $request->lastname;
        $data['phone'] = $request->phone;
        $data['email'] = $request->email;
        $data['reg_type'] = $request->reg_type;
        $data['cpf'] = $request->cpf;
        $data['passport'] = $request->passport;
        $data['username'] = $request->username;
        $data['gender'] = $request->gender;
        $data['country'] = $request->country;
        $data['state'] = $request->state;
        $data['city'] = $request->city;
        $data['address'] = $request->address;
        $data['zip'] = $request->zip;
        $data['location'] = $request->location;
        $data['password'] = $request->password;
        $data['transaction_pass'] = $request->transaction_pass;
        $data['sponsor'] = $request->sponsor;
        $data['package'] = $request->package;
        $data['leg'] = $request->leg;
        $data['placement_user'] = $request->placement_user;
        if($data['placement_user'] != null){
            $data['placement_user'] = $data['placement_user'];
        }else{
            $data['placement_user'] = $data['sponsor'];
        }
        $messages = [
            'unique'    => 'The :attribute already existis in the system',
            'exists'    => 'The :attribute not found in the system',
        ];
        $validator = Validator::make($data, [
            'sponsor' => 'required|exists:users,username|max:255',
            'placement_user' => 'sometimes|exists:users,username|max:255',
            'email' => 'required|unique:users,email|email|max:255',
            'username' => 'required|unique:users,username|alpha_num|max:255',
            'password' => 'required|alpha_num|min:6',
            'transaction_pass' => 'required|alpha_num|min:6',
            'leg' => 'required'
            ]);
        if($validator->fails()){
             return redirect()->back()->withErrors($validator)->withInput();
        }else{

               /**
             * Checking if sponsor_id exist in users table
             * @var [boolean]
             */
            $sponsor_id = User::checkUserAvailable($data['sponsor']);
            /**
             * Checking if placement_user exist in users table
             * @var [type]
             */
            $placement_id =  $sponsor_id ;// User::checkUserAvailable($data['placement_user']);
            if (!$sponsor_id) {
                /**
                 * If sponsor_id validates as false, redirect back without registering , with errors
                 */
                return redirect()->back()->withErrors(['The username not exist'])->withInput();
            }
            if (!$placement_id) {
                /**
                 * If placement_id validates as false, redirect back without registering , with errors
                 */
                return redirect()->back()->withErrors(['The username not exist'])->withInput();
            }


            /**
             * If request contains payment mode paypal, application will handle the payment process
             * @var [string]
             */



                $joiningfee = Settings::value('joinfee');

                $package_amount = Packages::where('id',$request->package)->value('amount');

                $fee = $joiningfee + $package_amount;

            if($request->payment == "paypal"){ 

                 // Paypal payment ..

                     $paypal = Paypalcustomers::create([
                          'data'=>json_encode($data)  
                         ]); 
                    Session::put('paypal_id',$paypal->id);

                    $data = [];
                    $data['items'] = [
                        [
                            'name' => Config('APP_NAME'),
                            'price' => $fee,
                            'qty' => 1
                        ]
                    ];

                    $data['invoice_id'] = time();
                    $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
                    $data['return_url'] = url('user/paypal/success');
                    $data['cancel_url'] = url('user/register');

                    $total = 0;
                    foreach($data['items'] as $item) {
                        $total += $item['price']*$item['qty'];
                    }

                    $data['total'] = $total; 
                    $response = self::$provider->setExpressCheckout($data); 
                    return redirect($response['paypal_link']);

                }if ($request->payment == "Stripe") {

                Stripe::setApiKey(config('services.stripe.secret'));
                    $customer=Customer::create([
                        'email' =>request('stripeEmail'),
                        'source' =>request('stripeToken')
                        ]);
                     
                    $id = $customer->id;
                    $Charge=Charge::create([
                        'customer' =>$id,
                        'amount' => $fee * 100,
                        'currency' => 'cad'
                        ]);
            }
                if($request->payment == 'voucher'){
                    $vocher=$request['payable_vouchers'];
                    $count=count($vocher);
                    $total=0;
                    for ($i=1; $i < $count-1; $i++) {
                        Voucher::where('voucher_code','=',$vocher[$i])->update(['balance_amount' => 0]);
                        $amount = Voucher::where('voucher_code','=',$vocher[$i])->pluck('total_amount');
                        $total+=$amount;
                    }
                    $last=last($vocher);
                    $last_amount=Voucher::where('voucher_code','=',$last)->pluck('total_amount');
                    $total_amount=$total+$last_amount;
                    $joiningfee = Settings::pluck('joinfee');
                    Voucher::where('voucher_code','=',$last)->update(['balance_amount' => $total_amount-$joiningfee]);
                }

                /**
                * Using beginTransaction for rollbacks on registration errors
                */
                /**
                Complete user regsitration process 

                **/
                $userresult = User::add($data,$sponsor_id,$placement_id);
                if(!$userresult){
                    return redirect()->back()->withErrors(['Opps something went wrong'])->withInput();
                }

                $userPackage = Packages::find($data['package']);
          

                $sponsorname = $userresult->sponsor ? $userresult->sponsor : Auth::user()->username;
                $placement_username = User::find($placement_id)->username;
                $legname = $data['leg'] == "L" ? "Left" : "right";            
            
                Activity::add("Added user $userresult->username","Added $userresult->username sponsor as $sponsorname and placement user as $placement_username in $legname Leg");

                Activity::add("Joined as $userresult->username","Joined in system as $userresult->username sponsor as $sponsorname and placement user as $placement_username in $legname Leg",$userresult->id);

                Activity::add("Package purchased","Purchased package - $userPackage->package ",$userresult->id);


            /**
             * Committing the changes to databases, so far no errors
             */
            
         



            /**
             * Getting the email record to be sent to user from database, this table will hold
             * welcome email and all other emails that can be updated from admin side.
             * @var [collection]
             */
            $email = Emails::find(1);
            /**
             * Returning app_settings to get company name
             * @var [collection]
             */
            $app_settings = AppSettings::find(1);
            /**
             * Sends mail to the user
             */
            Mail::send('emails.register',
                ['email'         => $email,
                    'company_name'   => $app_settings->company_name,
                    'firstname'      => $data['firstname'],
                    'name'           => $data['lastname'],
                    'login_username' => $data['username'],
                    'password'       => $data['password'],
                ], function ($m) use ($data, $email) {
                    $m->to($data['email'], $data['firstname'])->subject('Successfully registered')->from($email->from_email, $email->from_name);
                });
            return  redirect("user/register/preview/".Crypt::encrypt($userresult->id));
            }
        }
        public function RandomString()
        {
                    $characters = "23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ";
                    $charactersLength = strlen($characters);
                    $randstring = '';
                    for ($i = 0; $i < 11; $i++) {
                        $randstring .= $characters[rand(0, $charactersLength - 1)];
                    }
                    return $randstring;
        }
        public function data(Request $request){
                    $key = $request->key;
                    if(isset($key)){
                        $voucher = $request->voucher ;
                        $vocher=Voucher::where('voucher_code',$key)->get();
                        return Response::json($vocher);
                    }
        }
        public function preview($idencrypt){
            $title     = trans('register.registration');
            $sub_title = trans('register.preview');
            $method    = trans('register.preview');
            $base      = trans('register.preview');
    // echo Crypt::decrypt($idencrypt) ;
    // die();
            $userresult      = User::with(['profile_info', 'profile_info.package_detail', 'sponsor_tree', 'tree_table', 'purchase_history.package'])->find(Crypt::decrypt($idencrypt));


            $userCountry = $userresult->profile_info->country;
            if ($userCountry) {
                $countries = CountryState::getCountries();
                $country   = array_get($countries, $userCountry);
            } else {
                $country = "A downline";
            }
            $userState = $userresult->profile_info->state;
            if ($userState) {
                $states = CountryState::getStates($userCountry);
                $state  = array_get($states, $userState);
            } else {
                $state = "unknown";
            }

            $sponsorId       = $userresult->sponsor_tree->sponsor;
            $sponsorUserName = \App\User::find($sponsorId)->username;


            if ($userresult) {
                // dd($user);
                return view('app.user.register.preview', compact('title', 'sub_title', 'method', 'base', 'userresult', 'sponsorUserName', 'country', 'state', 'sub_title'));
            } else {
                return redirect()->back();
            }
        }
              

    public function paypalsuccess(Request $request)
    {
         $response = self::$provider->getExpressCheckoutDetails($request->token);

        $item = Paypalcustomers::find(Session::get('paypal_id'));
        $item->paypal_payment_responce = json_encode($response);
        $item->save();

        if($response['ACK'] == 'Success'){

            $data = json_decode($item->data,true); 

            $sponsor_id = User::checkUserAvailable($data['sponsor']); 
            $placement_id =  $sponsor_id ;
            if (!$sponsor_id) {
                return redirect()->back()->withErrors(['The sponsor not exist'])->withInput();
            }
            if (!$placement_id) {
                return redirect()->back()->withErrors(['The sponsor not exist'])->withInput();
            }

            $userresult = User::add($data,$sponsor_id,$placement_id);
            if(!$userresult){
                return redirect('user/register')->withErrors(['Opps something went wrong'])->withInput();                
            }
            
            $userPackage = Packages::find($data['package']);          

            $sponsorname = $data['sponsor'];
            $placement_username = User::find($placement_id)->username;
            $legname = $data['leg'] == "L" ? "Left" : "right";            
            
            Activity::add("Added user $userresult->username","Added $userresult->username sponsor as $sponsorname and placement user as $placement_username in $legname Leg");

            Activity::add("Joined as $userresult->username","Joined in system as $userresult->username sponsor as $sponsorname and placement user as $placement_username in $legname Leg",$userresult->id);

            Activity::add("Package purchased","Purchased package - $userPackage->package ",$userresult->id);

 
            $email = Emails::find(1);
            /**
             * Returning app_settings to get company name
             * @var [collection]
             */
            $app_settings = AppSettings::find(1);
            /**
             * Sends mail to the user
             */
            Mail::send('emails.register',
                ['email'         => $email,
                    'company_name'   => $app_settings->company_name,
                    'firstname'      => $data['firstname'],
                    'name'           => $data['lastname'],
                    'login_username' => $data['username'],
                    'password'       => $data['password'],
                ], function ($m) use ($data, $email) {
                    $m->to($data['email'], $data['firstname'])->subject('Successfully registered')->from($email->from_email, $email->from_name);
                });


            /**
             * redirects to preview page after successful registration
             */
            return redirect("user/register/preview/" . Crypt::encrypt($userresult->id));

        }else{

                 return redirect('user/register')->withErrors(['Opps something went wrong'])->withInput(); 
        }
    }
}
