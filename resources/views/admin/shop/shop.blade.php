@extends('admin.__layouts.admin-layout')
@section('heading')
	Shop
	@endSection
@section('main')
	<div ui-view class="app-body" id="view">

		<!-- ############ PAGE START-->

		<div class="padding" id="shopPage">
			<div class="shop-main">
				<div class="row">
					<div class="shop-inner">
						<div class="box">
							<div class="col-md-11">
								{{--
								<div id="shop-carousel" class="owl-carousel owl-theme">
									--}} {{--
									<div class="item active-item">
										--}} {{--
										<div class="parent-category-box">
											--}} {{--
											<div class="media">
												<a class="media-left" href="#"> <img class="media-object"
													src="../assets/images/food-ico.png" alt="icon">
												</a>--}} {{--
												<div class="media-body text-left">
													--}} {{--<a href="#.">--}} {{--
														<h4 class="media-heading">Food</h4>--}} {{--
														<p class="media-sub">Categories</p>--}} {{--
													</a>--}} {{--
												</div>
												--}} {{--
											</div>
											--}} {{--
										</div>
										--}} {{--
									</div>
									--}} {{--
									<div class="item">
										--}} {{--
										<div class="parent-category-box">
											--}} {{--
											<div class="media">
												<a class="media-left" href="#"> <img class="media-object"
													src="../assets/images/beverages.png" alt="icon">
												</a>--}} {{--
												<div class="media-body text-left">
													--}} {{--<a href="#.">--}} {{--
														<h4 class="media-heading">Beverages</h4>--}} {{--
														<p class="media-sub">No Shows</p>--}} {{--
													</a>--}} {{--
												</div>
												--}} {{--
											</div>
											--}} {{--
										</div>
										--}} {{--
									</div>
									--}} {{--
									<div class="item">
										--}} {{--
										<div class="parent-category-box">
											--}} {{--
											<div class="media">
												<a class="media-left" href="#"> <img class="media-object"
													src="../assets/images/clothing.png" alt="icon">
												</a>--}} {{--
												<div class="media-body text-left">
													--}} {{--<a href="#.">--}} {{--
														<h4 class="media-heading">Clothing</h4>--}} {{--
														<p class="media-sub">No Shows</p>--}} {{--
													</a>--}} {{--
												</div>
												--}} {{--
											</div>
											--}} {{--
										</div>
										--}} {{--
									</div>
									--}} {{--
									<div class="item">
										--}} {{--
										<div class="parent-category-box">
											--}} {{--
											<div class="media">
												<a class="media-left" href="#"> <img class="media-object"
													src="../assets/images/golf.png" alt="icon">
												</a>--}} {{--
												<div class="media-body text-left">
													--}} {{--<a href="#.">--}} {{--
														<h4 class="media-heading">Brands</h4>--}} {{--
														<p class="media-sub">Golf Brands</p>--}} {{--
													</a>--}} {{--
												</div>
												--}} {{--
											</div>
											--}} {{--
										</div>
										--}} {{--
									</div>
									--}} {{--
									<div class="item">
										--}} {{--
										<div class="parent-category-box">
											--}} {{--
											<div class="media">
												<a class="media-left" href="#"> <img class="media-object"
													src="../assets/images/beverages.png" alt="icon">
												</a>--}} {{--
												<div class="media-body text-left">
													--}} {{--<a href="#.">--}} {{--
														<h4 class="media-heading">Beverages</h4>--}} {{--
														<p class="media-sub">No Shows</p>--}} {{--
													</a>--}} {{--
												</div>
												--}} {{--
											</div>
											--}} {{--
										</div>
										--}} {{--
									</div>
									--}} {{--
								</div>
								--}}
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
							{{--
							<div class="menu-sidebar">
								--}} {{--
								<div class="sidebar-heading">
									--}} {{--
									<h3>Food Menu</h3>
									--}} {{--
								</div>
								--}} {{--
								<div class="menu-list">
									--}} {{--
									<ul>
										--}} {{--
										<li class="active-menu">--}} {{--<a href="#."
											class="pull-left">--}} {{--<span><i
													class="fa fa-long-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;Appetizers</span>--}}
												{{--
										</a>--}} {{--<a href="#." class="pull-right">--}} {{--<span><i
													class="fa fa-pencil"></i></span>--}} {{--
										</a>--}} {{--
										</li>--}} {{--
										<li>--}} {{--<a href="#." class="pull-left">--}} {{--<span><i
													class="fa fa-long-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;Desserts</span>--}}
												{{--
										</a>--}} {{--<a href="#." class="pull-right">--}} {{--<span><i
													class="fa fa-pencil"></i></span>--}} {{--
										</a>--}} {{--
										</li>--}} {{--
										<li>--}} {{--<a href="#." class="pull-left">--}} {{--<span><i
													class="fa fa-long-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;Nuggets</span>--}}
												{{--
										</a>--}} {{--<a href="#." class="pull-right">--}} {{--<span><i
													class="fa fa-pencil"></i></span>--}} {{--
										</a>--}} {{--
										</li>--}} {{--
										<li>--}} {{--<a href="#." class="pull-left">--}} {{--<span><i
													class="fa fa-long-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;Pizza</span>--}}
												{{--
										</a>--}} {{--<a href="#." class="pull-right">--}} {{--<span><i
													class="fa fa-pencil"></i></span>--}} {{--
										</a>--}} {{--
										</li>--}} {{--
										<li>--}} {{--<a href="#." class="pull-left">--}} {{--<span><i
													class="fa fa-long-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;Starters</span>--}}
												{{--
										</a>--}} {{--<a href="#." class="pull-right">--}} {{--<span><i
													class="fa fa-pencil"></i></span>--}} {{--
										</a>--}} {{--
										</li>--}} {{--
										<li>--}} {{--<a href="#." class="pull-left">--}} {{--<span><i
													class="fa fa-long-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;Appetizers</span>--}}
												{{--
										</a>--}} {{--<a href="#." class="pull-right">--}} {{--<span><i
													class="fa fa-pencil"></i></span>--}} {{--
										</a>--}} {{--
										</li>--}} {{--
										<li>--}} {{--<a href="#." class="pull-left">--}} {{--<span><i
													class="fa fa-long-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;Desserts</span>--}}
												{{--
										</a>--}} {{--<a href="#." class="pull-right">--}} {{--<span><i
													class="fa fa-pencil"></i></span>--}} {{--
										</a>--}} {{--
										</li>--}} {{--
										<li>--}} {{--<a href="#." class="pull-left">--}} {{--<span><i
													class="fa fa-long-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;Nuggets</span>--}}
												{{--
										</a>--}} {{--<a href="#." class="pull-right">--}} {{--<span><i
													class="fa fa-pencil"></i></span>--}} {{--
										</a>--}} {{--
										</li>--}} {{--
										<li>--}} {{--<a href="#." class="pull-left">--}} {{--<span><i
													class="fa fa-long-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;Pizza</span>--}}
												{{--
										</a>--}} {{--<a href="#." class="pull-right">--}} {{--<span><i
													class="fa fa-pencil"></i></span>--}} {{--
										</a>--}} {{--
										</li>--}} {{--
										<li>--}} {{--<a href="#." class="pull-left">--}} {{--<span><i
													class="fa fa-long-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;Starters</span>--}}
												{{--
										</a>--}} {{--<a href="#." class="pull-right">--}} {{--<span><i
													class="fa fa-pencil"></i></span>--}} {{--
										</a>--}} {{--
										</li>--}} {{--
									</ul>
									--}} {{--
									<div class="clearfix"></div>
									--}} {{--
								</div>
								--}} {{--
							</div>
							--}}
							<shop-menu :menu-list-m="menuListM"></shop-menu>
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
															<span class="search-box"> <input type="text"
																name="search" class="search-bar">
																<button type="submit" class="search-btn">
																	<i class="fa fa-search"></i>
																</button>
															</span> <span class="">
																<button type="button" name="add-segment" class="btn-def">
																	<i class="fa fa-plus-circle"></i>&nbsp;Add Item
																</button>
															</span>
														</div>
													</form>
												</div>
											</div>
											<div class="clearfix"></div>
										</div>
									</div>
									<!-- inner header -->
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
														<p>
															<a href="#." class="blue-c">Active</a>
														</p>
													</div>
												</td>
												<td>
													<div class="section-3 sec-style">
														<p>
															<span><a href="#." class="blue-cb">edit</a></span>&nbsp;&nbsp;&nbsp;
															<span><a href="#." class="del-icon"><i
																	class="fa fa-trash"></i></a></span>
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
														<p>
															<a href="#." class="blue-c">Active</a>
														</p>
													</div>
												</td>
												<td>
													<div class="section-3 sec-style">
														<p>
															<span><a href="#." class="blue-cb">edit</a></span>&nbsp;&nbsp;&nbsp;
															<span><a href="#." class="del-icon"><i
																	class="fa fa-trash"></i></a></span>
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
														<p>
															<a href="#." class="blue-c">Active</a>
														</p>
													</div>
												</td>
												<td>
													<div class="section-3 sec-style">
														<p>
															<span><a href="#." class="blue-cb">edit</a></span>&nbsp;&nbsp;&nbsp;
															<span><a href="#." class="del-icon"><i
																	class="fa fa-trash"></i></a></span>
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
														<p>
															<a href="#." class="blue-c">Active</a>
														</p>
													</div>
												</td>
												<td>
													<div class="section-3 sec-style">
														<p>
															<span><a href="#." class="blue-cb">edit</a></span>&nbsp;&nbsp;&nbsp;
															<span><a href="#." class="del-icon"><i
																	class="fa fa-trash"></i></a></span>
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
														<p>
															<a href="#." class="blue-c">Active</a>
														</p>
													</div>
												</td>
												<td>
													<div class="section-3 sec-style">
														<p>
															<span><a href="#." class="blue-cb">edit</a></span>&nbsp;&nbsp;&nbsp;
															<span><a href="#." class="del-icon"><i
																	class="fa fa-trash"></i></a></span>
														</p>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<!-- main padd ends here -->
				</div>
			</div>
			<!-- Segments Page End -->
		</div>


	</div>

@endsection

@section('page-specific-scripts')
@include("admin.__vue_components.shop.shop-scroller");
@include("admin.__vue_components.shop.shop-menu");
<script>
        var _shopMenuList = [
            {menuItem: 'Appetizers'},{menuItem: 'Desserts'},{menuItem: 'Nuggets'},{menuItem: 'Pizza'},
            {menuItem: 'Starters'},{menuItem: 'Desserts'},{menuItem: 'Appetizers'},{menuItem: 'Nuggets'},
            {menuItem: 'Pizza'},{menuItem: 'Starters'}
        ];

        //var _shopFood = {menu:{title:'',menuList:[{id:'1',name:'Appetizers'},{id:'2',name:'Pizza'},{id:'3',name:'Nuggets'}]},items:{title:'',menuList:[]}};

        var _shopFood = {
            			menu:{
            			    title:'Food',
                            menuList:[
                                {id:1,name:'Appetizers'},
                                {id:2,name:'Pizza'},
                                {id:3,name:'Nuggets'},
                                {id:4,name:'Starters'}
                            ]
						},
                        items:{
            			    title:'',
                            menuList:[{name:"Segment 1",description:"Sunday Members Only",coverage:"90%",dateTime:"Dec 9 2016 - 2:13:00 AM",status:"Active"},
                                {name:"Segment 1",description:"Sunday Members Only",coverage:"90%",dateTime:"Dec 9 2016 - 2:13:00 AM",status:"Active"},
                                {name:"Segment 1",description:"Sunday Members Only",coverage:"90%",dateTime:"Dec 9 2016 - 2:13:00 AM",status:"Active"},
                                {name:"Segment 1",description:"Sunday Members Only",coverage:"90%",dateTime:"Dec 9 2016 - 2:13:00 AM",status:"Active"},
                                {name:"Segment 1",description:"Sunday Members Only",coverage:"90%",dateTime:"Dec 9 2016 - 2:13:00 AM",status:"Active"}]
                        }
					};

        var vue = new Vue({
           el: "#shopPage",
           data: {
               menuListM:_shopFood
           }

        });

    </script>

@endSection
