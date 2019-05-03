@extends('app.admin.layouts.default') {{-- Web site Title --}} @section('title') {{{ $title }}} :: @parent @stop {{-- Content --}} @section('styles') @parent
<style type="text/css">
</style>
@endsection @section('main')
<!-- Basic datatable -->
<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">{{trans('Purchase History')}}</h5>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a data-action="collapse"></a></li>
            </ul>
        </div>
    </div>
    <table class="table datatable-basic table-striped table-hover" id="" >
                      
       
        <thead class="">
            <tr class="bg-indigo-600">
                
                 <th>
                  {{trans('Username')}}
                 </th>
                <th>
                    {{trans('Address')}}
                </th>
                <th>
                  {{trans('Product Name')}}
                </th>
                <th>
                    {{trans('Quantity')}}
                </th>
                <th>
                   {{trans('PV')}}
                </th>
                <th>
                   {{trans('Request Amount')}}
                </th>
                <th>
                  {{trans('Date')}}
                </th>
                <th>
                  {{trans('Action')}}
                </th>
            </tr>
        </thead>
        @foreach($purchasedata as $datas)
        <tbody>
         
           
            <tr class="">
              <td>
                {{$datas->username}}
              </td>
                <td>
               {{$datas->address}}
               <br> 
               Country : {{$datas->country}}
               
               State : {{$datas->state}} 
               <br>
               Zipcode : {{$datas->zipcode}}
                </td>
                <td>
                   {{$datas->productname}}
                </td>
                <td>
                   {{$datas->quantity}}
                </td>
                <td>
                   {{$datas->pv}}
                </td>
       
                <td>
                 {{$datas->amount}}
                </td>
                <td>
                  {{$datas->created_at}}
                </td>
              
                  <td>

                           <a href="{{ url('admin/product/approve/'.$datas->id) }}"
                                           class="btn btn-primary btn-sm">Approve</a> 
                                           <button class="btn btn-danger" data-target="#myModal2{{$datas->id}}" data-toggle="modal" type="button">
                                    {{trans('delete')}}
                                </button>
                                <div class="modal fade" id="myModal2{{$datas->id}}" role="dialog">
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
                                            <form action="{{URL::to('admin/purchasedeleteconfirm')}}" method="post">
                                            <div class="modal-body">
                                                    <input name="_token" type="hidden" value="{{csrf_token() }}">
                                                        <input name="requestid" type="hidden" value="{{$datas->id}}">
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
                            
                              </td>
            </tr>
              
        </tbody>
         @endforeach
      
    </table>
</div>
@endsection