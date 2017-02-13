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
                        <div class="dropdown-menu pull-right dropdown-menu-scale ng-scope">
                            <a class="dropdown-item" ui-sref="app.inbox.list" href="{{route('admin.profile.profile')}}">
                                <span>Profile</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" ui-sref="access.signin" href="#/access/signin">Sign out</a>
                        </div>
                    </li>
                    <li class="nav-item hidden-md-up"> <a class="nav-link" data-toggle="collapse" data-target="#collapse"> <i class="material-icons">&#xe5d4;</i> </a> </li>
                </ul>
                <!-- / navbar right -->

                <!-- navbar collapse -->
                <div class="collapse navbar-toggleable-sm" id="collapse">
                    <div class="main-page-heading">
                        <h3> <span>Shop</span></h3>
                    </div>
                </div>
                <!-- / navbar collapse -->
            </div>
        </div>
        {{--<div class="app-footer">--}}
        {{--<div class="p-a text-xs">--}}
        {{--<div class="pull-right text-muted"> &copy; Copyright <strong>Flatkit</strong> <span class="hidden-xs-down">- Built with Love v1.1.3</span> <a ui-scroll-to="content"><i class="fa fa-long-arrow-up p-x-sm"></i></a> </div>--}}
        {{--<div class="nav"> <a class="nav-link" href="../">About</a> <span class="text-muted">-</span> <a class="nav-link label accent" href="">Get it</a> </div>--}}
        {{--</div>--}}
        {{--</div>--}}
        <div ui-view class="app-body" id="view">

            <!-- ############ PAGE START-->

            <div class="padding" id="shopPage">
                <div class="shop-main">
                    <div class="row">
                        <div class="shop-inner">
                            <div class="box">
                                <div class="col-md-11">
                                    {{--<div id="shop-carousel" class="owl-carousel owl-theme">--}}
                                        {{--<div class="item active-item">--}}
                                            {{--<div class="parent-category-box">--}}
                                                {{--<div class="media"> <a class="media-left" href="#"> <img class="media-object" src="../assets/images/food-ico.png" alt="icon"> </a>--}}
                                                    {{--<div class="media-body text-left">--}}
                                                        {{--<a href="#.">--}}
                                                            {{--<h4 class="media-heading">Food</h4>--}}
                                                            {{--<p class="media-sub">Categories</p>--}}
                                                        {{--</a>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="item">--}}
                                            {{--<div class="parent-category-box">--}}
                                                {{--<div class="media"> <a class="media-left" href="#"> <img class="media-object" src="../assets/images/beverages.png" alt="icon"> </a>--}}
                                                    {{--<div class="media-body text-left">--}}
                                                        {{--<a href="#.">--}}
                                                            {{--<h4 class="media-heading">Beverages</h4>--}}
                                                            {{--<p class="media-sub">No Shows</p>--}}
                                                        {{--</a>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="item">--}}
                                            {{--<div class="parent-category-box">--}}
                                                {{--<div class="media"> <a class="media-left" href="#"> <img class="media-object" src="../assets/images/clothing.png" alt="icon"> </a>--}}
                                                    {{--<div class="media-body text-left">--}}
                                                        {{--<a href="#.">--}}
                                                            {{--<h4 class="media-heading">Clothing</h4>--}}
                                                            {{--<p class="media-sub">No Shows</p>--}}
                                                        {{--</a>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="item">--}}
                                            {{--<div class="parent-category-box">--}}
                                                {{--<div class="media"> <a class="media-left" href="#"> <img class="media-object" src="../assets/images/golf.png" alt="icon"> </a>--}}
                                                    {{--<div class="media-body text-left">--}}
                                                        {{--<a href="#.">--}}
                                                            {{--<h4 class="media-heading">Brands</h4>--}}
                                                            {{--<p class="media-sub">Golf Brands</p>--}}
                                                        {{--</a>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="item">--}}
                                            {{--<div class="parent-category-box">--}}
                                                {{--<div class="media"> <a class="media-left" href="#"> <img class="media-object" src="../assets/images/beverages.png" alt="icon"> </a>--}}
                                                    {{--<div class="media-body text-left">--}}
                                                        {{--<a href="#.">--}}
                                                            {{--<h4 class="media-heading">Beverages</h4>--}}
                                                            {{--<p class="media-sub">No Shows</p>--}}
                                                        {{--</a>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    <!-- owl carousel -->
                                    <shop-scroller></shop-scroller>
                                </div>
                                <div class="col-md-1">
                                    <div class="add-category-btn text-center">
                                        <a href="#."><i class="fa fa-plus"></i><br>More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- shop-inner ends here -->
                    </div>

                    <div class="row">
                        <div class="main-padd">
                            <div class="col-md-3">
                                {{--<div class="menu-sidebar">--}}
                                    {{--<div class="sidebar-heading">--}}
                                        {{--<h3>Food Menu</h3>--}}
                                    {{--</div>--}}
                                    {{--<div class="menu-list">--}}
                                        {{--<ul>--}}
                                            {{--<li class="active-menu">--}}
                                                {{--<a href="#." class="pull-left">--}}
                                                    {{--<span><i class="fa fa-long-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;Appetizers</span>--}}
                                                {{--</a>--}}
                                                {{--<a href="#." class="pull-right">--}}
                                                    {{--<span><i class="fa fa-pencil"></i></span>--}}
                                                {{--</a>--}}
                                            {{--</li>--}}
                                            {{--<li>--}}
                                                {{--<a href="#." class="pull-left">--}}
                                                    {{--<span><i class="fa fa-long-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;Desserts</span>--}}
                                                {{--</a>--}}
                                                {{--<a href="#." class="pull-right">--}}
                                                    {{--<span><i class="fa fa-pencil"></i></span>--}}
                                                {{--</a>--}}
                                            {{--</li>--}}
                                            {{--<li>--}}
                                                {{--<a href="#." class="pull-left">--}}
                                                    {{--<span><i class="fa fa-long-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;Nuggets</span>--}}
                                                {{--</a>--}}
                                                {{--<a href="#." class="pull-right">--}}
                                                    {{--<span><i class="fa fa-pencil"></i></span>--}}
                                                {{--</a>--}}
                                            {{--</li>--}}
                                            {{--<li>--}}
                                                {{--<a href="#." class="pull-left">--}}
                                                    {{--<span><i class="fa fa-long-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;Pizza</span>--}}
                                                {{--</a>--}}
                                                {{--<a href="#." class="pull-right">--}}
                                                    {{--<span><i class="fa fa-pencil"></i></span>--}}
                                                {{--</a>--}}
                                            {{--</li>--}}
                                            {{--<li>--}}
                                                {{--<a href="#." class="pull-left">--}}
                                                    {{--<span><i class="fa fa-long-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;Starters</span>--}}
                                                {{--</a>--}}
                                                {{--<a href="#." class="pull-right">--}}
                                                    {{--<span><i class="fa fa-pencil"></i></span>--}}
                                                {{--</a>--}}
                                            {{--</li>--}}
                                            {{--<li>--}}
                                                {{--<a href="#." class="pull-left">--}}
                                                    {{--<span><i class="fa fa-long-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;Appetizers</span>--}}
                                                {{--</a>--}}
                                                {{--<a href="#." class="pull-right">--}}
                                                    {{--<span><i class="fa fa-pencil"></i></span>--}}
                                                {{--</a>--}}
                                            {{--</li>--}}
                                            {{--<li>--}}
                                                {{--<a href="#." class="pull-left">--}}
                                                    {{--<span><i class="fa fa-long-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;Desserts</span>--}}
                                                {{--</a>--}}
                                                {{--<a href="#." class="pull-right">--}}
                                                    {{--<span><i class="fa fa-pencil"></i></span>--}}
                                                {{--</a>--}}
                                            {{--</li>--}}
                                            {{--<li>--}}
                                                {{--<a href="#." class="pull-left">--}}
                                                    {{--<span><i class="fa fa-long-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;Nuggets</span>--}}
                                                {{--</a>--}}
                                                {{--<a href="#." class="pull-right">--}}
                                                    {{--<span><i class="fa fa-pencil"></i></span>--}}
                                                {{--</a>--}}
                                            {{--</li>--}}
                                            {{--<li>--}}
                                                {{--<a href="#." class="pull-left">--}}
                                                    {{--<span><i class="fa fa-long-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;Pizza</span>--}}
                                                {{--</a>--}}
                                                {{--<a href="#." class="pull-right">--}}
                                                    {{--<span><i class="fa fa-pencil"></i></span>--}}
                                                {{--</a>--}}
                                            {{--</li>--}}
                                            {{--<li>--}}
                                                {{--<a href="#." class="pull-left">--}}
                                                    {{--<span><i class="fa fa-long-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;Starters</span>--}}
                                                {{--</a>--}}
                                                {{--<a href="#." class="pull-right">--}}
                                                    {{--<span><i class="fa fa-pencil"></i></span>--}}
                                                {{--</a>--}}
                                            {{--</li>--}}
                                        {{--</ul>--}}
                                        {{--<div class="clearfix"></div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                <shop-menu></shop-menu>
                            </div>
                            <div class="col-md-9">
                                <div class="segments-inner">
                                    <div class="box">
                                        <div class="inner-header">
                                            <div class="">
                                                <div class="col-md-4">
                                                    <div class="inner-page-heading text-left">
                                                        <h3>Appetizers</h3>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="search-form text-right">
                                                        <form action="#." method="post">
                                                            <div class="search-field">
                                    	<span class="search-box">
                                        	<input type="text" name="search" class="search-bar">
                                            <button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
                                        </span>
                                                                <span class="">
                                        	<button type="button" name="add-segment" class="btn-def"><i class="fa fa-plus-circle"></i>&nbsp;Add Item</button>
                                    	</span>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div><!-- inner header -->
                                        <table class="table table-hover b-t">
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <div class="section-1 sec-style">
                                                        <h3>Segment 01</h3>
                                                        <p>Sunday Members Only</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="section-2 sec-style">
                                                        <h3>90%</h3>
                                                        <p>Reach</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="section-3 sec-style">
                                                        <p>Dec 9 2016 - 2:13:00 AM</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="section-3 sec-style">
                                                        <p><a href="#." class="blue-c">Active</a></p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="section-3 sec-style">
                                                        <p>
                                                            <span><a href="#." class="blue-cb">edit</a></span>&nbsp;&nbsp;&nbsp;
                                                            <span><a href="#." class="del-icon"><i class="fa fa-trash"></i></a></span>
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="section-1 sec-style">
                                                        <h3>Segment 01</h3>
                                                        <p>Sunday Members Only</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="section-2 sec-style">
                                                        <h3>90%</h3>
                                                        <p>Reach</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="section-3 sec-style">
                                                        <p>Dec 9 2016 - 2:13:00 AM</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="section-3 sec-style">
                                                        <p><a href="#." class="blue-c">Active</a></p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="section-3 sec-style">
                                                        <p>
                                                            <span><a href="#." class="blue-cb">edit</a></span>&nbsp;&nbsp;&nbsp;
                                                            <span><a href="#." class="del-icon"><i class="fa fa-trash"></i></a></span>
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="section-1 sec-style">
                                                        <h3>Segment 01</h3>
                                                        <p>Sunday Members Only</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="section-2 sec-style">
                                                        <h3>90%</h3>
                                                        <p>Reach</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="section-3 sec-style">
                                                        <p>Dec 9 2016 - 2:13:00 AM</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="section-3 sec-style">
                                                        <p><a href="#." class="blue-c">Active</a></p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="section-3 sec-style">
                                                        <p>
                                                            <span><a href="#." class="blue-cb">edit</a></span>&nbsp;&nbsp;&nbsp;
                                                            <span><a href="#." class="del-icon"><i class="fa fa-trash"></i></a></span>
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="section-1 sec-style">
                                                        <h3>Segment 01</h3>
                                                        <p>Sunday Members Only</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="section-2 sec-style">
                                                        <h3>90%</h3>
                                                        <p>Reach</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="section-3 sec-style">
                                                        <p>Dec 9 2016 - 2:13:00 AM</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="section-3 sec-style">
                                                        <p><a href="#." class="blue-c">Active</a></p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="section-3 sec-style">
                                                        <p>
                                                            <span><a href="#." class="blue-cb">edit</a></span>&nbsp;&nbsp;&nbsp;
                                                            <span><a href="#." class="del-icon"><i class="fa fa-trash"></i></a></span>
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="section-1 sec-style">
                                                        <h3>Segment 01</h3>
                                                        <p>Sunday Members Only</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="section-2 sec-style">
                                                        <h3>90%</h3>
                                                        <p>Reach</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="section-3 sec-style">
                                                        <p>Dec 9 2016 - 2:13:00 AM</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="section-3 sec-style">
                                                        <p><a href="#." class="blue-c">Active</a></p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="section-3 sec-style">
                                                        <p>
                                                            <span><a href="#." class="blue-cb">edit</a></span>&nbsp;&nbsp;&nbsp;
                                                            <span><a href="#." class="del-icon"><i class="fa fa-trash"></i></a></span>
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div><!-- main padd ends here -->
                    </div>
                </div>
                <!-- Segments Page End -->
            </div>


        </div>
    </div>


    @include("admin.__vue_components.shop.shop-scroller");
    @include("admin.__vue_components.shop.shop-menu");
    <script>
        var _shopMenuList = [
            {menuItem: 'Appetizers'},{menuItem: 'Desserts'},{menuItem: 'Nuggets'},{menuItem: 'Pizza'},
            {menuItem: 'Starters'},{menuItem: 'Desserts'},{menuItem: 'Appetizers'},{menuItem: 'Nuggets'},
            {menuItem: 'Pizza'},{menuItem: 'Starters'}
        ];

        var vue = new Vue({
           el: "#shopPage"
        });

    </script>

    @endSection