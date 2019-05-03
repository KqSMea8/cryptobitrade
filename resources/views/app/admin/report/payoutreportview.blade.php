@extends('app.admin.layouts.default')

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
             <a href="javascript:;" onclick="window.print()" class="btn btn-sm btn-success m-b-10"><i class="fa fa-print m-r-5"></i> {{trans('report.print')}}</a>
         </span>
     </div>
     
     <!-- <div class="invoice-header">
     	<div class="invoice-from">
     		<address class="m-t-5 m-b-5">
     			<strong>Cloud MLM software</strong>
                            City, Zip Code<br>
                            Phone: (123) 456-7890<br>
                            email: info@cloudmlmsoftware.com
                </address>
        </div>
        <div class="invoice-date">
        	<div class="date m-t-5">{{ date('F d, Y') }}</div>
        	<div class="invoice-detail">
        		Payout released report
        	</div>
        </div>
    </div> -->
    <div class="invoice-content">
    	<div class="table-responsive">
    		<table class="table table-invoice">
    			<thead>
    				<tr>
    					<th>{{trans('report.no')}}</th>
                        <th>{{trans('report.userid')}}</th>
    					<th>{{trans('report.username')}}</th>
						<th>{{trans('report.fullname')}}</th>                        
                        <th>{{trans('report.bank_details')}}</th>
                        
                        <th>{{trans('report.amount_released')}}</th>
                        <th>{{trans('report.amount_in_user_currency')}}</th>
                        <!-- <th>{{trans('report.amount_in_user_currency')}}</th> -->
                        <!-- <th>Remarks </th> -->
                        <th>{{trans('report.date')}} </th>
                        
                    </tr>
                </thead>
	            <tbody>
	            	@foreach($reportdata as $key=> $report)
	            	<tr>
	            		<td>{{$key +1 }}</td>	                   
                        <td>{{$report->userid}}</td>
	                    <td>{{$report->username}}</td>
	                    <td>{{$report->name}} {{$report->lastname}}</td>	                   
                        <td>
                          <span style="font-weight: 700;">  {{trans('report.acc_name')}} : </span><br> {{$report->account_holder_name}} <br>
                          <span style="font-weight: 700;">  {{trans('report.acc_no')}}:</span><br> {{$report->account_number}} <br>
                          <span style="font-weight: 700;">  {{trans('report.bank_name')}}:</span><br> {{$report->sort_code}} <br>
                            </td>
                        <td>$ {{ number_format($report->amount,2) }}</td>
	                    <td>{{$report->symbol_left}} {{ number_format($report->value * $report->amount,2) }} {{$report->symbol_right}}</td>
	                    <td>{{date('d-M-Y H:i:s',strtotime($report->created_at))}}</td>
					</tr>
	                @endforeach   
				</tbody>
        	</table>
        </div>
        	<div class="invoice-price">                       
            	<div class="invoice-price-right col-sm-offset-6">
                	{{trans('report.total_amount')}}  $ {{number_format($totalamount,2)}}
                </div>
            </div>
    </div>
    <div class="invoice-footer text-muted">
    	<p class="text-center m-b-5">
        	{{trans('report.thank_you_for_your_business')}}
        </p>
       <!--  <p class="text-center">
        	<span class="m-r-10"><i class="fa fa-globe"></i> cloudmlmsoftware.com</span>
            <span class="m-r-10"><i class="fa fa-phone"></i> T:016-18192302</span>
            <span class="m-r-10"><i class="fa fa-envelope"></i> info@cloudmlmsoftware.com</span>
        </p> -->
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