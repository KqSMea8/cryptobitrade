<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Admin\AdminController;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Response;
use Session;
use Validator;
use Redirect;

class CategoryController extends AdminController
{
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function viewcategory()
    {

         $title     = trans('Categories');
        $sub_title = trans('Categories');
        $base      = trans('Categories');
        $method    = trans('Categories');

        $category_data = Category::select('id','name','description','order')->get();

     
     return view('app.admin.products.addcategory', compact('title', 'sub_title', 'base', 'method', 'category_data'));
    }
    
    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'description'   => 'required',
            'order'        => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {

            Category::create([
                'name'          => $request->name,
                'description'   => $request->description,
                'order'         => $request->order,
            ]);
            Session::flash('flash_notification', array('level' => 'success', 'message' => 'Category added succesfully'));
            return redirect()->back();
        }
    }




    public function categorydeleteconfirm(Request $request)
    {

        $requestid = $request->requestid;

        $res = Category::where('id', $requestid)->delete();
        Session::flash('flash_notification', array('level' => 'success', 'message' => trans('Category details')));
        return Redirect::action('Admin\CategoryController@viewcategory');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


 

    public function updateCategory(Request $request)
    {
        $requestid = $request->requestid;

        Category::where('id', $requestid)->update(array('name' => $request->name,'description' => $request->description,'order' => $request->order));
        Session::flash('flash_notification', array('level' => 'success', 'message' => trans('Category Updated')));
        return redirect()->back();

    }


}
