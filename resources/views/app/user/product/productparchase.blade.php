
@extends('app.user.layouts.default') {{-- Web site Title --}} @section('title') {{{ $title }}} :: @parent @stop {{-- Content --}} @section('styles') @parent
<style type="text/css">

</style>
@endsection @section('main')
<!-- Basic datatable -->
<h1>Product Purchase</h1>

<div class="shopping-cart">
  <div class="well">
  <table>
<tr>
@foreach($product_datas as $key => $items) 
@if($key%4 == 0)
  <tr>
    @endif
<td>
  
  <div class="well" style="border-radius: 5px;border-width: 8px;">
    <div class="product-image">
      <img src="{{url('/assets/uploads/'.$items->image)}}">
    </div>
    <div class="product-details">
      <div class="product-title" style="color:black;font-size: 20px;" align="center"> {{$items->productname}}</div>
    </div>
    <br>
    <div class="product-quantity" style="color:black" align="center">
      Code : {{$items->productcode}}
    </div>
    <br>
    <div class="product-price" style="color:black" align="center">
       ${{$items->pv}}
    </div>
    <br>
      <div class="product-pv" style="color:black" align="center">
    PV : {{$items->pv}}   
    </div>    
      <div align="center">
      <button type="button" class="fa fa-shopping-cart btn btn-info" data-toggle="modal" data-target="#myModals{{$items->id}}">ADD TO CART</button>
    </div>
  </div>  
</td>
                                <div class="modal fade" id="myModals{{$items->id}}" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
         <!--  <button type="button" class="close" data-dismiss="modal">&times;</button> -->
         
                                            
                                            <div class="modal-body">
                                                
                                                    
         <input name="requestid" type="hidden" value="{{$items->id}}">


         <div class="well" style="width:70px;"><img src="{{url('/assets/uploads/'.$items->image)}}" style="width:40px;"></div>
         <br><br>
                                                <h7 class="modal-title"  style="color:black" align="left">
                                                 {{$items->productname}}
                                                </h7>&nbsp;
                                                <h8 class="modal-title" style="color:black">
                                                 X
                                               </h8>
                                               &nbsp;

                                                <h8 class="modal-title" style="color:black">
                                                {{$items->quantity}}
                                                &nbsp;&nbsp;&nbsp;&nbsp;:
                                                {{$items->pv}}
                                              </h8>

                                                        
                                                                <div class="col-sm-8" align="left" style="color:black;margin-top: 20px;">

                                                                    Sub Total : {{$items->pv}} 
                                                              </div>
                                                              <br>
                                                               
                                                          
                                                                   <div class="col-sm-12" style="color:black;" align="left">
                                                                    Quantity :  {{$items->quantity}} 
                                                                </div>
                                                                <br><br>
                                                                <div class="col-sm-8" style="color:black;" align="left">
                                                                    Amount Payable :  {{$items->amount}} 
                                                                </div>
                                                         
                                              <!--   <div class="col-sm-8" style="color:black;" align="left">
                                                  Deal :  <input type="text" style="width:150px;" name="amountpayable" id="amountpayable" class="form-control" onkeyup="getamount()"> 
                                                                </div> -->
                                                                
                                                          
                                                           
                                                          
                                                            <div style="color:black;margin-top: 80px;">
                                                              <a href="{{url('user/productparchase/')}}">
                                                            <button class="btn btn-alert fa fa-shopping-cart btn btn-alert" type="button">
                                                                {{trans('View Cart')}}
                                                            </button>
                                                           </a>
                                                            <button class="btn btn-alert" style="color:black;" name="submit" data-toggle="modal" align="" data-target="#myModal" type="submit">
                                                                {{trans('checkout')}}
                                                            </button>
                                                         
                                                        <!-- </input> -->
                                                    </input>
                                                
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-default" data-dismiss="modal" type="button">
                                                    {{trans('all.close')}}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                        
      @endforeach


  </tr>
</table>
</div>
</div>
 <div id="myModal" class="modal fade" role="dialog">
 <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 style="color:black;" class="modal-title">
                                                    {{trans('Checkout')}}
                                                </h4>
                                            </div>
                                            <div class="modal-body">     

                                                           <div class="panel-body">
    <form action="{{url('user/product/productparchase')}}" class="smart-wizard form-horizontal" method="post">
   <input type="hidden" name="_token" value="{{ csrf_token() }}">
   <input name="requestid" type="hidden" value="{{$items->id}}">
        <table class="table-responsive">
          <tr>
            <td>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="username" >
             {{trans('Username')}}: <span class="symbol required"></span>
            </label>
            <div class="col-sm-8">
                <input type="text" id="username" name="username" placeholder="username"  value="{{Auth::user()->username}}" class="form-control" readonly="readonly" required>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="address" >
             {{trans('Address')}}: <span class="symbol required"></span>
            </label>
            <div class="col-sm-8">
                <input type="text" id="address" name="address" placeholder="Address"  class="form-control" required value="{{Auth::user()->address1}}">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="country" >
                 {{trans('Country')}}: <span class="symbol required"></span>
            </label>
                <div class="col-sm-8">
                <input type="text" id="country" name="country" placeholder="Country"  class="form-control" required>
            </div>           
        </div>
          <div class="form-group">
            <label class="col-sm-4 control-label" for="state" >
                 {{trans('State')}}: <span class="symbol required"></span>
            </label>
                <div class="col-sm-8">
                <input type="text" id="state" name="state" placeholder="State"  class="form-control" required>
            </div>           
        </div>
         <div class="form-group">
            <label class="col-sm-4 control-label" for="city" >
                 {{trans('City')}}: <span class="symbol required"></span>
            </label>
                <div class="col-sm-8">
                <input type="text" id="city" name="city" placeholder="City"  class="form-control" required>
            </div>           
        </div>
         <div class="form-group">
            <label class="col-sm-4 control-label" for="zipcode" >
                 {{trans('Zip Code')}}: <span class="symbol required"></span>
            </label>
                <div class="col-sm-8">
                <input type="text" id="zipcode" name="zipcode" placeholder="Zip Code"  class="form-control" required>
            </div>           
        </div>
</div>
</td>
<td>

  <div class="alert alert-info" style="margin-left: 100px;width:400px;">

     <label class="col-sm-4 control-label" for="" align="left" style="color:black;width:150px;">
                 {{trans('Product Name')}} : <span class="symbol required"></span>
            </label>   
   <div align="right" class="col-sm-20" style="color:black;">
 <input style="width:100px;" name="productname" class="form-control" readonly value="{{$items->productname}}"> 
</div>
<br>
    <label class="col-sm-4 control-label" for="" align="left" style="color:black;width:150px;">
                 {{trans('Quantity')}} : <span class="symbol required"></span>
            </label>   
   <div align="right" class="col-sm-20" style="color:black;">
 <input style="width:100px;" name="quantity" class="form-control" readonly value="{{$items->quantity}}"> 
</div>
<br>
  <label class="col-sm-4 control-label" for="" align="left" style="color:black;width:150px;">
                 {{trans('Price')}} : <span class="symbol required"></span>
            </label>   
   <div align="right" class="col-sm-20" style="color:black;">
 <input style="width:100px;" name="pv" class="form-control" readonly value="{{$items->pv}}"> 
</div>
<br>

   <!--  <label class="col-sm-4 control-label" for="" style="color:black;width:150px;">
                 {{trans('Price')}}&nbsp;    :    {{$items->pv}} <span class="symbol required"></span>
            </label>                                                         
   <div align="right" class="col-sm-20" style="color:black;">

   
 -->  
<!--          <label class="col-sm-4 control-label" for="zipcode" align="left" style="color:black;width:150px;">
                 {{trans('Delivary Charge')}} : $1<span class="symbol required"></span>
            </label>                                                          
<div align="right" class="col-sm-20" style="color:black;">
<input name="pv" readonly value="{{$items->pv}}"> 
</div>
  <br><br>
   <label class="col-sm-4 control-label" for="" align="left" style="color:black;width:150px;">
                 {{trans('Shipping Charge')}} : $2 <span class="symbol required"></span>
            </label>    -->
<!-- <div align="right" class="col-sm-20" style="color:black;">
<input name="quantity" readonly value=" {{$items->quantity}}">
</div> -->
<br>
<!-- <label class="col-sm-4 control-label" for="" align="left" style="color:black;width:150px;">
                 {{trans('Tax')}} : $1 <span class="symbol required"></span>
            </label>   
            <br><br> -->

 <label class="col-sm-4 control-label" for="" align="left" style="color:black;width:150px;">
                 {{trans('Deal Amount')}} : <span class="symbol required"></span>
            </label>   
   <div align="right" class="col-sm-20" style="color:black;">
 <input style="width:100px;" name="amount" class="form-control" value=""> 
</div>
</div>
<button class="btn btn-success" style="color:white;background-color: darkblue;margin-left: 200px;" name="submit" type="submit">{{trans('checkout')}}
  </button>
                                                        <!-- </input> -->
  </input>
</form>
    </div>
                                           
      </div>
           </div>
        </div>
      </div>
       </td>
   
         </tr>
  </table>          
@stop
<!-- @section('scripts') @parent
<script>
function getamount() {
   var amounts = document.getElementById("amountpayable").value;
   console.log(amounts);
 $('#amount').html(amounts);

 }
 </script>
@endsection -->

 
 
