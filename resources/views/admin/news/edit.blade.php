@extends('admin.__layouts.admin-layout')
@section('heading')
	Edit News
@endSection
@section('main')
	<div class="app-body" id="view">
		<!-- ############ PAGE START-->
		<div class="profile-main padding" id="selectionDepHidden">
			<div class="row details-section">
				<form action="{{route('admin.newsfeeds.update',$news['id'])}}"  method="post" enctype="multipart/form-data">
					@if(Session::has('error'))
                    <div class="alert alert-warning" role="alert"> {{Session::get('error')}} </div>
                    @endif
                    @if(Session::has('success'))
                    <div class="alert alert-success" role="alert"> {{Session::get('success')}} </div>
                    @endif
						<input type="hidden" name="_method" value="PUT" />
				    	{{ csrf_field() }}
					<div class="col-md-8">
						<div class="form-group {{($errors->has('title'))?'has-error':''}}">
                                <label class="form-control-label">Title</label> 
                                <input type="text" name="title"  class="form-control" value="{{(Request::old('title'))?Request::old('title'):array_get($news,'title')}}" />
                                 @if($errors->has('title')) <span class="help-block errorProfilePic">{{$errors->first('title') }}</span> @endif
                            </div>
                           <div class="form-group {{($errors->has('description'))?'has-error':''}}">
    							<label class="form-control-label">Description</label> 
    							<textarea rows="8" id="wysiwigEditor"  class="form-control user-success" name="description">{{(Request::old('description'))?Request::old('description'):array_get($news,'description')}}</textarea>
    							 @if($errors->has('description')) <span class="help-block errorProfilePic">{{$errors->first('description') }}</span> @endif
							</div>
                        <br />
						<div class="form-group">
							<button type="submit" class="btn btn-def"><i class="fa fa-floppy-o"></i>&nbsp;Update News</button> &nbsp;&nbsp;
							<a
								href="{{route('admin.newsfeeds.list')}}"
								class="btn btn-outline b-primary text-primary"><i
								class="fa fa-ban"></i> &nbsp;Cancel</a>
						</div>
					</div>
					<div class="col-md-4">
						<div class="text-center">
							<img src="{{(isset($news['image']) && $news['image'])? asset($news['image']): asset('assets/images/user.png')}}" class="img-responsive img-circle defaultImg" />
							<div class="form-group">
								<label class="form-control-label">Add Image</label> 
								<input type="file" class="form-control" name="image" />
								@if($errors->has('image ')) <span class="help-block errorProfilePic">{{$errors->first('image ') }}</span> @endif
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection
@section('page-specific-scripts')
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.js"></script>
<script>
        $(document).ready(function() {
            $('#wysiwigEditor').summernote({height: 200});
        });

</script>
@endsection
