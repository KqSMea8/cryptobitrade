@extends('app.user.layouts.default')
{{-- Web site Title --}}
@section('title') {{{ $title }}} :: @parent @stop
@section('main')
 @include('utils.errors.list')
 @include('utils.vendor.flash.message') 
<div class="content">
  <div class="panel panel-white">

    <form class="wizard-form steps-planpurchase" action="{{url('user/purchase-plan')}}" method="post"  data-parsley-validate="true">
        {!! csrf_field() !!}

            <h6>Choose pacakge </h6>
            <fieldset>
               <div class="col-md-12">                
                    <div class="d-flex align-items-start flex-column flex-md-row">   
                      <div class="row"> 
                        @forelse($products as $item)
                        <div class="col-xl-3 col-sm-4">
                          <label class="form-check-label" style="width: 100%;"> 
                            <div class="panel panel-default text-center">
                              <div class="panel-heading">
                                <div class="ribbon-container {{$item->package}} ">
                                  <div class="ribbon bg-indigo-400">Selected</div>
                                </div>
                                <h1>{{$item->package}}</h1>
                              </div>
                              <div class="panel-body">
                                <p><strong>{{$item->pv}}</strong> PV</p>                                     
                                <p><strong>Endless</strong> Amet</p>
                              </div>
                              <div class="panel-footer">
                                <h3>{{$item->amount}}</h3>
                                <h4>one time fee</h4>                                  
                                <div class="form-check">
                                  <div class="uniform-choice border-indigo-600 text-indigo-800"><span class="checked">
                                    <input type="radio"  required="required"    name="plan" badge-class="{{$item->package}}" class="form-check-input-styled-custom" data-fouc="" data-parsley-group="block-0" value="{{$item->id}}" plan-amount="{{$item->amount}}">
                                    <span class="checkmark"></span>
                                  </div>
                                </div>
                              </div>
                            </div> 
                          </label>
                        </div>
                        @empty
                      @endforelse
                    </div>  
                  </div> 
                </div>              
              </fieldset>
            <h6>Choose payment </h6>
            <fieldset> 

              <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-highlight nav-justified">
                  <li class="nav-item active"><a href="#steps-planpurchase-tab1" class="nav-link  steps-plan-payment active " data-toggle="tab" data-payment='cheque' >Cheque</a></li>
                  <li class="nav-item"><a href="#steps-planpurchase-tab2" class="nav-link steps-plan-payment" data-toggle="tab" data-payment='ewallet'>Ewallet</a></li>
                  <li class="nav-item"><a href="#steps-planpurchase-tab3" class="nav-link steps-plan-payment" data-toggle="tab" data-payment='voucher'>Voucher</a></li>
                  <li class="nav-item"> <a href="#steps-planpurchase-tab4" class="nav-link steps-plan-payment" data-toggle="tab" data-payment='paypal'>PayPal</a>
                  <li class="nav-item"> <a href="#steps-planpurchase-tab5" class="nav-link steps-plan-payment" data-toggle="tab" data-payment='bitcoin'>Bitcoin</a>
                  <li class="nav-item"> <a href="#steps-planpurchase-tab6" class="nav-link steps-plan-payment" data-toggle="tab" data-payment='cc'>Credit Card</a>
                  </li>
                </ul> 

                <div class="tab-content">
                  <div class="tab-pane active  " id="steps-planpurchase-tab1">
                    <input type="hidden" name="steps_plan_payment" value="cheque" data-parsley-group="block-1">
                     Send your payment checque to the company  <code>order will process after payment received  </code>, Thanks.
                  </div>

                  <div class="tab-pane fade" id="steps-planpurchase-tab2">
                     The amount will be deducted from your Ewallet , pease confirm to proceed
                  </div>

                  <div class="tab-pane fade" id="steps-planpurchase-tab3">
                    <div class="table-responsive div-vouher-payment">                      
                      <table class="table table-dark bg-slate-600 table-vouher-payment">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Voucher code</th>
                            <th>Amount  used</th>
                            <th>Voucher balance</th>
                            <th>Remaining</th>
                            <th>Validate Voucher</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>1</td>
                            <td><input type="text" name="voucher[]" class="form-control"></td>
                            <td><span class="amount"></span></td>
                            <td><span class="balance"></span></td>                             
                            <td><span class="remaining"></span></td>                             
                            <td class="td-validate-voucher"><button class="btn btn-info validatevoucher" onclick="return false;">Validate</button></td>
                          </tr>
                          </tbody>
                        </table>
                    </div>
                      
                  </div>

                  <div class="tab-pane fade" id="steps-planpurchase-tab4">
                    DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg whatever.
                  </div>

                  <div class="tab-pane fade" id="steps-planpurchase-tab5">
                    Aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthet.
                  </div>

                   <div class="tab-pane fade" id="steps-planpurchase-tab6">
                    Aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthet.
                  </div>
                </div>
              </div>




             
                
              
            </fieldset>

             

             
          </form>
    
  </div>
  
</div>

              

@endsection







