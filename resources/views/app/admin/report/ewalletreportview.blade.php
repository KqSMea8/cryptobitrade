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
     
    <!--  <div class="invoice-header">
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
        		E-wallet report from August 19, 2015 to  October 19, 2015
        	</div>
        </div>
    </div>
     -->
    <div class="invoice-content">
    	<div class="table-responsive">
    		<table class="table table-invoice">
    			<thead>
    				<tr>
    					<th>{{trans('report.no')}}</th>      
                        <th>{{trans('report.username')}}</th>                       
                        <th>{{trans('report.bonus_type')}}</th>                        
                        <th>{{trans('report.credit')}} </th>
                        <th>{{trans('report.date')}} </th>                     
                    </tr>
                </thead>
	            <tbody>
	            	@foreach($reportdata as $key=> $report)
	            	<tr>
	            		<td>{{$key +1 }}</td>	
                        <td>{{$report->username}}</td>
	                                    
                        <td>@if($report->payment_type == 'released') Payout Released @else  {{  str_replace('_', ' ', $report->payment_type)}} @endif</td>
                        <td>$ {{ number_format($report->total_amount,2)}}</td>
                        <td>{{ date('d M Y H:i:s',strtotime($report->created_at))}}</td>
					</tr>
	                @endforeach   
				</tbody>
        	</table>
        </div>
        	<div class="invoice-price">                       
            	<div class="invoice-price-right">
                  
                	{{trans('report.total_credit')}} {{ round($totalamount,2)}} 
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

    <!-- <script type="text/javascript">
        var oTable;
        $(document).ready(function () {
            oTable = $('#table').DataTable({
                "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                "sPaginationType": "bootstrap",
                "processing": true,
                "serverSide": true,
                 "ordering": false,
                "ajax": "{{ URL::to('/admin/ewalletreport/') }}",
                "fnDrawCallback": function (oSettings) {
                    $(".iframe").colorbox({
                        iframe: true,
                        width: "80%",
                        height: "80%",
                        onClosed: function () {
                            oTable.ajax.reload();
                        }
                    });
                }
            });
             
              



             App.init();
        });
var arra;
$.get( 
        'getAllUsers',
         { sponsor: 'ghjgj' },
            function(response) {
                    if (response) {
                        month_users=response;
arra = month_users.split(",");
$("#username").autocomplete({source:arra});
}
});
</script>-->
    @endsection