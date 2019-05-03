<?php

namespace App\Http\Controllers\admin;

use App\Balance;
use App\Commission;
use App\Http\Controllers\Admin\AdminController;
use App\Payout;
use App\RsHistory;
use App\User;
use App\UserDebit;
use Auth;
use Datatables;
use Illuminate\Http\Request;
use Session;
use Validator;
use Crypt;

class EwalletController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $title     = trans('ewallet.title');
        $sub_title = trans('ewallet.sub_title');
        $base      = trans('ewallet.title');
        $method    = trans('ewallet.method');

        $users     = User::pluck('users.username', 'users.id');
        if (!session('user')) {
            Session::put('user', 'none');
        }

        if (!session('wallet_type')) {
            Session::put('wallet_type', 'All');
        }

        $userss = User::getUserDetails(Auth::id());
        $user   = $userss[0];

        return view('app.admin.ewallet.wallet', compact('title', 'users', 'user', 'sub_title', 'base', 'method'));
    }

    public function data(Request $request)
    {
        $amount = 0;
        $users1 = array();
        $users2 = array();
        //echo $user_id;die();
        $users1 = Commission::select('commission.id', 'user.username', 'fromuser.username as fromuser', 'commission.payment_type', 'commission.user_id', 'commission.payable_amount', 'commission.created_at')
            ->join('users as fromuser', 'fromuser.id', '=', 'commission.from_id')
            ->join('users as user', 'user.id', '=', 'commission.user_id')
            ->orderBy('commission.id', 'desc');
        $users2 = Payout::select('payout_request.id', 'users.username', 'users.username as fromuser', 'payout_request.status as payment_type', 'payout_request.user_id', 'payout_request.amount as payable_amount', 'payout_request.created_at')
            ->join('users', 'users.id', '=', 'payout_request.user_id')
            ->orderBy('payout_request.id', 'desc');

        $users3 = UserDebit::select('user_debit.id', 'fromuser.username as fromuser', 'user.username', 'user_debit.payment_type', 'user_debit.user_id', 'user_debit.debit_amount', 'user_debit.created_at')
            ->join('users as fromuser', 'fromuser.id', '=', 'user_debit.from_id')
            ->join('users as user', 'user.id', '=', 'user_debit.user_id')
            ->orderBy('user_debit.id', 'desc');

         $ewallet_count = $users1->union($users2)->union($users3)->orderBy('created_at', 'DESC')->get()->count();
        $users = $users1->union($users2)->union($users3)->orderBy('created_at', 'DESC');

            // ->offset($request->start)
            // ->limit($request->length)
            // ->get();


      // die();

        return Datatables::of($users)
            ->edit_column('fromuser', '@if ($payment_type =="released") Adminuser @else {{$fromuser}} @endif')
            ->edit_column('user_id', '@if ($payment_type =="released" || $payment_type =="fund_transfer" || $payment_type =="plan_purchase") <span >{{currency($payable_amount)}}</span> @else <span class="">0</span>@endif')
            ->edit_column('payable_amount', '@if ($payment_type =="released" || $payment_type =="fund_transfer" || $payment_type == "plan_purchase") <span>0</span> @else <span class="">{{currency(round($payable_amount,2))}}</span>@endif')
            ->edit_column('payment_type', ' @if ($payment_type =="released") Payout released @else <?php  echo str_replace("_", " ", "$payment_type") ;  ?> @endif')
            ->remove_column('id')
            ->setTotalRecords($ewallet_count)
            ->escapeColumns([])
            ->make();

    }
    public function userwallet(Request $request)
    {
        $amount     = 0;
        $users1     = array();
        $users2     = array();
        $users      = array();
        $user_id    = Auth::id();
        $bonus_type = trans('ewallet.bonus_type');
        if (session('user') != 'none') {
            $user_id = $request->user;
        }
        if (session('wallet_type') != 'All') {
            $bonus_type = $request->bonus_type;
        }
        $title = trans('ewallet.title');
        $users = User::lists('users.username', 'users.id');
        Session::put('user', $request->user);
        Session::put('bonus_type', $request->bonus_type);
        return redirect('admin/wallet');
    }

    public function search(Request $request)
    {
        $keywords    = $request->get('username');
        $suggestions = User::where('username', 'LIKE', '%' . $keywords . '%')->get();
        return $suggestions;
    }

    public function fund()
    {

        $title     = trans('ewallet.credit_fund');
        $sub_title = trans('ewallet.credit_fund');
        $base      = trans('ewallet.credit_fund');
        $method    = trans('ewallet.credit_fund');
        return view('app.admin.ewallet.fund', compact('title', 'countries', 'user', 'sub_title', 'base', 'method'));

    }

    public function creditfund(Request $request)
    {    
        $input = $request->all();
        $input['username'] = $request->username;

        $request->merge($input);
   

        $validator = Validator::make($request->all(), [
            'username' => 'required|exists:users',
            'amount'   => 'required',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator);
        } else {
            $user_id = User::where('username', $request->username)->value('id');
            Commission::create([
                'user_id'        => $user_id,
                'from_id'        => Auth::user()->id,
                'total_amount'   => $request->amount,
                'payable_amount' => $request->amount,
                'payment_type'   => 'credited_by_admin',
            ]);
            Balance::where('user_id', $user_id)->increment('balance', $request->amount);

            Session::flash('flash_notification', array('message' => "Amount Credited to ({$request->username}) E-wallet", 'level' => 'success'));

            return redirect()->back();
        }

    }

    public function rs_wallet()
    {

        $title     = trans('ewallet.rs_wallet');
        $sub_title = trans('ewallet.rs_wallet');
        $base      = trans('ewallet.rs_wallet');
        $method    = trans('ewallet.rs_wallet');

        return view('app.admin.ewallet.rs_wallet', compact('title', 'sub_title', 'base', 'method'));
    }

    public function rs_data(Request $request)
    {

        $rs_count = RsHistory::count();

        $rstable = RsHistory::select('rs_history.id', 'user.username', 'fromuser.username as fromuser', 'rs_history.rs_debit', 'rs_history.rs_credit', 'rs_history.created_at')
            ->join('users as fromuser', 'fromuser.id', '=', 'rs_history.from_id')
            ->join('users as user', 'user.id', '=', 'rs_history.user_id')
            ->orderBy('rs_history.id', 'desc')             
            ->get();

        return Datatables::of($rstable)
            ->remove_column('id')
            ->setTotalRecords($rs_count)
            ->make();
    }
}
