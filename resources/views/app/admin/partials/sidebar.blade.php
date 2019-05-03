@if(Auth::check())
<!-- User menu -->

	<!-- Main sidebar -->
			<div class="sidebar sidebar-main ">
				<div class="sidebar-content">


					<div class="sidebar-user">
						<div class="category-content">
							<div class="media">
								<a href="#" class="media-left">

                 {{ Html::image(route('imagecache', ['template' => 'profile', 'filename' => $image]), 'Admin', array('class' => 'img-circle img-sm')) }}

               

                </a>
								<div class="media-body">
									<span class="media-heading text-semibold">  {{ Auth::user()->name }}</span>
									<div class="text-size-mini text-muted">
										<i class="icon-pin text-size-small"></i> Admin
									</div>
								</div>

								<div class="media-right media-middle">
									<ul class="icons-list">
										<li>
											<a href="#"><i class="icon-cog3"></i></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<!-- /user menu -->


					<!-- Main navigation -->
					<div class="sidebar-category sidebar-category-visible">
						<div class="category-content no-padding">
							<ul class="navigation navigation-main navigation-accordion">

								<!-- Main -->
								<li class="navigation-header"><span>Main</span> <i class="icon-menu" title="Main pages"></i></li>								
								<li class="{{set_active('admin/dashboard')}}">
                        <a href="{{url('admin/dashboard')}}">                           
                            <i class="icon-home4"></i>
                            <span class="text" >{{trans('menu.dashboard')}}</span>
                        </a>
                       
                    </li>
                    <li class="navigation-header"><span>Users</span> <i class="icon-menu" title="Users"></i></li>
                    <li class="has-sub {{set_active('admin/genealogy')}}{{set_active('admin/sponsortree')}}{{set_active('admin/tree')}}">
                        <a href="javascript:;">
                            <!--<b class="caret pull-right"></b>-->
                            <span class="badge pull-right"></span>
                            <i class="icon-tree7"></i> 
                            <span>{{trans('menu.genealogy')}}</span>
                        </a>
                         <ul class="sub-menu">
                            <li class="{{set_active('admin/genealogy')}}"><a href="{{url('admin/genealogy')}}">{{trans('menu.binary-genealogy')}}</a></li>
                            <li class="{{set_active('admin/sponsortree')}}"><a href="{{url('admin/sponsortree')}}">{{trans('menu.sponsor-genealogy')}}</a></li>
                            <li class="{{set_active('admin/tree')}}"><a href="{{url('admin/tree')}}">{{trans('menu.tree-genealogy')}}</a></li>
                           
                        </ul>
                    </li>
                     <li class="{{set_active('admin/register')}}">
                            <a href="{{url('admin/register')}}">
                                <i class="icon-add"></i>
                                <span class="text">{{trans('menu.register')}}  </span>
                            </a>
                    </li> 
                    <li class="navigation-header"><span>Funds Management</span> <i class="icon-menu" title="Funds Management"></i></li>
                    <li class="{{set_active('admin/wallet*')}}">
                            <a href="{{url('admin/wallet')}}">
                                <i class="icon-wallet"></i>
                                <span class="text">{{trans('menu.ewallet')}} </span>
                            </a>
                    </li> 
                     <li class="{{set_active('admin/rs-wallet')}}">
                            <a href="{{url('admin/rs-wallet')}}">
                                <i class="icon-cash3"></i>
                                <span class="text">{{trans('menu.rs-wallet')}}</span>
                            </a>
                    </li> 
                    <li class="{{set_active('admin/fund-credits')}}">
                        <a href="{{url('admin/fund-credits')}}">
                            <i class="icon-credit-card"></i>
                            <span class="text">{{trans('menu.fund-credits')}} </span>
                        </a>
                    </li>
                   <li class="navigation-header"><span>Communication</span> <i class="icon-menu" title="Forms"></i></li>
                   <li class="has-sub {{set_active('admin/inbox')}}">
                        <a href="{{url('admin/inbox')}}">
                            <span class="badge pull-right"></span>
                            <i class="icon-envelop5"></i> 
                            <span>{{trans('menu.emails')}}</span>
                        </a>
                       
                    </li> 
                    
                       <li class="has-sub {{set_active('admin/ticketdashboard')}} {{set_active('admin/view_ticket')}} {{set_active('admin/ticket_configuration')}} {{set_active('admin/get-faq')}}">
                        <a href="javascript:;">
                            <!--<b class="caret pull-right"></b>-->
                            <span class="badge pull-right"></span>
                            <i class="icon-ticket"></i> 
                            <span>{{trans('menu.ticket_center')}}</span>
                        </a>

                        <ul class="sub-menu">
                            <li class="{{set_active('admin/helpdesk/tickets-dashboard')}}"><a href="{{url('admin/helpdesk/tickets-dashboard')}}">Ticket dashboard</a></li>
                    
                        </ul>
                    </li> 

                    <li class="navigation-header"><span>Email Marketing </span> 
                        <i class="icon-menu" title="Settings"></i>
                    </li>

                    @if(false)
                    <li class="has-sub {{set_active('admin/campaign')}}">
                        <a  href="javascript:;" >
                            <i class="icon-cogs"></i>
                            <span class="text">{{trans('menu.campaigns')}}</span>
                        </a>
                        <ul class="sub-menu">

                            <li class="{{set_active('admin/campaign/create')}}">
                                <a href="{{url('admin/campaign/create')}}">
                                    {{trans('menu.create_new_campaign')}}
                                </a>
                            </li>

                             <li class="{{set_active('admin/campaign/lists')}}">
                                <a href="{{url('admin/campaign/lists')}}">
                                    {{trans('menu.manage_campaigns')}}
                                </a>
                            </li>
                            
                        </ul>
                    </li>
                    

                    <li class="has-sub {{set_active('admin/campaign/contacts')}}">
                        <a  href="javascript:;" >
                            <i class="icon-cogs"></i>
                            <span class="text">{{trans('menu.contacts')}}</span>
                        </a>
                        <ul class="sub-menu">
                          <!--   <li class="{{set_active('admin/campaign/contacts/create')}}">
                                <a href="{{url('admin/campaign/contacts/create')}}">
                                    {{trans('menu.create_new_contacts')}}
                                </a>
                            </li> -->
                             <li class="{{set_active('admin/campaign/contacts')}}">
                                <a href="{{url('admin/campaign/contacts')}}">
                                    {{trans('menu.contacts_lists')}}
                                </a>
                            </li>                             
                        </ul>
                    </li>


                    <li class="has-sub {{set_active('admin/campaign/autoresponder')}}">
                        <a  href="javascript:;" >
                            <i class="icon-cogs"></i>
                            <span class="text">{{trans('menu.autoresponders')}}</span>
                        </a>
                        <ul class="sub-menu">
                            <li class="{{set_active('admin/campaign/autoresponders')}}">
                                <a href="{{url('admin/campaign/autoresponders')}}">
                                    {{trans('menu.autoresponders_list')}}
                                </a>
                            </li>
                            <li class="{{set_active('admin/campaign/autoresponders/create')}}">
                                <a href="{{url('admin/campaign/autoresponders/create')}}">
                                    {{trans('menu.create_autoresponder')}}
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    
                    <li class="navigation-header"><span>{{trans('menu.Tools')}}</span> <i class="icon-menu" title="Settings"></i></li>

                     <li class="has-sub {{set_active('admin/autoresponse')}} {{set_active('admin/documentupload')}} {{set_active('admin/paymentsettings')}}
                     {{set_active('admin/optionsettings')}}">
                        <a  href="javascript:;" >
                           
                            <i class="icon-cogs"></i>
                            <span class="text">{{trans('menu.Tools')}}</span>
                        </a>
                        <ul class="sub-menu">
                             <!-- <li class="{{set_active('admin/autoresponse')}}"><a href="{{url('admin/autoresponse')}}">{{trans('menu.Auto_Responder')}}</a></li> -->
                             <li class="{{set_active('admin/documentupload')}}"><a href="{{url('admin/documentupload')}}">{{trans('menu.Documents')}}</a></li>
                             <!-- <li class="{{set_active('admin/paymentsettings')}}"><a href="{{url('admin/paymentsettings')}}">Payment Gateway Settings</a></li> -->
                             <li class="{{set_active('admin/optionsettings')}}"><a href="{{url('admin/optionsettings')}}">Block Options</a></li>
                            
                      
                        </ul>
                    </li>  
                    <li class="navigation-header"><span>Voucher Management</span> <i class="icon-menu" title="Voucher Management"></i></li>
                    <li class="has-sub {{set_active('admin/voucherlist')}} {{set_active('admin/voucherrequest')}}">
                        <a  href="javascript:;" >
                            <!--<b class="caret pull-right"></b>-->
                            <i class="icon-file-text"></i>
                            <span class="text">{{trans('menu.Voucher')}}</span>
                        </a>
                        <ul class="sub-menu">
                             <li class="{{set_active('admin/voucherlist')}}"><a href="{{url('admin/voucherlist')}}">{{trans('menu.Voucher_List')}}</a></li>
                              <li class="{{set_active('admin/voucherrequest')}}"><a href="{{url('admin/voucherrequest')}}">{{trans('menu.Voucher_Request')}}</a></li>
                            
                      
                        </ul>
                    </li>
                   <li class="navigation-header"><span>Profile Management</span> <i class="icon-menu" title="Profile Management"></i></li>
                     <li class="{{set_active('admin/userprofile*')}}">
                        <a href="{{url('admin/userprofiles/'.Auth::user()->username)}}">
                            <i class="icon-profile"></i>
                            <span class="text">{{trans('menu.profile')}} </span>
                        </a>
                    </li>
                    <li class="navigation-header"><span>Members Management</span> <i class="icon-menu" title="Forms"></i></li>
                    <li class="has-sub {{set_active('admin/users')}}{{set_active('admin/users/*')}}">
                        <a href="javascript:;">
                             <!--<b class="caret pull-right"></b>-->
                            <i class="icon-users2"></i>
                            <span class="text"> {{trans('menu.members')}}</span>
                        </a>
                        <ul class="sub-menu">
                             <li class="{{set_active('admin/users')}}"><a href="{{url('admin/users')}}">{{trans('menu.view-all')}}</a></li>
                             <li class="{{set_active('admin/users/activate')}}"><a href="{{url('admin/users/activate')}}">{{trans('menu.activate-member')}}</a></li>
                             <li class="{{set_active('admin/users/password')}}"><a href="{{url('admin/users/password')}}">{{trans('menu.change-password')}}</a></li>
            
               <li class="{{set_active('admin/users/changeusername')}}"><a href="{{url('admin/users/changeusername')}}">{{trans('menu.Change_Username')}}</a></li>
                             <!-- <li class="{{set_active('admin/userprofile')}}"><a href="{{url('admin/userprofile')}}">View member profile</a></li> -->
                        </ul>
                    </li>
                    
                                        
                    <li class="navigation-header"><span>Settings</span> <i class="icon-menu" title="Settings"></i></li>

                  
                   


                    <li class="has-sub {{set_active('admin/appsettings')}}{{set_active('admin/themesettings')}} {{set_active('admin/emailsettings')}}{{set_active('admin/uploads')}}">
                        <a  href="javascript:;" >
                            <!--<b class="caret pull-right"></b>-->
                            <i class="icon-cog4"></i>
                            <span class="text">  {{trans('menu.system_settings')}}</span>
                        </a>
                        <ul class="sub-menu">                             
                            <li class="{{set_active('admin/appsettings')}}"><a href="{{url('admin/appsettings')}}">App settings</a></li> 
                             <!-- <li class="{{set_active('admin/themesettings')}}"><a href="{{url('admin/themesettings')}}">Theme settings</a></li>  -->
                             <!-- <li class="{{set_active('admin/emailsettings')}}"><a href="{{url('admin/emailsettings')}}">{{trans('menu.Email_Settings')}}</a></li> -->
                             <li class="{{set_active('admin/uploads')}}"><a href="{{url('admin/uploads')}}">{{trans('menu.change_logo')}}</a></li>

                        </ul>
                    </li>
                

                     <li class="has-sub {{set_active('admin/plansettings')}} {{set_active('admin/bonus')}} {{set_active('admin/ranksetting')}}{{set_active('admin/settings')}}">
                        <a  href="javascript:;" >
                            <!--<b class="caret pull-right"></b>-->
                            <i class="icon-cog4"></i>
                            <span class="text">  {{trans('menu.network_settings')}}</span>
                        </a>
                        <ul class="sub-menu">
                             <li class="{{set_active('admin/settings')}}"><a href="{{url('admin/settings')}}"> {{trans('menu.Commission_Settings')}}  </a></li>
                             
                             <li class="{{set_active('admin/plansettings')}}"><a href="{{url('admin/plansettings')}}">  {{trans('menu.plan-settings')}} </a></li>
                             

                             <li class="{{set_active('admin/bonus')}}"><a href="{{url('admin/bonus')}}">
                                {{trans('menu.bonus-settings')}}  </a></li>
                             
                             <li class="{{set_active('admin/ranksetting')}}"><a href="{{url('admin/ranksetting')}}">{{trans('menu.rank-settings')}}</a></li>

                             
                        </ul>
                    </li>
                   
                   

                    <li class="has-sub {{set_active('admin/currency')}}">
                        <a  href="javascript:;" >
                            <!--<b class="caret pull-right"></b>-->
                            <i class="icon-cog4"></i>
                            <span class="text">  {{trans('menu.currency_settings')}}</span>
                        </a>
                        <ul class="sub-menu">
                             
                             <li class="{{set_active('admin/currency')}}"><a href="{{url('admin/currency')}}"> {{trans('menu.currency-settings')}}  </a></li>
                            
                        </ul>
                    </li>

                    <li class="has-sub {{set_active('admin/product/addproducts')}}">
                        <a  href="javascript:;" >
                            <!--<b class="caret pull-right"></b>-->
                            <i class="icon-cog4"></i>
                            <span class="text">  {{trans('menu.product_settings')}}</span>
                        </a>
                        <ul class="sub-menu">
                             
                             <li class="{{set_active('admin/product/addcategory')}}"><a href="{{url('admin/product/addcategory')}}"> {{trans('menu.category')}}  </a></li>
                             <li class="{{set_active('admin/product/addproducts')}}"><a href="{{url('admin/product/addproducts')}}"> {{trans('menu.add_products')}}  </a></li>
                             <li class="{{set_active('admin/product/productpurchasehistory')}}"><a href="{{url('admin/product/productpurchasehistory')}}"> {{trans('menu.purchase_history')}}  </a></li>
                            
                        </ul>
                    </li>
                    
                 
                    <li class="has-sub {{set_active('admin/welcomeemail')}}  {{set_active('admin/payoutnotification')}}">
                        <a  href="javascript:;" >
                            <!--<b class="caret pull-right"></b>-->
                            <i class="icon-cog4"></i>
                            <span class="text">  {{trans('menu.system_templates')}}</span>
                        </a>
                        <ul class="sub-menu">
                            

                             <li class="{{set_active('admin/welcomeemail')}}"><a href="{{url('admin/welcomeemail')}}">{{trans('menu.Welcome_Email')}}</a></li>

                            <li class="{{set_active('admin/payoutnotification')}}"><a href="{{url('admin/payoutnotification')}}">Payout Notification Settings</a></li>
                             

                          
                        </ul>
                    </li>
                    
                   
                    
                   

                    <li class="navigation-header"><span>Payout Management</span> <i class="icon-menu" title="Payout Management"></i></li>
                    <li class="{{set_active('admin/payoutrequest')}}">
                        <a href="{{url('admin/payoutrequest')}}">
                            <i class="icon-paypal2"></i>
                            <span class="text">{{trans('menu.payout')}}</span>
                        </a>
                    </li>
  
                    <li class="navigation-header"><span>Reports</span> <i class="icon-menu" title="Reports"></i></li>
                    <li class="has-sub {{set_active('admin/salesreport')}}{{set_active('admin/topearners')}}{{set_active('admin/joiningreport')}}{{set_active('admin/incomereport')}}
                    {{set_active('admin/payoutreport')}}{{set_active('admin/joiningreportbysponsor')}}{{set_active('admin/joiningreportbycountry')}}{{set_active('admin/fundcredit')}} ">
                        <a  href="javascript:;" >
                            <!--<b class="caret pull-right"></b>-->
                            <i class="fa fa-sticky-note"></i>
                            <span class="text"> {{trans('menu.reports')}}</span>
                        </a> 
                        <ul class="sub-menu">
                            <li class="{{set_active('admin/joiningreport')}}"><a href="{{url('admin/joiningreport')}}">{{trans('menu.joining-report')}}</a></li>
                             <!--<li class="{{set_active('admin/fundtransfer')}}"><a href="{{url('admin/fundtransfer')}}"> {{trans('menu.fund-transfer')}} </a></li>-->
                             <li class="{{set_active('admin/fundcredit')}}"><a href="{{url('admin/fundcredit')}}"> {{trans('menu.fund-credit-report')}}</a></li>
                            <li class="{{set_active('admin/incomereport')}}"><a href="{{url('admin/incomereport')}}">{{trans('menu.member-income-report')}}</a></li>
                            <li class="{{set_active('admin/topearners')}}"><a href="{{url('admin/topearners')}}"> {{trans('menu.top-earners-report')}} </a></li>
                            <li class="{{set_active('admin/payoutreport')}}"><a href="{{url('admin/payoutreport')}}">{{trans('menu.payout-report')}}</a></li>
                            <li class="{{set_active('admin/salesreport')}}"><a href="{{url('admin/salesreport')}}">{{trans('menu.sales_report')}}</a></li>
                        </ul>
                    </li>
 

                  

                       <li><a href="{{ url('/logout') }}"  onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="icon-switch2"></i> Logout</a></li>
                    
								</ul>
						</div>
					</div>
					<!-- /main navigation -->
				</div>
			</div>
			<!-- /main sidebar -->

       
@endif


