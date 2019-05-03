<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\MatchingBonus;
use App\Balance;
use App\User;
use App\Packages;
use App\Commission;
use App\Transactions;
use App\Sponsortree;
use App\PurchaseHistory;
use App\Ranksetting;
use App\Sales;
use App\PointTable;
use App\DirectSposnor;
use App\RsHistory;
use App\LeadershipBonus;
use App\Tree_Table;
use App\UserDebit;
use App\Currency;
use App\Voucher;
use App\VoucherHistory;
use App\Product;
use App\Category;
use App\ProductPurchas;
use Auth;
use Session;
use Validator;



use App\Http\Controllers\user\UserAdminController;


class productController extends UserAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
                
        $products = Packages::get();  
        $title = trans('products.purchase_plan');
        $sub_title =  trans('products.purchase_plan');       
        $rules = ['count' => 'required|min:1'];
        $base =  trans('products.purchase_plan');
        $method =  trans('products.purchase_plan');
        $balance =  Balance::where('user_id',Auth::user()->id)->value('balance');
        $min_amount =  Packages::min('amount');
        return view('app.user.product.index',compact('title','products','rules','base','method','sub_title','balance','min_amount'));    
    }


     public function purchasehistory()
    {
          $data = PurchaseHistory::join('packages','packages.id','=','purchase_history.package_id')
                  ->where('user_id',Auth::user()->id)
                  ->where('purchase_user_id',Auth::user()->id)
                  ->select('packages.package','count','packages.amount','total_amount','purchase_history.created_at','purchase_history.pv','purchase_history.pay_by')
                  ->orderBy('purchase_history.id','DESC')
                  ->paginate(10);
           
        $title = trans('products.purchase_history');
        $sub_title = trans('products.purchase_history'); 
        $base = trans('products.purchase_history');  
        $method = trans('products.purchase_history');     
        return view('app.user.product.purchase-history',compact('title','data','base','method','sub_title'));
    
    }


    public function purchase(Request $request){


        $validator = Validator::make($request->all(), [
            // 'username'=>'required|exists:users,username' ,
            'steps_plan_payment'=>'required|min:1' ,
            'plan'=>'required|exists:packages,id' ,
            ]);


        if($validator->fails()){
            return redirect()->back()->withErrors($validator);
        }else{

                    

            $package = Packages::find($request->plan); 
            $sponsor_id =Sponsortree::where('user_id',Auth::user()->id)->value('sponsor') ;
           


            $falg =false;
            /*  payment validation and update balance */

            if($request->steps_plan_payment == 'cheque'){
                $flag = true;
            }elseif($request->steps_plan_payment == 'ewallet'){
                $userbalance =Balance::where('user_id',Auth::user()->id)->value('balance');  
                if($userbalance < $package->amount){
                    return redirect()->back()->withErrors(["Ewallet doesn't have enough balance, "]); 
                }
                  $userbalance = Balance::where('user_id',Auth::user()->id)->decrement('balance',$package->amount);
                  UserDebit::create([
                        'user_id'=>Auth::user()->id,
                        'from_id'=>Auth::user()->id,
                        'debit_total'=>$package->amount,
                        'debit_amount'=>$package->amount,
                        'payment_type'=>'plan_purchase',
                        ]);
                $flag = true;
            }elseif($request->steps_plan_payment == 'voucher'){

                    $voucher_total = $package->amount ;
                    foreach ($request->voucher as $key => $vouchervalue) {
                         $voucher = Voucher::where('voucher_code', $vouchervalue)->first();
                         $voucher_total = $voucher_total - $voucher->balance_amount ;
                         if($voucher_total <=0 ){
                             $flag = true;
                         }
                    }

                    if($flag){
                         $package_amount = $package->amount ;
                            foreach ($request->voucher as $key => $vouchervalue) {
                                 $voucher = Voucher::where('voucher_code', $vouchervalue)->first();                                 
                                 if($package_amount > $voucher->balance_amount){
                                    $package_amount = $package_amount -  $voucher->balance_amount ;
                                    $used_amount =  $voucher->balance_amount;                                    
                                    $voucher->balance_amount = 0 ;
                                    $voucher->save();
                                 }else{
                                    // $package_amount =$voucher->balance_amount - $package_amount  ;
                                    echo $used_amount =  $voucher->balance_amount - $package_amount;          
                                    $voucher->balance_amount = $used_amount;
                                    $voucher->save();                                    
                                 }

                                 // die();

                                 VoucherHistory::create([
                                    'voucher_id'=>$voucher->voucher_code,
                                    'used_by'=>Auth::user()->id,
                                    'used_for'=> "pacakge purchase",
                                    'used_amount'=>$used_amount,
                                ])  ;                           
                            }
                    }  
            }   

            if($flag){

                  // dd($request->all());
                 PurchaseHistory::create([
                    'user_id'=>Auth::user()->id,
                    'purchase_user_id'=>Auth::user()->id,
                    'package_id'=>$package->id,
                    'count'=>$package->top_count,
                    'pv'=>$package->pv,
                    'total_amount'=>$package->amount,
                    'pay_by'=>$request->steps_plan_payment,
                    'rs_balance'=>$package->rs,
                    'sales_status'=>'yes',

                ]);

                  RsHistory::create([
                    'user_id'=>Auth::user()->id,                   
                    'from_id'=>Auth::user()->id,
                    'rs_credit'=>$package->rs,
                  ]);
                /*  Commissions calculation and point distributione */

                Tree_Table::getAllUpline(Auth::user()->id);
                PointTable::updatePoint($package->pv, Auth::user()->id);
                Transactions::sponsorcommission($sponsor_id,Auth::user()->id);
                // $sponsor_id
                LeadershipBonus::allocateCommission($sponsor_id,Sponsortree::where('user_id',$sponsor_id)->value('sponsor'),$package->pv/10);

            }      
        }

        Session::flash('flash_notification',array('message'=>"You have purchased the plan succesfully ",'level'=>'success'));
        return redirect()->back();







    }

    public function getProducts()
    {
         $title = trans('Product Purchase');
        $sub_title = trans('Product Purchase'); 
        $base = trans('Product Purchase');  
        $method = trans('Product Purchase');   

        $product_datas = Product::join('users','users.id','=','addproducts.user_id')->select('addproducts.id','addproducts.user_id','addproducts.productname','addproducts.productcode','addproducts.description','addproducts.amount','addproducts.pv','addproducts.quantity','addproducts.image','users.username')->get();    

        return view('app.user.product.productparchase',compact('title','base','method','sub_title','product_datas','productamount'));

    }
    public function create(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'username'  => 'required',
            'address'   => 'required',
            'country'        => 'required',
            'state'              => 'required',
            'city'              => 'required',
            'zipcode'              => 'required',
            'image'               => '',
            'productname'         => '',
            'pv'                  => '',
            'tax'                 => '',
            'amount'              => 'required',             
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
         

            ProductPurchas::create([
            'user_id'         => Auth::user()->id,
            'username'        =>$request->username,
            'address'         => $request->address,
            'country'         => $request->country,
            'state'           => $request->state,
            'city'            => $request->city,
            'zipcode'         => $request->zipcode,
            'image'           => '',
            'productname'     => $request->productname,
            'pv'              => $request->pv,
            'quantity'        => $request->quantity, 
            'amount'          => $request->amount,              
            ]);
            Session::flash('flash_notification', array('level' => 'success', 'message' => 'Product Purchase succesfully'));
            return redirect()->back();
        }
    }

      public function purchaseProducthistory()
{
     $title     = trans('Product Purchase List');
        $sub_title = trans('Product Purchase List');
        $base      = trans('Product Purchase List');
        $method    = trans('Product Purchase List');
        
       $purchasedatas = ProductPurchas::join('users','users.id','=','productspurchase.user_id')->select('productspurchase.id','users.username','productspurchase.address','productspurchase.country','productspurchase.state','productspurchase.zipcode','productspurchase.productname','productspurchase.quantity','productspurchase.pv','productspurchase.amount','productspurchase.approved_at','productspurchase.created_at')->get();
     
     return view('app.user.product.producthistory', compact('title', 'sub_title', 'base', 'method', 'purchasedatas'));

}


}
