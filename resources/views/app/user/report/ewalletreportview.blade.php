@extends('app.user.layouts.default')

{{-- Web site Title --}}
@section('title') {{{ $title }}} :: @parent @stop
@section('styles')
<style type="text/css">
.invoice>div:not(.invoice-footer) {
    margin-bottom: 43px;
}
.invoice-price .invoice-price-right {
    padding: 3px;
}
</style>
@endsection
{{-- Content --}}
@section('main')

 <div class="invoice">

     <div class="invoice-company">
         <span class="pull-right hidden-print">                   
             <a href="javascript:;" onclick="window.print()" class="btn btn-sm btn-success m-b-10"><i class="fa fa-print m-r-5"></i> Print</a>
         </span>
     </div>


    <div class="invoice-content">
    	<div class="table-responsive">
    		<table class="table table-invoice">
    			<thead>
    				<tr>
    					<th>{{trans('report.no')}}</th>
                        <th>{{trans('report.username')}}</th>
                        <th>{{trans('report.fullname')}}</th>                        
                        <th>{{trans('report.amount_type')}}</th>
                        <th>{{trans('report.debit')}}  </th>
                        <th>{{trans('report.credit')}}  </th>
                        <th>{{trans('report.created')}}</th>                        
                    </tr>
                </thead>
	            <tbody>
                     @php $credit = $debit= 0 @endphp
	            	@foreach($reportdata as $key=> $report)
	            	<tr>
	            		<td>{{$key +1 }}</td>	                   
                        <td>{{$report->username}}</td>
                        <td>{{$report->name}} {{$report->lastname}}</td>                      
                        <td>@if($report->payment_type != 'released')  {{  str_replace('_', ' ', $report->payment_type)}} @else  Payout Released  @endif</td>                        
                        <td>@if($report->payment_type == 'released')  {{  number_format( $report->payable_amount,2)}}    @endif</td>
                        <td> @if($report->payment_type != 'released')   {{  number_format( $report->payable_amount,2)}}  @endif</td>
                        <td>{{  date('d M Y H:i:s',strtotime($report->created_at)) }}</td>


                        @if($report->payment_type != 'released')

                               @php $credit += $report->payable_amount @endphp

                        @else
                             @php $debit += $report->payable_amount @endphp

                        @endif

					</tr>
	                @endforeach   
				</tbody>
        	</table>
        </div>
        	<div class="invoice-price">                       
            	<div class="invoice-price-right">
                	{{trans('report.total_debit')}}   {{  number_format( $debit,2)}} 
                   , {{trans('report.total_credit')}}   {{  number_format( $credit,2)}} 
                </div>
            </div>
    </div>
    <div class="invoice-footer text-muted">
    	<p class="text-center m-b-5">
        	{{trans('report.thank_you_for_business')}}
        </p>
    
    </div>
</div>             
@endsection



@section('scripts') @parent
    <script>
        $(document).ready(function() {
            App.init();                 
        });        
    </script>
    @endsection