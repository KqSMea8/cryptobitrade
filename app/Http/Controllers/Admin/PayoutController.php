<?php

namespace App\Http\Controllers\Admin;

use App\Balance;
use App\Http\Controllers\Admin\AdminController;
use App\Payout;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Session;
use Excel;

class PayoutController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $title     = trans('payout.payout');
        $sub_title = trans('payout.view_payout');
        $base      = trans('payout.payout');
        $method    = trans('payout.payout_request');

        $userss = User::getUserDetails(Auth::id());
        $user   = $userss[0];

        $vocherrquest = Payout::select('payout_request.*', 'users.username', 'user_balance.balance')->join('user_balance', 'user_balance.user_id', '=', 'payout_request.user_id')->join('users', 'users.id', '=', 'payout_request.user_id')->where('status', '<>', 'released')->orderBy('status', 'ASC')->paginate(10);

        $count_requests = count($vocherrquest);

        // $vocherrquest=Balance::select('users.username','user_balance.*')->join('users', 'users.id', '=', 'user_balance.user_id')->orderBy('balance','DESC')->where('balance','>',0)->paginate(10);

        // $count_requests = count($vocherrquest);

        return view('app.admin.payout.index', compact('title', 'vocherrquest', 'user', 'count_requests', 'sub_title', 'base', 'method'));
    }

    public function getpayout()
    {
        // $details = Payout::getUserPayoutDetails();
        // print_r($details);

        $payout = Payout::sum('amount');

        $balance = Balance::sum('balance');
        echo isset($payout) ? $payout : 0;
        echo ',';
        echo isset($balance) ? $balance : 0;

    }
    public function confirm(Request $request)
    {

        

        $pay_reqid = $request->requestid;
        $user = User::find($request->user_id);
        
        $payout_request = Payout::find($pay_reqid);
        if ($payout_request->amount > $request->amount) {

            $diff_amount = $payout_request->amount - $request->amount;
            $res         = Balance::where('user_id',$diff_amount)->increment('balance', $request->amount);
        }

        $hyperwallet = new \Hyperwallet\Hyperwallet("restapiuser@16040611614", "1Cloud@Hyper", "prg-bb4b4532-62ff-4cb0-b031-319c0a2606dc");
      
        $payment = new \Hyperwallet\Model\Payment();
        $payment
            ->setDestinationToken($user->hypperwallet_token)
            ->setProgramToken('prg-bb4b4532-62ff-4cb0-b031-319c0a2606dc')
            ->setClientPaymentId(uniqid($user->hypperwalletid))
            ->setCurrency('USD')
            ->setAmount($request->amount)
            ->setPurpose('OTHER');
        try {
            $payment = $hyperwallet->createPayment($payment);
            var_dump('Payment created', $payment);
        } catch (\Hyperwallet\Exception\HyperwalletException $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
             $error = $e->getMessage();
        }

        $res = Payout::confirmPayoutRequest($pay_reqid, $request->amount);

        if ($res) {
            Session::flash('flash_notification', array('level' => 'success', 'message' => 'Details updated'));
            // $vocherrquest->status="complete";
            // $vocherrquest->save();
        } else {
            Session::flash('flash_notification', array('level' => 'danger', 'message' => 'Details updated'));

        }
        return redirect()->back();

    }

    public function payoutexport(Request $request){
              $items = Payout::join('users','users.id','=','payout_request.user_id')
                        ->where('payout_request.status', '<>', 'released')
                        ->select('username','name','email','amount','payout_request.created_at')
                        ->get();
              Excel::create('Payout request '. Date('Y-m-d H:i:s'), function($excel) use($items) {
                  $excel->sheet('ExportFile', function($sheet) use($items) {
                      $sheet->fromArray($items);
                  });
              })->export('xls');
    }
    public function payoutdelete(Request $request)
    {
        $res = Payout::deletePayoutRequest($request->requestid);
        if ($res) {
            Session::flash('flash_notification', array('level' => 'success', 'message' => 'Details updated'));

        } else {
            Session::flash('flash_notification', array('level' => 'danger', 'message' => 'Details updated'));

        }
        return redirect()->back();
    }
}
