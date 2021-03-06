@extends('admin.__layouts.admin-layout')
@section('heading')
    Add Training
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
                                <label class="form-control-label">Training Name</label>
                                <input type="text" class="form-control" name="name" value="{{old('name')}}" />
                                @if($errors->has('name')) <span class="help-block errorProfilePic">{{$errors->first('name') }}</span> @endif
                            </div>
                            <div class="form-group {{($errors->has('lessonDescription'))?'has-error':''}}">
                                <label class="form-control-label">Training Description</label>
                                <textarea name="lessonDescription" value="{{old('lessonDescription')}}" id="" class="form-control" rows="8"></textarea>
                                @if($errors->has('lessonDescription')) <span class="help-block errorProfilePic">{{$errors->first('lessonDescription') }}</span> @endif
                            </div>
                            <div class="form-group {{($errors->has('coach'))?'has-error':''}}">
                                <label class="form-control-label">Coach Name</label>
                                <select name="coach" id="" class="form-control">
                                    <option value="">Please Select</option>
                                   @if($coaches && $coaches->count()>0)
                                   		@foreach($coaches as $key=>$coach)
                                   			<option value="{{$coach->id}}" {{(old('coach') && old('coach')==$coach->id)?'selected="selected"':''}}>{{$coach->name}}</option>
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
                            <div class="row">
                                <div class="form-group col-md-6 {{($errors->has('startDate'))?'has-error':''}}">
                                    <label class="form-control-label">Start Date</label>
                                    <input type="date" class="form-control" data-date-inline-picker="false" data-date-open-on-focus="true" name="startDate" value="{{old('startDate')}}"/>
                                    @if($errors->has('startDate')) <span class="help-block errorProfilePic">{{$errors->first('startDate') }}</span> @endif
                                </div>
                                <div class="form-group col-md-6 {{($errors->has('endDate'))?'has-error':''}}">
                                    <label class="form-control-label">End Date</label>
                                    <input type="date" class="form-control" data-date-inline-picker="false" data-date-open-on-focus="true" name="endDate" value="{{old('endDate')}}"/>
                                    @if($errors->has('endDate')) <span class="help-block errorProfilePic">{{$errors->first('endDate') }}</span> @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 {{($errors->has('numberOfSessions'))?'has-error':''}}">
                                    <label class="form-control-label">Number of Sessions</label>
                                    <input type="number" class="form-control" data-date-inline-picker="false" data-date-open-on-focus="true" name="numberOfSessions" value="{{old('numberOfSessions')}}"/>
                                    @if($errors->has('numberOfSessions')) <span class="help-block errorProfilePic">{{$errors->first('numberOfSessions') }}</span> @endif
                                </div>
                                <div class="form-group col-md-6 {{($errors->has('price'))?'has-error':''}}">
                                    <label class="form-control-label">Price</label>
                                    <input type="text" class="form-control" data-date-inline-picker="false" data-date-open-on-focus="true" name="price" value="{{old('price')}}"/>
                                    @if($errors->has('price')) <span class="help-block errorProfilePic">{{$errors->first('price') }}</span> @endif
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-control-label">
                                            Select Training Media
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="radio">
                                            <label class="ui-check">
                                                <input type="radio" name="lessonMedia" value="image" class="has-value" v-model="lessonMediaType" >
                                                <i class="dark-white"></i>
                                                Image
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="radio">
                                            <label class="ui-check">
                                                <input type="radio" name="lessonMedia" value="video" class="has-value" v-model="lessonMediaType" >
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
                                    <i class="fa fa-floppy-o"></i> &nbsp;Add Training
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
                showMediaImage:{{$errors->has('videoUrl') ? 'false' : 'true'}},
                showMediaVideo:{{$errors->has('videoUrl') ? 'true' : 'false'}},
                lessonMediaType:'{{(old('lessonMedia') == 'video')?'video':'image'}}',
                selectedId: '',
            },
            computed:{
                showMediaImage:function(){
                    if(this.lessonMediaType == 'image'){
                        return true;
                    }else{
                        return false;
                    }
                },
                showMediaVideo:function(){
                    if(this.lessonMediaType == 'video'){
                        return true;
                    }else{
                        return false;
                    }
                }
            },
            methods: {

            }
        });
    </script>
    @endSection
