@extends('admin.__layouts.admin-layout')
@section('heading')
    Edit Events
    @endSection
@section('main')
        <div class="app-body" id="view">
            <!-- ############ PAGE START-->
            <div class="profile-main padding" id="selectionDepHidden">
                <div class="row details-section">
                    <form name="" action="{{route('admin.events.update',$event->id)}}" method="post" enctype="multipart/form-data">
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
                                                Select Event Media
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="radio">
                                                <label class="ui-check">
                                                    <input type="radio" name="eventMedia" value="image" class="has-value" v-model="eventMediaType" >
                                                    <i class="dark-white"></i>
                                                    Image
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="radio">
                                                <label class="ui-check">
                                                    <input type="radio" name="eventMedia" value="video" class="has-value" v-model="eventMediaType" >
                                                    <i class="dark-white"></i>
                                                    Video
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group animated fadeInUp" v-cloak v-if="showMediaImage">
                                    <img src="{{((strtolower($event->promotionType) == 'image')?asset($event->promotionContent):'')}}" alt="" class="ïmg-responsive" />
                                    <label class="form-control-label">Select Image File</label>
                                     <input type="file" class="form-control" name="promotionImage" value=""/>
                                	@if($errors->has('promotionImage')) <span class="help-block errorProfilePic">{{$errors->first('promotionImage') }}</span> @endif
                                </div>
                                <div class="form-group animated fadeInUp" v-cloak v-if="showMediaVideo">
                                    <iframe width="460" height="315" src="{{((strtolower($event->promotionType) == 'video')?$event->promotionContent:'')}}" frameborder="0" allowfullscreen></iframe>
                                    <label class="form-control-label">Link to Youtube/Vimeo Video</label>
                                    <input type="url" class="form-control" value="{{$event->promotionContent}}" name="videoUrl" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Event Name</label>
                                    <input type="text" class="form-control" value="{{old('name')?old('name'):$event->name}}" name="name" />
                                </div>
                                <div class="form-group {{($errors->has('eventDescription'))?'has-error':''}}">
                                    <label class="form-control-label">Event Description</label>
                                    <textarea name="eventDescription" id="" class="form-control" rows="4">{!!old('eventDescription')?old('eventDescription'):$event->description !!}</textarea>
                                    @if($errors->has('eventDescription')) <span class="help-block errorProfilePic">{{$errors->first('eventDescription') }}</span> @endif
                                </div>
                                <div class="form-group {{($errors->has('coach'))?'has-error':''}}">
                                    <label class="form-control-label">Coach Name</label>
                                    <select name="coach" id="" class="form-control">
                                    <option value="">Please Select</option>
                                   @if($coaches && $coaches->count()>0)
                                   		@foreach($coaches as $key=>$coach)
                                   			<option value="{{$coach->id}}" {{(old('coach') && old('coach')==$coach->id)?'selected="selected"':($event->coach_id == $coach->id)?'selected="selected"':'' }}>{{$coach->name}}</option>
                                   		@endforeach
                                   @endif
                                </select>
                                @if($errors->has('coach')) <span class="help-block errorProfilePic">{{$errors->first('coach') }}</span> @endif
                                </div>
                                <div class="form-group {{($errors->has('numberOfSeats'))?'has-error':''}}">
                                    <label class="form-control-label">Number of seats available</label>
                                    <input type="number" class="form-control" name="numberOfSeats" value="{{old('numberOfSeats')?old('numberOfSeats'):$event->seats}}" />
                                     @if($errors->has('numberOfSeats')) <span class="help-block errorProfilePic">{{$errors->first('numberOfSeats') }}</span> @endif
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 {{($errors->has('startDate'))?'has-error':''}}">
                                        <label class="form-control-label">Start Date</label>
                                        <input type="date" class="form-control" name="startDate" value="{{old('startDate')?old('startDate'):$event->startDate}}" data-date-inline-picker="false" data-date-open-on-focus="true" />
                                        @if($errors->has('startDate')) <span class="help-block errorProfilePic">{{$errors->first('startDate') }}</span> @endif
                                    </div>
                                    <div class="form-group col-md-6 {{($errors->has('endDate'))?'has-error':''}}">
                                        <label class="form-control-label">End Date</label>
                                        <input type="date" class="form-control" name="endDate" value="{{old('endDate')?old('endDate'):$event->endDate}}" data-date-inline-picker="false" data-date-open-on-focus="true" />
                                        @if($errors->has('endDate')) <span class="help-block errorProfilePic">{{$errors->first('endDate') }}</span> @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 {{($errors->has('numberOfSessions'))?'has-error':''}}">
                                        <label class="form-control-label">Number of Sessions</label>
                                        <input type="number" class="form-control" name="numberOfSessions" value="{{old('numberOfSessions')?old('numberOfSessions'):$event->sessions}}"/>
                                        @if($errors->has('numberOfSessions')) <span class="help-block errorProfilePic">{{$errors->first('numberOfSessions') }}</span> @endif
                                    </div>
                                    <div class="form-group col-md-6 {{($errors->has('price'))?'has-error':''}}">
                                        <label class="form-control-label">Price</label>
                                        <input type="text" class="form-control" name="price" value="{{old('price')?old('price'):$event->price}}"/>
                                        @if($errors->has('price')) <span class="help-block errorProfilePic">{{$errors->first('price') }}</span> @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-def">
                                        <i class="fa fa-floppy-o"></i> &nbsp;Update Event
                                    </button>
                                    <a href="{{route("admin.events.index")}}" class="btn btn-outline b-primary text-primary">
                                        <i class="fa fa-ban"></i> &nbsp;Cancel
                                    </a>
                                </div>
                            </div>
                    </form>
                </div>
                <div class="padding-small"></div>

                        <events-persons-list :event-id="eventId" :persons-list="playersList" :url-for-crud="urlForDeletion"></events-persons-list>

            </div>
        </div>

@endsection

@section('page-specific-scripts')
    @include("admin.__vue_components.autocomplete.autocomplete")
    @include("admin.__vue_components.events.events-persons-list");
    <script>

        var baseUrl = "{{url('/admin/events/'.$event->id).'/players'}}";

        var vue = new Vue({
            el: "#selectionDepHidden",
            data: {
                //showParentSelector:false,
                //memberType:'',
                eventId: {{$event->id}},
                playersList:{!! (json_encode($players))!!}.data ,
                nextAvailablePage:{!! (json_encode($players))!!}.next_page_url !== null ? 2 : null ,
                ajaxRequestInProcess:false,
                eventMediaType:'{{(old('eventMedia'))?old('eventMedia'):strtolower($event->promotionType)}}',
                urlForDeletion:baseUrl,

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
                                playersList = pageDataReceived.data ;

                                //Success code to follow
                                if(pageDataReceived.next_page_url !== null){
                                    this.nextAvailablePage = pageDataReceived.current_page+1;
                                }else{
                                    this.nextAvailablePage = null;
                                }

                                 appendArray(this.playersList,playersList);

                            }.bind(this),

                            error: function(jqXHR, textStatus ) {
                                this.ajaxRequestInProcess = false;

                                //Error code to follow


                            }.bind(this)
                        });
                    }
                }
            },
            computed:{
                showMediaImage:function(){
                    if (this.eventMediaType == 'image') {
                        return true;
                    }
                    else {
                        return false;
                    }
                },
                showMediaVideo:function(){
                    if (this.eventMediaType == 'video') {
                        return true;
                    }
                    else {
                        return false;
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
