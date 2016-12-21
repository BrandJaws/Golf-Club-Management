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
          <li class="nav-item dropdown"> <a class="nav-link clear" href data-toggle="dropdown"> <span class="avatar w-32"> <img src="{{asset('assets/images/a0.jpg')}}" alt="..."> <i class="on b-white bottom"></i> </span> </a>
            <div ui-include="'../views/blocks/dropdown.user.html'"></div>
          </li>
          <li class="nav-item hidden-md-up"> <a class="nav-link" data-toggle="collapse" data-target="#collapse"> <i class="material-icons">&#xe5d4;</i> </a> </li>
        </ul>
        <!-- / navbar right --> 
        
        <!-- navbar collapse -->
        <div class="search__bar">
          <!--<div ui-include="'../views/blocks/navbar.form.right.html'"></div>-->
          <!-- link and dropdown -->
          <div class="row">
          	<div class="col-md-11">	
                <ul class="nav navbar-nav">
            <li class="nav-item dropdown"> 
            	<h5>Dashboard</h5>
            </li>
          </ul>
          	</div>
          </div>
          <!-- / --> 
        </div>
        <!-- / navbar collapse --> 
      </div>
    </div>
    <div class="app-footer">
      <div class="p-a text-xs">
        <div class="pull-right text-muted"> &copy; Copyright <strong>Flatkit</strong> <span class="hidden-xs-down">- Built with Love v1.1.3</span> <a ui-scroll-to="content"><i class="fa fa-long-arrow-up p-x-sm"></i></a> </div>
        <div class="nav"> <a class="nav-link" href="../">About</a> <span class="text-muted">-</span> <a class="nav-link label accent" href="">Get it</a> </div>
      </div>
    </div>
    <div ui-view class="app-body" id="view"> 
      
      <!-- ############ PAGE START-->
      <div class="p-a white lt box-shadow">
        <div class="row">
          <div class="col-sm-6">
            <h4 class="m-b-0 _300">Welcome to Grit</h4>
            <small class="text-muted">The Ultimate Booking Platform </small></div>
          <div class="col-sm-6 text-sm-right">
            <div class="m-y-sm"> <span class="m-r-sm">Start manage:</span>
              <div class="btn-group dropdown">
                <button class="btn white btn-sm ">Projects</button>
                <button class="btn white btn-sm dropdown-toggle" data-toggle="dropdown"></button>
                <div class="dropdown-menu dropdown-menu-scale pull-right"> <a class="dropdown-item" href>Members</a> <a class="dropdown-item" href>Tasks</a> <a class="dropdown-item" href>Inbox</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item">Profile</a> </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
     <div class="padding">
      <div class="row">
      <div class="col-sm-6 col-md-4">
        <div class="box">
          <div class="box-body">
            <div ui-jp="plot" ui-refresh="app.setting.color" ui-options="
              [{data: 20, label:&#x27;Server&#x27;}, {data: 50, label: &#x27;Client&#x27;}, {data: 30, label:&#x27;Server&#x27;}],
              {
                series: { pie: { show: true, innerRadius: 0.6, stroke: { width: 0 }, label: { show: true, threshold: 0.05 } } },
                legend: {backgroundColor: 'transparent'},
                colors: ['#0cc2aa','#fcc100'],
                grid: { hoverable: true, clickable: true, borderWidth: 0, color: 'rgba(120,120,120,0.5)' },   
                tooltip: true,
                tooltipOpts: { content: '%s: %p.0%' }
              }
            " style="height:118px"></div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-4">
        <div class="box p-a" style="height:150px">
	          <div class="pull-left m-r">
	            <span class="w-48 rounded  accent">
	              <i class="material-icons">&#xe151;</i>
	            </span>
	          </div>
	          <div class="clear">
	            <h4 class="m-a-0 text-lg _300"><a href>125 <span class="text-sm">Emails</span></a></h4>
	            <small class="text-muted">6 new arrivals.</small>
	          </div>
	        </div>
      </div>
      <div class="col-sm-6 col-md-4">
        <div class="box p-a" style="height:150px">
	          <div class="pull-left m-r">
	            <span class="w-48 rounded primary">
	              <i class="material-icons">&#xe54f;</i>
	            </span>
	          </div>
	          <div class="clear">
	            <h4 class="m-a-0 text-lg _300"><a href>40 <span class="text-sm">Projects</span></a></h4>
	            <small class="text-muted">38 open.</small>
	          </div>
	        </div>
      </div>
      </div>
      </div><!-- padding -->
      
     <div class="padding"> 
         <div class="dashboard-tsheet">
             <div class="row">
             	<div class="tsheet-header padd-15">
                	<div class="col-md-8">
                    	<h2>Tee Sheet</h2>
                        <p>This is dummy text Ipsum</p>
                    </div><!-- col-6 -->
                    <div class="col-md-4">
                    	<div class="input-group">
      <input type="text" class="form-control" placeholder="Search for...">
      <span class="input-group-btn">
        <button class="btn btn-default" type="button">Go!</button>
      </span>
    </div><!-- /input-group -->
                    </div><!-- col-6 -->
                </div>
             </div><!-- row -->
             <div class="row">
             	<div class="col-md-12">
             		<div class="tsheet-tabs padd-15">
                	  	<div class="b-b nav-active-bg">
          <ul class="nav nav-tabs">
            <li class="nav-item">
              <a class="nav-link active" href data-toggle="tab" data-target="#tab1">
              <p>02</p><p>Wednesday</p>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href data-toggle="tab" data-target="#tab2">
              	<p>03</p><p>Thursday</p>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href data-toggle="tab" data-target="#tab3">
              	<p>04</p><p>Friday</p>
              </a>
            </li>
             <li class="nav-item">
              <a class="nav-link" href data-toggle="tab" data-target="#tab4">
              	<p>05</p><p>Saturday</p>
              </a>
            </li>
          </ul>
        </div>
        				<div class="tab-content p-a m-b-md">
          <div class="tab-pane animated fadeIn active text-muted" id="tab1">
            <div class="tab-pane-content">
            <div class="table-responsive">
             <table class="table table-hover b-t">
              <tbody>
                <tr>
                  <td>19:00</td>
                  <td colspan="6">
                  	<ul class="members-add">
                    	<li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li class="add-btn"><a href="#."><i class="fa fa-plus"></i></a></li>
                    </ul>
                  </td>
                  <td>
                  	<div class="ts-action-btn">
                  		<a href="#." class="save-btn"><i class="fa fa-save"></i></a>&nbsp;
                   		<a href="#." class="cancel-btn"><i class="fa fa-ban"></i></a>
                   </div>
                  </td>
                 
                </tr>
                <tr>
                  <td>19:00</td>
                  <td colspan="6">
                  	<ul class="members-add">
                    	<li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li class="add-btn"><a href="#."><i class="fa fa-plus"></i></a></li>
                    </ul>
                  </td>
                  <td>
                  	<div class="ts-action-btn">
                  		<a href="#." class="save-btn"><i class="fa fa-save"></i></a>&nbsp;
                   		<a href="#." class="cancel-btn"><i class="fa fa-ban"></i></a>
                   </div>
                  </td>
                 
                </tr>
                <tr>
                  <td>19:00</td>
                  <td colspan="6">
                  	<ul class="members-add">
                    	<li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li class="add-btn"><a href="#."><i class="fa fa-plus"></i></a></li>
                    </ul>
                  </td>
                  <td>
                  	<div class="ts-action-btn">
                  		<a href="#." class="save-btn"><i class="fa fa-save"></i></a>&nbsp;
                   		<a href="#." class="cancel-btn"><i class="fa fa-ban"></i></a>
                   </div>
                  </td>
                 
                </tr>
                <tr>
                  <td>19:00</td>
                  <td colspan="6">
                  	<ul class="members-add">
                    	<li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li class="add-btn"><a href="#."><i class="fa fa-plus"></i></a></li>
                    </ul>
                  </td>
                  <td>
                  	<div class="ts-action-btn">
                  		<a href="#." class="save-btn"><i class="fa fa-save"></i></a>&nbsp;
                   		<a href="#." class="cancel-btn"><i class="fa fa-ban"></i></a>
                   </div>
                  </td>
                 
                </tr>
                <tr>
                  <td>19:00</td>
                  <td colspan="6">
                  	<ul class="members-add">
                    	<li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li class="add-btn"><a href="#."><i class="fa fa-plus"></i></a></li>
                    </ul>
                  </td>
                  <td>
                  	<div class="ts-action-btn">
                  		<a href="#." class="save-btn"><i class="fa fa-save"></i></a>&nbsp;
                   		<a href="#." class="cancel-btn"><i class="fa fa-ban"></i></a>
                   </div>
                  </td>
                 
                </tr>
              </tbody>
            </table>
            </div>
            </div>
          </div>
          <div class="tab-pane animated fadeIn text-muted" id="tab2">
            <div class="tab-pane-content">
            <div class="table-responsive">
             <table class="table table-hover b-t">
              <tbody>
                <tr>
                  <td>19:00</td>
                  <td colspan="6">
                  	<ul class="members-add">
                    	<li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li class="add-btn"><a href="#."><i class="fa fa-plus"></i></a></li>
                    </ul>
                  </td>
                  <td>
                  	<div class="ts-action-btn">
                  		<a href="#." class="save-btn"><i class="fa fa-save"></i></a>&nbsp;
                   		<a href="#." class="cancel-btn"><i class="fa fa-ban"></i></a>
                   </div>
                  </td>
                 
                </tr>
                <tr>
                  <td>19:00</td>
                  <td colspan="6">
                  	<ul class="members-add">
                    	<li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li class="add-btn"><a href="#."><i class="fa fa-plus"></i></a></li>
                    </ul>
                  </td>
                  <td>
                  	<div class="ts-action-btn">
                  		<a href="#." class="save-btn"><i class="fa fa-save"></i></a>&nbsp;
                   		<a href="#." class="cancel-btn"><i class="fa fa-ban"></i></a>
                   </div>
                  </td>
                 
                </tr>
                <tr>
                  <td>19:00</td>
                  <td colspan="6">
                  	<ul class="members-add">
                    	<li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li class="add-btn"><a href="#."><i class="fa fa-plus"></i></a></li>
                    </ul>
                  </td>
                  <td>
                  	<div class="ts-action-btn">
                  		<a href="#." class="save-btn"><i class="fa fa-save"></i></a>&nbsp;
                   		<a href="#." class="cancel-btn"><i class="fa fa-ban"></i></a>
                   </div>
                  </td>
                 
                </tr>
                <tr>
                  <td>19:00</td>
                  <td colspan="6">
                  	<ul class="members-add">
                    	<li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li class="add-btn"><a href="#."><i class="fa fa-plus"></i></a></li>
                    </ul>
                  </td>
                  <td>
                  	<div class="ts-action-btn">
                  		<a href="#." class="save-btn"><i class="fa fa-save"></i></a>&nbsp;
                   		<a href="#." class="cancel-btn"><i class="fa fa-ban"></i></a>
                   </div>
                  </td>
                 
                </tr>
                <tr>
                  <td>19:00</td>
                  <td colspan="6">
                  	<ul class="members-add">
                    	<li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li class="add-btn"><a href="#."><i class="fa fa-plus"></i></a></li>
                    </ul>
                  </td>
                  <td>
                  	<div class="ts-action-btn">
                  		<a href="#." class="save-btn"><i class="fa fa-save"></i></a>&nbsp;
                   		<a href="#." class="cancel-btn"><i class="fa fa-ban"></i></a>
                   </div>
                  </td>
                 
                </tr>
              </tbody>
            </table>
            </div>
            </div>
          </div>
          <div class="tab-pane animated fadeIn text-muted" id="tab3">
            <div class="tab-pane-content">
            <div class="table-responsive">
             <table class="table table-hover b-t">
              <tbody>
                <tr>
                  <td>19:00</td>
                  <td colspan="6">
                  	<ul class="members-add">
                    	<li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li class="add-btn"><a href="#."><i class="fa fa-plus"></i></a></li>
                    </ul>
                  </td>
                  <td>
                  	<div class="ts-action-btn">
                  		<a href="#." class="save-btn"><i class="fa fa-save"></i></a>&nbsp;
                   		<a href="#." class="cancel-btn"><i class="fa fa-ban"></i></a>
                   </div>
                  </td>
                 
                </tr>
                <tr>
                  <td>19:00</td>
                  <td colspan="6">
                  	<ul class="members-add">
                    	<li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li class="add-btn"><a href="#."><i class="fa fa-plus"></i></a></li>
                    </ul>
                  </td>
                  <td>
                  	<div class="ts-action-btn">
                  		<a href="#." class="save-btn"><i class="fa fa-save"></i></a>&nbsp;
                   		<a href="#." class="cancel-btn"><i class="fa fa-ban"></i></a>
                   </div>
                  </td>
                 
                </tr>
                <tr>
                  <td>19:00</td>
                  <td colspan="6">
                  	<ul class="members-add">
                    	<li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li class="add-btn"><a href="#."><i class="fa fa-plus"></i></a></li>
                    </ul>
                  </td>
                  <td>
                  	<div class="ts-action-btn">
                  		<a href="#." class="save-btn"><i class="fa fa-save"></i></a>&nbsp;
                   		<a href="#." class="cancel-btn"><i class="fa fa-ban"></i></a>
                   </div>
                  </td>
                 
                </tr>
                <tr>
                  <td>19:00</td>
                  <td colspan="6">
                  	<ul class="members-add">
                    	<li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li class="add-btn"><a href="#."><i class="fa fa-plus"></i></a></li>
                    </ul>
                  </td>
                  <td>
                  	<div class="ts-action-btn">
                  		<a href="#." class="save-btn"><i class="fa fa-save"></i></a>&nbsp;
                   		<a href="#." class="cancel-btn"><i class="fa fa-ban"></i></a>
                   </div>
                  </td>
                 
                </tr>
                <tr>
                  <td>19:00</td>
                  <td colspan="6">
                  	<ul class="members-add">
                    	<li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li class="add-btn"><a href="#."><i class="fa fa-plus"></i></a></li>
                    </ul>
                  </td>
                  <td>
                  	<div class="ts-action-btn">
                  		<a href="#." class="save-btn"><i class="fa fa-save"></i></a>&nbsp;
                   		<a href="#." class="cancel-btn"><i class="fa fa-ban"></i></a>
                   </div>
                  </td>
                 
                </tr>
              </tbody>
            </table>
            </div>
            </div>
          </div>
          <div class="tab-pane animated fadeIn text-muted" id="tab4">
            <div class="tab-pane-content">
            <div class="table-responsive">
             <table class="table table-hover b-t">
              <tbody>
                <tr>
                  <td>19:00</td>
                  <td colspan="6">
                  	<ul class="members-add">
                    	<li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li class="add-btn"><a href="#."><i class="fa fa-plus"></i></a></li>
                    </ul>
                  </td>
                  <td>
                  	<div class="ts-action-btn">
                  		<a href="#." class="save-btn"><i class="fa fa-save"></i></a>&nbsp;
                   		<a href="#." class="cancel-btn"><i class="fa fa-ban"></i></a>
                   </div>
                  </td>
                 
                </tr>
                <tr>
                  <td>19:00</td>
                  <td colspan="6">
                  	<ul class="members-add">
                    	<li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li class="add-btn"><a href="#."><i class="fa fa-plus"></i></a></li>
                    </ul>
                  </td>
                  <td>
                  	<div class="ts-action-btn">
                  		<a href="#." class="save-btn"><i class="fa fa-save"></i></a>&nbsp;
                   		<a href="#." class="cancel-btn"><i class="fa fa-ban"></i></a>
                   </div>
                  </td>
                 
                </tr>
                <tr>
                  <td>19:00</td>
                  <td colspan="6">
                  	<ul class="members-add">
                    	<li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li class="add-btn"><a href="#."><i class="fa fa-plus"></i></a></li>
                    </ul>
                  </td>
                  <td>
                  	<div class="ts-action-btn">
                  		<a href="#." class="save-btn"><i class="fa fa-save"></i></a>&nbsp;
                   		<a href="#." class="cancel-btn"><i class="fa fa-ban"></i></a>
                   </div>
                  </td>
                 
                </tr>
                <tr>
                  <td>19:00</td>
                  <td colspan="6">
                  	<ul class="members-add">
                    	<li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li class="add-btn"><a href="#."><i class="fa fa-plus"></i></a></li>
                    </ul>
                  </td>
                  <td>
                  	<div class="ts-action-btn">
                  		<a href="#." class="save-btn"><i class="fa fa-save"></i></a>&nbsp;
                   		<a href="#." class="cancel-btn"><i class="fa fa-ban"></i></a>
                   </div>
                  </td>
                 
                </tr>
                <tr>
                  <td>19:00</td>
                  <td colspan="6">
                  	<ul class="members-add">
                    	<li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li>John Doe &nbsp;<a href="#."><i class="fa fa-times"></i></a></li>
                        <li class="add-btn"><a href="#."><i class="fa fa-plus"></i></a></li>
                    </ul>
                  </td>
                  <td>
                  	<div class="ts-action-btn">
                  		<a href="#." class="save-btn"><i class="fa fa-save"></i></a>&nbsp;
                   		<a href="#." class="cancel-btn"><i class="fa fa-ban"></i></a>
                   </div>
                  </td>
                 
                </tr>
              </tbody>
            </table>
            </div>
            </div>
          </div>
        </div>
                </div><!-- tsheet tabs -->
             	</div>
             </div>
         </div><!-- dashboard tee sheet -->
     </div><!-- padding -->
    </div>
  </div>
@endSection  