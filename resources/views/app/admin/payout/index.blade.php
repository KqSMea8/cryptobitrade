@extends('app.admin.layouts.default')

{{-- Web site Title --}}
@section('title')  @parent
@stop


@section('styles')
@endsection

{{-- Content --}}
@section('main')




<div class="panel panel-flat" >
                        <div class="panel-heading">
                           
                            <h4 class="panel-title">{{ trans('payout.payout') }}  </h4> 
                            <div class="col-sm-offset-10">
                                <a  class="btn btn-info" href="{{url('admin/payoutexport')}}">Download Excel  </a>
                            </div>
                        </div>
                        <div class="panel-body"> 




    <table id="data-table" class="table table-striped ">
                                    @if($count_requests > 0)
                                    <thead>
                                        <tr role="row">
                                            <th>{{ trans('payout.username') }} </th>
                                            <th >{{ trans('payout.user_balance') }}</th>
                                            <!-- <th style="background-color: #008A8A;">{{trans('ticket_config.request_date')}}</th> -->
                                            <!-- <th style="background-color: #008A8A;">{{trans('ticket_config.status')}}</th> -->
                                            <th >{{ trans('payout.approve') }}</th> 
                                          
                                        </tr>
                                    </thead>
                                    <tbody>    
                                    @foreach($vocherrquest as $request)
                                        <tr class="gradeC " role="row">
                                            <td class="sorting_1">{{$request->username}}</td>
                                            <td>{{round($request->balance,2)}}</td>
                                            <!-- <td>{{$request->created_at}}</td> -->
                                            <td>

                                                <form action="{{URL::to('admin/payoutconfirm')}}" method="post">
                                                    <!-- <input type="hidden" value="{{csrf_token() }}" name="_token">
                                                    
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control"value="{{$request->count}}" name="count">                                     
                                                    </div> -->
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="hidden" value="{{$request->id}}" name="requestid">
                                                    <input type="hidden" value="{{$request->user_id}}" name="user_id">
                                                    <div class="row">
                                                       
                                                        <div class="col-sm-3">
                                                             <input type="text" class="form-control" value="{{round($request->amount,2)}}" name="amount">
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <button type="submit" class="btn btn-success" value="{{round($request->amount,2)}}" name="submit">{{ trans('payout.confirm') }} </button>
                                                            
                                                        </div>
                                                        

                                                    </div>
                                                   
                                                    
                                                </form>
                                            </td>                                                                                    
                                        </tr>
                                    @endforeach  



                                    </tbody>
                                    @else
                                      {{ trans('ticket_config.no_payout_request_so_far') }} !!
                                    @endif
                                </table>


                             <div class="text-center">   {!! $vocherrquest->render() !!} </div>


                       
                </div>
            </div>
  
@stop

{{-- Scripts --}}
@section('scripts')
    @parent
    <script type="text/javascript"> 
     
             App.init();
             TableManageDefault.init();
       
    </script>
    
@stop
