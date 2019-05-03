<?php
namespace App\Http\Controllers\admin;
use App\Balance;
use App\Commission;
use App\Country;
use App\DirectSposnor;
use App\Helpers\Thumbnail;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\DeleteRequest;
use App\Http\Requests\Admin\UserEditRequest;
use App\Http\Requests\Admin\UserRequest;

use App\LeadershipBonus;
use App\Mail;
use App\Packages;
use App\PointTable;
use App\ProfileInfo;
use App\PurchaseHistory;
use App\RsHistory;
use App\Sponsortree;
use App\Tree_Table;
use App\User;
use App\Voucher;
use Auth;
use Datatables;
use DB;
use Illuminate\Http\Request;
use Input;
use Redirect;
use Response;
use Session;
use Validator;
use App\Activity;
use App\Payout;
use Crypt;
use CountryState;


use App\Ranksetting;

use Storage;

class UserController extends AdminController
{

    /*
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        // Show the page
        $title     = trans('users.title');
        $sub_title = trans('users.your_profile');
        $base      = trans('users.base');
        $method    = trans('users.view_all');
        // $unread_count  = Mail::unreadMailCount(Auth::id());
        // $unread_mail  = Mail::unreadMail(Auth::id());
        // $userss = User::getUserDetails(Auth::id());

        // $user = $userss[0];

        // $userss = User::getUserDetails(Auth::id());


        //     $user = $userss[0];

    
        // return view('app.admin.users.index',  compact('title','user','sub_title','base','method','profile_infos'));
        return view('app.admin.users.index',  compact('title','sub_title','base','method'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate()
    {
        return view('app.admin.users.create_edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(UserRequest $request)
    {

        $user                    = new User();
        $user->name              = $request->name;
        $user->username          = $request->username;
        $user->email             = $request->email;
        $user->password          = bcrypt($request->password);
        $user->confirmation_code = str_random(32);
        $user->confirmed         = $request->confirmed;
        $user->save();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $user
     * @return Response
     */
    public function getEdit()
    {

        $title     = trans('users.change_password');
        $sub_title = trans('users.change_password');
        $base      = trans('users.base');
        $method    = trans('users.change_password');

        $userss   = User::getUserDetails(Auth::id());
        $user     = $userss[0];
        $users    = User::where('id', '>', 1)->get();
        $packages = Packages::all();

        return view('app.admin.users.create_edit', compact('title', 'base', 'method', 'User', 'sub_title', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $user
     * @return Response
     */
    public function postEdit(UserEditRequest $request)
    {

        $user                 = User::find(User::userNameToId($request->username));
        $password             = $request->password;
        $passwordConfirmation = $request->password_confirmation;

        if (!empty($password) && $user->id > 1) {
            if ($password === $passwordConfirmation) {
                $user->password = bcrypt($password);
            }
        }
        $user->save();

        Session::flash('flash_notification', array('message' => "Password has been changed ", 'level' => 'success'));

        return redirect()->back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param $user
     * @return Response
     */

    public function getDelete($id)
    {
        $user = User::find($id);
        // Show the page
        return view('app.admin.users.delete', compact('user'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $user
     * @return Response
     */
    public function postDelete(DeleteRequest $request, $id)
    {
        $user = User::find($id);
        $user->delete();
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data(Request $request)
    {

        $user_count = User::where('id', '>', 1)->count();
        // $users = User::select(array('users.id','users.name','users.username','packages.package','users.email', 'users.created_at'))
        // ->join('packages','packages.id','=','users.package')->where('admin','<>',1)
        $users = ProfileInfo::select(array('users.id', 'users.name', 'users.username', 'packages.package', 'users.email', 'users.created_at'))
            ->join('users', 'users.id', '=', 'profile_infos.user_id')
            ->join('packages', 'packages.id', '=', 'profile_infos.package')->where('admin', '<>', 1);
           
            // ->get();

        return Datatables::of($users)                
            // ->remove_column('id')
            ->edit_column('created_at', '{{ date("D M Y",strtotime($created_at)) }}')
            // ->add_column('actions', '@if ($id!="1")<a href="{{{ URL::to(\'admin/users/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm iframe" ><span class="fa fa-lock"></span>  {{ trans("admin/modal.changepassword") }}</a>
                    // <a href="{{{ URL::to(\'admin/users/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger iframe"><span class="glyphicon glyphicon-trash"></span> {{ trans("admin/modal.terminate") }}</a>
                // @endif')
            ->setTotalRecords($user_count)
             ->escapeColumns([])
             ->make();

    }

    public function viewprofile($user='mlmadmin')
    {

        
        $title     = trans('users.member_profile');
        $sub_title = trans('users.view_all');
        $base = trans('users.view_all');
        $method = trans('users.view_all');



        if ($user) {
            $user_id = User::where('username', $user)->value('id');
            if($user_id != NULL){
                Session::put('prof_username', $user);                
            }
        } else {
            $user_id = Auth::id();
            Session::put('prof_username', Auth::user()->username);
        }


        $user_id = $user_id;



        $selecteduser = User::with('profile_info')->find($user_id);
        // dd($selecteduser);
      
        $profile_infos = ProfileInfo::with('images')->where('user_id',$user_id)->first();
        $profile_photo = $profile_infos->profile;
        
        //if (!Storage::disk('images')->exists($profile_photo)){
        //    $profile_photo = 'avatar-big.png';
        //}

        if(!$profile_photo){
            $profile_photo = 'avatar-big.png';
        }

        $cover_photo = $profile_infos->cover;

        if (!Storage::disk('images')->exists($cover_photo)){
            $cover_photo = 'cover.jpg';
        }

        if(!$cover_photo){
            $cover_photo = 'cover.jpg';
        }
       

        $referals = User::select('users.*')->join('tree_table', 'tree_table.user_id', '=', 'users.id')->where('tree_table.sponsor', $user_id)->get();

      
        $total_referals = count($referals);
        $base           = trans('users.profile');
        $method         = trans('users.profile_view');

        $referrals      = Sponsortree::getMyReferals($user_id);

        $balance         = Balance::getTotalBalance($user_id);         
        $vouchers        = Voucher::getAllVouchers();
        $voucher_count   = count($vouchers);
        $mails           = Mail::getMyMail($user_id);
        $mail_count      = count($mails);
        $referrals_count = $total_referals;
        $sponsor_id      = Sponsortree::getSponsorID($user_id);
        $sponsor      = User::with('profile_info')->where('id',$sponsor_id)->first();
        // dd($sponsor);


        $left_bv         = PointTable::where('user_id', '=', $user_id)->value('left_carry');
        $right_bv = PointTable::where('user_id', '=', $user_id)->value('right_carry');
        $total_payout = Payout::where('user_id', '=', $user_id)->sum('amount');

        $user_package    = Packages::where('id', $selecteduser->profile_info->package)->value('package');
        $user_rank = Ranksetting::getUserRank($user_id);
        $user_rank_name = Ranksetting::idToRankname($user_rank);
    

        $countries = Country::all();


        $userCountry = $selecteduser->profile_info->country;
        if ($userCountry) {
        $countries = CountryState::getCountries();
        $country   = array_get($countries, $userCountry);
        } else {
        $country = "Unknown";
        }


        $userState = $selecteduser->profile_info->state;
        if ($userState) {
        $states = CountryState::getStates($userCountry);
        $state  = array_get($states, $userState);
        } else {
        $state = "unknown";
        }


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

 

        return view('app.admin.users.profile', compact('title','sub_title', 'base', 'method', 'mail_count', 'voucher_count', 'balance', 'referrals', 'countries', 'selecteduser', 'state_list', 'sponsor', 'referals', 'unread_count', 'unread_mail',  'left_bv', 'right_bv', 'user_package','profile_infos','countries','country','states','state','sponsorId','sponsorUserName','referrals_count','user_rank_name','profile_photo','cover_photo','total_payout'));
    }
    public function profile(Request $request)
    {

        $validator = Validator::make($request->all(), ["user" => 'required|exists:users,username']);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['The username not exist']);
        } else {
            Session::put('prof_username', $request->user);
            return redirect()->back();
        }

    }
    public function suggestlist(Request $request)
    {
        if ($request->input != '/' && $request->input != '.') {
            $users['results'] = User::where('username', 'like', "%" . trim($request->input) . "%")->select('id', 'username as value', 'email as info')->get();
        } else {
            $users['results'] = User::select('id', 'username as value', 'email as info')->get();
        }

        echo json_encode($users);

    }
    public function saveprofile(Request $request)
    {

        //dd($request->all());

        // die(Session::get('prof_username'));

        if (!Session::has('prof_username')) {
            return redirect()->back();
        }

        $id = User::where('username', Session::get('prof_username'))->value('id');

        $user = User::find($id);

        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->hypperwalletid = $request->hypperwalletid;




        $user->save();
        // dd($user);
// Role::with('users')->whereName($name)->first();
        $related_profile_info = ProfileInfo::where('user_id', $id)->first();
// dd($related_profile_info);
        $related_profile_info->location    = $request->location;
        $related_profile_info->occuption   = $request->occuption;
        $related_profile_info->gender      = $request->gender;
        $related_profile_info->dateofbirth = date('d/m/Y', strtotime($request->day . "-" . $request->month . "-" . $request->year));
        $related_profile_info->address1    = $request->address1;
        $related_profile_info->address2    = $request->address2;
        $related_profile_info->gender      = $request->gender;
        $related_profile_info->city        = $request->city;
        $related_profile_info->country     = $request->country;
        $related_profile_info->state       = $request->state;
        $related_profile_info->zip         = $request->zip;
        $related_profile_info->mobile      = $request->mobile;

        $related_profile_info->skype       = $request->skype;
        $related_profile_info->facebook    = $request->fb;
        $related_profile_info->twitter     = $request->twitter;

        $related_profile_info->account_number      = $request->account_number;
        $related_profile_info->account_holder_name = $request->account_holder_name;
        $related_profile_info->swift               = $request->swift;
        $related_profile_info->sort_code           = $request->sort_code;
        $related_profile_info->bank_code           = $request->bank_code;
        $related_profile_info->paypal              = $request->paypal;
        $related_profile_info->about               = $request->about_me;

        // if ($request->hasFile('profile_pic')) {
        //     $destinationPath = base_path() . "\public\appfiles\images\profileimages";
        //     $extension       = Input::file('profile_pic')->getClientOriginalExtension();
        //     $fileName        = rand(11111, 99999) . '.' . $extension;
        //     Input::file('profile_pic')->move($destinationPath, $fileName);
        //     $new_user->image = $fileName;

        //     $path2 = public_path() . '/appfiles/images/profileimages/thumbs/';
        //     Thumbnail::generate_profile_thumbnail($destinationPath . '/' . $fileName, $path2 . $fileName);
        //     $path3 = public_path() . '/appfiles/images/profileimages/small_thumbs/';
        //     Thumbnail::generate_profile_small_thumbnail($destinationPath . '/' . $fileName, $path3 . $fileName);

        // }

        if ($related_profile_info->save()) {
            Session::flash('flash_notification', array('message' => "Profile updated succesfully", 'level' => 'success'));
            return redirect()->back();
        } else {
            return redirect()->back()->withErrors(['Whoops, looks like something went wrong']);
        }

    }
    public function allusers()
    {
        $users       = User::select('users.username')->get();
        $loop_end    = count($users);
        $user_string = '';
        for ($i = 0; $i < $loop_end; $i++) {
            $user_string = $user_string . $users[$i]->username;
            if ($i < ($loop_end - 1)) {
                $user_string = $user_string . ",";
            }

        }
        print_r($user_string);
    }

    public function validateuser(Request $request)
    {
        return User::takeUserId($request->sponsor);
    }

    public function activate()
    {

        $title     = trans('users.activate_user');
        $sub_title = trans('users.activate_user');
        $base      = trans('users.activate_user');
        $method    = trans('users.activate_user');




     

        $users = User::join('profile_infos', 'profile_infos.user_id', '=', 'users.id')
            ->join('tree_table', 'tree_table.user_id', '=', 'users.id')
            ->join('sponsortree', 'sponsortree.user_id', '=', 'users.id')
            ->join('users as sponsors', 'sponsors.id', '=', 'sponsortree.sponsor')
            ->select('sponsors.username as sponsors', 'users.username', 'users.id', 'users.email', 'users.created_at', 'users.name', 'users.lastname', 'profile_infos.package')
            ->where('tree_table.type', '=', 'yes')
            ->paginate(10);
            // dd($users);

        return view('app.admin.users.activate', compact('title','sub_title','base','method', 'users'));
    }

    public function confirme_active(Request $request, $id)
    {

        $user_detail = User::find($request->user);

        if ($user_detail) {

            $sponsor_id   = Sponsortree::where('user_id', $user_detail->id)->pluck('sponsor');
            $sponsor_comm = DirectSposnor::where('package_id', $user_detail->package)->get();
            $package      = Packages::find($user_detail->package);

            Tree_Table::where('user_id', $user_detail->id)->update(['type' => 'yes']);
            Sponsortree::where('user_id', $user_detail->id)->update(['type' => 'yes']);
            User::where('id', $user_detail->id)->increment('revenue_share', $package->rs);

            RsHistory::create([
                'user_id'   => $user_detail->id,
                'from_id'   => $user_detail->id,
                'rs_credit' => $package->rs,
            ]);

            PurchaseHistory::create([
                'user_id'          => $user_detail->id,
                'purchase_user_id' => $user_detail->id,
                'package_id'       => $user_detail->package,
                'count'            => $package->top_count,
                'pv'               => $package->pv,
                'total_amount'     => $package->amount,
            ]);

            /* sposnor RS */
            RsHistory::create([
                'user_id'   => $sponsor_id,
                'from_id'   => $user_detail->id,
                'rs_credit' => $sponsor_comm[0]->rs,
            ]);
            PurchaseHistory::create([
                'user_id'          => $sponsor_id,
                'purchase_user_id' => $user_detail->id,
                'package_id'       => $user_detail->package,
                'count'            => $package->ref_top_count,
                'total_amount'     => $package->amount,
            ]);
            User::where('id', $sponsor_id)->increment('revenue_share', $sponsor_comm[0]->rs);

            /* sposnor commission */
            $sponsor_comm_amt = $package->pv * $sponsor_comm[0]->pv / 100;

            SPOSNSORCOMMISSION:

            if (User::where('id', $sponsor_id)->pluck('revenue_share') >= $sponsor_comm_amt) {
                Commission::create([
                    'user_id'        => $sponsor_id,
                    'from_id'        => $user_detail->id,
                    'total_amount'   => $sponsor_comm_amt,
                    'payable_amount' => $sponsor_comm_amt,
                    'payment_type'   => 'direct_sponsor_bonus',
                ]);
                Balance::where('user_id', $sponsor_id)->increment('balance', $sponsor_comm_amt);
                User::where('id', $sponsor_id)->decrement('revenue_share', $sponsor_comm_amt);
                RsHistory::create([
                    'user_id'  => $sponsor_id,
                    'from_id'  => $user_detail->id,
                    'rs_debit' => $sponsor_comm_amt,
                ]);
            } else if (User::where('id', $sponsor_id)->pluck('auto_rs') == 'yes') {

                $top_up = Packages::TopUPAutomatic($sponsor_id);

                if ($top_up) {
                    goto SPOSNSORCOMMISSION;
                }

            }

            /* Leadership bonus*/
            LeadershipBonus::allocateCommission($user_detail->id, $sponsor_id, $package->id, $package->pv);
            /* Ponit update and matching bonus*/
            Tree_Table::getAllUpline($user_detail->id);
            $key = array_search($sponsor_id, Tree_Table::$upline_id_list);
            if ($key >= 0) {
                if (Tree_Table::$upline_users[$key]['leg'] == 'L') {

                    User::where('id', $sponsor_id)->increment('left_count');

                } else if (Tree_Table::$upline_users[$key]['leg'] == 'R') {
                    User::where('id', $sponsor_id)->increment('right_count');
                }
            }
            PointTable::updatePoint($package->pv, $user_detail->id);
            PointTable::pairing($user_detail->id);

        } else {
            return redirect()->back()->withErrors(['Whoops, User not found ']);
        }

        Session::flash('flash_notification', array('message' => "Member activated succesfully", 'level' => 'success'));

        return redirect()->back();

    }
    public function search(Request $request)
    {
        $keywords    = $request->get('username');
        $suggestions = User::where('username', 'LIKE', '%' . $keywords . '%')->get();
        return $suggestions;
    }
    public function changeusername()
    {
        $title     = trans('adminuser.change_username');
        $sub_title     = trans('adminuser.change_username');
        $base     = trans('adminuser.change_username');
        $method     = trans('adminuser.change_username');


        return view('app.admin.users.changeusername', compact('title', 'sub_title', 'base', 'method'));

    }
    public function updatename(Request $request)
    {
        if (strtolower($request->username) == 'adminuser') {
            Session::flash('flash_notification', array('message' => "Username can not changed", 'level' => 'success'));
            return redirect()->back();
        }
        $username         = $request->username;
        $new_username     = $request->new_username;
        $data             = array();
        $user['username'] = $request->username;
        $validator        = Validator::make($user,
            ['username' => 'required|exists:users']);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $data['username'] = $request->new_username;
            $validator        = Validator::make($data,
                ['username' => 'required|unique:users,username|alpha_num|max:255']);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            } else {
                $update = DB::table('users')->where('username', $username)->update(['username' => $new_username]);
                Session::flash('flash_notification', array('message' => "Username Changed Successfully", 'level' => 'success'));

                return redirect()->back();

            }
        }
    }

    public function createhypperwalletid($id){

        $users=User::find($id);
        $prof_info=ProfileInfo::find(ProfileInfo::where('user_id',$id)->value('id'));
        $hypperwalletid = 'hypperwallet_'.$id;
   
        $client = new \Hyperwallet\Hyperwallet("restapiuser@16040611614", "1Cloud@Hyper", "prg-bb4b4532-62ff-4cb0-b031-319c0a2606dc");
        
        $user = new \Hyperwallet\Model\User();
       // $uid = time();
        $user
              ->setClientUserId($hypperwalletid)
              ->setProfileType(\Hyperwallet\Model\User::PROFILE_TYPE_INDIVIDUAL)
              ->setFirstName($users->name)
              ->setLastName($users->lastname)
              ->setEmail($users->email)
              ->setAddressLine1($prof_info->address1)
              ->setCity($prof_info->city)
              ->setStateProvince('CA')
              ->setCountry($prof_info->country)
              ->setPostalCode($prof_info->zip);

        
           
        try{

            $createdUser = $client->createUser($user);
            User::where('id',$id)->update([

                'hypperwallet_token' => $createdUser->token,
                'hypperwalletid' => $hypperwalletid,

              ]);
 
        }catch (\Hyperwallet\Exception\HyperwalletException $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
             $error = $e->getMessage();
        }

        Session::flash('flash_notification',array('message'=>'Successfully Created','level'=>'success'));
         
        return redirect()->back();

     


        
    }
}
