@extends('app.admin.layouts.default') {{-- Web site Title --}} @section('title') {{{ $title }}} :: @parent @stop @section('styles') @parent @endsection {{-- Content --}} @section('main') @include('flash::message') @include('utils.errors.list')
<div class="panel panel-flat border-top-success">
    <div class="panel-heading">
        <h4 class="panel-title">
            {{ trans('ewallet.credit_fund') }}
        </h4>
    </div>
    <div class="panel-body">
        <form action="{{url('admin/credit-fund')}}" class="smart-wizard form-horizontal" method="post">
            <input name="_token" type="hidden" value="{{csrf_token()}}">
            <div class="form-group">
                <label class="col-sm-2 control-label" for="username">
                    {{trans('ewallet.enter_username')}}:
                    <span class="symbol required">
                        </span>
                </label>
                <div class="col-sm-4">
                    <input class="form-control autocompleteusers" id="username" name="autocompleteusers" type="text">
                    <input class="form-control key_user_hidden" name="username" type="hidden">
                    </input>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="amount">
                    {{trans('ewallet.amount')}}:
                    <span class="symbol required">
                        </span>
                </label>
                <div class="col-sm-4">
                    <input class="form-control" id="amount" name="amount" type="text">
                    </input>
                </div>
            </div>
            <div class="col-sm-offset-2">
                <div class="form-group" style="float: left; margin-right: 0px;">
                    <div class="col-sm-2">
                        <button class="btn btn-info" id="add_amount" name="add_amount" tabindex="4" type="submit" value="{{trans('ewallet.add_amount')}}">
                            {{trans('ewallet.add_amount')}}
                        </button>
                    </div>
                </div>
            </div>
            </input>
        </form>
    </div>
</div>
@endsection @section('scripts') @parent @endsection