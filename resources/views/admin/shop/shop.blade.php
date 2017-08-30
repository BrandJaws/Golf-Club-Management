@extends('admin.__layouts.admin-layout')
@section('heading')
	Shop
	@endSection
@section('main')
	<div ui-view class="app-body" id="view">

		<!-- ############ PAGE START-->

		<div class="padding" id="shopPage">
			<shop :categories="categories" :base-url="baseUrl"></shop>
			<!-- Segments Page End -->
		</div>


	</div>

@endsection

@section('page-specific-scripts')
{{--@include("admin.__vue_components.shop.shop-scroller");--}}
{{--@include("admin.__vue_components.shop.shop-menu");--}}
@include("admin.__vue_components.shop.shop");
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

		var _categories = {!!$categories!!};

        var vue = new Vue({
           el: "#shopPage",
           data: {
               categories:_categories,
			   baseUrl :"{{url('')}}",
           }

        });

    </script>

@endSection
