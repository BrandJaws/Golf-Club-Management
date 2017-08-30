<template id = "shopTemplate">
    <div class="shop-main">
        <div class="row">
            <div class="shop-inner">
                <div class="box">
                    <div class="col-md-11">

                        <div id="shop-carousel" class="owl-carousel owl-theme">

                            <div class="item active-item">

                                <div class="parent-category-box">

                                    <div class="media">
                                        <a class="media-left" href="#"> <img class="media-object"
                                                                             src="../assets/images/food-ico.png" alt="icon">
                                        </a>
                                        <div class="media-body text-left">
                                            <a href="#.">
                                                <h4 class="media-heading">Food</h4>
                                                <p class="media-sub">Categories</p>
                                            </a>
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="item">

                                <div class="parent-category-box">

                                    <div class="media">
                                        <a class="media-left" href="#"> <img class="media-object"
                                                                             src="../assets/images/beverages.png" alt="icon">
                                        </a>
                                        <div class="media-body text-left">
                                            <a href="#.">
                                                <h4 class="media-heading">Beverages</h4>
                                                <p class="media-sub">No Shows</p>
                                            </a>
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="item">

                                <div class="parent-category-box">

                                    <div class="media">
                                        <a class="media-left" href="#"> <img class="media-object"
                                                                             src="../assets/images/clothing.png" alt="icon">
                                        </a>
                                        <div class="media-body text-left">
                                            <a href="#.">
                                                <h4 class="media-heading">Clothing</h4>
                                                <p class="media-sub">No Shows</p>
                                            </a>
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="item">

                                <div class="parent-category-box">

                                    <div class="media">
                                        <a class="media-left" href="#"> <img class="media-object"
                                                                             src="../assets/images/golf.png" alt="icon">
                                        </a>
                                        <div class="media-body text-left">
                                            <a href="#.">
                                                <h4 class="media-heading">Brands</h4>
                                                <p class="media-sub">Golf Brands</p>
                                            </a>
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="item">

                                <div class="parent-category-box">

                                    <div class="media">
                                        <a class="media-left" href="#"> <img class="media-object"
                                                                             src="../assets/images/beverages.png" alt="icon">
                                        </a>
                                        <div class="media-body text-left">
                                            <a href="#.">
                                                <h4 class="media-heading">Beverages</h4>
                                                <p class="media-sub">No Shows</p>
                                            </a>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- owl carousel -->

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

                    <div class="menu-sidebar">

                        <div class="sidebar-heading">

                            <h3>Categories</h3>

                        </div>

                        <div class="menu-list">

                            <ul>

                                <li v-for="category in categoriesData" class="active-menu" v-on:click="categorySelected(category)"> <a href="#."
                                                                                                                                       class="pull-left"> <span><i
                                                    class="fa fa-long-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;@{{category.name}}</span>

                                    </a> <a href="#." class="pull-right"> <span><i
                                                    class="fa fa-pencil"></i></span>
                                    </a>
                                </li>

                            </ul>

                            <div class="clearfix"></div>

                        </div>

                    </div>

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
																<a href="{{route('admin.shop.create_product')}}">
                                                                    <button type="button" name="add-segment" class="btn-def">
                                                                        <i class="fa fa-plus-circle"></i>&nbsp;Add Item
                                                                    </button>
                                                                </a>
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
                                <tr v-for="product in categories[selectedCategoryIndex].products.data">
                                    <td>
                                        <div class="section-1 sec-style">
                                            <h3>@{{ product.name }}</h3>
                                            <p>Product Name</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="section-3 sec-style">
                                            <img :src="baseUrl+'/'+product.image"></imgsrc>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="section-3 sec-style">
                                            <h3>@{{ product.in_stock }}</h3>
                                            <p>In Stock</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="section-3 sec-style">
                                            <p>
                                                <span><a :href="baseUrl+'/admin/shop/products/'+product.id+'/edit'" class="blue-cb">edit</a></span>&nbsp;&nbsp;&nbsp;
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

</template>

<script>

    Vue.component('shop',{
        template: "#shopTemplate",
        mounted:function(){
            $(".owl-carousel").owlCarousel({
                //autoplay: true,
                //autoplayTimeout: 5000,
                // items: 1,
                //nav: true,
                // smartSpeed: 500,
            });
        },
        props:[
            'categories',
            'baseUrl'
        ],
        data:function(){
            return {
                categoriesData:this.processCategoriesForBinding(this.categories),
                selectedCategoryId: this.categories.length >0 ? this.categories[0].id : null,
                productListPageUrl:this.baseUrl+"/admin/shop/products/by-category"
            }
        },
        computed:{
            selectedCategoryIndex:function(){
                for(categoryIndex in this.categories){
                    if(this.categories[categoryIndex].id == this.selectedCategoryId){
                        return categoryIndex;
                    }
                }
            },
        },
        methods:{
            categorySelected:function(category){

                if(this.selectedCategoryId != category.id){
                    if(category.firstLoadDone == false){
                        console.log("data not loaded from the server before");
                        //send ajax call
                        this.loadNextPage(false,category);


                    }else{
                        this.selectedCategoryId = category.id;
                    }


                }
            },
            getCategoryIndexFromCategoryId(categoryId){
                for(categoryIndex in this.categories){
                    if(this.categories[categoryIndex].id == categoryId){
                        return categoryIndex;
                    }
                }
            },
            loadNextPage:function(isSearchQuery, category){

                queryParams = {category_id:category.id};

                if(isSearchQuery){
                    if(category.ajaxRequestInProcess){
                        category.searchRequestHeld=true;
                        return;
                    }
                    if(category.searchQuery !== category.lastSearchTerm){
                        category.nextAvailablePage = 1;
                    }
                    category.lastSearchTerm = category.searchQuery;

                    queryParams.search = category.searchQuery;
                    queryParams.current_page = category.nextAvailablePage;
                    //_url = baseUrl+'?search='+category.searchQuery+'&current_page='+(category.nextAvailablePage);


                }else if(category.searchQuery != ""){
                    queryParams.search = category.searchQuery;
                    queryParams.current_page = category.nextAvailablePage;
                    //_url = baseUrl+'?search='+category.searchQuery+'&current_page='+(category.nextAvailablePage);
                }else{
                    queryParams.current_page = category.nextAvailablePage;
                    //     _url = baseUrl+'?current_page='+(category.nextAvailablePage);
                }


                if(category.nextAvailablePage === null){
                    return;
                }

                if(!category.ajaxRequestInProcess){
                    category.ajaxRequestInProcess = true;
                    var request = $.ajax({

                        url: this.productListPageUrl,
                        data:queryParams,
                        method: "GET",
                        success:function(msg){

                            category.ajaxRequestInProcess = false;
                            if(category.searchRequestHeld){

                                category.searchRequestHeld=false;
                                this.loadNextPage(true,category);

                            }

                            pageDataReceived = msg;
                            productsList = pageDataReceived.response.data;

                            //Success code to follow
                            if(pageDataReceived.next_page_url !== null){
                                category.nextAvailablePage = pageDataReceived.current_page+1;
                            }else{
                                category.nextAvailablePage = null;
                            }

                            if(isSearchQuery){

                                this.categories[this.getCategoryIndexFromCategoryId(category.id)]=productsList;
                            }else{

                                appendArray(this.categories[this.getCategoryIndexFromCategoryId(category.id)].products.data,productsList);
                            }

                            //Change flag property set to see if the data was ever loaded from the server to true
                            if(category.firstLoadDone != true){
                                this.selectedCategoryId = category.id;
                                category.firstLoadDone = true;
                            }

                        }.bind(this),

                        error: function(jqXHR, textStatus ) {
                            this.ajaxRequestInProcess = false;

                            //Error code to follow


                        }.bind(this)
                    });
                }
            },
            processCategoriesForBinding:function(categories){

                for(categoryIndex in categories){
                    categories[categoryIndex].searchQuery = "";
                    categories[categoryIndex].lastSearchTerm = "";
                    categories[categoryIndex].ajaxRequestInProcess = false;
                    categories[categoryIndex].searchRequestHeld = false;

                    if(categories[categoryIndex].products == undefined){
                        //Flag property set to see if the data was ever loaded from the server to false. When the category is next selected
                        //this property will be checked against to load data from the server if it wasn't before
                        categories[categoryIndex].firstLoadDone = false;

                        categories[categoryIndex].nextAvailablePage = 1;
                        categories[categoryIndex].products = JSON.parse(JSON.stringify(categories[0].products));
                        categories[categoryIndex].products.data  = [];
                    }else{
                        categories[categoryIndex].firstLoadDone = true;
                        categories[categoryIndex].nextAvailablePage = categories[categoryIndex].products.next_page_url;
                    }





                }

                return categories;
            },
        },

    });

</script>