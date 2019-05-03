@extends('app.admin.layouts.default') {{-- Web site Title --}} @section('title') {{{ $title }}} :: @parent @stop  {{-- Content --}} @section('main') @include('app.admin.layouts.records')
@section('styles') 
@parent 

@endsection
@section('scripts')
@parent 

@endsection

<div class="row">
    <div class="col-lg-7">
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h6 class="panel-title">
                Users Joined over the time
                </h6>
                <div class="heading-elements">
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-inline text-center">
                            <li>
                                <a href="#" class="btn border-teal text-teal btn-flat btn-rounded btn-icon btn-xs valign-text-bottom"><i class="icon-plus3"></i></a>
                            </li>
                            <li class="text-left">
                                <div class="text-semibold">Weekly Joining</div>
                                <div class="text-muted">{{$weekly_users_count}}</div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-inline text-center">
                            <li>
                                <a href="#" class="btn border-warning-400 text-warning-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom"><i class="icon-watch2"></i></a>
                            </li>
                            <li class="text-left">
                                <div class="text-semibold">Montly Joining</div>
                                <div class="text-muted">{{$monthly_users_count}}</div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-inline text-center">
                            <li>
                                <a href="#" class="btn border-indigo-400 text-indigo-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom"><i class="icon-people"></i></a>
                            </li>
                            <li class="text-left">
                                <div class="text-semibold">Yearly Joining</div>
                                <div class="text-muted"> {{$yearly_users_count}} </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="chart-container">
                <div class="chart has-fixed-height" id="users_join" style="height:350px">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <!-- Sales stats -->
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h6 class="panel-title">Package purchase</h6>
                <div class="heading-elements">
                </div>
            </div>
            <div class="container-fluid">
                <div class="row text-center">
                    @foreach($packages_data as $package)
                    <div class="col-md-4">
                        <div class="content-group">
                            <h5 class="text-semibold no-margin"><i class="icon-cash3 position-left text-slate"></i>{{$package->purchase_history_r_count}}
                            @if($package->special == 'yes')
                            <span class="label label-flat border-green-400 label-icon text-green-400" style="display: inline-block;"><i class="icon-stars"></i> Special</span>
                            @endif
                            </h5>
                            <span class="text-muted text-size-small">{{$package->package}} Purchases</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="content-group-sm" id="package_purchase_graph" style="height:350px"></div>
        </div>
        <!-- /sales stats -->
    </div>
</div>
<div class="row">
    <div class="col-md-7 col-sm-12">
           <div class="panel border-top-purple-300 border-bottom-purple-300">
             <div class="panel-heading">
                <h6 class="panel-title">Global view </h6>
            </div>
            <div class="panel-body ">
                <div class="has-fixed-height map-choropleth"></div>                
            </div>
        </div>
    </div>    
    <div class="col-md-5 col-sm-12">


<!-- <div class="col-md-6 col-sm-12"> -->
    <div class="panel border-top-purple-300 border-bottom-purple-300">
        <div class="panel-heading">
            <h6 class="panel-title">Referral link</h6>
        </div>
        <div class="panel-body">
            <div class="input-group">
                <input id="referrallink" type="text" readonly="true" class="selectall form-control" spellcheck="false" value="{{url('/',Auth::user()->username)}}" />
                <span class="input-group-addon copylink">
                    <button class="btn btn-link btn-copy"  style="margin: 0 auto;padding: 0px;font-size: 12px;" data-clipboard-target="#referrallink">
                    <i class="fa fa-copy"></i>
                    </button>
                </span>
            </div>
        </div>
        <div class="panel-footer"><a class="heading-elements-toggle"><i class="icon-more"></i></a>
        <div class="">
            <div class="text-semibold text-center">Share</div>
            <hr class="mb-5 mt-5" />
            <div class="panel-body text-center">
                <button class="btn btn-info btn-labeled btn-xs btn-modal"
                data-toggle="modal"
                data-target="#fsModal">
                <b><i class="icon-share2"></i></b>
                Share your referral link to 100+ Social Pages! 
                </button>
                <div id="fsModal" class="sharemodal modal animated bounceIn"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-full">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h5 class="modal-title"> Share your referral link across the web!!</h5>
                            </div>
                            <div class="modal-body">
                                <div class="share_target social_links" data-title="Cloud MLM Software for multilevel network marketing, direct selling business"  data-url="{{url('/',Auth::user()->username)}}"  data-img="https://cloudmlmsoftware.com/sites/default/files/mlm-software.jpg" data-desc="Best MLM Software that is customizable for any type of online business , multilevel marketing and direct selling business with best support, Try our free MLM software demo today!" data-rurl="{{url('/',Auth::user()->username)}}" data-via="cloudmlmsoft" data-hashtags="MLM,Software"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
<!-- </div> -->


           




    </div>    
</div>

<!-- 
<div class="row">

<div class="col-md-6 col-sm-12">
    <div class="panel border-top-purple-300 border-bottom-purple-300">
        <div class="panel-heading">
            <h6 class="panel-title">Lead Capture Page</h6>
        </div>
        <div class="panel-body">
            <div class="input-group">
                <input id="replicationlink" type="text" readonly="true" class="selectall form-control" spellcheck="false" value="{{url('lead',Auth::user()->username)}}" />
                <span class="input-group-addon copylink">
                    <button class="btn btn-link btn-copy"  style="margin: 0 auto;padding: 0px;font-size: 12px;" data-clipboard-target="#replicationlink">
                    <i class="fa fa-copy"></i>
                    </button>
                </span>
            </div>
        </div>
        <div class="panel-footer"><a class="heading-elements-toggle"><i class="icon-more"></i></a>
        <div class="">
            <div class="text-semibold text-center">Share</div>
            <hr class="mb-5 mt-5" />
            <div class="panel-body text-center">
                <button class="btn btn-info btn-labeled btn-xs btn-modal"
                data-toggle="modal"
                data-target="#fsModalRepli">
                <b><i class="icon-share2"></i></b>
                Share your replication to 100+ Social Pages! 
                </button>
                <div id="fsModalRepli" class="sharemodal modal animated bounceIn"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-full">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h5 class="modal-title"> Share your Lead Capture across the web!!</h5>
                            </div>
                            <div class="modal-body">
                                <div class="share_target social_links" data-title="Cloud MLM Software for multilevel network marketing, direct selling business" data-url="{{url('lead',Auth::user()->username)}}" data-img="https://cloudmlmsoftware.com/sites/default/files/mlm-software.jpg" data-desc="Best MLM Software that is customizable for any type of online business , multilevel marketing and direct selling business with best support, Try our free MLM software demo today!" data-rurl="{{url('lead',Auth::user()->username)}}" data-via="cloudmlmsoft" data-hashtags="MLM,Software"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

</div>
 -->


<div class="row">
<div class="col-md-8 col-sm-12">
<div class="row">
    <div class="col-sm-12">
        
        <div class="panel panel-body">
            <div class="panel-heading border-bottom border-teal">
                <h6 class="panel-title">Tickets Overview</h6>
                <div class="heading-elements">
                    <button type="button" class="btn btn-link daterange-ranges-tickets heading-btn text-semibold">
                    <i class="icon-calendar3 position-left"></i> <span></span> <b class="caret"></b>
                    </button>
                </div>
            </div>
            <div class="text-size-small text-muted content-group-sm">
            </div>
            <div class="svg-center has-fixed-height" id="graph_tickets_legend" style="height: 300px">
            </div>
        </div>
    </div>
</div>
<!-- /pie with legend -->
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-flat border-top-success">
            <div class="panel-heading">
                <h6 class="panel-title">Recruiters (TOP)</h6>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                            <!-- <tr>
                                <th>User</th>
                                <th>Followers Count</th>
                                <th></th>
                            </tr> -->
                        </thead>
                        <tbody>
                            @foreach($top_recruiters as $user)
                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        {{ Html::image(route('imagecache', ['template' => 'profile', 'filename' => $user->profile]), 'Admin', array('class' => 'img-circle img-xs')) }}
                                    </div>
                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="{{url('admin/userprofiles/')}}/{{$user->username}}" target="_blank" class="letter-icon-title">{{$user->username}}</a>
                                        </div>
                                        <div class="text-muted text-size-small"><i class="icon-user-tie text-size-mini position-left"></i>{{$user->name}}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="label label-flat border-primary text-primary-600 label-icon">
                                        {{$user->count}}
                                    </span>
                                </td>
                                <td> 
                                    <a href="{{url('/admin/sponsortree?st='.$user->username)}}" target="_blank" class="button btn bg-blue btn-xs"><i class="icon-tree5 position-left"></i>Tree</a>
                                    <a href="{{url('/admin/userprofiles',$user->username )}}" target="_blank" class="button btn bg-blue btn-xs"><i class="icon-tree5 position-left"></i>Profile</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h6 class="panel-title">
                {{trans('dashboard.recent_plan_top_up')}}
                </h6>
            </div>
            <div class="panel-body">
                <table class="table table-stripped">
                    <thead>
                    <tr>
                        <th>
                            {{trans('dashboard.username')}}
                        </th>
                        <th>
                            {{trans('dashboard.plan')}}
                        </th>
                        <th>
                            {{trans('dashboard.count')}}
                        </th>
                        <th>
                            {{trans('dashboard.date')}}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($recent as $item)
                        <tr>
                            <td>
                                <a href="{{url('admin/userprofiles/')}}/{{$item->username}}" target="_blank">{{$item->username}}</a>
                                
                            </td>
                            <td>
                                {{$item->package }}
                            </td>
                            <td>
                                {{$item->count }}
                            </td>
                            <td>
                                {{date('d M Y H:i:s',strtotime($item->created_at)) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-inverse" data-sortable-id="index-4">
            <div class="panel-heading">
                <h4 class="panel-title">
                {{trans('dashboard.new-registered-users')}}
                <span class="pull-right label label-success">
                    {!! $count_new !!} {{trans('dashboard.new-users')}}
                </span>
                </h4>
            </div>
            <div class="panel-body">
                                 
                @foreach($new_users->chunk(3) as $chunk)
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <ul class="media-list media-list-linked">
                        @foreach($chunk as $user)
                       
                        <li class="media">
                            <a href="{{url('/admin/userprofiles/')}}/{{$user->username}}" target="_blank" class="media-link">
                                <div class="media-left">
                                    {{ Html::image(route('imagecache', ['template' => 'profile', 'filename' => $user->profile]), 'Admin', array('class' => 'img-circle img-xs')) }}
                                </div>
                                <div class="media-body">
                                    <div class="media-heading text-semibold"> {!! $user->name !!}</div>
                                    <span class="text-muted"> {!! $user->username !!}</span>
                                </div>
                                <div class="media-right media-middle text-nowrap">
                                    <span class="text-muted">
                                        <i class="icon-pin-alt text-size-base"></i>
                                        &nbsp;{!! $user->country !!}
                                    </span>
                                </div>
                            </a>
                        </li>
                        
                        @endforeach
                    </ul>
                </div>
                @endforeach           
            
        </div>
    </div>
</div>
</div>
</div>
<div class="col-md-4 col-sm-12">
<!-- Pie with legend -->
<div class="panel panel-body text-center">
<div class="panel-heading">
    <h6 class="panel-title text-semibold">Gender ratio</h6>
</div>
<div class="text-size-small text-muted content-group-sm">
</div>
<div class="svg-center has-fixed-height" id="pie_gender_legend" style="height: 300px">
</div>
<script type="text/javascript">
var pie_gender_legend_data = [{
"name": "Male",
"value": {{$male_users_count}},
"color": "#66BB6A"
}, {
"name": "Female",
"value": {{$female_users_count}},
"color": "#EF5350"
}];
</script>
</div>
<!-- /pie with legend -->

<div class="panel panel-body border-top-danger">

<div class="panel-heading">
    <h6 class="panel-title text-semibold">Recent activities</h6>
</div>
<div class="row"> 
@foreach($all_activities->chunk(10) as $chunk)
<div class="col-sm-12">
    <ul class="list-feed media-list">
        @foreach($chunk as $activity)
        
        
        
        <li class="media">
            <div class="media-body">
                <a href="{{url('admin/userprofiles/')}}/{{$activity->username}}" target="_blank">{{$activity->name}}</a> {{$activity->description}}
            </div>
            <div class="media-right">
                <ul class="icons-list icons-list-extended text-nowrap">
                    <li>
                        <a href="#"><i class="icon-bubble-dots4"></i></a>
                    </li>
                    <li>
                        <a href="#"><i class="icon-circle-right2"></i></a>
                    </li>
                </ul>
            </div>
        </li>
        @endforeach
        
    </ul>
    
</div>
@endforeach
</div>
<hr class="mb-10 mt-10"/>
<div class="text-center">
    <a href="{{url('admin/all_activities')}}" class="btn btn-primary"><i class="icon-make-group position-left"></i> View all activities </a>
</div>

</div>

</div>
<!-- Dashboard content -->
</div>

@endsection


@section('scripts')
@parent

<script type="text/javascript"> 
    


</script>
@endsection