
@extends('app.user.layouts.default') {{-- Web site Title --}} @section('title') {{{ $title }}} :: @parent @stop {{-- Content --}} @section('styles') @parent
<style type="text/css">
</style>
@endsection @section('main')
<!-- Basic datatable -->
<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">{{$title}}</h5>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a data-action="collapse"></a></li>
            </ul>
        </div>
    </div>
   
    <div class="panel-body">

      <table class="table table-condensed">

      <thead class="">

        <tr><th>
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
                   {{trans('Deal Amount')}}
                </th>
                <th>
                  {{trans('Date')}}
                </th>
                <th>
                  {{trans('Status')}}
                </th>
            </tr>
        </thead>
        @foreach($purchasedatas as $data)
        <tbody>
                  
            <tr class="">
              <td>
                {{$data->username}}
              </td>
                <td>
               {{$data->address}}
               <br> 
               Country : {{$data->country}}
               
               State : {{$data->state}} 
               <br>
               Zipcode : {{$data->zipcode}}
                </td>
                <td>
                   {{$data->productname}}
                </td>
                <td>
                   {{$data->quantity}}
                </td>
                <td>
                   {{$data->pv}}
                </td>
                    
                <td>
                 {{$data->amount}}
                
                </td>
                <td>
                  {{$data->created_at}}
                </td>
                 @if($data->approved_at == 1) 
                <td>
                  <button class="btn btn-primary">Approved</button>
                </td>
                @elseif($data->approved_at == 0)
                <td>
                  <button class="btn btn-danger">Pending</button>
                </td>
                 @endif
            </tr>
              
        </tbody>
         @endforeach
      
    </table>
</div>
</div>
@endsection