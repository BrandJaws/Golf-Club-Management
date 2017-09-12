@extends('admin.__layouts.admin-layout')
@section('heading')
    Rewards
    @endSection
@section('main')
	<div ui-view class="app-body" id="view">
		<!-- ############ PAGE START-->
		<div id="rewards-list-table" class="segments-main padding">
			<div class="row">
				<div class="segments-inner">
					<div class="box">
						<div class="inner-header">
							<div class="">
								<div class="col-md-8">
									<div class="search-form">
										<form action="#." method="post" v-on:click.prevent>
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
									<a href="{{ Request::url() }}/create" class="btn-def btn"><i
										class="fa fa-plus-circle"></i>&nbsp;Create Offer</a>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<!-- inner header -->
						<offers :rewards-list="rewardsList"> </offers>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('page-specific-scripts')
@include("admin.__vue_components.rewards.rewards-table")
<script>
        var baseUrl = "{{url('')}}";
        _rewardsList = [{name:"Rewarad 01",description:"Description goes here...",segmentName:"Segment 01",segmentDescription:"Description goes here...",coverage:"90%",redeemed:"04",claimed:"03",expired:"0"},
            {name:"Rewarad 01",description:"Description goes here...",segmentName:"Segment 01",segmentDescription:"Description goes here...",coverage:"90%",redeemed:"04",claimed:"03",expired:"0"},
            {name:"Rewarad 02",description:"Description goes here...",segmentName:"Segment 02",segmentDescription:"Description goes here...",coverage:"90%",redeemed:"04",claimed:"03",expired:"0"},
            {name:"Rewarad 03",description:"Description goes here...",segmentName:"Segment 03",segmentDescription:"Description goes here...",coverage:"90%",redeemed:"04",claimed:"03",expired:"0"},
            {name:"Rewarad 04",description:"Description goes here...",segmentName:"Segment 04",segmentDescription:"Description goes here...",coverage:"90%",redeemed:"04",claimed:"03",expired:"0"},
            {name:"Rewarad 05",description:"Description goes here...",segmentName:"Segment 05",segmentDescription:"Description goes here...",coverage:"90%",redeemed:"04",claimed:"03",expired:"0"},
            {name:"Rewarad 06",description:"Description goes here...",segmentName:"Segment 06",segmentDescription:"Description goes here...",coverage:"90%",redeemed:"04",claimed:"03",expired:"0"}];

        var vue = new Vue({
            el: "#rewards-list-table",
            data: {
                rewardsList:[],
                latestPageLoaded:0,
                ajaxRequestInProcess:false
            },
            methods: {
                loadNextPage:function(){
                    if(this.latestPageLoaded == 0){
                        for(x=0; x<_rewardsList.length; x++){
                            this.rewardsList.push(_rewardsList[x]);
                        }

                    }
                    return;
                }
            },
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

//        $(window).scroll(function() {
//            var flag = true;
//            if($(window).scrollTop() + $(window).height() == $(document).height()) {
//                if(flag==true) {
//                    vue.loadNextPage();
//                    console.log("bottom!");
//                    flag = false;
//                }
//            }
//            Vue.nextTick(function(){
//                flag = true;
//            });
//        });

    </script>

@endSection
