@extends('admin.__layouts.admin-layout')
@section('heading')
    Edit Lessons
    @endSection
@section('main')
        <div class="app-body" id="view">
            <!-- ############ PAGE START-->
            <div class="profile-main padding" id="selectionDepHidden">
                <div class="row details-section">
                    <form name="" action="" method="post" enctype="multipart/form-data">
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
                                    <img src="{{asset("/assets/images/c1.jpg")}}" alt="" class="ïmg-responsive" />
                                    <label class="form-control-label">Select Image File</label>
                                    <input type="file" class="form-control" />
                                </div>
                                <div class="form-group animated fadeInUp" v-cloak v-if="showMediaVideo">
                                    <iframe width="460" height="315" src="https://www.youtube.com/embed/lbO9LhD9PsI" frameborder="0" allowfullscreen></iframe>
                                    <label class="form-control-label">Link to Youtube/Vimeo Video</label>
                                    <input type="url" class="form-control" value="https://www.youtube.com/watch?v=lbO9LhD9PsI" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Lesson Name</label>
                                    <input type="text" class="form-control" value="Jinga Lala Boom Boom" />
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Lesson Description</label>
                                    <textarea name="" id="" class="form-control" rows="4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eu velit interdum, pulvinar velit non, varius ex. Fusce ac libero nec mi dictum rutrum quis at leo. Maecenas id fringilla erat. Praesent malesuada at elit a placerat. Aliquam faucibus massa non diam semper dapibus. Quisque convallis eget ipsum vitae viverra.</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Coach Name</label>
                                    <select name="" id="" class="form-control">
                                        <option selected value="">Bashir</option>
                                        <option value="">Jamil</option>
                                        <option value="">Sarfraz</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Number of seats available</label>
                                    <input type="number" class="form-control" value="24" />
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Lesson Date</label>
                                    <input type="date" class="form-control" value="2017-04-22" data-date-inline-picker="false" data-date-open-on-focus="true" />
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

        var baseUrl = "{{url('')}}";
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
                lessonMediaType:'video',
            },
            methods: {
                loadNextPage:function() {
                    //add sample data to array to check scroll functionality
                    if (this.latestPageLoaded == 0) {
                        for (x = 0; x < _persons.length; x++) {
                            this.personsList.push(_persons[x]);
                        }

                    }
                    return;
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
