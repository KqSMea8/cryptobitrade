@extends('app.admin.layouts.default')



{{-- Web site Title --}}

@section('title') Member profile:: @parent

@stop


{{-- Content --}}

@section('main')
<!-- Cover area -->
<div class="profile-cover">
    <div class="profile-cover-img" style="background-image: url({{ url('img/cache/original/'.$cover_photo) }}">
    </div>

    

    <div class="media">
        <div class="media-left">
            <div class="avatar">
                <div class="avatarin ajxloaderouter">
                    <div class="img-circle" id="profilephotopreview" style="width:100px;height:100px;margin:0px auto;background-image:url({{ url('img/cache/profile/'.$profile_photo) }}">
                    </div>
                    <!--  {{ Html::image(route('imagecache', ['template' => 'profile', 'filename' => $selecteduser->profile_info->image]), 'Admin', array('class' => '','style'=>'img-circle')) }} -->
                    <div class="profileuploadwrapper">
                        <form id="profilepicform" method="post" name="profilepicform">
                            {!! Form::file('file', ['class' => 'inputfile profilepic','id'=>'profile']) !!}
                {!! Form::hidden('type', 'profile') !!}
                {!! Form::hidden('user_id', $selecteduser->id) !!}
                {!! csrf_field() !!}
                            <label for="profile">
                                <i class="icon-file-plus position-left">
                                </i>
                                <span>
                                </span>
                            </label>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="media-body">
            <h1>
                {{ $selecteduser->name }} {{ $selecteduser->lastname }}
                <small class="display-block">
                    {{ $selecteduser->username }}
                </small>
            </h1>
        </div>
        <div class="media-right media-middle">
            <ul class="list-inline list-inline-condensed no-margin-bottom text-nowrap">
                <li>
                    <form id="coverpicform" method="post" name="coverpicform">
                        {!! Form::file('file', ['class' => 'inputfile coverpic','style'=>'display:none;','id'=>'cover']) !!}
                    {!! Form::hidden('type', 'cover') !!}
                    {!! Form::hidden('user_id', $selecteduser->id) !!}
                    {!! csrf_field() !!}
                        <label for="cover">
                            <span class="btn btn-default" href="#">
                                <i class="icon-file-picture position-left">
                                </i>
                                Cover image
                            </span>
                        </label>
                    </form>
                </li>
               
            </ul>
        </div>
    </div>
</div>
<!-- /cover area -->
<!-- Toolbar -->
<div class="navbar navbar-default navbar-xs content-group">
    <ul class="nav navbar-nav visible-xs-block">
        <li class="full-width text-center">
            <a data-target="#navbar-filter" data-toggle="collapse">
                <i class="icon-menu7">
                </i>
            </a>
        </li>
    </ul>
    <div class="navbar-collapse collapse" id="navbar-filter">
        <ul class="nav navbar-nav">
            <li class="active">
                <a data-toggle="tab" href="#overview">
                    <i class="icon-calendar3 position-left">
                    </i>
                    Overview
                </a>
            </li>
             <li >
                <a data-toggle="tab" href="#update">
                    <i class="icon-pencil position-left">
                    </i>
                    Edit info
                </a>
            </li>
            <li>
                <a data-toggle="tab" href="#activity">
                    <i class="icon-menu7 position-left">
                    </i>
                    Activity
                </a>
            </li>
            <li>
                <a data-toggle="tab" href="#settings">
                    <i class="icon-cog3 position-left">
                    </i> 
                    Account Settings
                </a>
            </li>
        </ul>
        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <li>
                    <a href="{{ url('admin/notes') }}">
                        <i class="icon-stack-text position-left">
                        </i>
                        Notes
                    </a>
                </li>
                
            
            </ul>
        </div>
    </div>

</div>
<!-- /toolbar -->
<!-- Content area -->
<div class="content">
    <!-- User profile -->
    <div class="row">
        <div class="col-lg-9">
            <div class="tabbable">
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="overview">
                        @include('app.admin.users.earnings')
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="content-group user-details-profile">
                                            <div class="form-group">
                                                <label class="text-semibold">
                                                    {{ trans('register.username') }} :
                                                </label>
                                                <span class="pull-right-sm">
                                                    {{ $selecteduser->username }}
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-semibold">
                                                    {{ trans('register.email') }} :
                                                </label>
                                                <span class="pull-right-sm">
                                                    <a href="emailto: {{ $selecteduser->email }}">
                                                        {{ $selecteduser->email }}
                                                    </a>
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-semibold">
                                                    {{ trans('register.sponsor') }}
                                                </label>
                                                <span class="pull-right-sm">
                                                    {{ Auth::user()->username }}
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-semibold">
                                                    {{ trans('register.position') }}
                                                </label>
                                                <span class="pull-right-sm">
                                                    @if($selecteduser->tree_table->leg == 'L')  {{ trans('users.left') }}

                                                    @else  {{ trans('users.right') }}  @endif 
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-semibold">
                                                    {{ trans('register.package') }}
                                                </label>
                                                <span class="pull-right-sm">
                                                    {{ $selecteduser->profile_info->package_detail->package }}
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-semibold">
                                                    {{ trans('register.firstname') }}
                                                </label>
                                                <span class="pull-right-sm">
                                                    {{ $selecteduser->name }}
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-semibold">
                                                    {{ trans('register.lastname') }}
                                                </label>
                                                <span class="pull-right-sm">
                                                    {{ $selecteduser->lastname }}
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-semibold">
                                                    {{ trans('register.gender') }}
                                                </label>
                                                <span class="pull-right-sm">
                                                    @if($selecteduser->profile_info->gender == 'm')  {{ trans('register.male') }}
                                                    @else {{ trans('register.female') }}  @endif
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-semibold">
                                                    {{ trans('register.phone') }}
                                                </label>
                                                <span class="pull-right-sm">
                                                    {{ $selecteduser->profile_info->mobile }}
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-semibold">
                                                    {{ trans('register.wechat') }}
                                                </label>
                                                <span class="pull-right-sm">
                                                    {{ $selecteduser->id }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="content-group user-details-profile">
                                            <div class="form-group">
                                                <span class="">
                                                    <div class="flag-icon flag-icon-{{ strtolower($selecteduser->profile_info->country) }}" style="width: 250px;height: 188px;">
                                                    </div>
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-semibold">
                                                    {{ trans('register.country') }}:
                                                </label>
                                                <span class="pull-right-sm">
                                                    {{ $country }}
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-semibold">
                                                    {{ trans('register.state') }}:
                                                </label>
                                                <span class="pull-right-sm">
                                                    {{ $state }}
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-semibold">
                                                    {{ trans('register.zipcode') }}:
                                                </label>
                                                <span class="pull-right-sm">
                                                    {{ $selecteduser->profile_info->zip }}
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-semibold">
                                                    {{ trans('register.address') }} :
                                                </label>
                                                <span class="pull-right-sm">
                                                    {{ $selecteduser->profile_info->address1 }}
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-semibold">
                                                    {{ trans('register.city') }} :
                                                </label>
                                                <span class="pull-right-sm">
                                                    {{ $selecteduser->profile_info->city }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-flat bg-indigo-400">
                            <div class="panel-heading">
                                <h6 class="panel-title text-semibold">
                                    {{ trans('users.referrals') }}
                                    <a class="heading-elements-toggle">
                                        <i class="icon-more">
                                        </i>
                                    </a>
                                </h6>
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <li>
                                            <a data-action="collapse">
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body">
                                @include('app.admin.users.referrals')
                            </div>
                        </div>
                    </div>


                    <div class="tab-pane fade in " id="update">                             
                        <div class="panel panel-flat">

                           {!!  Form::model($selecteduser, array('route' => array('admin.saveprofile', $selecteduser->id))) !!} 


                            <form action="{{ url('admin/saveprofile') }}" method="post">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                   {{ trans("all.update_profile") }}    
                                    </h6>
                                    <div class="heading-elements">
                                        <ul class="icons-list">
                                            <li><a data-action="collapse"></a></li>
                                            <li><a data-action="reload"></a></li>
                                            <li><a data-action="close"></a></li>
                                        </ul>
                                    </div>
                                </div>
                               



                                <div class="panel-body">
                                <div class="row">
<div class="col-md-4">
<div class="required form-group {{ $errors->has('firstname') ? ' has-error' : '' }}">
    {!! Form::label('name', trans("register.firstname"), array('class' => 'control-label')) !!} {!! Form::text('name', Input::old('name'), ['class' => 'form-control','required' => 'required','data-parsley-required-message' => trans("all.please_enter_first_name"),'data-parsley-group' => 'block-1']) !!}
    <span class="help-block">
        <small>{!! trans("all.your_firstname") !!}</small>
        @if ($errors->has('name'))
        <strong>{{ $errors->first('name') }}</strong>
        @endif
    </span>
</div>
</div>
<div class="col-md-4">
<div class="required form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
    {!! Form::label('lastname', trans("register.lastname"), array('class' => 'control-label')) !!} {!! Form::text('lastname', Input::old('lastname'), ['class' => 'form-control','required' => 'required','data-parsley-required-message' => trans("all.please_enter_last_name"),'data-parsley-group' => 'block-1']) !!}
    <span class="help-block">
        <small>{!! trans("all.your_lastname") !!}</small>
        @if ($errors->has('lastname'))
        <strong>{{ $errors->first('lastname') }}</strong>
        @endif
    </span>
</div>
</div>
<!-- begin col-6 -->

<div class="col-md-4">
<div class="required form-group has-feedbackX has-feedback-leftx {{ $errors->has('gender') ? ' has-error' : '' }}">
    {!! Form::label('gender', trans("register.gender"), array('class' => 'control-label')) !!} {!! Form::select('gender', array('m' => trans("all.male"), 'f' => trans("all.female") ,'other' =>trans("all.trans")),null !==(Input::old('gender')) ? Input::old('gender') : $selecteduser->profile_info->gender,['class' => 'form-control','required' => 'required','data-parsley-required-message' => trans("all.please_select_gender"),'data-parsley-group' => 'block-1']) !!}
    <div class="form-control-feedback">
        <i class="fa fa-neuter text-muted"></i>
    </div>
    <span class="help-block">
        <small>{!! trans("all.select_your_gender_from_list") !!}</small>
        @if ($errors->has('gender'))
        <strong>{{ $errors->first('gender') }}</strong>
        @endif
    </span>
</div>
</div>

</div>
<!-- end row -->
<div class="row">
<div class="col-md-4">
<div class="required form-group has-feedbackX has-feedback-leftx {{ $errors->has('country') ? ' has-error' : '' }}">
    {!! Form::label('country', trans("register.country"), array('class' => 'control-label')) !!} {!! Form::select('country', $countries ,null !==(Input::old('country')) ? Input::old('country') : $selecteduser->profile_info->country,['class' => 'form-control','id' => 'country','required' => 'required','data-parsley-required-message' => trans("all.please_select_country"),'data-parsley-group' => 'block-1']) !!}
    <div class="form-control-feedback">
        <i class="fa fa-flag-o text-muted"></i>
    </div>
    <span class="help-block">
        <small>{!! trans("all.select_country") !!}</small>
        @if ($errors->has('country'))
        <strong>{{ $errors->first('country') }}</strong>
        @endif
    </span>
</div>
</div>
<div class="col-md-4">
<div class="required form-group{{ $errors->has('state') ? ' has-error' : '' }}">
    {!! Form::label('state', trans("register.state"), array('class' => 'control-label')) !!} {!! Form::select('state', $states ,null !==(Input::old('state')) ? Input::old('state') : $selecteduser->profile_info->state,['class' => 'form-control','id' => 'state']) !!}
    <span class="help-block">
        <small>{!! trans("all.select_your_state") !!}</small>
        @if ($errors->has('state'))
        <strong>{{ $errors->first('state') }}</strong>
        @endif
    </span>
</div>
</div>
<div class="col-md-4">
<div class="required form-group has-feedbackX has-feedback-leftx {{ $errors->has('city') ? ' has-error' : '' }}">
    {!! Form::label('city', trans("register.city"), array('class' => 'control-label')) !!} {!! Form::text('city', null !==(Input::old('city')) ? Input::old('city') : $selecteduser->profile_info->city, ['class' => 'form-control','required' => 'required','id' => 'city','data-parsley-required-message' => trans("all.please_enter_city"),'data-parsley-group' => 'block-1']) !!}
    <div class="form-control-feedback">
        <i class="icon-city text-muted"></i>
    </div>
    <span class="help-block">
        <small>{!! trans("all.your_city") !!}</small>
        @if ($errors->has('city'))
        <strong>{{ $errors->first('city') }}</strong>
        @endif
    </span>
</div>
</div>
</div>
<!-- end row -->
<div class="row">
    <!-- begin col-6 -->
<div class="col-md-6">
<div class="required form-group{{ $errors->has('zip') ? ' has-error' : '' }}">
    {!! Form::label('zip', trans("register.zip_code"), array('class' => 'control-label')) !!} {!! Form::text('zip', null !==(Input::old('zip')) ? Input::old('zip') : $selecteduser->profile_info->zip, ['class' => 'form-control','required' => 'required','id' => 'zip','data-parsley-required-message' => trans("all.please_enter_zip"),'data-parsley-group' => 'block-1','data-parsley-zip' => 'us','data-parsley-type' => 'digits','data-parsley-length' => '[5,8]','data-parsley-state-and-zip' => 'us','data-parsley-validate-if-empty' => '','data-parsley-errors-container' => '#ziperror' ]) !!}
    <span class="help-block">
        <span id="ziplocation"><span></span></span>
    <span id="ziperror"></span>
    <small>{!! trans("all.your_zip") !!}</small> @if ($errors->has('zip'))
    <strong>{{ $errors->first('zip') }}</strong> @endif
    </span>
</div>
</div>
</div>
<div class="row">

<div class="col-md-6">
<div class="required form-group{{ $errors->has('address1') ? ' has-error' : '' }}">
    {!! Form::label('address1', trans("register.address1"), array('class' => 'control-label')) !!} {!! Form::textarea('address1', null !==(Input::old('address1')) ? Input::old('address1') : $selecteduser->profile_info->address1, ['class' => 'form-control','required' => 'required','id' => 'address1','rows'=>'2','data-parsley-required-message' => trans("all.please_enter_address1"),'data-parsley-group' => 'block-1']) !!}
    <span class="help-block">
        <small>{!! trans("all.your_address1") !!}</small>
        @if ($errors->has('address'))
        <strong>{{ $errors->first('address1') }}</strong>
        @endif
    </span>
</div>
</div>
<div class="col-md-6">
<div class="required form-group{{ $errors->has('address2') ? ' has-error' : '' }}">
    {!! Form::label('address2', trans("register.address2"), array('class' => 'control-label')) !!} {!! Form::textarea('address2', null !==(Input::old('address2')) ? Input::old('address2') : $selecteduser->profile_info->address2, ['class' => 'form-control','required' => 'required','id' => 'address2','rows'=>'2','data-parsley-required-message' => trans("all.please_enter_address2"),'data-parsley-group' => 'block-1']) !!}
    <span class="help-block">
        <small>{!! trans("all.your_address1") !!}</small>
        @if ($errors->has('address'))
        <strong>{{ $errors->first('address1') }}</strong>
        @endif
    </span>
</div>
</div>

</div>

<div class="row">
<!-- begin col-6 -->
<div class="col-md-6">
<div class="required form-group has-feedbackX has-feedback-leftx {{ $errors->has('phone') ? ' has-error' : '' }}">
    {!! Form::label('phone', trans("register.phone"), array('class' => 'control-label')) !!} {!! Form::text('phone', null !==(Input::old('phone')) ? Input::old('phone') : $selecteduser->profile_info->phone, ['class' => 'form-control','id' => 'phone','data-parsley-required-message' => trans("all.please_enter_phone_number"),'data-parsley-group' => 'block-1']) !!}
    <div class="form-control-feedback">
        <i class=" icon-mobile3 text-muted"></i>
    </div>
    <span class="help-block">
        <small>{!! trans("all.enter_your_phone_number") !!}</small>
        @if ($errors->has('phone'))
        <strong>{{ $errors->first('phone') }}</strong>
        @endif
    </span>
</div>
</div>
<div class="col-md-6">
<div class="required form-group has-feedbackX has-feedback-leftx {{ $errors->has('email') ? ' has-error' : '' }}">
    {!! Form::label('email', trans("register.email"), array('class' => 'control-label')) !!} {!! Form::email('email', Input::old('email'), ['class' => 'form-control','required' => 'required','id' => 'email','data-parsley-required-message' => trans("all.please_enter_email"),'data-parsley-group' => 'block-1']) !!}
    <div class="form-control-feedback">
        <i class="icon-mail5 text-muted"></i>
    </div>
    <span class="help-block">
        <small>{!! trans("all.type_your_email") !!}</small>
        @if ($errors->has('email'))
        <strong>{{ $errors->first('email') }}</strong>
        @endif
    </span>
</div>
</div>
</div>
<div class="row">
<!-- begin col-6 -->
<div class="col-md-6">
<div class="required form-group has-feedbackX has-feedback-leftx {{ $errors->has('wechat') ? ' has-error' : '' }}">
    {!! Form::label('wechat', trans("register.wechat"), array('class' => 'control-label')) !!} {!! Form::text('wechat', null !==(Input::old('wechat')) ? Input::old('wechat') : $selecteduser->profile_info->wechat, ['class' => 'form-control','id' => 'wechat','data-parsley-required-message' => trans("all.please_enter_wechat"),'data-parsley-group' => 'block-1']) !!}
    <span class="help-block">
        <small>{!! trans("all.type_your_wechat") !!}</small>
        @if ($errors->has('wechat'))
        <strong>{{ $errors->first('wechat') }}</strong>
        @endif
    </span>
</div>
</div>
<!-- begin col-4 -->
<div class="col-md-6">
<div class="required form-group has-feedbackX has-feedback-leftx {{ $errors->has('passport') ? ' has-error' : '' }}">
    {!! Form::label('passport', trans("register.national_identification_number"), array('class' => 'control-label')) !!} {!! Form::text('passport', null !==(Input::old('passport')) ? Input::old('passport') : $selecteduser->profile_info->passport, ['class' => 'form-control','required' => 'required','id' => 'passport','data-parsley-required-message' => trans("all.please_enter_passport"),'data-parsley-group' => 'block-1']) !!}
    <div class="form-control-feedback">
        <i class="icon-user-check text-muted"></i>
    </div>
    <span class="help-block">
        <small>{!! trans("all.type_your_passport_number") !!}</small>
        @if ($errors->has('passport'))
        <strong>{{ $errors->first('passport') }}</strong>
        @endif
    </span>
</div>
</div>
</div>


</div>

<!-- #start payout -->
  <div class="row">
<div class="col-lg-9 ">
 <div class="card-body">
  <ul class="nav nav-tabs nav-tabs-highlight nav-justified">
            <li class="nav-item active"><a href="#steps-bankpayout-tab1" class="nav-link  steps-leadership active  " data-toggle="tab" data-payment='newregiserd'> {{ trans("all.payout_info") }}</a>
            </li>
            <li class="nav-item"><a href="#steps-hypperwallet-tab2" class="nav-link steps-plan-payment" data-toggle="tab" data-payment='BinaryMatchBonus'>{{ trans("all.hypperwallet") }}</a></li>
            
        </ul>




    <div class="tab-content" style="height: 300px">
         <div class="tab-pane active  " id="steps-bankpayout-tab1"> 
           <div class="panel-body"> 

     <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <label>
                    {{ trans('register.account_number') }}
                </label>
                <input class="form-control" name="account_number" type="text" value="{{ $selecteduser->profile_info->account_number }}">
                 
            </div>
            <div class="col-md-6">
                <label>
                    {{ trans('register.account_holder_name') }}
                </label>
                <input class="form-control" name="account_holder_name" type="text" value="{{ $selecteduser->profile_info->account_holder_name }}">
                
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <label>
                    {{ trans('register.bank_code') }}
                </label>
                <input class="form-control" name="swift" type="text" value="{{ $selecteduser->profile_info->swift }}">
                 
            </div>
            <div class="col-md-6">
                <label>
                    {{ trans('register.bank_code') }}
                </label>
                <input class="form-control" name="bank_code" type="text" value="{{ $selecteduser->profile_info->bank_code }}">
                
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <label>
                    {{ trans('register.paypal') }}
                </label>
                <input class="form-control" name="paypal" type="text" value="{{ $selecteduser->profile_info->paypal }}">
                 
            </div>
            <div class="col-md-6">
                <label>
                    {{ trans('register.btc_wallet') }}
                </label>
                <input class="form-control" name="btc_wallet" type="text" value="{{ $selecteduser->profile_info->btc_wallet }}">
                
            </div>
        </div>
    </div>

    <div class="text-right">
        <button class="btn btn-primary" type="submit">
            Save
            <i class="icon-arrow-right14 position-right">
            </i>
        </button>
    </div>


           



                </div>       
        </div>
           
          
          <div class="tab-pane   " id="steps-hypperwallet-tab2">
               <div class="panel-body">
@if($selecteduser->hypperwalletid!=null)
    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <label>
                   HypperWallet Id
                </label>
                <input class="form-control" name="hypperwalletid" type="text" readonly="" value="{{ $selecteduser->hypperwalletid }}">
                 
            </div>
           
        </div>
    </div>


     <div class="text-right">
        <button class="btn btn-primary" type="submit">
            Save
            <i class="icon-arrow-right14 position-right">
            </i>
        </button>
        </div>
@else
   Please Create Hypperwallet ID 
<br> 
<div>
  <a href="{{ URL::to('admin/createhypperwalletid/'.$selecteduser->id) }}" type="button" class="btn btn-primary">createID</a>
</div>
  @endif
    </div>
        </div> 
          </div>

</div>
</div>

</div>

</form>
</div>
</div>


<div class="tab-pane fade in " id="activity">
<!-- Timeline -->
<div class="timeline timeline-left content-group">
<div class="timeline-container">


@foreach($activities as $activity)
<div class="timeline-row">
    <div class="timeline-icon">
        <a href="#">
            {{ Html::image(route('imagecache', ['template' => 'profile', 'filename' => $activity->user->profile_info->image]), $activity->user->username, array('class' => '','style'=>'')) }}
        </a>
    </div>
    <div class="panel panel-flat timeline-content">
        <div class="panel-heading">
            <h6 class="panel-title">
                {{ $activity->title }}
            </h6>
            <div class="heading-elements">
                <span class="label label-success heading-text">
                    <i class="icon-history position-left text-success">
                    </i>
                    {{ $activity->created_at->diffForHumans() }}
                </span>
                <ul class="icons-list">
                    <li>
                        <a data-action="reload">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {{ $activity->description }}
        </div>
    </div>
</div>
@endforeach
</div>
</div>
<!-- /timeline -->
</div>
<div class="tab-pane fade" id="settings">
<!-- Profile info -->
<div class="panel panel-flat">
<div class="panel-heading">
<h6 class="panel-title">
    {{ trans('profile_information') }}
</h6>
<div class="heading-elements">
    <ul class="icons-list">
        <li>
            <a data-action="collapse">
            </a>
        </li>
        <li>
            <a data-action="reload">
            </a>
        </li>
        <li>
            <a data-action="close">
            </a>
        </li>
    </ul>
</div>
</div>
<div class="panel-body">
</div>
</div>
<!-- /profile info -->
<!-- Account settings -->
<div class="panel panel-flat">
<div class="panel-heading">
<h6 class="panel-title">
    Account settings
</h6>
<div class="heading-elements">
    <ul class="icons-list">
        <li>
            <a data-action="collapse">
            </a>
        </li>
        <li>
            <a data-action="reload">
            </a>
        </li>
        <li>
            <a data-action="close">
            </a>
        </li>
    </ul>
</div>
</div>
<div class="panel-body">
<form action="#">
    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <label>
                    Username
                </label>
                <input class="form-control" readonly="readonly" type="text" value="{{ $selecteduser->username }}">
                </input>
            </div>
            <div class="col-md-6">
                <label>
                    Current password
                </label>
                <input class="form-control" readonly="readonly" type="password" value="password">
                </input>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <label>
                    New password
                </label>
                <input class="form-control" placeholder="Enter new password" type="password">
                </input>
            </div>
            <div class="col-md-6">
                <label>
                    Repeat password
                </label>
                <input class="form-control" placeholder="Repeat new password" type="password">
                </input>
            </div>
        </div>
    </div>
  
    <div class="text-right">
        <button class="btn btn-primary" type="submit">
            Save
            <i class="icon-arrow-right14 position-right">
            </i>
        </button>
    </div>
<!-- </form>
-->
{!! Form::close() !!}
</div>
</div>
<!-- /account settings -->
</div>
</div>
</div>
</div>
<div class="col-lg-3">
<div class="panel panel-body">
<div class="row text-center">
<div class="col-xs-4">
<p>
<i class="icon-medal-star icon-2x display-inline-block text-warning">
</i>
</p>
<h5 class="text-semibold no-margin">
{{ $user_rank_name }}
</h5>
<span class="text-muted text-size-small">
Rank
</span>
</div>
<div class="col-xs-4">
<p>
<i class="icon-users2 icon-2x display-inline-block text-info">
</i>
</p>
<h5 class="text-semibold no-margin">
{{ $referrals_count }}
</h5>
<span class="text-muted text-size-small">
{{ trans('all.referrals') }}
</span>
</div>
<div class="col-xs-4">
<p>
<i class="icon-cash3 icon-2x display-inline-block text-success">
</i>
</p>
<h5 class="text-semibold no-margin">
{{ $balance }}
</h5>
<span class="text-muted text-size-small">
{{ trans('all.balance') }}
</span>
</div>
</div>
</div>
<div class="content-group">
@if(isset($sponsor->username))
<div background-size:="" class="panel-body bg-blue border-radius-top text-center" contain;"="">
<div class="content-group-sm">
<h5 class="text-semibold no-margin-bottom">
Sponsor Information
</h5>
</div>
</div>
<div class="panel panel-body no-border-top no-border-radius-top">
<div class="form-group mt-5">
<label class="text-semibold">
Sponsor name:
</label>
<span class="pull-right-sm">
{{ $sponsor->name }}
</span>
</div>
<div class="form-group mt-5">
<label class="text-semibold">
Sponsor username:
</label>
<span class="pull-right-sm">
{{ $sponsor->username }}
</span>
</div>
<div class="form-group mt-5">
<label class="text-semibold">
Sponsor Country:
</label>
<span class="pull-right-sm">
{{ $sponsor->profile_info->country }}
</span>
</div>
<div class="form-group mt-5">
<label class="text-semibold">
Date Joined:
</label>
<span class="pull-right-sm">
{{ $sponsor->profile_info->created_at->toFormattedDateString() }}
</span>
</div>
</div>
@endif
</div>
<!-- Share your thoughts -->
<div class="panel panel-flat">
<div class="panel-heading">
<h6 class="panel-title">
Create a note
<a class="heading-elements-toggle">
<i class="icon-more">
</i>
</a>
</h6>
<div class="heading-elements">
</div>
</div>
<div class="panel-body">
<form action="#" class="notesform" data-parsley-validate="">
<div class="form-group">
<input class="form-control mb-15" cols="1" id="title" name="title" placeholder="Note title" required="" type="text"/>
</div>
<div class="form-group">
<textarea class="form-control mb-15" cols="1" id="description" name="description" placeholder="Note content" required="" rows="3"></textarea>
</div>
<div class="form-group">

<div class="btn-group hide hidden" id="note-color" data-toggle="buttons">
<label class="btn btn-primary btn-xs">
<input type="radio" name="color" value="bg-primary" checked> primary </label>
<label class="btn btn-success btn-xs">
<input type="radio" name="color" value="bg-success">Success</label>
<label class="btn btn-info btn-xs">
<input type="radio" name="color" value="bg-info">Info</label>
<label class="btn btn-warning btn-xs">
<input type="radio" name="color" value="bg-warning">Warning</label>
<label class="btn btn-danger btn-xs">
<input type="radio" name="color" value="bg-danger">Danger</label>
</div>
</div>
<div class="row">
<div class="col-sm-6">
<a href="{{ url('admin/notes') }}" class="btn btn-link">
 View all notes <i class="icon-arrow-right14 position-right">
</i>                                
</a>
</div>

<div class="col-sm-6 text-right">
<button class="submit-note btn btn-primary btn-labeled btn-labeled-right" type="button">
Save
<b>
    <i class="icon-circle-right2">
    </i>
</b>
</button>
</div>

</div>
</form>
</div>
</div>
</div>
</div>
</div>
@endsection 
{{-- Scripts --}}
@section('scripts')
@parent
<script type="text/javascript">
  
</script>
@endsection

@section('styles')
@parent
<style type="text/css">
    div#profilephotopreview {
    background-size: cover;
}
</style>
@endsection
