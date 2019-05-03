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
        <h4 class="panel-title">{{trans('Categories')}} </h4>
     </div>
     <div class="panel-body">
    <form action="{{url('admin/product/addcategory')}}" class="smart-wizard form-horizontal" method="post"  >
   <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label class="col-sm-2 control-label" for="name">
             {{trans('Category Name')}}: <span class="symbol required"></span>
            </label>
            <div class="col-sm-4">
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="description">
                 {{trans('Category Description')}}: <span class="symbol required"></span>
            </label>
                <div class="col-sm-4">
                <input type="text" id="description" name="description" class="form-control" required>
            </div>           
        </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="order">
                 {{trans('Category Order')}}: <span class="symbol required"></span>
            </label>
                <div class="col-sm-4">
                <input type="text" id="order" name="order" class="form-control" required>
            </div>           
        </div>
       
         
         <div class="col-md-12">
                    <div class="col-md-6 col-md-offset-4">
                        
                       
                        <button type="submit" class="btn btn-sm btn-success">
                        
                        {{ trans("Submit") }}
                        
                        </button>
                    </div>
                </div>


    </form>
</div>
    

 <div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">{{trans('Categories')}}</h5>
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
                                        {{trans('Category Name')}}
                                    </th>
                                    <th>
                                        {{trans('Category Description')}}
                                    </th>
                                    <th>
                                        {{trans('Category Order')}}
                                    </th>
                                    <th>
                                        {{trans('Action')}}
                                    </th>
                                </tr>
                            </thead>
                            @foreach($category_data as $data)
                            <tbody>
                              <tr>
                                <td>{{$data->name}}</td>
                                <td>{{$data->description}}</td>
                                <td>{{$data->order}}</td>
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
                                            <form action="{{URL::to('admin/categorydeleteconfirm')}}" method="post">
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
                                                        </input>
                                                    </input>
                                               
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
                                                <p>
                                                </p>
                                                <form action="{{URL::to('admin/updateCategory')}}" method="post">
                                                    <input name="_token" type="hidden" value="{{csrf_token() }}">
                                                        <input name="requestid" type="hidden" value="{{$data->id}}">
                                                            <div class="col-sm-12">
                                                                <div class="col-sm-4">
                                                                    {{trans('Name')}}
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <input class="form-control" min="" name="name" type="text" value="{{$data->name}}">
                                                                    </input>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    {{trans('Description')}}
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <input class="form-control" min="" name="description" type="text" value="{{$data->description}}">
                                                                    </input>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    {{trans('Order')}}
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <input class="form-control" min="" name="order" type="text" value="{{$data->order}}">
                                                                    </input>
                                                                </div>
                                                            </div>
                                                            <button class="btn btn-success" name="submit" type="submit">
                                                                {{trans('update')}}
                                                            </button>
                                                        </input>
                                                    </input>
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
