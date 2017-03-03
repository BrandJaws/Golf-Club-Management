@extends('admin.__layouts.admin-layout')
@section('heading')
	Members
	@endSection
@section('main')
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
												<span class="search-box"> <input type="text" name="search"
													class="search-bar">
													<button type="submit" class="search-btn">
														<i class="fa fa-search"></i>
													</button>
												</span>
											</div>
										</form>
									</div>
								</div>
								<div class="col-md-4 text-right">
									<button class="btn-def btn">
										<i class="fa fa-plus-circle"></i>&nbsp;Add Members
									</button>
									<button class="btn-def btn">
										<i class="fa fa-upload"></i>&nbsp;Import CSV
									</button>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<!-- inner header -->
						<table class="table table-hover b-t">
							<thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>Gender</th>
									<th>Warnings</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Bilal Nisar</td>
									<td>bilalbinnisar@gmail.com</td>
									<td>Male</td>
									<td>02</td>
									<td><a href="#." class="blue-cb" title="Edit">edit</a>
										&nbsp;&nbsp; <a href="#." class="del-icon" title="Delete"><i
											class="fa fa-trash"></i></a></td>
								</tr>
								<tr>
									<td>Kashif Chishti</td>
									<td>kashifchishti@gmail.com</td>
									<td>Male</td>
									<td>03</td>
									<td><a href="#." class="blue-cb" title="Edit">edit</a>
										&nbsp;&nbsp; <a href="#." class="del-icon" title="Delete"><i
											class="fa fa-trash"></i></a></td>
								</tr>
								<tr>
									<td>Salman Abid</td>
									<td>salmanabid@gmail.com</td>
									<td>Male</td>
									<td>00</td>
									<td><a href="#." class="blue-cb" title="Edit">edit</a>
										&nbsp;&nbsp; <a href="#." class="del-icon" title="Delete"><i
											class="fa fa-trash"></i></a></td>
								</tr>
								<tr>
									<td>Shandna Karim</td>
									<td>shandnakarim@gmail.com</td>
									<td>Female</td>
									<td>00</td>
									<td><a href="#." class="blue-cb" title="Edit">edit</a>
										&nbsp;&nbsp; <a href="#." class="del-icon" title="Delete"><i
											class="fa fa-trash"></i></a></td>
								</tr>
								<tr>
									<td>Umar Daraz</td>
									<td>umardaraz@gmail.com</td>
									<td>Male</td>
									<td>05</td>
									<td><a href="#." class="blue-cb" title="Edit">edit</a>
										&nbsp;&nbsp; <a href="#." class="del-icon" title="Delete"><i
											class="fa fa-trash"></i></a></td>
								</tr>
								<tr>
									<td>Afshan Zubair</td>
									<td>afshanzubair@gmail.com</td>
									<td>Female</td>
									<td>00</td>
									<td><a href="#." class="blue-cb" title="Edit">edit</a>
										&nbsp;&nbsp; <a href="#." class="del-icon" title="Delete"><i
											class="fa fa-trash"></i></a></td>
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

@endSection
