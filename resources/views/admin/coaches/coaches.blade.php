@extends('admin.__layouts.admin-layout')
@section('heading')
    Coaches
    @endSection
@section('main')
	<div ui-view class="app-body" id="view">
		<!-- ############ PAGE START-->
		<div id="coaches-list-table" class="segments-main padding">
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
									<a href="{{Request::url()}}/create" class="btn-def btn"><i
										class="fa fa-plus-circle"></i>&nbsp;Add Coaches</a>
									<button class="btn-def btn">
										<i class="fa fa-upload"></i>&nbsp;Import CSV
									</button>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<!-- inner header -->
						<coaches :coaches-list="coachesList"> </coaches>
					</div>
				</div>
			</div>
		</div>
	</div>

@include("admin.__vue_components.coaches.coaches-table")
<script>
        var baseUrl = "{{url('')}}";
        _coachesList = [{name:'John Wick',email:'john.wick@mail.com',contact:'0423456783',spcl:'Strong, Fast, Lean'},
            {name:'Johnny Depp',email:'johnny.depp@mail.com',contact:'042343284',spcl:'Great Runner, Bowler, Chakka'},
            {name:'Emma Watson',email:'emma.watson@mail.com',contact:'98323456783',spcl:'Diver, Swimmer, Noob'},
            {name:'Emma Brows',email:'emma.brown@mail.com',contact:'0423656783',spcl:'Lovely, Amazing, Nerd'},
            {name:'Harry Potter',email:'harry.potter@mail.com',contact:'34563456783',spcl:'Great Runner, Bowler, Chakka'},
            {name:'Atif Aslam',email:'atif.aslam@mail.com',contact:'65472836399',spcl:'Lovely, Amazing, Nerd'},
            {name:'Emma Watson',email:'emma.watson@mail.com',contact:'98323456783',spcl:'Strong, Fast, Lean'},
            {name:'Emma Brows',email:'emma.brown@mail.com',contact:'0423656783',spcl:'Diver, Swimmer, Noob'},
            {name:'Harry Potter',email:'harry.potter@mail.com',contact:'34563456783',spcl:'Lovely, Amazing, Nerd'},];

        var vue = new Vue({
            el: "#coaches-list-table",
            data: {
                coachesList:[],
                latestPageLoaded:0,
                ajaxRequestInProcess:false
            },
            methods: {
                loadNextPage:function(){
                    if(this.latestPageLoaded == 0){
                        for(x=0; x<_coachesList.length; x++){
                            this.coachesList.push(_coachesList[x]);
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
    </script>

@endSection
