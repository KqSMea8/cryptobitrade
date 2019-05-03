<?php
namespace App\Http\Controllers\Admin;

use App\AppSettings;
use App\Balance;
use App\Commission;
use App\Country;
use App\Emails;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Controller;
use App\LeadershipBonus;
use App\MenuSettings;
use App\Packages;
use App\Transactions;
use App\PaymentType;
use App\PointTable;
use App\ProfileInfo;
use App\PurchaseHistory;
use App\Settings;
use App\Sponsortree;
use App\TempDetails;
use App\Tree_Table;
use App\RsHistory;
use App\ProfileModel;
use App\User;
use App\Voucher;
use Auth;
use CountryState;
use Crypt;
use DB;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use Mail;
use Redirect;
use Session;
use Validator;
use App\Activity;
use App\Paypalcustomers;

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Account;
use Stripe\Token;

use Srmklive\PayPal\Services\ExpressCheckout;

class RegisterController extends AdminController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller - Admin
    |--------------------------------------------------------------------------
    |
    | This controller handles registering users for the application and
    | redirecting them to preview screen.
    |
     */

    /**
     * View registration page.
     *
     * @var placement_id (Encrypted Placement Id) : if placement id is set, registration will be done under the-
     * specified placement id rather than authenticated user.
     * returns view page
     *
     */

      protected static  $provider ;

    public function __construct()
    {
        
        self::$provider = new ExpressCheckout;    
    }



    public function index($placement_id = "")
    {

        $title     = trans('registration.register');
        $sub_title = trans('registration.register');
        $base      = trans('registration.register');
        $method    = trans('registration.register');

        /**
         * Checking if the current user permitted for accessing the registration page.
         *
         * @var id : Checks against the specified userid-
         * if status set to no will redirect to dashboard with warning message
         * if status set to yes, will proceed
         */

        $status = MenuSettings::find(1);
        if ($status->status == "no") {
            Session::flash('flash_notification', array('level' => 'danger', 'message' => 'Permission Denied'));
            return Redirect::to('admin\dashboard');
        } else {
            if ($placement_id) {
                /**
                 * if placement id set ,will decrypt and check in tree_table to find it has vacant positions
                 */

                $placement_id  = urldecode(Crypt::decrypt($placement_id));
                $place_details = Tree_Table::find($placement_id);
                /**
                 * if no vacant positions available under specified placement id,
                 * redirect back without placement id param
                 */

                if ($place_details->type != 'vaccant') {
                    return redirect()->back();
                }
                $placement_user = User::where('id', $place_details->placement_id)->value('username');
                $leg            = $place_details->leg;
            } else {
                $leg            = null;
                $placement_user = Auth::user()->username;
            }

            $user_details = array();
            $user_details = User::where('username', Auth::user()->username)->get();
            $title        = $sub_title        = trans('register.register_new_member');
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
            /**
             * Get all packages from database
             * @var [collection]
             */
            $package = Packages::all();
            /**
             * Get joining fee from settings table
             * @var int
             */
            $joiningfee = Settings::value('joinfee');

            $default_plan_amount = Packages::where('id',1)->value('amount');

            $joiningfee = $joiningfee + $default_plan_amount;
            /**
             * Get Voucher code from Voucher table
             * @var [type]
             */
            $voucher_code = Voucher::value('voucher_code');
            /**
             * Get all active payment methods from database [payment_type]
             * @var [collection]
             */
            $payment_type = PaymentType::where('status', 'yes')->get();
            /**
             * Generate a random string for the transation password for user
             * to keep in database for future use,
             * @var string
             */
            $transaction_pass = SELF::RandomString();
            /**
             * returns registration view with provided variables
             */
            return view('app.admin.register.index', compact('title', 'sub_title', 'base', 'method', 'requests', 'package', 'countries', 'states', 'user_details', 'placement_user', 'leg', 'joiningfee', 'voucher_code', 'payment_type', 'count', 'transaction_pass'));

        }
    }

    /**
     * Once the registration form filled, and on submit, this function will handle the process/
     * @param  Request $request , We do not use laravel specific request file to -
     * validate but in controller itself for easy access
     * @return [array]           [this array will contain all values passed from the registration form]
     */
    public function register(Request $request)
    {

        //dd($request->all());
        /**
         * [$data array to hold specified incming request values]
         * @var array
         */
        $data                     = array();
        $data['reg_by']           = $request->payment;
        $data['firstname']        = $request->firstname;
        $data['lastname']         = $request->lastname;
        $data['phone']            = $request->phone;
        $data['email']            = $request->email;
        $data['reg_type']         = $request->reg_type;
        $data['cpf']              = $request->cpf;
        $data['passport']         = $request->passport;
        $data['username']         = $request->username;
        $data['gender']           = $request->gender;
        $data['country']          = $request->country;
        $data['state']            = $request->state;
        $data['city']             = $request->city;
        $data['address']          = $request->address;
        $data['zip']              = $request->zip;
        $data['location']         = $request->location;
        $data['password']         = $request->password;
        $data['transaction_pass'] = $request->transaction_pass;
        $data['sponsor']          = $request->sponsor;
        $data['package']          = $request->package;
        $data['leg']              = $request->leg;
        /**
         * if placement user passed from form
         * (Which will be set as hidden input if placement_id specified and if it has vacant positions ),
         * it will set as placement_user, else the placement will be under sponsor
         *
         */
        if ($request->placement_user) {
            $data['placement_user'] = $request->placement_user;
        } else {
            $data['placement_user'] = $request->sponsor;
        }
        /**
         * Validation custom messages
         * @var [array]
         */
        $messages = [
            'unique' => 'The :attribute already existis in the system',
            'exists' => 'The :attribute not found in the system',

        ];
        /**
         * Validating the incoming data we stored the $data variable
         * @var [boolean]
         */
        $validator = Validator::make($data, [
            'sponsor'          => 'required|exists:users,username|max:255',
            'placement_user'   => 'sometimes|exists:users,username|max:255',
            'email'            => 'required|unique:users,email|email|max:255',
            'username'         => 'required|unique:users,username|alpha_num|max:255',
            'password'         => 'required|min:6',
            'transaction_pass' => 'required|alpha_num|min:6',
            'package'          => 'required|exists:packages,id',
            'leg'              => 'required',
            'country'          => 'required|country',
        ]);
        /**
         * On fail, redirect back with error messages
         */
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {


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

            if ($request->payment == "paypal") {

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
                    $data['return_url'] = url('admin/paypal/success');
                    $data['cancel_url'] = url('admin/register');

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
            /**
             * If request has payment mode as voucher, will check against the vouchers for validation
             * @var [type]
             */
            if ($request->payment == 'voucher') {
                $vocher = $request['payable_vouchers'];
                $count  = count($vocher);
                $total  = 0;
                for ($i = 1; $i < $count - 1; $i++) {
                    Voucher::where('voucher_code', '=', $vocher[$i])->update(['balance_amount' => 0]);
                    $amount = Voucher::where('voucher_code', '=', $vocher[$i])->value('total_amount');
                    $total += $amount;
                }
                $last         = last($vocher);
                $last_amount  = Voucher::where('voucher_code', '=', $last)->value('total_amount');
                $total_amount = $total + $last_amount;
                $joiningfee   = Settings::value('joinfee');
                Voucher::where('voucher_code', '=', $last)->update(['balance_amount' => $total_amount - $joiningfee]);
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


            /**
             * redirects to preview page after successful registration
             */
            return redirect("admin/register/preview/" . Crypt::encrypt($userresult->id));
        }
    }

    public function preview($idencrypt)
    {
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
            return view('app.admin.register.preview', compact('title', 'sub_title', 'method', 'base', 'userresult', 'sponsorUserName', 'country', 'state', 'sub_title'));
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
                return redirect('admin/register')->withErrors(['Opps something went wrong'])->withInput();                
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
            return redirect("admin/register/preview/" . Crypt::encrypt($userresult->id));

        }else{

                 return redirect('admin/register')->withErrors(['Opps something went wrong'])->withInput(); 
        }
    }

    public function cancelreg()
    {
        dd("cancelreg-RegisterController");
    }

    public function RandomString()
    {
        $characters       = "23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ";
        $charactersLength = strlen($characters);
        $randstring       = '';
        for ($i = 0; $i < 11; $i++) {
            $randstring .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randstring;
    }



}
