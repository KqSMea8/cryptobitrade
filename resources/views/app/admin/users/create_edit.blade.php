@extends('app.admin.layouts.default')
@section('main')
<div class="panel panel-flat" >
	<div class="panel-heading">
		<div class="panel-heading-btn">
			
		</div>
		<h4 class="panel-title">{{ trans('users.change_password') }} </h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal" method="post"	action="{{ URL::to('admin/users/edit') }}"	autocomplete="off">
			<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
			<div>
				<div class=" col-md-12">
					

					

					<div id="searchtreeholder"  class="form-group {{{ $errors->has('username') ? 'has-error' : '' }}}">
						<label class="col-sm-4 control-label" for="username">
							{{trans('ewallet.username')}} <span class="symbol required"></span>
						</label>
						<div class="col-sm-4">
							<span class="input-group"> 
							 <input type="text" class="form-control" id="key-word" name="key-word" placeholder="Search Member">
							  <input type="hidden" id="key_user_hidden" name="username" >
							  <span class="input-group-btn">
							  		<button class="btn btn-danger" type="button"  id="btn-cancel"><i class="icon-cross"></i></button>
							  </span>
							</span>							 
						</div>
						
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group {{{ $errors->has('password') ? 'has-error' : '' }}}">
						<label class="col-md-4 control-label" for="password">{{
						trans('users.password') }}</label>
						<div class="col-md-4">
							<input class="form-control" tabindex="6" placeholder="{{ trans('users.password') }}"	type="password" name="password" id="password" value="" />
							{!!$errors->first('password', '<label class="control-label"	for="password">:message</label>')!!}
							
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group {{{ $errors->has('password_confirmation') ? 'has-error' : '' }}}">
						<label class="col-md-4 control-label" for="password_confirmation">{!!
						trans('users.password_confirmation') !!}</label>
						<div class="col-md-4">
							<input class="form-control" type="password" tabindex="6"
							placeholder="{{ trans('users.password_confirmation') }}"
							name="password_confirmation" id="password_confirmation" value="" />
							{{$errors->first('password_confirmation', '<label
							class="control-label" for="password_confirmation">:message</label>')}}
						</div>
					</div>
				</div>
				
				
				<div class="col-md-12">
					<div class="col-md-6 col-md-offset-4">
						
						<button type="reset" class="btn btn-sm btn-default">
						<span class="glyphicon glyphicon-remove-circle"></span> {{
						trans("modal.reset") }}
						</button>
						<button type="submit" class="btn btn-sm btn-success">
						<span class="glyphicon glyphicon-ok-circle"></span>
						
						{{ trans("modal.edit") }}
						
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	@stop
	@section('scripts') @parent
  
	@endsection