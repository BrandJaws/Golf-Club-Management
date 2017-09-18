@extends('admin.__layouts.admin-layout')
@section('heading')
    Edit Main Category
    @endSection
@section('main')
        <div class="app-body" id="view">
            <!-- ############ PAGE START-->
            <div class="profile-main padding" id="selectionDepHidden">
                <div class="row details-section">
                    <form action="{{route('admin.restaurant.update_main_category',$main_category->id)}}"  method="post" enctype="multipart/form-data">
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
                                <label class="form-control-label">Main Category Name</label>
                                <input type="text" class="form-control" name="name" value="{{Request::old('name') ? Request::old('name') : $main_category->name}}" />
                                @if($errors->has('name')) <span class="help-block errorProfilePic">{{$errors->first('name') }}</span> @endif
                            </div>

                            <div class="form-group  {{($errors->has('icon'))?'has-error':''}}">
                                <img src="{{asset($main_category->icon)}}" alt="" class="img-thumbnail" />
                                <label class="form-control-label">Select Image File</label>
                                <input type="file" class="form-control" name="icon" value=""/>
                                @if($errors->has('icon')) <span class="help-block errorProfilePic">{{$errors->first('icon') }}</span> @endif
                            </div>
                            <div class="form-group">
                                <button class="btn btn-def">
                                    <i class="fa fa-floppy-o"></i> &nbsp;Update Main Category
                                </button>
                                <a href="{{route("admin.restaurant.restaurant")}}" class="btn btn-outline b-primary text-primary">
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
