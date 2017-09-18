@extends('admin.__layouts.admin-layout')
@section('heading')
	Shop
	@endSection
@section('main')
	<div ui-view class="app-body" id="view">

		<!-- ############ PAGE START-->

		<div class="padding" id="shopPage">
			<restaurant :main-categories="mainCategories" :categories="categories" :base-url="baseUrl"></restaurant>
			<!-- Segments Page End -->
		</div>


	</div>

@endsection

@section('page-specific-scripts')
{{--@include("admin.__vue_components.shop.shop-scroller");--}}
{{--@include("admin.__vue_components.shop.shop-menu");--}}
@include("admin.__vue_components.restaurant.restaurant");
<script>

        var _mainCategories = {!!$mainCategories!!};
		var _categories = {!!$categories!!};

        var vue = new Vue({
           el: "#shopPage",
           data: {
               mainCategories: _mainCategories,
               categories:_categories,
			   baseUrl :"{{url('')}}",
           }

        });

    </script>

@endSection
