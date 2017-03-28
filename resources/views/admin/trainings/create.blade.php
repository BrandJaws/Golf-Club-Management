@extends('admin.__layouts.admin-layout')
@section('heading')
    Add Lessons
    @endSection
@section('main')
        <div class="app-body" id="view">
            <!-- ############ PAGE START-->
            <div class="profile-main padding" id="selectionDepHidden">
                <div class="row details-section">
                    <form action="{{route('admin.trainings.store')}}"  method="post" enctype="multipart/form-data">
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
                                <label class="form-control-label">Lesson Name</label>
                                <input type="text" class="form-control" name="name" value="{{old('name')}}" />
                                @if($errors->has('name')) <span class="help-block errorProfilePic">{{$errors->first('name') }}</span> @endif
                            </div>
                            <div class="form-group {{($errors->has('lessonDescription'))?'has-error':''}}">
                                <label class="form-control-label">Lesson Description</label>
                                <textarea name="lessonDescription" value="{{old('lessonDescription')}}" id="" class="form-control" rows="8"></textarea>
                                @if($errors->has('lessonDescription')) <span class="help-block errorProfilePic">{{$errors->first('lessonDescription') }}</span> @endif
                            </div>
                            <div class="form-group {{($errors->has('coach'))?'has-error':''}}">
                                <label class="form-control-label">Coach Name</label>
                                <select name="coach" id="" class="form-control">
                                    <option value="">Please Select</option>
                                   @if($coaches && $coaches->count()>0)
                                   		@foreach($coaches as $key=>$coach)
                                   			<option value="{{$coach->id}}">{{$coach->name}}</option>
                                   		@endforeach
                                   @endif
                                </select>
                                @if($errors->has('coach')) <span class="help-block errorProfilePic">{{$errors->first('coach') }}</span> @endif
                            </div>
                            <div class="form-group {{($errors->has('numberOfSeats'))?'has-error':''}}">
                                <label class="form-control-label">Number of seats available</label>
                                <input type="number" class="form-control" name="numberOfSeats"/>
                                @if($errors->has('numberOfSeats')) <span class="help-block errorProfilePic">{{$errors->first('numberOfSeats') }}</span> @endif
                            </div>
                            <div class="form-group {{($errors->has('name'))?'has-error':''}}">
                                <label class="form-control-label">Lesson Date</label>
                                <input type="date" class="form-control" data-date-inline-picker="false" data-date-open-on-focus="true" name="lessonDate" value="{{old('lessonDate')}}"/>
                                @if($errors->has('lessonDate')) <span class="help-block errorProfilePic">{{$errors->first('lessonDate') }}</span> @endif
                            </div>
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-control-label">
                                            Select Lesson Media
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="radio">
                                            <label class="ui-check">
                                                <input type="radio" name="lessonMedia" value="image" class="has-value" v-model="lessonMediaType" @change="lessonMedia()">
                                                <i class="dark-white"></i>
                                                Image
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="radio">
                                            <label class="ui-check">
                                                <input type="radio" name="lessonMedia" value="video" class="has-value" v-model="lessonMediaType" @change="lessonMedia()">
                                                <i class="dark-white"></i>
                                                Video
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group animated fadeInUp {{($errors->has('promotionImage'))?'has-error':''}}" v-cloak v-if="showMediaImage">
                                <label class="form-control-label">Select Image File</label>
                                <input type="file" class="form-control" name="promotionImage" value=""/>
                                @if($errors->has('promotionImage')) <span class="help-block errorProfilePic">{{$errors->first('promotionImage') }}</span> @endif
                            </div>
                            <div class="form-group animated fadeInUp {{($errors->has('videoUrl'))?'has-error':''}}" v-cloak v-if="showMediaVideo">
                                <label class="form-control-label">Link to Youtube/Vimeo Video</label>
                                <input type="url" class="form-control" name="videoUrl" value="{{old('videoUrl')}}" />
                                @if($errors->has('videoUrl')) <span class="help-block errorProfilePic">{{$errors->first('videoUrl') }}</span> @endif
                            </div>
                            <div class="form-group">
                                <button class="btn btn-def">
                                    <i class="fa fa-floppy-o"></i> &nbsp;Add Lesson
                                </button>
                                <a href="{{route("admin.trainings.index")}}" class="btn btn-outline b-primary text-primary">
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
    @include("admin.__vue_components.autocomplete.autocomplete")
    <script>
        var vue = new Vue({
            el: "#selectionDepHidden",
            data: {
                showMediaImage:false,
                showMediaVideo:false,
                lessonMediaType:'{{(old('lessonMedia') == 'video')?'video':'image'}}',
                selectedId: '',
            },
            methods: {
                lessonMedia:function() {
                    console.log(this.lessonMediaType);
                    if (this.lessonMediaType == 'image') {
                        this.showMediaImage = true;
                        this.showMediaVideo = false;
                    }
                    else if (this.lessonMediaType == 'video') {
                        this.showMediaVideo = true;
                        this.showMediaImage = false;
                    }
                    else {
                        this.showMediaImage = true;
                    }
                }
            }
        });
    </script>
    @endSection
