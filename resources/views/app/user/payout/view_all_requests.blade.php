
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

				<tr>

					<th>{{trans('payout.sl_no')}}</th>

					<th>{{trans('payout.amount')}}</th>

					<th>{{trans('payout.status')}}</th>

					<th>{{trans('payout.date')}}</th>

				</tr>

			</thead>

			<tbody>

				<?php $i = 1; ?>

				@foreach ($requests as $request)

					<tr class="text-success text-bold">

						<td>{!!$i++!!}</td>

						<td>{{ $USER_CURRENCY->symbol_left}} {{ $request->amount}} </td>
						<td>{!!$request->status!!}</td>
						  <td>{{ date('d M Y',strtotime($request->created_at))}}</td>
					</tr>

				@endforeach

			</tbody>

		</table>

    </div>        
  </div>
                  
@stop

 