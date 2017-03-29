@extends('admin.__layouts.admin-layout')
@section('heading')
    Edit Lessons
    @endSection
@section('main')
        <div class="app-body" id="view">
            <!-- ############ PAGE START-->
            <div class="profile-main padding" id="selectionDepHidden">
                <div class="row details-section">
                    <form name="" action="{{route('admin.trainings.update',$training->id)}}" method="post" enctype="multipart/form-data">
                        @if(Session::has('error'))
                            <div class="alert alert-warning" role="alert"> {{Session::get('error')}} </div>
                        @endif
                        @if(Session::has('success'))
                            <div class="alert alert-success" role="alert"> {{Session::get('success')}} </div>
                        @endif
                        <input type="hidden" name="_method" value="PUT" />
                        {{ csrf_field() }}
                            <div class="col-md-6">
                                <div class="form-group">
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
                                
                                <div class="form-group animated fadeInUp" v-cloak v-if="showMediaImage">
                                    <img src="{{((strtolower($training->promotionType) == 'image')?asset($training->promotionContent):'')}}" alt="" class="ïmg-responsive" />
                                    <label class="form-control-label">Select Image File</label>
                                     <input type="file" class="form-control" name="promotionImage" value=""/>
                                	@if($errors->has('promotionImage')) <span class="help-block errorProfilePic">{{$errors->first('promotionImage') }}</span> @endif
                                </div>
                                <div class="form-group animated fadeInUp" v-cloak v-if="showMediaVideo">
                                    <iframe width="460" height="315" src="{{((strtolower($training->promotionType) == 'video')?$training->promotionContent:'')}}" frameborder="0" allowfullscreen></iframe>
                                    <label class="form-control-label">Link to Youtube/Vimeo Video</label>
                                    <input type="url" class="form-control" value="{{$training->promotionContent}}" name="videoUrl" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Lesson Name</label>
                                    <input type="text" class="form-control" value="{{old('name')?old('name'):$training->name}}" name="name" />
                                </div>
                                <div class="form-group {{($errors->has('lessonDescription'))?'has-error':''}}">
                                    <label class="form-control-label">Lesson Description</label>
                                    <textarea name="lessonDescription" id="" class="form-control" rows="4">{!!old('lessonDescription')?old('lessonDescription'):$training->description !!}</textarea>
                                    @if($errors->has('lessonDescription')) <span class="help-block errorProfilePic">{{$errors->first('lessonDescription') }}</span> @endif
                                </div>
                                <div class="form-group {{($errors->has('coach'))?'has-error':''}}">
                                    <label class="form-control-label">Coach Name</label>
                                    <select name="coach" id="" class="form-control">
                                    <option value="">Please Select</option>
                                   @if($coaches && $coaches->count()>0)
                                   		@foreach($coaches as $key=>$coach)
                                   			<option value="{{$coach->id}}" {{(old('coach') && old('coach')==$coach->id)?'selected="selected"':($training->coach_id == $coach->id)?'selected="selected"':'' }}>{{$coach->name}}</option>
                                   		@endforeach
                                   @endif
                                </select>
                                @if($errors->has('coach')) <span class="help-block errorProfilePic">{{$errors->first('coach') }}</span> @endif
                                </div>
                                <div class="form-group {{($errors->has('numberOfSeats'))?'has-error':''}}">
                                    <label class="form-control-label">Number of seats available</label>
                                    <input type="number" class="form-control" name="numberOfSeats" value="{{old('numberOfSeats')?old('numberOfSeats'):$training->seats}}" />
                                     @if($errors->has('numberOfSeats')) <span class="help-block errorProfilePic">{{$errors->first('numberOfSeats') }}</span> @endif
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 {{($errors->has('startDate'))?'has-error':''}}">
                                        <label class="form-control-label">Start Date</label>
                                        <input type="date" class="form-control" name="startDate" value="{{old('startDate')?old('startDate'):$training->startDate}}" data-date-inline-picker="false" data-date-open-on-focus="true" />
                                        @if($errors->has('startDate')) <span class="help-block errorProfilePic">{{$errors->first('startDate') }}</span> @endif
                                    </div>
                                    <div class="form-group col-md-6 {{($errors->has('endDate'))?'has-error':''}}">
                                        <label class="form-control-label">End Date</label>
                                        <input type="date" class="form-control" name="endDate" value="{{old('endDate')?old('endDate'):$training->endDate}}" data-date-inline-picker="false" data-date-open-on-focus="true" />
                                        @if($errors->has('endDate')) <span class="help-block errorProfilePic">{{$errors->first('endDate') }}</span> @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 {{($errors->has('numberOfSessions'))?'has-error':''}}">
                                        <label class="form-control-label">Number of Sessions</label>
                                        <input type="number" class="form-control" name="numberOfSessions" value="{{old('numberOfSessions')?old('numberOfSessions'):$training->sessions}}"/>
                                        @if($errors->has('numberOfSessions')) <span class="help-block errorProfilePic">{{$errors->first('numberOfSessions') }}</span> @endif
                                    </div>
                                    <div class="form-group col-md-6 {{($errors->has('price'))?'has-error':''}}">
                                        <label class="form-control-label">Price</label>
                                        <input type="text" class="form-control" name="price" value="{{old('price')?old('price'):$training->price}}"/>
                                        @if($errors->has('price')) <span class="help-block errorProfilePic">{{$errors->first('price') }}</span> @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-def">
                                        <i class="fa fa-floppy-o"></i> &nbsp;Update Lesson
                                    </button>
                                    <a href="{{route("admin.trainings.index")}}" class="btn btn-outline b-primary text-primary">
                                        <i class="fa fa-ban"></i> &nbsp;Cancel
                                    </a>
                                </div>
                            </div>
                    </form>
                </div>
                <div class="padding-small"></div>
                <div class="row bg-white">
                    <div class="col-md-6">
                        <div class="main-page-heading">
                            <h3>
                                <span>Person Taking this Lesson</span>
                            </h3>
                        </div>
                    </div>
                    <div class="col-md-6 text-right p-10">
                        <button class="btn btn-def" type="button" data-toggle="modal" data-target="#addMembersToLesson">Add Member</button>
                        <div class="modal fade" tabindex="-1" role="dialog" id="addMembersToLesson">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header text-left">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Select Member to Add</h4>
                                    </div>
                                    <div class="modal-body" id="membersPageAutoCom">
                                        <auto-complete-box url="{{url('admin/member/search-list')}}" property-for-id="id" property-for-name="name" filtered-from-source="true" include-id-in-list="true" v-model="selectedId" initial-text-value="" search-query-key="search" field-name="memberId"> </auto-complete-box>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-def"><i class="fa fa-floppy-o"></i> Add Member</button>
                                        <button type="button" class="btn btn-outline b-primary text-primary" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                    </div>
                    <div class="col-md-12 padding-none">
                        <person-list :persons-list="personsList"></person-list>
                    </div>
                </div>
            </div>
        </div>

@endsection

@section('page-specific-scripts')
    @include("admin.__vue_components.autocomplete.autocomplete")
    @include("admin.__vue_components.trainings.persons-list");
    <script>

        var baseUrl = "{{url('trainings/')}}";
        _persons = [{name:'John Wick',email:'someone@example.com',id:'BVUBFSJPQ'},
            {name:'Spider Man',email:'someone@example.com',id:'BVUBFSJPQ'},
            {name:'Iron Man',email:'someone@example.com',id:'BVUBFSJPQ'},
            {name:'Hulk',email:'someone@example.com',id:'BVUBFSJPQ'},
            {name:'Captain America',email:'someone@example.com',id:'BVUBFSJPQ'},
            {name:'Black Widow',email:'someone@example.com',id:'BVUBFSJPQ'},
            {name:'Hansel , The Witch Hunter',email:'someone@example.com',id:'BVUBFSJPQ'},
        ];

        var vue = new Vue({
            el: "#selectionDepHidden",
            data: {
                //showParentSelector:false,
                //memberType:'',
                selectedId: '',
                personsList:[],
                latestPageLoaded:0,
                ajaxRequestInProcess:false,
                showMediaImage:false,
                showMediaVideo:true,
                lessonMediaType:'{{(old('lessonMedia'))?old('lessonMedia'):strtolower($training->promotionType)}}',
            },
            methods: {
                loadNextPage:function() {


                    if(this.nextAvailablePage === null){
                        return;
                    }
                    
                    _url = baseUrl+'?current_page='+(this.nextAvailablePage);

                    if(!this.ajaxRequestInProcess){
                        this.ajaxRequestInProcess = true;
                        var request = $.ajax({

                            url: _url,
                            method: "GET",
                            success:function(msg){

                                this.ajaxRequestInProcess = false;

                                pageDataReceived = msg;
                                membersList = pageDataReceived.data ;

                                //Success code to follow
                                if(pageDataReceived.next_page_url !== null){
                                    this.nextAvailablePage = pageDataReceived.current_page+1;
                                }else{
                                    this.nextAvailablePage = null;
                                }

                                if(isSearchQuery){

                                    this.membersList=membersList;
                                }else{

                                    appendArray(this.membersList,membersList);
                                }



                            }.bind(this),

                            error: function(jqXHR, textStatus ) {
                                this.ajaxRequestInProcess = false;

                                //Error code to follow


                            }.bind(this)
                        });
                    }
                },
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
        $(document).ready(function() {
            vue.loadNextPage();
            console.log("bottom!");

        });
        $(window).scroll(function() {
            if($(window).scrollTop() + $(window).height() == $(document).height()) {
                vue.loadNextPage();
                console.log("bottom!");
            }
        });
    </script>
    <script>


        $( function() {
            $( "#datePicker" ).datepicker();
        } );
    </script>
    @endSection
