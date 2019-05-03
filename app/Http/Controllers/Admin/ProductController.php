<?php

namespace App\Http\Controllers\Admin;

use App\Commission;
use App\Http\Controllers\Admin\AdminController;
use App\PackageHistory;
use App\Packages;
use App\PointTable;
use App\Products;
use App\Product;
use App\Category;
use App\PurchaseHistory;
use App\ProductPurchas;
use App\Sponsortree;
use App\Tree_Table;
use App\User;
use Auth;
use Input;
use DB;
use Illuminate\Http\Request;
use Response;
use Session;

// use App\Http\Controllers\Admin\DB;

use Validator;

class ProductController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $title     = trans('products.title');
        $sub_title = trans('products.sub_title');
        $base      = trans('products.base');
        $method    = trans('products.sub_title');
        $settings  = Products::select('products.*')->get();
        $packages  = Packages::all();
        $userss    = User::getUserDetails(Auth::id());
        $user      = $userss[0];
        return view('app.admin.products.index', compact('title', 'settings', 'packages', 'user', 'sub_title', 'base', 'method'));
    }

    public function update(Request $request)
    {
        $package = Products::find($request->pk);

        $variable = $request->name;

        $package->$variable = $request->value;

        if ($package->save()) {
            return Response::json(array('status' => 1));
        } else {
            return Response::json(array('status' => 0));
        }

    }
    public function purchasehistory()
    {
        $title     = trans('products.product_purchase_history');
        $sub_title = trans('products.product_purchase_history');
        $base      = trans('products.product_purchase_history');
        $method    = trans('products.product_purchase_history');

        $data = PurchaseHistory::join('products', 'products.id', '=', 'purchase_history.product_id')
            ->join('users', 'users.id', '=', 'purchase_history.user_id')
            ->select('products.product', 'users.username', 'count', 'products.member_amount', 'total_amount', 'purchase_history.status', 'purchase_history.pay_by', 'purchase_history.created_at', 'purchase_history.id', 'purchase_history.BV')
            ->where('status', 'pending')
            ->paginate(10);

        return view('app.admin.products.purchase-history', compact('user', 'title', 'data', 'rules', 'base', 'method', 'sub_title'));

    }
    public function purchasehistoryshow(Request $request)
    {
        $user_id = User::where('username', $request->user)->pluck('id');

        $data = PurchaseHistory::join('products', 'products.id', '=', 'purchase_history.product_id')
            ->join('users', 'users.id', '=', 'purchase_history.user_id')
            ->where('purchase_history.user_id', $user_id)
            ->select('products.product', 'users.username', 'count', 'products.member_amount', 'total_amount', 'purchase_history.created_at', 'purchase_history.status', 'purchase_history.pay_by', 'purchase_history.BV')
            ->orderBy('purchase_history.status', 'DESC')
            ->paginate(10);

        $title     = trans('products.product_purchase_history');
        $sub_title = trans('products.product_purchase_history');
        $user      = User::find($user_id);
        $rules     = ['count' => 'required|min:1'];
        $base      = trans('products.product_purchase_history');
        $method    = trans('products.product_purchase_history');
        $userss    = User::getUserDetails(Auth::id());
        $user      = $userss[0];
        return view('app.admin.products.purchase-history', compact('user', 'title', 'data', 'rules', 'base', 'method', 'sub_title'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [

            // 'redemption_pv'=>'required',
            'name'             => 'required',
            'size'             => 'required',
            'member_prize'     => 'required',
            'non_member_prize' => 'required',
            'pv'               => 'required',
            // 'packages'=>'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {

            Products::create([
                'product'          => $request->name,
                'size'             => $request->size,
                'member_amount'    => $request->member_prize,
                'nonmember_amount' => $request->non_member_prize,
                'pv'               => $request->pv,
                'redeption_pv'     => $request->redemption_pv,
                'package'          => $request->packages,
            ]);
            Session::flash('flash_notification', array('level' => 'success', 'message' => 'Product added succesfully'));
            return redirect()->back();
        }
    }

    public function destroy(Request $request, $id)
    {

        $product = Products::find($id);

        $product->delete();

        Session::flash('flash_notification', array('level' => 'success', 'message' => 'Product deleted succesfully'));

        return redirect()->back();

    }

    public function delete_order(Request $request, $id)
    {
        $product = PurchaseHistory::find($id);

        $product->delete();

        Session::flash('flash_notification', array('level' => 'success', 'message' => 'Purchase deleted succesfully'));

        return redirect()->back();

    }

    public function confirm_order(Request $request, $id)
    {
        $order = PurchaseHistory::find($id);

        $order->status = 'approved';

        $order->save();

        try {
            DB::beginTransaction();

            $user_status         = Sponsortree::where('user_id', '=', $order->user_id)->pluck('type');
            $monthly_maintenance = User::where('id', '=', $order->user_id)->pluck('monthly_maintenance');
            $total_bv            = PurchaseHistory::where('user_id', '=', $order->user_id)
                ->where('status', '=', 'approved')
                ->sum('BV');
            $max_package         = Packages::where('bv', '<=', $total_bv)->max('id');
            $user_package        = User::where('id', $order->user_id)->pluck('package');
            $max_package_details = Packages::find($max_package);
            $last_purchase_pv    = PurchaseHistory::where('status', '=', 'approved')->where('user_id', '=', $order->user_id)->orderBy('id', 'DESC')->take(1)->pluck('BV');

            echo "$user_status == 'no' && $monthly_maintenance == 1";

            if ($user_status == 'no' && $monthly_maintenance == 1) {
                $user_package_details = Packages::find($user_package);

                if ($max_package == $user_package and $total_bv >= $user_package_details->bv) {

                    Sponsortree::where('user_id', '=', $order->user_id)->update(['type' => 'yes']);
                    Tree_Table::where('user_id', '=', $order->user_id)->update(['type' => 'yes']);

                } elseif ($max_package > $user_package) {

                    if ($last_purchase_pv >= $max_package_details->last_purchase_bv) {
                        Sponsortree::where('user_id', '=', $order->user_id)->update(['type' => 'yes']);
                        Tree_Table::where('user_id', '=', $order->user_id)->update(['type' => 'yes']);

                        User::where('id', $order->user_id)->update(['package' => $max_package]);

                        PackageHistory::create(['user_id' => $order->user_id, 'package_id' => $user_package, 'new_package_id' => $max_package]);
                    }

                }

            } elseif ($user_status == 'no' && $monthly_maintenance == 0) {

                echo $this_month_total = PurchaseHistory::where('user_id', '=', $order->user_id)
                    ->where('status', '=', 'approved')
                    ->whereMonth('created_at', '=', date('m'))
                    ->whereYear('created_at', '=', date('Y'))
                    ->sum('BV');

                if ($this_month_total >= 100) {
                    Sponsortree::where('user_id', '=', $order->user_id)->update(['type' => 'yes']);
                    Tree_Table::where('user_id', '=', $order->user_id)->update(['type' => 'yes']);
                    User::where('id', $order->user_id)->update(['monthly_maintenance' => 1]);

                }

                // die();
                if ($max_package > $user_package) {
                    if ($last_purchase_pv >= $max_package_details->last_purchase_bv) {
                        User::where('id', $order->user_id)->update(['package' => $max_package]);
                        PackageHistory::create([
                            'user_id'        => $order->user_id,
                            'package_id'     => $user_package,
                            'new_package_id' => $max_package,
                        ]);
                    }

                }

            } elseif ($user_status == 'yes') {

                if ($max_package > $user_package) {
                    if ($last_purchase_pv >= $max_package_details->last_purchase_bv) {
                        User::where('id', $order->user_id)->update(['package' => $max_package]);
                        PackageHistory::create([
                            'user_id'        => $order->user_id,
                            'package_id'     => $user_package,
                            'new_package_id' => $max_package,
                        ]);
                    }

                }
            }

            /**
            directSponsorBonus
             */
            $sponsor_id = Sponsortree::getSponsorID($order->user_id);

            $direct_sponsor_bonus = Commission::directSponsorBonus($sponsor_id, $order->user_id, $order->BV);

/**
Binary point update
 */

            $user_leg     = Tree_Table::getUserLeg($order->user_id);
            $placement_id = Tree_Table::getFatherID($order->user_id);
            Tree_Table::getAllUpline($placement_id, $user_leg);
            PointTable::updatePoint($order->BV, $order->user_id);

        } catch (Exception $e) {
            DB::rollBack();
            echo 'Caught exception: ', $e->getMessage(), "\n";
        } finally {
            DB::commit();
        }

        Session::flash('flash_notification', array('level' => 'success', 'message' => 'Purchase approved succesfully'));

        return redirect()->back();

    }





    // 



    public function approve($id){

        ProductPurchas::where('id',$id)->update(['approved_at' => 1]);
        Session::flash('flash_notification', array('level' => 'success', 'message' => 'Product Purchase upproved successfully'));
        return redirect()->back()->withMessage('User approved successfully');
    }

    public function viewProducts()
    {
        $title     = trans('Products');
        $sub_title = trans('Products');
        $base      = trans('Products');
        $method    = trans('Products');

        $product_data = Product::join('categories','categories.id','=','addproducts.category')->select('addproducts.id','addproducts.user_id','addproducts.productcode','addproducts.productname','addproducts.description','addproducts.amount','addproducts.pv','addproducts.quantity','addproducts.image','categories.name','addproducts.category')->get();
         $categories = Category::select('id','name')->get();   
     
     return view('app.admin.products.addproducts', compact('title', 'sub_title', 'base', 'method', 'product_data','categories'));
    }
    
    public function purchaseProducthistory()
    {
        $title     = trans('Product Purchase List');
        $sub_title = trans('Product Purchase List');
        $base      = trans('Product Purchase List');
        $method    = trans('Product Purchase List');

       $purchasedata = ProductPurchas::join('users','users.id','productspurchase.user_id')->where('approved_at',0)->select('productspurchase.id','users.username','productspurchase.address','productspurchase.country','productspurchase.state','productspurchase.zipcode','productspurchase.productname','productspurchase.quantity','productspurchase.pv','productspurchase.amount','productspurchase.created_at')->get();

     
     return view('app.admin.products.productpurchasehistory', compact('title', 'sub_title', 'base', 'method', 'purchasedata'));

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function productscreate(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'user_id'             => '', 
            'productcode'         => 'required',
            'productname'          => 'required',
            'description'   => 'required',
            'category'      => '',
            'amount'        => 'required',
            'pv'              => 'required',
            'quantity'              => 'required',
            'image'              => '',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {

            $destinationPath = public_path() . '/assets/uploads';
            $extension       = Input::file('file')->getClientOriginalExtension();
            $fileName        = rand(000011111, 99999999999) . '.' . $extension;
            Input::file('file')->move($destinationPath, $fileName);
            
            

            Product::create([
                
                'user_id'              => Auth::user()->id,  
                'productcode'          => $request->productcode,
                'productname'          => $request->productname,
                'description'   => $request->description,
                'category'       => $request->category,
                'amount'         => $request->amount,
                'pv'         => $request->pv,
                'quantity'         => $request->quantity,
                'image'         =>  $fileName,
            ]);
            Session::flash('flash_notification', array('level' => 'success', 'message' => 'Product added succesfully'));
            return redirect()->back();
        }
    }



    public function productdeleteconfirm(Request $request)
    {

        $requestid = $request->requestid;

        $res = Product::where('id', $requestid)->delete();
        Session::flash('flash_notification', array('level' => 'success', 'message' => trans('Product deleted Success')));
        return Redirect::action('Admin\ProdctsController@viewProducts');
    }
    



    public function purchasedeleteconfirm(Request $request)
    {

        $requestid = $request->requestid;

        $res = ProductPurchas::where('id', $requestid)->delete();
        Session::flash('flash_notification', array('level' => 'success', 'message' => trans('Purchase list deleted')));
        return redirect()->back();
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    


    public function updateProduct(Request $request)
    {
         
         $item = Product::find($request->requestid);

         $item->productname = $request->productname;
         $item->description = $request->description;
         $item->amount      = $request->amount;
         $item->pv      = $request->pv;
         $item->quantity      = $request->quantity;
 
         
         if($request->hasFile('file')){
            $destinationPath = base_path() . "/assets/uploads";
            $extension       = Input::file('file')->getClientOriginalExtension();
            $fileName        = rand(11111, 99999) . '.' . $extension;
            Input::file('file')->move($destinationPath, $fileName);
            $item ->image = $filename;
        }
        $item->save();          

        Session::flash('flash_notification', array('level' => 'success', 'message' => trans('Product Updated')));
        return redirect()->back();
    }

}
