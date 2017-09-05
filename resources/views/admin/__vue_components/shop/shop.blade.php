@include("admin.__vue_components.popups.confirmation-popup")
<template id="shopTemplate" xmlns:v-on="http://www.w3.org/1999/xhtml">
    <div>
    <confirmation-popup v-on:close-popup="closeConfirmationPopup"  v-if="showConfirmationPopup" :popup-message="dataHeldForConfirmation.confirmationMessage" :error-message="confirmationPopupErrorMessage" :confirm-callback="dataHeldForConfirmation.confirmCallback"></confirmation-popup>

    <div class="shop-main">
        <div class="row">
            <div class="main-padd">
                <div class="col-md-3">

                    <div class="menu-sidebar">

                        <div class="sidebar-heading">

                            <h3>Categories</h3>

                            <span class="addCategoryButton">
                                <button v-on:click="showAddCategoryField()" title="Add Category"><i class="fa fa-plus"></i></button>
                            </span>

                        </div>
                        <div v-if="addCategoryFieldVisible" class="addCategoryField">
                            <form action="" v-on:submit.prevent="addNewCategory">
                                <div class="form-group">
                                    <label for="">Category Name</label>
                                    <input type="text" class="form-control" v-model="newCategoryName"/>
                                    <button v-on:click.prevent="addNewCategory" title="Add Category" class="form-control"><i class="fa fa-plus"></i></button>

                                </div>
                            </form>
                        </div>

                        <div class="menu-list">

                            <ul>
                                <li  v-for="category in categoriesData" :class="[category.id == selectedCategoryId ? 'active-menu' : '']" v-on:click="categorySelected(category)">
                                    <a href="#." class="pull-left" v-if="!category.editModeOn">
                                        <span>
                                            <i class="fa fa-long-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;@{{category.name}}
                                        </span>
                                    </a>

                                    <input v-if="category.editModeOn" type="text" class="form-control" v-model="category.editableName" />
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
                                </li>
                                <li v-if="categoriesData.length < 1">
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
                                            <h3>@{{ selectedCategoryIndex != null ? categoriesData[selectedCategoryIndex].name : 'No Category Selected' }}</h3>
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
                            <div class="productsTableContainer" v-on:scroll="productsScrolled">
                                <table class="table table-hover b-t shopTable">
                                    <tbody v-if=" selectedCategoryIndex != null">
                                    <template v-if="categoriesData[selectedCategoryIndex].products.data.length > 0 ">
                                        <tr  v-for="(product,productIndex) in categoriesData[selectedCategoryIndex].products.data" :key="productIndex">
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
                                                    <h3>@{{ product.in_stock }}</h3>
                                                    <p>In Stock</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="section-3 sec-style">
                                                    <p>
                                                        <span><a :href="baseUrl+'/admin/shop/products/'+product.id+'/edit'" class="blue-cb">edit</a></span>&nbsp;&nbsp;&nbsp;
                                                        <span><a href="#." class="del-icon" v-on:click="deleteProduct(product,false)"><i
                                                                        class="fa fa-trash"></i></a></span>
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    <template v-else-if="categoriesData[selectedCategoryIndex].firstLoadDone">
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
                productListPageUrl:this.baseUrl+"/admin/shop/products/by-category",
                addNewCategoryUrl:this.baseUrl+"/admin/shop/categories",
                updateCategoryUrl:this.baseUrl+"/admin/shop/categories",
                deleteCategoryUrl:this.baseUrl+"/admin/shop/categories/",
                deleteProductUrl:this.baseUrl+"/admin/shop/products/",
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
            selectedCategoryIndex:function(){

                for(categoryIndex in this.categoriesData){
                    if(this.categoriesData[categoryIndex].id == this.selectedCategoryId){

                        return categoryIndex;
                    }
                }
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
            categorySelected:function(category){

                if(this.selectedCategoryId != category.id){
                    if(category.firstLoadDone == false){

                        this.selectedCategoryId = category.id;
                        //send ajax call
                        this.loadNextPage(false,category);


                    }else{
                        this.selectedCategoryId = category.id;
                    }

                    this.searchQuery = this.categoriesData[this.selectedCategoryIndex].searchQuery;



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
                var selectedCategory = this.categoriesData[this.selectedCategoryIndex];
                var element = e.target;

                if (element.scrollHeight - element.scrollTop === element.clientHeight)
                {
                    // element is at the end of its scroll, load more content

                    this.loadNextPage(false,selectedCategory);
                }

            },
            performSearchQuery:function(){
                var selectedCategory = this.categoriesData[this.selectedCategoryIndex];
                selectedCategory.searchQuery = this.searchQuery;
                this.loadNextPage(true,selectedCategory);
            },
            getCategoryIndexFromCategoryId(categoryId){
                if(this.categoriesData[categoryIndex].id != null){
                    for(categoryIndex in this.categoriesData){
                        if(this.categoriesData[categoryIndex].id == categoryId){
                            return categoryIndex;
                        }
                    }
                }

                return null;
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
                            if(pageDataReceived.response.next_page_url !== null){
                                category.nextAvailablePage = pageDataReceived.response.current_page+1;
                            }else{
                                category.nextAvailablePage = null;
                            }

                            if(isSearchQuery){


                                this.categoriesData[this.getCategoryIndexFromCategoryId(category.id)].products.data = productsList;
                            }else{
                                console.log(this.categoriesData[this.getCategoryIndexFromCategoryId(category.id)].products.data);
                                appendArray(this.categoriesData[this.getCategoryIndexFromCategoryId(category.id)].products.data,productsList);
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
            addNewCategory:function(){
                var request = $.ajax({

                    url: this.addNewCategoryUrl,
                    data:{name:this.newCategoryName},
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
                        appendArray(this.categoriesData,newCategory);

                        this.categoriesData.sort(function(a,b){

                            if (a.name < b.name)
                                return -1;
                            if (a.name > b.name)
                                return 1;
                            return 0;
                        });


                        //Select newly added category
                        this.selectedCategoryId = newCategory[0].id;




                    }.bind(this),

                    error: function(jqXHR, textStatus ) {
                        this.ajaxRequestInProcess = false;

                        //Error code to follow


                    }.bind(this)
                });
            },
            updateCategory:function(category){
                var request = $.ajax({

                    url: this.updateCategoryUrl,
                    data:{_method:"PUT",
                          category_id:category.id,
                          name:category.editableName},
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}',
                    },
                    method: "POST",
                    success:function(msg){

                        editedCategory = msg.response;
                        //Success code to follow

                       for(categoryIndex in this.categoriesData){
                           if(this.categoriesData[categoryIndex].id == editedCategory.id){
                               this.categoriesData[categoryIndex].name = editedCategory.name;
                               break;

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

                        url: this.deleteCategoryUrl + categoryId,
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                        },
                        data: {
                            _method: "DELETE",


                        },
                        success: function (msg) {


                            for(categoryIndex in this.categoriesData){
                                if(this.categoriesData[categoryIndex].id == category.id){

                                    this.categoriesData.splice(categoryIndex,1);

                                    this.selectedCategoryId = this.categoriesData.length >0 ? this.categoriesData[0].id : null;
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

                            categoryOfProduct = this.categoriesData[this.getCategoryIndexFromCategoryId(product.category_id)];


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