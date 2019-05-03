@extends('app.admin.layouts.default')

{{-- Web site Title --}}
@section('title') {{{ $title }}} :: @parent @stop
@section('styles')
@parent

<link rel="stylesheet" href="{{asset('assets/globals/css/autosuggest.css')}}" type="text/css" media="screen" charset="utf-8" />



@endsection
{{-- Content --}}
@section('main')
    
         @include('flash::message')

      @include('utils.errors.list')

<div class="panel panel-flat" >
	<div class="panel-heading">
    	<div class="panel-heading-btn">
			
            
            
            
        </div>
        <h4 class="panel-title">{{trans('Products')}} </h4>
     </div>
     <div class="panel-body">
    <form action="{{url('admin/product/addproducts')}}" class="smart-wizard form-horizontal" method="post" enctype="multipart/form-data" >
   <input type="hidden" name="_token" value="{{ csrf_token() }}">
         <div class="form-group">
            <label class="col-sm-2 control-label" for="productcode">
             {{trans('Product Code')}}: <span class="symbol required"></span>
            </label>
            <div class="col-sm-4">
                <input type="text" id="productcode" name="productcode" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="productname">
             {{trans('Product Name')}}: <span class="symbol required"></span>
            </label>
            <div class="col-sm-4">
                <input type="text" id="productname" name="productname" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="description">
                 {{trans('Product Description')}}: <span class="symbol required"></span>
            </label>
                <div class="col-sm-4">
                <input type="text" id="description" name="description" class="form-control" required>
            </div>           
        </div>
         <div class="form-group">
            <label class="col-sm-2 control-label" for="category">
                 {{trans('Category')}}: <span class="symbol required"></span>
            </label>
                <div class="col-sm-4">
                <select name="category" class="form-control">
                    @foreach($categories as $category)
                    <option value="{{$category->id}}" >{{$category->name}}</option>
                    @endforeach
                </select>
            </div>           
        </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="amount">
                 {{trans('Amount')}}: <span class="symbol required"></span>
            </label>
                <div class="col-sm-4">
                <input type="text" id="amount" name="amount" class="form-control" required>
            </div>           
        </div>
         <div class="form-group">
            <label class="col-sm-2 control-label" for="pv">
                 {{trans('pv')}}: <span class="symbol required"></span>
            </label>
                <div class="col-sm-4">
                <input type="text" id="pv" name="pv" class="form-control" required>
            </div>           
        </div>
         <div class="form-group">
            <label class="col-sm-2 control-label" for="quantity">
                 {{trans('Quantity')}}: <span class="symbol required"></span>
            </label>
                <div class="col-sm-4">
                <input type="text" id="quantity" name="quantity" class="form-control" required>
            </div>           
        </div>
         <div class="form-group">
            <label class="col-sm-2 control-label" for="image">
                 {{trans('Image')}}: <span class="symbol required"></span>
            </label>
                <div class="col-sm-4">
                    <input type="file" name="file" class="file-input" data-show-caption="false" data-show-upload="false" data-browse-class="btn btn-primary" data-remove-class="btn btn-light" data-fouc>
            </div>           
        </div>
       
       
       
       
         
         <div class="col-md-12">
                    <div class="col-md-6 col-md-offset-4">
                        
                       
                        <button type="submit" class="btn btn-sm btn-success">
                        
                        {{ trans("Save Product") }}
                        
                        </button>
                    </div>
                </div>


    </form>
</div>
    

 <div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">{{trans('Products')}}</h5>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a data-action="collapse"></a></li>
            </ul>
        </div>
    </div>
    <table class="table datatable-basic table-striped table-hover" id="" >
                            <thead>
                                <tr>
                                    <th>
                                        {{trans('Product Code')}}
                                    </th>
                                    <th>
                                        {{trans('Product Name')}}
                                    </th>
                                    <th>
                                        {{trans('Product Description')}}
                                    </th>
                                    <th>
                                        {{trans('Category')}}
                                    </th>
                                    <th>
                                        {{trans('Amount')}}
                                    </th>
                                    <th>
                                        {{trans('pv')}}
                                    </th>
                                    <th>
                                        {{trans('Quantity')}}
                                    </th>
                                    <th>
                                        {{trans('Image')}}
                                    </th>
                                    <th>
                                        {{trans('Action')}}
                                    </th>
                                </tr>
                            </thead>
                            @foreach($product_data as $data)
                            <tbody>
                              <tr>
                                <td>{{$data->productcode}}</td>
                                <td>{{$data->productname}}</td>
                                <td>{{$data->description}}</td>
                                <td>{{$data->category}}</td>
                                <td>{{$data->amount}}</td>
                                <td>{{$data->pv}}</td>
                                <td>{{$data->quantity}}</td>
                                <td>{{$data->image}}</td>
                                     <td>
                                <button class="btn btn-danger" data-target="#myModal2{{$data->id}}" data-toggle="modal" type="button">
                                    {{trans('delete')}}
                                </button>
                                <div class="modal fade" id="myModal2{{$data->id}}" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header ">
                                                <button class="close" data-dismiss="modal" type="button">
                                                    ×
                                                </button>
                                                <h4 class="modal-title text-danger">
                                                    {{trans('delete')}}
                                                </h4>
                                            </div>
                                            <form action="{{URL::to('admin/productdeleteconfirm')}}" method="post">
                                            <div class="modal-body">
                                                    <input name="_token" type="hidden" value="{{csrf_token() }}">
                                                        <input name="requestid" type="hidden" value="{{$data->id}}">
                                                            <center>
                                                                <h4>
                                                                    {{trans('all.Are_You_Sure_You_Want_To_Delete')}}
                                                                    <h4>
                                                                    </h4>
                                                                </h4>
                                                            </center>
                                                        
                                                    
                                               
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-success" name="submit" type="submit">
                                                    {{trans('all.delete')}}
                                                </button>
                                                <button class="btn btn-danger" data-dismiss="modal" type="button">
                                                    {{trans('all.cancel')}}
                                                </button>
                                            </div>
                                             </form>
                                        </div>
                                    </div>
                                </div>

                                 <button class="btn btn-info" data-target="#myModal{{$data->id}}" data-toggle="modal" type="button">
                                    {{trans('all.update')}}
                                </button>
                                <div class="modal fade" id="myModal{{$data->id}}" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button class="close" data-dismiss="modal" type="button">
                                                    ×
                                                </button>
                                                <h4 class="modal-title">
                                                    {{trans('all.edit')}}
                                                </h4>
                                            </div>
                                            <div class="modal-body"> 
                                                <form action="{{URL::to('admin/updateProduct')}}" method="post">
                                                    <input name="_token" type="hidden" value="{{csrf_token() }}">
                                                        <input name="requestid" type="hidden" value="{{$data->id}}">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <div class="col-sm-4">
                                                                    {{trans('Product Name')}}
                                                                    </div>
                                                                    <div class="col-sm-8">
                                                                        <input class="form-control" min="" name="productname" type="text" value="{{$data->productname}}">
                                                                        
                                                                    </div>
                                                                </div>
                                                                 
                                                                 <div class="form-group">
                                                                <div class="col-sm-4">
                                                                    {{trans('Product Description')}}
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <input class="form-control" min="" name="description" type="text" value="{{$data->description}}">
                                                                    
                                                                </div>
                                                                </div>
                                                                 <div class="form-group">
                                                                <div class="col-sm-4">
                                                                    {{trans('Amount')}}
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <input class="form-control" min="" name="amount" type="text" value="{{$data->amount}}">
                                                                    
                                                                </div>
                                                                </div>
                                                                 <div class="form-group">
                                                                <div class="col-sm-4">
                                                                    {{trans('PV')}}
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <input class="form-control" min="" name="pv" type="text" value="{{$data->pv}}">
                                                                    
                                                                </div>
                                                                </div>
                                                                 <div class="form-group">
                                                                <div class="col-sm-4">
                                                                    {{trans('Quantity')}}
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <input class="form-control" min="" name="quantity" type="text" value="{{$data->quantity}}">
                                                                    
                                                                </div>
                                                                </div>
                                                               
                                                                <div class="col-sm-4">
                                                                    {{trans('Image')}}
                                                                </div>
                                                                
                                                                
            <label class="col-sm-2 control-label" for="image">
                 {{trans('Image')}}: <span class="symbol required"></span>
            </label>
                <div class="col-sm-4">
                    <input type="file" name="image" class="file-input" data-show-caption="false" data-show-upload="false" data-browse-class="btn btn-primary" data-remove-class="btn btn-light" data-fouc>
            </div>           
        
                                                            </div>
                                                            <button class="btn btn-success" name="submit" type="submit">
                                                                {{trans('update')}}
                                                            </button>
                                                        
                                                    
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-default" data-dismiss="modal" type="button">
                                                    {{trans('all.close')}}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                              </tr>
                            </tbody>
                            @endforeach
                        </table>

	</div>
</div>

              

           

            
@endsection
