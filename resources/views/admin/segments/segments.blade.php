@extends('admin.__layouts.admin-layout')
@section('main')
<div id="content" class="app-content box-shadow-z0" role="main">
    <div class="app-header white box-shadow">
        <div class="navbar"> 
            <!-- Open side - Naviation on mobile --> 
            <a data-toggle="modal" data-target="#aside" class="navbar-item pull-left hidden-lg-up"> <i class="material-icons"></i> </a> 
            <!-- / --> 
            <!-- Page title - Bind to $state's title -->
            <div class="navbar-item pull-left h5" ng-bind="$state.current.data.title" id="pageTitle"></div>
            <!-- navbar right -->
            <ul class="nav navbar-nav pull-right">
                <li class="nav-item dropdown pos-stc-xs"> <a class="nav-link" href="" data-toggle="dropdown"> <i class="material-icons"></i> <span class="label label-sm up warn">3</span> </a>
                    <div ui-include="'../views/blocks/dropdown.notification.html'"></div>
                </li>
                <li class="nav-item dropdown"> <a class="nav-link clear" href="" data-toggle="dropdown"> <span class="avatar w-32"> <img src="../assets/images/a0.jpg" alt="..."> <i class="on b-white bottom"></i> </span> </a>
                    <div ui-include="'../views/blocks/dropdown.user.html'"></div>
                </li>
                <li class="nav-item hidden-md-up"> <a class="nav-link" data-toggle="collapse" data-target="#collapse"> <i class="material-icons"></i> </a> </li>
            </ul>
            <!-- / navbar right --> 

            <!-- navbar collapse -->
            <div class="collapse navbar-toggleable-sm" id="collapse">
                <div class="main-page-heading">
                    <h3> <span>Segments</span></h3>
                </div>
            </div>
            <!-- / navbar collapse --> 
        </div>
    </div>
    {{--<div class="app-footer">--}}
        {{--<div class="p-a text-xs">--}}
            {{--<div class="pull-right text-muted"> © Copyright <strong>Grit Golf</strong> <span class="hidden-xs-down">- Built with Love v1.1.3</span> <a ui-scroll-to="content"><i class="fa fa-long-arrow-up p-x-sm"></i></a> </div>--}}
            {{--<div class="nav"> <a class="nav-link" href="../">About</a> <span class="text-muted">-</span> <a class="nav-link label accent" href="http://themeforest.net/user/flatfull/portfolio?ref=flatfull">Get it</a> </div>--}}
        {{--</div>--}}
    {{--</div>--}}
    <div ui-view="" class="app-body" id="view"> 

        <!-- Profile Page Start -->

        <div id="segments-vue-container" class="segments-main padding">
            <div class="row"> 
                <div class="segments-inner">
                    <div class="box">
                        <div class="inner-header">
                            <div class="">
                                <div class="col-md-4">
                                    <div class="inner-page-heading text-left">
                                        <h3>Segments Listing</h3>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="search-form text-right">
                                        <form action="#." method="post">
                                            <div class="search-field">
                                                <span class="search-box">
                                                    <input name="search" class="search-bar" type="text">
                                                    <button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
                                                </span>
                                                <span class="">
                                                    <button type="button" name="add-segment" class="btn-def"><i class="fa fa-plus-circle"></i>&nbsp;Add New Segment</button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div><!-- inner header -->
                        <segments-table :segments-list="segmentsList"></segments-table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Segments Page End --> 
    </div>
</div>
@include("admin.__vue_components.segments.segments-table")
<script>
    var baseUrl = "{{url('')}}";
    _segmentsList = [{name:"Segment 1",description:"Sunday Members Only",coverage:"90%",dateTime:"Dec 9 2016 - 2:13:00 AM",status:"Active"},
                    {name:"Segment 1",description:"Sunday Members Only",coverage:"90%",dateTime:"Dec 9 2016 - 2:13:00 AM",status:"Active"},
                    {name:"Segment 1",description:"Sunday Members Only",coverage:"90%",dateTime:"Dec 9 2016 - 2:13:00 AM",status:"Active"},
                    {name:"Segment 1",description:"Sunday Members Only",coverage:"90%",dateTime:"Dec 9 2016 - 2:13:00 AM",status:"Active"},
                    {name:"Segment 1",description:"Sunday Members Only",coverage:"90%",dateTime:"Dec 9 2016 - 2:13:00 AM",status:"Active"}];
   
    var vue = new Vue({
        el: "#segments-vue-container",
        data: {
            segmentsList:[],
            latestPageLoaded:0,
            ajaxRequestInProcess:false,
        },
        methods: {
            loadNextPage:function(){
                //add sample data to array to check scroll functionality
                if(this.latestPageLoaded == 0){
                    for(x=0; x<_segmentsList.length; x++){
                         this.segmentsList.push(_segmentsList[x]);
                    }
                   
                }
                
                
                return;
                
                //End Check: Delete block after real data is available
                if(!this.ajaxRequestInProcess){
                    this.ajaxRequestInProcess = true;
                    var request = $.ajax({

                        url: baseUrl+'/segements?page='+(this.latestPageLoaded+1),
                        method: "GET",
                        success:function(msg){
                                    this.ajaxRequestInProcess = false;
                                    this.latestPageLoaded++;
                                    //Success code to follow

                                }.bind(this),

                        error: function(jqXHR, textStatus ) {
                                    this.ajaxRequestInProcess = false;
                                    
                                    //Error code to follow
                                    
                                    
                               }.bind(this)
                    }); 
                }
                
            }
        }
    });
    
    $(document).ready(function() {
       vue.loadNextPage();
       console.log("bottom!");
       
    });
    $(window).scroll(function() {
       if($(window).scrollTop() + $(window).height() == $(document).height()) {
           vue.loadNextPage();
           console.log("bottom!");
       }
    });
</script>

@endSection  