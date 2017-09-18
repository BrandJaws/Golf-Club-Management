@include("admin.__vue_components.popups.confirmation-popup")
<template id="restaurantTemplate" xmlns:v-on="http://www.w3.org/1999/xhtml">
    <div>
    <confirmation-popup v-on:close-popup="closeConfirmationPopup"  v-if="showConfirmationPopup" :popup-message="dataHeldForConfirmation.confirmationMessage" :error-message="confirmationPopupErrorMessage" :confirm-callback="dataHeldForConfirmation.confirmCallback"></confirmation-popup>

    <div class="restaurant-main">
        <div class="row">
            <div class="shop-inner">
                <div class="box">
                    <div class="col-md-11">

                        <div id="shop-carousel" class="owl-carousel owl-theme">

                            <div :class="['item', selectedMainCategoryId == mainCategory.id ? 'active-item' : '']" v-for="mainCategory in mainCategoriesData" :key="mainCategory.id" v-on:click="mainCategorySelected(mainCategory)">

                                <div class="parent-category-box">

                                    <div class="media">
                                        <a class="media-left" href="#"> <img class="media-object"
                                                                             :src="baseUrl+'/'+mainCategory.icon" alt="icon">
                                        </a>
                                        <div class="media-body text-left">
                                            <a href="#.">
                                                <h4 class="media-heading">@{{mainCategory.name}}</h4>
                                                <p class="media-sub">-</p>
                                            </a>
                                        </div>
                                        <span>
                                            <a href="#" class="del-icon pull-right" v-on:click="deleteMainCategory(mainCategory,false)">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </span>
                                         <span>
                                            <a v-on:click.stop :href="baseUrl+'/admin/restaurant/main-categories/'+mainCategory.id+'/edit'" class="del-icon pull-right">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </span>


                                    </div>

                                </div>

                            </div>


                        </div>

                        <!-- owl carousel -->

                    </div>
                    <div class="col-md-1">
                        <div class="add-category-btn text-center">
                            <a href="{{route('admin.restaurant.create_main_category')}}"><i class="fa fa-plus"></i><br>More</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- shop-inner ends here -->
        </div>
        <div class="row" v-if="this.mainCategoriesData.length < 1">
            No Categories Added Yet. Please Add One To Continue
        </div>
        <div class="row" v-else>
            <div class="main-padd">
                <div class="col-md-3">

                    <div class="menu-sidebar">

                        <div class="sidebar-heading">

                            <h3>Sub Categories</h3>

                            <span class="addCategoryButton">
                                <button v-on:click="showAddCategoryField()" title="Add Category"><i class="fa fa-plus"></i></button>
                            </span>

                        </div>
                        <div v-if="addCategoryFieldVisible" class="addCategoryField">
                            <form action="" v-on:submit.prevent="addNewCategory">
                                <div class="form-group">
                                    <label for="">Category Name</label>
                                    <div class="row">
                                        <div class=" col-sm-9">
                                            <input type="text" class="form-control" v-model="newCategoryName"/>
                                        </div>
                                        <div class="col-sm-3">
                                            <button v-on:click.prevent="addNewCategory" title="Add Category" class="form-control"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="menu-list">

                            <ul>
                                <li  v-for="category in mainCategoriesData[selectedMainCategoryIndex].sub_categories" :class="[category.id == mainCategoriesData[selectedMainCategoryIndex].selectedSubCategoryId ? 'active-menu' : '']" v-on:click="categorySelected(category)">

                                    <div class="">
                                    <div class="col-sm-7">
                                        <a href="#." class="pull-left" v-if="!category.editModeOn">
                                        <span>
                                            <i class="fa fa-long-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;@{{category.name}}
                                        </span>
                                        </a>
                                        <input v-if="category.editModeOn" type="text" class="form-control" v-model="category.editableName" />
                                    </div>

                                    <div class="col-sm-5">
                                    <a href="#." class="pull-right" v-on:click="deleteCategory(category,false)" v-if="!category.editModeOn">
                                        <span>
                                            <i class="fa fa-trash"></i>
                                        </span>
                                    </a>
                                    <a href="#." class="pull-right" v-on:click="switchEditModeForCategory(category)" v-if="!category.editModeOn">
                                        <span>
                                            <i class="fa fa-pencil"></i>
                                        </span>
                                    </a>
                                    <a href="#." class="pull-right" v-on:click="switchEditModeForCategory(category)" v-if="category.editModeOn">
                                        <span>
                                            <i class="fa fa-ban"></i>
                                        </span>
                                    </a>
                                    <a href="#." class="pull-right" v-on:click="updateCategory(category)" v-if="category.editModeOn">
                                        <span>
                                            <i class="fa fa-floppy-o"></i>
                                        </span>
                                    </a>
                                        </div>
                                    </div>
                                </li>
                                <li v-if="mainCategoriesData[selectedMainCategoryIndex].sub_categories.length < 1">
                                    No Categories Found
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
                                            <h3>@{{ selectedMainCategoryIndex != null && mainCategoriesData[selectedMainCategoryIndex].selectedSubCategoryIndex != -1 ?  mainCategoriesData[selectedMainCategoryIndex].sub_categories[mainCategoriesData[selectedMainCategoryIndex].selectedSubCategoryIndex].name : 'No Category Selected' }}</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="search-form text-right">
                                            <form action="#." method="post"  v-on:submit.prevent>
                                                <div class="search-field">
															<span class="search-box">
                                                                <input type="text" name="search" class="search-bar" v-model="searchQuery" v-on:input="performSearchQuery()">
																<button type="submit" class="search-btn">
                                                                    <i class="fa fa-search"></i>
                                                                </button>
															</span> <span class="">
																<a :href="addNewProductFormUrl">
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
                            <div class="productsTableContainer" v-on:scroll="productsScrolled">
                                <table class="table table-hover b-t restaurantTable">
                                    <tbody v-if=" selectedMainCategoryIndex != null && mainCategoriesData[selectedMainCategoryIndex].selectedSubCategoryIndex != -1">
                                    <template v-if="mainCategoriesData[selectedMainCategoryIndex].sub_categories[mainCategoriesData[selectedMainCategoryIndex].selectedSubCategoryIndex].products.data.length > 0 ">
                                        <tr  v-for="(product,productIndex) in mainCategoriesData[selectedMainCategoryIndex].sub_categories[mainCategoriesData[selectedMainCategoryIndex].selectedSubCategoryIndex].products.data" :key="productIndex">
                                            <td>
                                                <div class="section-3 sec-style text-center">
                                                    <img :src="baseUrl+'/'+product.image" class="shopProdImage"></imgsrc>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="section-1 sec-style">
                                                    <h3>@{{ product.name }}</h3>
                                                    <p>Product Name</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="section-3 sec-style">
                                                    <h3>@{{ '$ '+product.price }}</h3>
                                                    <p>Price</p>
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
                                                    <h3>@{{ product.visible }}</h3>
                                                    <p>Is Visible</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="section-3 sec-style">
                                                    <p>
                                                        <span><a :href="baseUrl+'/admin/restaurant/products/'+product.id+'/edit'" class="blue-cb">edit</a></span>&nbsp;&nbsp;&nbsp;
                                                        <span><a href="#." class="del-icon" v-on:click="deleteProduct(product,false)"><i
                                                                        class="fa fa-trash"></i></a></span>
                                                    </p>
                                                </div>
                                            </td>

                                        </tr>
                                    </template>
                                    <template v-else-if="mainCategoriesData[selectedMainCategoryIndex].sub_categories[mainCategoriesData[selectedMainCategoryIndex].selectedSubCategoryIndex].firstLoadDone">
                                        <tr >
                                            <td>
                                                No Products Found
                                            </td>

                                        </tr>
                                    </template>


                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td>
                                                No Category Selected
                                            </td>

                                        </tr>
                                    </tbody>


                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- main padd ends here -->
        </div>
    </div>
    </div>
</template>

<script>

    Vue.component('restaurant',{
       template: "#restaurantTemplate",
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
                'mainCategories',
                'categories',
                'baseUrl'
        ],
        data:function(){
            return {
                mainCategoriesData:this.processMainCategoriesForBinding(this.mainCategories),
                categoriesData:this.processCategoriesForBinding(this.categories),
                selectedMainCategoryId: this.mainCategories.length >0 ? this.mainCategories[0].id : null,
//                selectedCategoryId: this.categories.length >0 ? this.categories[0].id : null,
                deleteMainCategoryUrl:this.baseUrl+"/admin/restaurant/main-categories/",
                productListPageUrl:this.baseUrl+"/admin/restaurant/products/by-category",
                addNewSubCategoryUrl:this.baseUrl+"/admin/restaurant/sub-categories",
                updateSubCategoryUrl:this.baseUrl+"/admin/restaurant/sub-categories",
                deleteSubCategoryUrl:this.baseUrl+"/admin/restaurant/sub-categories/",
                deleteProductUrl:this.baseUrl+"/admin/restaurant/products/",
                addCategoryFieldVisible: false,
                searchQuery:"",
                newCategoryName:"",
                showConfirmationPopup: false,
                confirmationPopupErrorMessage: "",
                dataHeldForConfirmation: {
                    confirmationMessage: null,
                    confirmCallback:null

                },
            }
        },
        computed:{
            selectedMainCategoryIndex:function(){

                for(mainCategoryIndex in this.mainCategoriesData){
                    if(this.mainCategoriesData[mainCategoryIndex].id == this.selectedMainCategoryId){

                        return mainCategoryIndex;
                    }
                }
            },
//            selectedCategoryIndex:function(){
//
//                for(mainCategoryIndex in this.mainCategoriesData){
//                    for(subCategoryIndex in this.mainCategoriesData[mainCategoryIndex].sub_categories){
//                        if(this.mainCategoriesData[mainCategoryIndex].sub_categories[subCategoryIndex].id == this.selectedCategoryId){
//
//                            return subCategoryIndex;
//                        }
//                    }
//
//                }
//                return null;
//            },
            addNewProductFormUrl:function(){
                return this.baseUrl+"/admin/restaurant/products/new?category="+(this.mainCategoriesData[mainCategoryIndex].selectedSubCategoryId)
            },
        },
        methods:{
            showAddCategoryField:function(){
                if(this.addCategoryFieldVisible) {
                    this.addCategoryFieldVisible = false;
                } else {
                    this.addCategoryFieldVisible = true;
                }
            },
            mainCategorySelected:function(mainCategory){

                if(this.selectedMainCategoryId != mainCategory.id){

                    this.selectedMainCategoryId = mainCategory.id;
                    if(this.mainCategoriesData[this.selectedMainCategoryIndex].sub_categories.length > 0 && this.mainCategoriesData[this.selectedMainCategoryIndex].sub_categories[0].firstLoadDone === false){

                        this.loadNextPage(false,this.mainCategoriesData[this.selectedMainCategoryIndex].sub_categories[0]);

                    }

                    this.searchQuery = this.mainCategoriesData[this.selectedMainCategoryIndex].selectedSubCategoryIndex != -1 ? this.mainCategoriesData[this.selectedMainCategoryIndex].sub_categories[this.mainCategoriesData[this.selectedMainCategoryIndex].selectedSubCategoryIndex].searchQuery : "";

                }
            },
            categorySelected:function(category){

                if(this.mainCategoriesData[mainCategoryIndex].selectedSubCategoryId != category.id){

                    this.selectSubCategoryAndIndexForAMainCategoryBySubCategoryId(category.id);
                    if(category.firstLoadDone === false){
                        //send ajax call
                        this.loadNextPage(false,category);

                    }

                    this.searchQuery = this.mainCategoriesData[this.selectedMainCategoryIndex].sub_categories[this.mainCategoriesData[this.selectedMainCategoryIndex].selectedSubCategoryIndex].searchQuery;



                }
            },
            switchEditModeForCategory:function(category){

                if(category.editModeOn == true){
                    category.editModeOn = false;
                    category.editableName = category.name;
                }else{
                    category.editModeOn = true;
                }


            },
            productsScrolled:function(e){
                var selectedCategory = this.mainCategoriesData[this.selectedMainCategoryIndex].sub_categories[this.mainCategoriesData[this.selectedMainCategoryIndex].selectedSubCategoryIndex];
                var element = e.target;

                if (element.scrollHeight - element.scrollTop === element.clientHeight)
                {
                    // element is at the end of its scroll, load more content

                    this.loadNextPage(false,selectedCategory);
                }

            },
            performSearchQuery:function(){
                var selectedCategory = this.mainCategoriesData[this.selectedMainCategoryIndex].sub_categories[this.mainCategoriesData[this.selectedMainCategoryIndex].selectedSubCategoryIndex];
                selectedCategory.searchQuery = this.searchQuery;
                this.loadNextPage(true,selectedCategory);
            },
//            getCategoryIndexFromCategoryId(categoryId){
//                if(this.categoriesData[categoryIndex].id != null){
//                    for(categoryIndex in this.categoriesData){
//                        if(this.categoriesData[categoryIndex].id == categoryId){
//                            return categoryIndex;
//                        }
//                    }
//                }
//
//                return null;
//            },

            loadNextPage:function(isSearchQuery, category){


                queryParams = {restaurant_sub_category_id:category.id};

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
                            if(pageDataReceived.response.next_page_url !== null){
                                category.nextAvailablePage = pageDataReceived.response.current_page+1;
                            }else{
                                category.nextAvailablePage = null;
                            }

                            if(isSearchQuery){

                                categoryIndices = this.getMainAndSubCategoryIndexFromSubCategoryId(category.id);
                                console.log(categoryIndices);
                                this.mainCategoriesData[categoryIndices.mainCategoryIndex].sub_categories[categoryIndices.subCategoryIndex].products.data = productsList;
                            }else{
                                categoryIndices = this.getMainAndSubCategoryIndexFromSubCategoryId(category.id);
                                console.log(categoryIndices);
                                appendArray(this.mainCategoriesData[categoryIndices.mainCategoryIndex].sub_categories[categoryIndices.subCategoryIndex].products.data,productsList);
                            }

                            //Change flag property set to see if the data was ever loaded from the server to true
                            if(category.firstLoadDone != true){

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
            processMainCategoriesForBinding:function(categories){
                categories = JSON.parse(JSON.stringify(categories));
                for(mainCategoryIndex in categories){
                    categories[mainCategoryIndex].selectedSubCategoryId = categories[mainCategoryIndex].sub_categories.length >0 ? categories[mainCategoryIndex].sub_categories[0].id : null;
                    categories[mainCategoryIndex].selectedSubCategoryIndex = categories[mainCategoryIndex].sub_categories.length >0 ? 0 : -1;

                    for(subCategoryIndex in categories[mainCategoryIndex].sub_categories){
                        categories[mainCategoryIndex].sub_categories[subCategoryIndex].editModeOn =  false;
                        categories[mainCategoryIndex].sub_categories[subCategoryIndex].editableName =  categories[mainCategoryIndex].sub_categories[subCategoryIndex].name;
                        categories[mainCategoryIndex].sub_categories[subCategoryIndex].searchQuery = "";
                        categories[mainCategoryIndex].sub_categories[subCategoryIndex].lastSearchTerm = "";
                        categories[mainCategoryIndex].sub_categories[subCategoryIndex].ajaxRequestInProcess = false;
                        categories[mainCategoryIndex].sub_categories[subCategoryIndex].searchRequestHeld = false;

                        if(categories[mainCategoryIndex].sub_categories[subCategoryIndex].products == undefined){
                            //Flag property set to see if the data was ever loaded from the server to false. When the category is next selected
                            //this property will be checked against to load data from the server if it wasn't before
                            categories[mainCategoryIndex].sub_categories[subCategoryIndex].firstLoadDone = false;

                            categories[mainCategoryIndex].sub_categories[subCategoryIndex].nextAvailablePage = 1;
                            categories[mainCategoryIndex].sub_categories[subCategoryIndex].products = {
                                current_page:0,
                                data:[],
                                next_page_url:null,

                            };



                        }else{
                            categories[mainCategoryIndex].sub_categories[subCategoryIndex].firstLoadDone = true;
                            categories[mainCategoryIndex].sub_categories[subCategoryIndex].nextAvailablePage = categories[mainCategoryIndex].sub_categories[subCategoryIndex].products.next_page_url !== null ? 2 : null;
                        }
                    }

                }

                return categories;
            },
            processCategoriesForBinding:function(categories){


                categories = JSON.parse(JSON.stringify(categories));
                for(categoryIndex in categories){
                    categories[categoryIndex].editModeOn =  false;
                    categories[categoryIndex].editableName =  categories[categoryIndex].name;
                    categories[categoryIndex].searchQuery = "";
                    categories[categoryIndex].lastSearchTerm = "";
                    categories[categoryIndex].ajaxRequestInProcess = false;
                    categories[categoryIndex].searchRequestHeld = false;

                    if(categories[categoryIndex].products == undefined){
                        //Flag property set to see if the data was ever loaded from the server to false. When the category is next selected
                        //this property will be checked against to load data from the server if it wasn't before
                        categories[categoryIndex].firstLoadDone = false;

                        categories[categoryIndex].nextAvailablePage = 1;
                        categories[categoryIndex].products = {
                            current_page:0,
                            data:[],
                            next_page_url:null,

                        };



                    }else{
                        categories[categoryIndex].firstLoadDone = true;
                        categories[categoryIndex].nextAvailablePage = categories[categoryIndex].products.next_page_url !== null ? 2 : null;
                    }





                }

                return categories;
            },
            deleteMainCategory: function (category, confirmed) {

                categoryId = category.id;


                this.dataHeldForConfirmation.confirmCallback = function(){
                    var request = $.ajax({

                        url: this.deleteMainCategoryUrl + categoryId,
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                        },
                        data: {
                            _method: "DELETE",


                        },
                        success: function (msg) {

                           for(mainCategoryIndex in this.mainCategoriesData){

                               if(this.mainCategoriesData[mainCategoryIndex].id == category.id){
                                   this.mainCategoriesData.splice(mainCategoryIndex,1);
                                   break;
                               }
                           }

                            if(this.mainCategoriesData.length > 0){
                                this.selectedMainCategoryId = this.mainCategoriesData[0].id;

                            }


                            this.closeConfirmationPopup();
                        }.bind(this),

                        error: function (jqXHR, textStatus) {
                            this.ajaxRequestInProcess = false;

                            //Error code to follow
                            if (jqXHR.hasOwnProperty("responseText")) {
                                this.confirmationPopupErrorMessage = JSON.parse(jqXHR.responseText).response;
                            }

                        }.bind(this)
                    });
                }.bind(this);

                if(!confirmed){
                    this.dataHeldForConfirmation.confirmationMessage = "Are you sure you want to delete this category?";
                    this.displayConfirmationPopup();

                }else{
                    this.dataHeldForConfirmation.confirmCallback();
                    this.dataHeldForConfirmation.confirmCallback = null;
                }





            },
            addNewCategory:function(){
                var request = $.ajax({

                    url: this.addNewSubCategoryUrl,
                    data:{name:this.newCategoryName, restaurant_main_category_id:this.selectedMainCategoryId},
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}',
                    },
                    method: "POST",
                    success:function(msg){

                        newCategory = msg.response;

                        //Success code to follow
                        newCategory = this.processCategoriesForBinding([newCategory]);
                        //Set first load done to true for te newly added category since the newly added category wont have products already
                        newCategory[0].firstLoadDone = true;
                        appendArray(this.mainCategoriesData[this.selectedMainCategoryIndex].sub_categories,newCategory);

                        this.mainCategoriesData[this.selectedMainCategoryIndex].sub_categories.sort(function(a,b){

                            if (a.name < b.name)
                                return -1;
                            if (a.name > b.name)
                                return 1;
                            return 0;
                        });


                        //Select newly added category
                        this.selectSubCategoryAndIndexForAMainCategoryBySubCategoryId(newCategory[0].id);
                        this.newCategoryName = "";



                    }.bind(this),

                    error: function(jqXHR, textStatus ) {
                        this.ajaxRequestInProcess = false;

                        //Error code to follow


                    }.bind(this)
                });
            },
            updateCategory:function(category){
                var request = $.ajax({

                    url: this.updateSubCategoryUrl,
                    data:{_method:"PUT",
                          restaurant_sub_category_id:category.id,
                          name:category.editableName},
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}',
                    },
                    method: "POST",
                    success:function(msg){

                        editedCategory = msg.response;
                        //Success code to follow

                       for(mainCategoryIndex in this.mainCategoriesData){
                           for(subCategoryIndex in this.mainCategoriesData[mainCategoryIndex].sub_categories){
                               if(this.mainCategoriesData[mainCategoryIndex].sub_categories[subCategoryIndex].id == editedCategory.id){
                                   this.mainCategoriesData[mainCategoryIndex].sub_categories[subCategoryIndex].name = editedCategory.name;
                                   break;

                               }
                           }

                       }
                        category.editModeOn = false;

                    }.bind(this),

                    error: function(jqXHR, textStatus ) {
                        this.ajaxRequestInProcess = false;

                        //Error code to follow


                    }.bind(this)
                });
            },
            deleteCategory: function (category, confirmed) {

                categoryId = category.id;


                this.dataHeldForConfirmation.confirmCallback = function(){
                    var request = $.ajax({

                        url: this.deleteSubCategoryUrl + categoryId,
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                        },
                        data: {
                            _method: "DELETE",


                        },
                        success: function (msg) {

                            mainAndSubCategoryIndices = this.getMainAndSubCategoryIndexFromSubCategoryId(category.id);

                            if(mainAndSubCategoryIndices.mainCategoryIndex != null && mainAndSubCategoryIndices.subCategoryIndex != null){
                                this.mainCategoriesData[mainAndSubCategoryIndices.mainCategoryIndex].sub_categories.splice(mainAndSubCategoryIndices.subCategoryIndex,1);
                                if(this.mainCategoriesData[mainAndSubCategoryIndices.mainCategoryIndex].sub_categories.length >0){
                                    this.selectSubCategoryAndIndexForAMainCategoryBySubCategoryId(this.mainCategoriesData[mainAndSubCategoryIndices.mainCategoryIndex].sub_categories[0].id);
                                }else{
                                    this.setSelectedSubCategoryAndIdToNullForAMainCategoryById(this.mainCategoriesData[mainAndSubCategoryIndices.mainCategoryIndex].id);
                                }
                            }

                            this.closeConfirmationPopup();
                        }.bind(this),

                        error: function (jqXHR, textStatus) {
                            this.ajaxRequestInProcess = false;

                            //Error code to follow
                            if (jqXHR.hasOwnProperty("responseText")) {
                                this.confirmationPopupErrorMessage = JSON.parse(jqXHR.responseText).response;
                            }

                        }.bind(this)
                    });
                }.bind(this);

                if(!confirmed){
                    this.dataHeldForConfirmation.confirmationMessage = "Are you sure you want to delete this category?";
                    this.displayConfirmationPopup();

                }else{
                    this.dataHeldForConfirmation.confirmCallback();
                    this.dataHeldForConfirmation.confirmCallback = null;
                }





            },
            deleteProduct: function (product, confirmed) {

                productId = product.id;


                this.dataHeldForConfirmation.confirmCallback = function(){
                    var request = $.ajax({

                        url: this.deleteProductUrl + productId,
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                        },
                        data: {
                            _method: "DELETE",


                        },
                        success: function (msg) {

                            categoryIndices = this.getMainAndSubCategoryIndexFromSubCategoryId(product.restaurant_sub_category_id);
                            categoryOfProduct = this.mainCategoriesData[categoryIndices.mainCategoryIndex].sub_categories[categoryIndices.subCategoryIndex];


                            for(productIndex in categoryOfProduct.products.data){
                                if(categoryOfProduct.products.data[productIndex].id == product.id){
                                    categoryOfProduct.products.data.splice(productIndex,1);
                                    break;
                                }
                            }


                            this.closeConfirmationPopup();
                        }.bind(this),

                        error: function (jqXHR, textStatus) {
                            this.ajaxRequestInProcess = false;

                            //Error code to follow
                            if (jqXHR.hasOwnProperty("responseText")) {
                                this.confirmationPopupErrorMessage = JSON.parse(jqXHR.responseText).response;
                            }

                        }.bind(this)
                    });
                }.bind(this);

                if(!confirmed){
                    this.dataHeldForConfirmation.confirmationMessage = "Are you sure you want to delete this product?";
                    this.displayConfirmationPopup();

                }else{
                    this.dataHeldForConfirmation.confirmCallback();
                    this.dataHeldForConfirmation.confirmCallback = null;
                }





            },
            selectSubCategoryAndIndexForAMainCategoryBySubCategoryId(subCategoryId){
                var mainAndSubCategoryIndices = this.getMainAndSubCategoryIndexFromSubCategoryId(subCategoryId);
                console.log(mainAndSubCategoryIndices);
                this.mainCategoriesData[mainAndSubCategoryIndices.mainCategoryIndex].selectedSubCategoryId = subCategoryId;
                this.mainCategoriesData[mainAndSubCategoryIndices.mainCategoryIndex].selectedSubCategoryIndex = mainAndSubCategoryIndices.subCategoryIndex;
            },
            setSelectedSubCategoryAndIdToNullForAMainCategoryById(mainCategoryId){

                for(mainCategoryIndex in this.mainCategoriesData){

                        if(this.mainCategoriesData[mainCategoryIndex].id == mainCategoryId){
                            this.mainCategoriesData[mainCategoryIndex].selectedSubCategoryId = null;
                            this.mainCategoriesData[mainCategoryIndex].selectedSubCategoryIndex = -1;
                            return;

                        }


                }

            },
            getMainAndSubCategoryIndexFromSubCategoryId(subCategoryId){
                console.log("sub cat id: "+subCategoryId);
                console.log(subCategoryId,this.mainCategoriesData);
                for(_mainCategoryIndex in this.mainCategoriesData){
                    for(_subCategoryIndex in this.mainCategoriesData[_mainCategoryIndex].sub_categories){
                        if(this.mainCategoriesData[_mainCategoryIndex].sub_categories[_subCategoryIndex].id == subCategoryId){
                            return {
                                mainCategoryIndex:_mainCategoryIndex,
                                subCategoryIndex:_subCategoryIndex

                            };

                        }
                    }

                }

                return {
                    mainCategoryIndex:null,
                    subCategoryIndex:null
                };
            },
            displayConfirmationPopup: function () {
                //            console.log('emit received');
                this.showConfirmationPopup = true;
            },
            closeConfirmationPopup: function () {
                //            console.log('emit received');
                this.showConfirmationPopup = false;
                this.confirmationPopupErrorMessage = "";
                this.dataHeldForConfirmation.confirmationMessage = null;
                this.dataHeldForConfirmation.confirmCallback = null;

            },


        },

    });

</script>