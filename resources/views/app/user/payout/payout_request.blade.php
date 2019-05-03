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
  @include('utils.errors.list') 

        {!! Form::open(array('url' => 'user/request','enctype'=>'multipart/form-data'),$rules) !!}

          <div class="form-group">

                <label class="control-label label label-primary">

        {{trans('payout.balance_amount')}} :$ {{$user_balance}}

                </label>

            </div>

            <div class="row">

              <div class="col-sm-4">

               {!!  Form::label('req_amount', trans("user/payout.request_amount") ,array('class'=>'control-label'))  !!}  

            {!!  Form::text('req_amount',$user_balance, array('class'=>'form-control','required'=>'true' ))  !!}

              </div>

             

              <div class="col-sm-4">

              {!! Form::submit(trans('payout.update'),array('class'=>'btn btn-success','style'=>'MARGIN: 20PX ;')

                  ) !!}

         

              </div>

            </div>

            

        {!! Form::close() !!}


            </div>            
  </div>
                  
@stop
