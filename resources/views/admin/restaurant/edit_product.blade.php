@extends('admin.__layouts.admin-layout')
@section('heading')
    Edit Product
    @endSection
@section('main')
        <div class="app-body" id="view">
            <!-- ############ PAGE START-->
            <div class="profile-main padding" id="selectionDepHidden">
                <div class="row details-section">
                    <form action="{{route('admin.restaurant.update_product',$product->id)}}"  method="post" enctype="multipart/form-data">
                        @if(Session::has('error'))
                        	<div class="alert alert-warning" role="alert"> {{Session::get('error')}} </div>
                        @endif
                        @if(Session::has('success'))
                        	<div class="alert alert-success" role="alert"> {{Session::get('success')}} </div>
                        @endif
						<input type="hidden" name="_method" value="POST" />
				    	{{ csrf_field() }}
                        <div class="col-md-8">
                            <div class="form-group {{($errors->has('name'))?'has-error':''}}">
                                <label class="form-control-label">Product Name</label>
                                <input type="text" class="form-control" name="name" value="{{Request::old('name') ? Request::old('name') : $product->name}}" />
                                @if($errors->has('name')) <span class="help-block errorProfilePic">{{$errors->first('name') }}</span> @endif
                            </div>
                            <div class="form-group {{($errors->has('restaurant_sub_category_id'))?'has-error':''}}">
                                <label class="form-control-label">Category</label>
                                <select name="restaurant_sub_category_id" id="" class="form-control">
                                    <option value="0">Please Select</option>
                                    @if($categories && $categories->count()>0)
                                        @foreach($categories as $key=>$category)
                                            <option value="{{$category->id}}" {{(old('restaurant_sub_category_id') && old('restaurant_sub_category_id')==$category->id)?'selected="selected"':($product->restaurant_sub_category_id == $category->id)?'selected="selected"':'' }}>{{$category->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if($errors->has('restaurant_sub_category_id')) <span class="help-block errorProfilePic">{{$errors->first('restaurant_sub_category_id') }}</span> @endif
                            </div>
                            <div class="form-group {{($errors->has('description'))?'has-error':''}}">
                                <label class="form-control-label">Product Description</label>
                                <textarea name="description"  id="" class="form-control" rows="8">{{Request::old('description') ? Request::old('description') : $product->description}}</textarea>
                                @if($errors->has('description')) <span class="help-block errorProfilePic">{{$errors->first('description') }}</span> @endif
                            </div>
                            <div class="row">
                            <div class="col-sm-4">
                            <div class="form-group  {{($errors->has('image'))?'has-error':''}}">
                                <img src="{{asset($product->image)}}" alt="" class="img-thumbnail" />
                                <label class="form-control-label">Select Image File</label>
                                <input type="file" class="form-control" name="image" value=""/>
                                @if($errors->has('image')) <span class="help-block errorProfilePic">{{$errors->first('image') }}</span> @endif
                            </div>
                            </div>
                            </div>
                            <div class="form-group {{($errors->has('price'))?'has-error':''}}">
                                <label class="form-control-label">Price</label>
                                <input type="number" class="form-control" name="price" value="{{Request::old('price') ? Request::old('price') : $product->price}}" step=".01"/>
                                @if($errors->has('price')) <span class="help-block errorProfilePic">{{$errors->first('price') }}</span> @endif
                            </div>
                            <div class="form-group {{($errors->has('in_stock'))?'has-error':''}}">
                                <label class="form-control-label">In Stock</label>
                                <select name="in_stock" id="" class="form-control">
                                    <option value="YES" {{(old('in_stock') && old('in_stock')=="YES")?'selected="selected"':($product->in_stock == "YES")?'selected="selected"':'' }}>Yes</option>
                                    <option value="NO" {{(old('in_stock') && old('in_stock')=="NO")?'selected="selected"':($product->in_stock == "NO")?'selected="selected"':'' }} >No</option>

                                </select>
                                @if($errors->has('in_stock')) <span class="help-block errorProfilePic">{{$errors->first('in_stock') }}</span> @endif
                            </div>
                            <div class="form-group {{($errors->has('visible'))?'has-error':''}}">
                                <label class="form-control-label">Visible</label>
                                <select name="visible" id="" class="form-control">
                                    <option value="YES" {{(old('visible') && old('visible')=="YES")?'selected="selected"':($product->visible == "YES")?'selected="selected"':'' }}>Yes</option>
                                    <option value="NO" {{(old('visible') && old('visible')=="NO")?'selected="selected"':($product->visible == "NO")?'selected="selected"':'' }} >No</option>

                                </select>
                                @if($errors->has('visible')) <span class="help-block errorProfilePic">{{$errors->first('visible') }}</span> @endif
                            </div>

                            <div class="form-group">
                                <button class="btn btn-def">
                                    <i class="fa fa-floppy-o"></i> &nbsp;Update Product
                                </button>
                                <a href="{{route("admin.shop.shop")}}" class="btn btn-outline b-primary text-primary">
                                    <i class="fa fa-ban"></i> &nbsp;Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection

@section('page-specific-scripts')

@endSection
