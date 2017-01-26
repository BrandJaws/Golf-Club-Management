@extends('admin.__layouts.admin-layout')
@section('main')
<div id="content" class="app-content box-shadow-z0" role="main">
    <div class="app-header white box-shadow">
      <div class="navbar"> 
        <!-- Open side - Naviation on mobile --> 
        <a data-toggle="modal" data-target="#aside" class="navbar-item pull-left hidden-lg-up"> <i class="material-icons">&#xe5d2;</i> </a> 
        <!-- / --> 
        <!-- Page title - Bind to $state's title -->
        <div class="navbar-item pull-left h5" ng-bind="$state.current.data.title" id="pageTitle"></div>
        <!-- navbar right -->
        <ul class="nav navbar-nav pull-right">
          <li class="nav-item dropdown pos-stc-xs"> <a class="nav-link" href data-toggle="dropdown"> <i class="material-icons">&#xe7f5;</i> <span class="label label-sm up warn">3</span> </a>
            <div ui-include="'../views/blocks/dropdown.notification.html'"></div>
          </li>
          <li class="nav-item dropdown"> <a class="nav-link clear" href data-toggle="dropdown"> <span class="avatar w-32"> <img src="../assets/images/a0.jpg" alt="..."> <i class="on b-white bottom"></i> </span> </a>
            <div ui-include="'../views/blocks/dropdown.user.html'"></div>
          </li>
          <li class="nav-item hidden-md-up"> <a class="nav-link" data-toggle="collapse" data-target="#collapse"> <i class="material-icons">&#xe5d4;</i> </a> </li>
        </ul>
        <!-- / navbar right --> 
        
        <!-- navbar collapse -->
        <div class="collapse navbar-toggleable-sm" id="collapse">
          <div class="main-page-heading">
            <h3> <span>Members</span></h3>
          </div>
        </div>
        <!-- / navbar collapse --> 
      </div>
    </div>
    <div class="app-footer">
      <div class="p-a text-xs">
        <div class="pull-right text-muted"> &copy; Copyright <strong>Grit Golf</strong> <span class="hidden-xs-down">- Built with Love v1.1.3</span> <a ui-scroll-to="content"><i class="fa fa-long-arrow-up p-x-sm"></i></a> </div>
        <div class="nav"> <a class="nav-link" href="../">About</a> <span class="text-muted">-</span> <a class="nav-link label accent" href="http://themeforest.net/user/flatfull/portfolio?ref=flatfull">Get it</a> </div>
      </div>
    </div>
    <div ui-view class="app-body" id="view">
        <!-- ############ PAGE START-->
        <div class="segments-main padding">
        <div class="row"> 
	        <div class="segments-inner">
	            <div class="box">
	                <div class="inner-header">
	                	<div class="">
	                    	<div class="col-md-8">
	                        	<div class="search-form">
	                            	<form action="#." method="post">
	                                	<div class="search-field">
	                                    	<span class="search-box">
	                                        	<input type="text" name="search" class="search-bar">
	                                            <button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
	                                        </span>
	                                    </div>
	                                </form>
	                            </div>
	                        </div>
	                        <div class="col-md-4 text-right">
	                        	<button class="btn-def btn"><i class="fa fa-plus-circle"></i>&nbsp;Add Members</button>
	                        	<button class="btn-def btn"><i class="fa fa-upload"></i>&nbsp;Import CSV</button>
	                        </div>
	                        <div class="clearfix"></div>
	                    </div>
	                </div><!-- inner header -->
			        <table class="table table-hover b-t">
			          	<thead>
			          		<tr>
			          			<th>
			          				Name
			          			</th>
			          			<th>
			          				Email
			          			</th>
			          			<th>
			          				Gender
			          			</th>
			          			<th>
			          				Warnings
			          			</th>
			          			<th>
			          				Actions
			          			</th>
			          		</tr>
			          	</thead>
			          	<tbody>
				            <tr>
			          			<td>
			          				Bilal Nisar
			          			</td>
			          			<td>
			          				bilalbinnisar@gmail.com
			          			</td>
			          			<td>
			          				Male
			          			</td>
			          			<td>
			          				02
			          			</td>
			          			<td>
			          				<a href="#." class="blue-cb" title="Edit">edit</a>
			          				&nbsp;&nbsp;
			          				<a href="#." class="del-icon" title="Delete"><i class="fa fa-trash"></i></a>
			          			</td>
			          		</tr>
			          		<tr>
			          			<td>
			          				Kashif Chishti
			          			</td>
			          			<td>
			          				kashifchishti@gmail.com
			          			</td>
			          			<td>
			          				Male
			          			</td>
			          			<td>
			          				03
			          			</td>
			          			<td>
			          				<a href="#." class="blue-cb" title="Edit">edit</a>
			          				&nbsp;&nbsp;
			          				<a href="#." class="del-icon" title="Delete"><i class="fa fa-trash"></i></a>
			          			</td>
			          		</tr>
			          		<tr>
			          			<td>
			          				Salman Abid
			          			</td>
			          			<td>
			          				salmanabid@gmail.com
			          			</td>
			          			<td>
			          				Male
			          			</td>
			          			<td>
			          				00
			          			</td>
			          			<td>
			          				<a href="#." class="blue-cb" title="Edit">edit</a>
			          				&nbsp;&nbsp;
			          				<a href="#." class="del-icon" title="Delete"><i class="fa fa-trash"></i></a>
			          			</td>
			          		</tr>
			          		<tr>
			          			<td>
			          				Shandna Karim
			          			</td>
			          			<td>
			          				shandnakarim@gmail.com
			          			</td>
			          			<td>
			          				Female
			          			</td>
			          			<td>
			          				00
			          			</td>
			          			<td>
			          				<a href="#." class="blue-cb" title="Edit">edit</a>
			          				&nbsp;&nbsp;
			          				<a href="#." class="del-icon" title="Delete"><i class="fa fa-trash"></i></a>
			          			</td>
			          		</tr>
			          		<tr>
			          			<td>
			          				Umar Daraz
			          			</td>
			          			<td>
			          				umardaraz@gmail.com
			          			</td>
			          			<td>
			          				Male
			          			</td>
			          			<td>
			          				05
			          			</td>
			          			<td>
			          				<a href="#." class="blue-cb" title="Edit">edit</a>
			          				&nbsp;&nbsp;
			          				<a href="#." class="del-icon" title="Delete"><i class="fa fa-trash"></i></a>
			          			</td>
			          		</tr>
			          		<tr>
			          			<td>
			          				Afshan Zubair
			          			</td>
			          			<td>
			          				afshanzubair@gmail.com
			          			</td>
			          			<td>
			          				Female
			          			</td>
			          			<td>
			          				00
			          			</td>
			          			<td>
			          				<a href="#." class="blue-cb" title="Edit">edit</a>
			          				&nbsp;&nbsp;
			          				<a href="#." class="del-icon" title="Delete"><i class="fa fa-trash"></i></a>
			          			</td>
			          		</tr>
				            <!-- <tr>
				              	<td>
				                  	<div class="section-1 sec-style">
				                  		<h3>Fantatstic start to sunday lunch promotion</h3>
				                    	<p>Content Goes here...</p>
				                  	</div>
				              	</td>
				              	<td>
				              		<div class="section-2 sec-style text-center">
				                		<h3 class="if-sent"><i class="fa fa-paper-plane" aria-hidden="true"></i></h3>
					                    <p>Sent</p>
				                	</div>
				              	</td>
				              	<td>
				                  	<div class="section-3 sec-style text-center">
				                  		<p class="green-cal"><i class="fa fa-calendar"></i></p>
				                    	<p>Dec 9 2016 - 2:13:00 AM</p>
				                  	</div>
				              	</td>
				               	<td>
				              	  	<div class="section-3 sec-style">
					                    <p>
				                    		<span><a href="#." class="green-cb" title="View"><i class="fa fa-eye"></i></a></span>&nbsp;&nbsp;&nbsp;
				                    		<span><a href="#." class="del-icon" title="Delete"><i class="fa fa-trash"></i></a></span>
				                    	</p>
				                  	</div>
				              	</td>
				            </tr> -->
			          	</tbody>
			        </table>
	      		</div>
	        </div>
        </div>
      </div>
    </div>
</div>

@endSection