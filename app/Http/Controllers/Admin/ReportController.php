<?php

namespace App\Http\Controllers\admin;

use App\CarryForwardHistory;
use App\Commission;
use App\Country;
use App\Http\Controllers\Admin\AdminController;
use App\Mail;
use App\Packages;
use App\PairingHistory;
use App\Payout;
use App\PurchaseHistory;
use App\User;
use Assets;
use Auth;
use DB;
use Illuminate\Http\Request;
use Validator;
use CountryState;

class ReportController extends AdminController
{

    public function joiningreport()
    {
        $title     = trans('report.joining_report');
        $sub_title = trans('report.joining_report');
        // $countries = Country::all();
        // dd($countries);

        /**
        * Get Countries from mmdb
        * @var [collection]
        */
        $countries = CountryState::getCountries();
        // dd($countries);
        /**
        * [Get States from mmdb]
        * @var [collection]
        */
        $states = CountryState::getStates('US');
        /**
        * Get all packages from database
        * @var [collection]
        */


        $base      = trans('report.report');
        $method    = trans('report.joining_report');
        $userss    = User::getUserDetails(Auth::id());
        $user      = $userss[0];
        return view('app.admin.report.joiningreport', compact('title', 'countries', 'user', 'sub_title', 'base', 'method'));
    }

    public function joiningreportview(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'start'   => 'required|date',
            'end'     => 'required|date',
            'country' => '',
            'sponsor' => 'exists:users,username',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $title      = trans('report.joining_report');
        $sub_title  = trans('report.joining_report_view');
        $base       = trans('report.report');
        $method     = trans('report.joining_report_view');
        $reportdata = User::join('profile_infos', 'profile_infos.user_id', '=', 'users.id')->select('users.username', 'users.name', 'users.lastname', 'users.email', 'profile_infos.mobile', 'users.created_at')->where('users.created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))->where('users.created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))->get();
        return view('app.admin.report.joiningreportview', compact('title', 'countries', 'reportdata', 'sub_title', 'base', 'method'));
    }

    public function joiningreportbysponsorview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sponsor' => 'exists:users,username',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $sponsor_id = User::userNameToId($request->sponsor);

        $title     = trans('report.joining_report');
        $base      = trans('report.report');
        $method    = trans('report.joining_report_by_sponsor');
        $sub_title = trans('report.joining_report_by_sponsor');
        //$reportdata=User::select('username','name','lastname','email','mobile','created_at')->where(t->end)))->get();
        $reportdata  = DB::table('users')->join('sponsortree', 'sponsortree.user_id', '=', 'users.id')->where('sponsortree.sponsor', '=', $sponsor_id)->get();
        $count_users = count($reportdata);
        for ($i = 0; $i < $count_users; $i++) {
            $reportdata[$i]->country = User::countryIdToName($reportdata[$i]->country);
        }

        return view('app.admin.report.joiningreportview', compact('title', 'countries', 'reportdata', 'base', 'method', 'sub_title'));
    }

    public function joiningreportbycountryview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        //echo   $request->country;die();
        $title       = trans('report.joining_report');
        $base        = trans('report.report');
        $method      = trans('report.joining_report_by_country');
        $sub_title   = trans('report.joining_report_by_country');
        $reportdata  = User::join('profile_infos', 'profile_infos.user_id', '=', 'users.id')->select('users.username', 'users.name', 'users.lastname', 'users.email', 'profile_infos.mobile', 'users.created_at', 'profile_infos.country')->where('profile_infos.country', '=', $request->country)->get();
        $count_users = count($reportdata);
        for ($i = 0; $i < $count_users; $i++) {
            $reportdata[$i]->country = User::countryIdToName($reportdata[$i]->country);
        }
        return view('app.admin.report.joiningreportview', compact('title', 'countries', 'reportdata', 'base', 'method', 'sub_title'));

    }

    public function fundcredit()
    {
        $title        = trans('report.fund_credit');
        $sub_title    = trans('report.fund_credit');
        $unread_count = Mail::unreadMailCount(Auth::id());
        $unread_mail  = Mail::unreadMail(Auth::id());
        $base         = trans('report.fund_credit');
        $method       = trans('report.fund_credit');
        $userss       = User::getUserDetails(Auth::id());
        $user         = $userss[0];
        return view('app.admin.report.fundcredit', compact('title', 'unread_count', 'unread_mail', 'user', 'sub_title', 'base', 'method'));
    }

    public function fundcreditview(Request $request)
    {

        // Assets::AddCSS(asset('assets/globals/css/print.css'));

        $validator = Validator::make($request->all(), [
            'start' => 'required|date',
            'end'   => 'required|date|',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $title      = trans('report.fund_credit');
        $sub_title  = trans('report.fund_credit');
        $base       = trans('report.fund_credit');
        $method     = trans('report.fund_credit');
        $reportdata = Commission::where('commission.created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))->where('commission.created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))
            ->join('users', 'users.id', '=', 'commission.user_id')
            ->select('commission.created_at', 'users.username', 'users.name', 'users.lastname', 'users.email', 'commission.payable_amount as amount', 'commission.payment_type')
            ->where('commission.payment_type', '=', 'credited_by_admin')
            ->get();

        $payable_amount = Commission::where('commission.created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))->where('commission.created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))
            ->join('users', 'users.id', '=', 'commission.user_id')
            ->sum('payable_amount');

        return view('app.admin.report.fundcreditview', compact('title', 'reportdata', 'sub_title', 'payable_amount', 'base', 'method'));

    }

    public function ewalletreport()
    {
        $title     = trans('report.members_income_report');
        $sub_title = trans('report.income_report');
        $base      = trans('report.report');
        $method    = trans('report.income_report');

        $userss   = User::getUserDetails(Auth::id());
        $user     = $userss[0];
        $users    = User::where('id', '>', 1)->get();
        $packages = Packages::all();
        return view('app.admin.report.ewalletreport', compact('title', 'user', 'users', 'sub_title', 'base', 'method', 'packages'));
    }

    public function ewalletreportview(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'start'    => 'required|date',
            'end'      => 'required|date|',
            'username' => 'sometimes|exists:users',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $user_id = User::where('username', $request->username)->pluck('id');

        $title     = trans('report.members_income_report');
        $sub_title = trans('report.income_report');
        $base      = trans('report.report');
        $method    = trans('report.income_report');

        $reportdata = Commission::where('commission.created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))->where('commission.created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))
            ->join('users', 'users.id', '=', 'commission.user_id')
            ->join('profile_infos', 'profile_infos.user_id', '=', 'commission.user_id')
            ->join('packages', 'packages.id', '=', 'profile_infos.package')
            ->select('users.username', 'packages.package as position', 'users.lastname', 'users.name', 'commission.created_at', 'commission.total_amount', 'commission.payment_type')
            ->where(function ($query) use ($request, $user_id) {
                if ($request->bonus_type != 'all') {
                    $query->where('commission.payment_type', '=', $request->bonus_type);
                }
                if ($user_id != null) {
                    $query->where('commission.user_id', '=', $user_id);
                }
            })

            ->get();

        $totalamount = Commission::where('commission.created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))
            ->where('commission.created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))
            ->where('commission.payment_status', '=', 'yes')
            ->where(function ($query) use ($request, $user_id) {
                if ($request->bonus_type != 'all') {
                    $query->where('commission.payment_type', '=', $request->bonus_type);
                }
                if ($user_id != null) {
                    $query->where('commission.user_id', '=', $user_id);
                }
            })
            ->join('users', 'users.id', '=', 'commission.user_id')
            ->sum('total_amount');

        return view('app.admin.report.ewalletreportview', compact('title', 'reportdata', 'totalamount', 'sub_title', 'base', 'method'));
    }

    public function payoutreport()
    {
        $title     = trans('report.payout_released_report');
        $sub_title = trans('report.payout_release_report');
        $base      = trans('report.report');
        $method    = trans('report.payout_release_report');
        $users     = User::where('id', '>', 1)->get();
        $packages  = Packages::all();
        $user      = User::find(Auth::user()->id);
        return view('app.admin.report.payoutreport', compact('title', 'users', 'user', 'packages', 'sub_title', 'base', 'method'));
    }

    public function payoutreportview(Request $request)
    {

        // Assets::AddCSS(asset('assets/globals/css/print.css'));

        $validator = Validator::make($request->all(), [
            'start' => 'required|date',
            'end'   => 'required|date|',
            // 'username'=>'required|exists:users'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $title      = trans('report.payout_released_report');
        $sub_title  = trans('report.payout_release_report');
        $base       = trans('report.report');
        $method     = 'Payout release report view';
        $reportdata = Payout::where(function ($query) use ($request) {
            if ($request->username != 'all') {
                $query->where('payout_request.user_id', '=', $request->username);
            }
        })
            ->where('payout_request.created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))
            ->where('payout_request.created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))
            ->where('status', '=', 'released')
            ->join('users', 'users.id', '=', 'payout_request.user_id')
            ->join('profile_infos', 'profile_infos.user_id', '=', 'payout_request.user_id')
            // ->join('currency', 'currency.id', '=', 'profile_infos.currency')
            ->select('users.username', 'users.lastname', 'users.name', 'profile_infos.account_holder_name', 'profile_infos.account_number', 'profile_infos.bank_code', 'profile_infos.sort_code', 'profile_infos.swift', 'payout_request.*')
            ->get();

        $totalamount = Payout::where(function ($query) use ($request) {
            if ($request->username != 'all') {
                $query->where('payout_request.user_id', '=', $request->username);
            }
        })
            ->where('payout_request.created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))
            ->where('payout_request.created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))
            ->where('status', '=', 'released')
            ->sum('amount');

        return view('app.admin.report.payoutreportview', compact('title', 'reportdata', 'totalamount', 'sub_title', 'base', 'method'));
    }

    public function salesreport()
    {
        $title        = trans('report.sales_report');
        $sub_title    = trans('report.sales_report');
        $unread_count = Mail::unreadMailCount(Auth::id());
        $unread_mail  = Mail::unreadMail(Auth::id());
        $base         = trans('report.sales_report');
        $method       = trans('report.sales_report');
        $userss       = User::getUserDetails(Auth::id());
        $user         = $userss[0];
        return view('app.admin.report.salesreport', compact('title', 'unread_count', 'unread_mail', 'user', 'sub_title', 'base', 'method'));
    }

    public function salesreportview(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'start' => 'required|date',
            'end'   => 'required|date|',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $title      = trans('report.sales_report');
        $sub_title  = trans('report.sales_report');
        $base       = trans('report.sales_report');
        $method     = trans('report.sales_report');
        $reportdata = PurchaseHistory::where('purchase_history.created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))
            ->where('purchase_history.created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))
            ->where('purchase_history.sales_status', 'yes')

            ->join('users', 'users.id', '=', 'purchase_history.purchase_user_id')
            ->join('packages', 'packages.id', '=', 'purchase_history.package_id')
            ->select('purchase_history.created_at', 'users.username', 'users.name', 'users.lastname', 'users.email', 'purchase_history.total_amount as amount')
        // ->sum('total_amount')
            ->get();

        $total_amount = PurchaseHistory::where('purchase_history.created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))
            ->where('purchase_history.created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))
            ->where('purchase_history.sales_status', 'yes')
            ->sum('total_amount');

        return view('app.admin.report.salesreportview', compact('title', 'reportdata', 'total_amount', 'sub_title', 'base', 'method'));

    }

    public function pairingreport()
    {
        $title     = trans('report.pairing_report');
        $sub_title = trans('report.pairing_report');
        $base      = trans('report.report');
        $method    = trans('report.pairing_report');

        $user     = User::find(Auth::id());
        $users    = User::where('id', '>', 1)->get();
        $packages = Packages::all();
        return view('app.admin.report.pairingreport', compact('title', 'user', 'users', 'sub_title', 'base', 'method', 'packages'));
    }

    public function pairingreportview(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'start' => 'required|date',
            'end'   => 'required|date|',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if ($request->user_id != 'all') {
            $request->username = $request->user_id;
        }

        $title     = trans('report.pairing_report');
        $sub_title = trans('report.pairing_report');
        $base      = trans('report.report');
        $method    = trans('report.pairing_report');

        if (date('l', strtotime($request->start)) != 'Sunday') {

            $nextSunday     = strtotime('last Sunday', strtotime($request->start));
            $request->start = date('Y-m-d', $nextSunday);
        }
        if (date('l', strtotime($request->end)) != 'Saturday') {

            $nextSaturday = strtotime('next Saturday', strtotime($request->end));
            $request->end = date('Y-m-d', $nextSaturday);

        }

        $date_arr = array();

        $start_date = date('Y-m-d', strtotime($request->start));
        $end_date   = date('Y-m-d', strtotime($request->end));

        while (strtotime($end_date) >= strtotime('next Saturday', strtotime($start_date))) {
            $date_arr[] = array('start' => $start_date, 'end' => date('Y-m-d', strtotime('next Saturday', strtotime($start_date))));
            $start_date = date('Y-m-d', strtotime('next Sunday', strtotime($start_date)));
        }

        $final_arr = [];

        foreach ($date_arr as $key => $value) {

            $final_arr[] = $reportdata = PairingHistory::where('pairing_history.created_at', '>', date('Y-m-d 00:00:00', strtotime($value['start'])))
                ->where('pairing_history.created_at', '<', date('Y-m-d 23:59:59', strtotime($value['end'])))
                ->where(function ($query) use ($request) {
                    if ($request->username != 'all') {
                        $query->where('pairing_history.user_id', '=', $request->username);
                    }
                    if ($request->position != 'all') {
                        $query->where('users.package', '=', $request->position);
                    }
                })
                ->join('users', 'users.id', '=', 'pairing_history.user_id')
                ->select('users.username', 'users.lastname', 'users.name', 'pairing_history.*')
                ->orderBy('created_at', 'ASC')
                ->get();

        }

        return view('app.admin.report.pairingreportview', compact('title', 'final_arr', 'date_arr', 'sub_title', 'base', 'method'));
    }

    public function carryreportview(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'start' => 'required|date',
            'end'   => 'required|date|',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if ($request->user_id != 'all') {
            $request->username = $request->user_id;
        }
        $title     = trans('report.carry_forward');
        $sub_title = trans('report.pairing_report');
        $base      = trans('report.report');
        $method    = trans('report.pairing_report');

        if (date('l', strtotime($request->start)) != 'Sunday') {

            $nextSunday     = strtotime('last Sunday', strtotime($request->start));
            $request->start = date('Y-m-d', $nextSunday);
        }
        if (date('l', strtotime($request->end)) != 'Saturday') {

            $nextSaturday = strtotime('next Saturday', strtotime($request->end));
            $request->end = date('Y-m-d', $nextSaturday);

        }

        $date_arr = array();

        $start_date = date('Y-m-d', strtotime($request->start));
        $end_date   = date('Y-m-d', strtotime($request->end));

        while (strtotime($end_date) >= strtotime('next Saturday', strtotime($start_date))) {
            $date_arr[] = array('start' => $start_date, 'end' => date('Y-m-d', strtotime('next Saturday', strtotime($start_date))));
            $start_date = date('Y-m-d', strtotime('next Sunday', strtotime($start_date)));
        }

        $final_arr = [];

        foreach ($date_arr as $key => $value) {

            $final_arr[] = $reportdata = CarryForwardHistory::where('carry_forward_history.created_at', '>', date('Y-m-d 00:00:00', strtotime($value['start'])))
                ->where('carry_forward_history.created_at', '<', date('Y-m-d 23:59:59', strtotime($value['end'])))
                ->where(function ($query) use ($request) {
                    if ($request->username != 'all') {
                        $query->where('carry_forward_history.user_id', '=', $request->username);

                    }
                    if ($request->position != 'all') {
                        $query->where('users.package', '=', $request->position);
                    }

                })
                ->join('users', 'users.id', '=', 'carry_forward_history.user_id')
                ->select('users.username', 'users.lastname', 'users.name', 'carry_forward_history.*')
                ->orderBy('created_at', 'ASC')
                ->get();

        }

        return view('app.admin.report.carryreportview', compact('title', 'final_arr', 'date_arr', 'sub_title', 'base', 'method'));
    }

    public function topearners()
    {
        $title        = trans('report.top_earners');
        $sub_title    = trans('report.top_earners');
        $unread_count = Mail::unreadMailCount(Auth::id());
        $unread_mail  = Mail::unreadMail(Auth::id());
        $base         = trans('report.top_earners');
        $method       = trans('report.top_earners');
        $userss       = User::getUserDetails(Auth::id());
        $user         = $userss[0];
        return view('app.admin.report.topearners', compact('title', 'unread_count', 'unread_mail', 'user', 'sub_title', 'base', 'method'));
    }

    public function topearnersview(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'start' => 'required|date',
            'end'   => 'required|date|',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $title      = trans('report.top_earners_report');
        $sub_title  = trans('report.top_earners_report');
        $base       = trans('report.top_earners_report');
        $method     = trans('report.top_earners_report');
        $reportdata = Commission::where('commission.created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))->where('commission.created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))
            ->where('commission.payment_status', '=', 'yes')
            ->join('users', 'users.id', '=', 'commission.user_id')
            ->join('profile_infos', 'profile_infos.user_id', '=', 'commission.user_id')
            ->join('packages', 'packages.id', '=', 'profile_infos.package')
            ->groupBY('commission.user_id')
            ->select('users.username', 'users.name', 'users.lastname', 'packages.package', DB::raw('SUM(payable_amount) as amount'))
            ->where('payable_amount', '>', 0)
            ->orderby('amount', 'DESC')
            ->get();

        return view('app.admin.report.topearnersview', compact('title', 'reportdata', 'sub_title', 'base', 'method'));

    }

    public function revenuereport()
    {
        $title     = trans('report.revenue_report');
        $sub_title = trans('report.revenue_report');
        $base      = trans('report.report');
        $method    = trans('report.revenue_report');
        $user      = User::find(Auth::id());
        $users     = User::where('id', '>', 1)->get();

        return view('app.admin.report.revenuereport', compact('title', 'users', 'user', 'sub_title', 'base', 'method'));
    }

    public function revenuereportview(Request $request)
    {

        $title     = trans('report.revenue_report');
        $sub_title = trans('report.revenue_report');
        $base      = trans('report.report');
        $method    = trans('report.revenue_report');
        $user      = User::find(Auth::id());

        if ($request->user_id != 'all') {
            $request->username = $request->user_id;
        }

        $user = User::find($request->username);

        $total_sales = PurchaseHistory::where('purchase_history.created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))
            ->where('purchase_history.created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))
            ->where('purchase_history.status', '=', 'approved')
            ->where(function ($query) use ($request) {
                if ($request->username != 'all') {
                    $query->where('purchase_history.user_id', '=', $request->username);
                }
            })
            ->sum('total_amount');

        $payout = Commission::where('commission.created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))
            ->where('commission.created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))
            ->where('commission.payment_status', '=', 'yes')
            ->where(function ($query) use ($request) {
                if ($request->username != 'all') {
                    $query->where('commission.user_id', '=', $request->username);
                }
                if ($request->bonus_type != 'all') {
                    $query->where('commission.payment_type', '=', $request->bonus_type);
                }
            })
            ->sum('payable_amount');

        return view('app.admin.report.revenuereportview', compact('title', 'user', 'sub_title', 'base', 'method', 'request', 'user', 'total_sales', 'payout'));

    }

    public function salereport()
    {
        $title     = trans('report.sales_report');
        $sub_title = trans('report.sales_report');
        $base      = trans('report.report');
        $method    = trans('report.sales_report');
        $user      = User::find(Auth::id());
        $users     = User::where('id', '>', 1)->get();
        $package   = Packages::all();

        return view('app.admin.report.salesreport', compact('title', 'users', 'user', 'sub_title', 'base', 'method', 'package'));
    }

    public function salereportview(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'start' => 'required|date',
            'end'   => 'required|date|',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if ($request->user_id != 'all') {
            $request->username = $request->user_id;
        }

        $title      = trans('report.sales_report');
        $sub_title  = trans('report.sales_report');
        $base       = trans('report.report');
        $method     = trans('report.sales_report');
        $reportdata = PurchaseHistory::where('purchase_history.created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))
            ->where('purchase_history.created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))
            ->where('purchase_history.status', '=', 'approved')
            ->where(function ($query) use ($request) {
                if ($request->username != 'all') {
                    $query->where('purchase_history.user_id', '=', $request->username);
                }
                if ($request->package != 'all') {
                    $query->where('users.package', '=', $request->package);
                }
            })
            ->join('users', 'users.id', '=', 'purchase_history.user_id')
            ->join('packages', 'packages.id', '=', 'users.package')
            ->select('purchase_history.*', 'users.username', 'users.user_id as userid', 'users.lastname', 'users.name', 'packages.package')
            ->orderBy('purchase_history.created_at', 'ASC')
            ->get();

        return view('app.admin.report.salereportview', compact('title', 'reportdata', 'sub_title', 'base', 'method'));

    }

    public function maintenancereport()
    {
        $title     = trans('report.maintenance_report');
        $sub_title = trans('report.maintenance_report');
        $base      = trans('report.report');
        $method    = trans('report.maintenance_report');
        $user      = User::find(Auth::id());
        $users     = User::where('id', '>', 1)->get();

        return view('app.admin.report.maintenancereport', compact('title', 'users', 'user', 'sub_title', 'base', 'method'));
    }
    public function maintenancereportview(Request $request)
    {
        $title     = trans('report.maintenance_report');
        $sub_title = trans('report.maintenance_report');
        $base      = trans('report.report');
        $method    = trans('report.maintenance_report');
        $users     = array();
        if ($request->bv == 'm') {
            $users = User::rightjoin('purchase_history', 'purchase_history.user_id', '=', 'users.id')
                ->whereYear('purchase_history.created_at', '=', date('Y', strtotime($request->start)))
                ->whereMonth('purchase_history.created_at', '=', date('m', strtotime($request->start)))
                ->where('purchase_history.status', '=', 'approved')
                ->leftjoin('packages', 'packages.id', '=', 'users.package')
                ->select('users.username', 'users.id', 'users.name', 'packages.package', 'users.lastname', 'users.user_id', DB::raw('SUM(purchase_history.BV) as BV'))
                ->having(DB::raw('SUM(purchase_history.BV)'), '<', 100)
                ->groupBY('purchase_history.user_id')
                ->get();

        } elseif ($request->bv == 'n') {
            $users = User::rightjoin('purchase_history', 'purchase_history.user_id', '=', 'users.id')
                ->whereYear('purchase_history.created_at', '=', date('Y', strtotime($request->start)))
                ->whereMonth('purchase_history.created_at', '=', date('m', strtotime($request->start)))
                ->where('purchase_history.status', '=', 'approved')
                ->leftjoin('packages', 'packages.id', '=', 'users.package')
                ->select('users.username', 'users.id', 'users.name', 'packages.package', 'users.lastname', 'users.user_id', DB::raw('SUM(purchase_history.BV) as BV'))
                ->having(DB::raw('SUM(purchase_history.BV)'), '>=', 100)
                ->groupBY('purchase_history.user_id')
                ->get();

        }

        $users_not_in = array(1);
        $users_in     = array();

        foreach ($users as $key => $value) {
            array_push($users_not_in, $value->id);
        }

        $user_list = User::where(function ($query) use ($users_not_in) {
            $query->whereNotIn('users.id', $users_not_in);
        })
            ->leftjoin('packages', 'packages.id', '=', 'users.package')
            ->select('users.*', 'packages.package')
            ->get();

        $reportdata = array();

        foreach ($user_list as $key => $value) {
            $bv = PurchaseHistory::getMonthlyTotal($value->id, $request->start);
            if ($bv >= 100) {$status = 'Maintain';} else { $status = 'Not Maintain';}
            $reportdata[] = array(
                'username' => $value->username,
                'user_id'  => $value->user_id,
                'name'     => $value->name . ' ' . $value->lastname,
                'package'  => $value->package,
                'BV'       => $bv,
                'status'   => $status,
            );
        }

        return view('app.admin.report.maintenancereportview', compact('title', 'sub_title', 'base', 'method', 'reportdata'));
    }

    public function summuryreport()
    {
        $title     = trans('report.summary_report');
        $sub_title = trans('report.summary_report');
        $base      = trans('report.report');
        $method    = trans('report.summary_report');
        $user      = User::find(Auth::id());
        $users     = User::where('id', '>', 1)->get();

        return view('app.admin.report.summuryreport', compact('title', 'users', 'user', 'sub_title', 'base', 'method'));
    }

    public function summuryreportview(Request $request)
    {

        $title     = trans('report.summary_report');
        $sub_title = trans('report.summary_report');
        $base      = trans('report.report');
        $method    = trans('report.summary_report');
        $user      = User::find($request->username);

        $direct_sponsor = Commission::whereYear('commission.created_at', '=', date('Y', strtotime($request->start)))
            ->whereMonth('commission.created_at', '=', date('m', strtotime($request->start)))
            ->where('commission.payment_type', '=', 'direct_sponsor_bonus')
            ->where('commission.payment_status', '=', 'yes')
            ->where(function ($query) use ($request) {
                if ($request->username != 'all') {
                    $query->where('commission.user_id', '=', $request->username);
                }
            })
            ->join('users', 'users.id', '=', 'commission.user_id')
            ->join('users as from', 'from.id', '=', 'commission.from_id')
            ->select('users.username', 'users.name', 'users.lastname', 'from.username as fromuser', 'commission.*')
            ->get();
        $total_direct_sponsor = Commission::whereYear('commission.created_at', '=', date('Y', strtotime($request->start)))
            ->whereMonth('commission.created_at', '=', date('m', strtotime($request->start)))
            ->where('commission.payment_type', '=', 'direct_sponsor_bonus')
            ->where('commission.payment_status', '=', 'yes')
            ->where(function ($query) use ($request) {
                if ($request->username != 'all') {
                    $query->where('commission.user_id', '=', $request->username);
                }
            })
            ->sum('commission.payable_amount');

        $pairing_bonus = PairingHistory::whereYear('pairing_history.created_at', '=', date('Y', strtotime($request->start)))
            ->whereMonth('pairing_history.created_at', '=', date('m', strtotime($request->start)))
            ->where(function ($query) use ($request) {
                if ($request->username != 'all') {
                    $query->where('pairing_history.user_id', '=', $request->username);
                }
            })
            ->join('users', 'users.id', '=', 'pairing_history.user_id')
            ->select('users.username', 'users.name', 'users.lastname', 'pairing_history.*')
            ->get();
        $total_pairing_bonus = PairingHistory::whereYear('pairing_history.created_at', '=', date('Y', strtotime($request->start)))
            ->whereMonth('pairing_history.created_at', '=', date('m', strtotime($request->start)))
            ->where(function ($query) use ($request) {
                if ($request->username != 'all') {
                    $query->where('pairing_history.user_id', '=', $request->username);
                }
            })
            ->select(DB::raw('SUM(first_bonus) as first_bonus'), DB::raw('SUM(second_bonus) as second_bonus'), DB::raw('SUM(third_bonus) as third_bonus'))
            ->get();

        $matching_bonus = Commission::whereYear('commission.created_at', '=', date('Y', strtotime($request->start)))
            ->whereMonth('commission.created_at', '=', date('m', strtotime($request->start)))
            ->where('commission.payment_type', '=', 'matching_bonus')
            ->where('commission.payment_status', '=', 'yes')
            ->where(function ($query) use ($request) {
                if ($request->username != 'all') {
                    $query->where('commission.user_id', '=', $request->username);
                }
            })
            ->join('users', 'users.id', '=', 'commission.user_id')
            ->join('users as from', 'from.id', '=', 'commission.from_id')
            ->select('users.username', 'users.name', 'users.lastname', 'from.username as fromuser', 'commission.*')
            ->get();

        $total_matching_bonus = Commission::whereYear('commission.created_at', '=', date('Y', strtotime($request->start)))
            ->whereMonth('commission.created_at', '=', date('m', strtotime($request->start)))
            ->where('commission.payment_type', '=', 'matching_bonus')
            ->where('commission.payment_status', '=', 'yes')
            ->where(function ($query) use ($request) {
                if ($request->username != 'all') {
                    $query->where('commission.user_id', '=', $request->username);
                }
            })
            ->sum('commission.payable_amount');
        $loyalty_bonus = Commission::whereYear('commission.created_at', '=', date('Y', strtotime($request->start)))
            ->whereMonth('commission.created_at', '=', date('m', strtotime($request->start)))
            ->where('commission.payment_type', '=', 'loyalty_bonus')
            ->where('commission.payment_status', '=', 'yes')
            ->where(function ($query) use ($request) {
                if ($request->username != 'all') {
                    $query->where('commission.user_id', '=', $request->username);
                }
            })
            ->join('users', 'users.id', '=', 'commission.user_id')
            ->join('users as from', 'from.id', '=', 'commission.from_id')
            ->select('users.username', 'users.name', 'users.lastname', 'from.username as fromuser', 'commission.*')
            ->get();

        $totaloyalty_bonus = Commission::whereYear('commission.created_at', '=', date('Y', strtotime($request->start)))
            ->whereMonth('commission.created_at', '=', date('m', strtotime($request->start)))
            ->where('commission.payment_type', '=', 'loyalty_bonus')
            ->where('commission.payment_status', '=', 'yes')
            ->where(function ($query) use ($request) {
                if ($request->username != 'all') {
                    $query->where('commission.user_id', '=', $request->username);
                }
            })
            ->sum('commission.payable_amount');

        return view('app.admin.report.summuryreportview', compact('title', 'sub_title', 'base', 'method', 'direct_sponsor', 'total_direct_sponsor', 'matching_bonus', 'total_matching_bonus', 'pairing_bonus', 'total_pairing_bonus', 'loyalty_bonus', 'totaloyalty_bonus', 'request', 'user'));

    }

}
