<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\user\UserAdminController;
use App\Http\Controllers;
use Auth;
use App\User;
use App\Mail;
use App\Payout;
use App\AppSettings;
use App\Tree_Table;
use App\Balance;
use App\Commission;
use App\PointTable;
use App\PurchaseHistory;
use App\RsHistory;
use App\Currency;

class dashboard extends UserAdminController{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $title = trans('dashboard.method');
       
        $users = User::count();
        
       
        $total_bv =  Auth::user()->revenue_share;
        $left_bv =  PointTable::where('user_id','=',Auth::user()->id)->value('left_carry');
        $right_bv =  PointTable::where('user_id','=',Auth::user()->id)->value('right_carry');
      
        
        $balance = Balance::getTotalBalance(Auth::user()->id);

        $details = Payout::getUserPayoutDetails();
        $details = explode(',', $details);
        $percentage_balance = 0;
        $percentage_released = 0;

        if($details[0]+$balance != 0)
        $percentage_balance = ($balance*100)/($details[0]+$balance);
        if($details[0]+$details[1] !=0)
        $percentage_released = ($details[0]*100)/($details[0]+$details[1]);
        $new_users = User::getNewUsers();
        $count_new = count($new_users);  
        $total_top_up = PurchaseHistory::where('user_id',Auth::user()->id)->sum('count');
        $total_rs = RsHistory::where('user_id',Auth::user()->id)->sum('rs_credit');
        // $credits = User::where('id',Auth::user()->id)->pluck('credits');

        $USER_CURRENCY=Currency::all();

        $base = trans('dashboard.base');
        $method = trans('dashboard.method');
        $sub_title = trans('dashboard.sub_title');

       return view('app.user.dashboard.index', compact('count_new','new_users','title','point_details', 'users', 'balance','percentage_released','percentage_balance','total_bonus','sub_title','right_bv','left_bv','total_bv','total_top_up','total_rs','base','method','USER_CURRENCY'));
    }

    public function getmonthusers(){
        $downline_users = array();
        Tree_Table::getDownlines(1,true ,Auth::user()->id,$downline_users);
        $users = Tree_Table::getDown();       
        print_r($users);
    }

}
