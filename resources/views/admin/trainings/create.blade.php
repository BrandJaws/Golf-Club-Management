@extends('admin.__layouts.admin-layout') @section('main')
    <div id="content" class="app-content box-shadow-z0" role="main">
        <div class="app-header white box-shadow">
            <div class="navbar">
                <!-- Open side - Naviation on mobile -->
                <a data-toggle="modal" data-target="#aside"
                   class="navbar-item pull-left hidden-lg-up"> <i
                            class="material-icons">&#xe5d2;</i>
                </a>
                <!-- / -->
                <!-- Page title - Bind to $state's title -->
                <div class="navbar-item pull-left h5"
                     ng-bind="$state.current.data.title" id="pageTitle"></div>
                <!-- navbar right -->
                <ul class="nav navbar-nav pull-right">
                    <li class="nav-item dropdown pos-stc-xs"><a class="nav-link" href
                                                                data-toggle="dropdown"> <i class="material-icons">&#xe7f5;</i> <span
                                    class="label label-sm up warn">3</span>
                        </a></li>
                    <li class="nav-item dropdown"><a class="nav-link clear" href
                                                     data-toggle="dropdown"> <span class="avatar w-32"> <img
                                        src="../../assets/images/a0.jpg" alt="..."> <i
                                        class="on b-white bottom"></i>
					</span>
                        </a>
                        <div class="dropdown-menu pull-right dropdown-menu-scale ng-scope">
                            <a class="dropdown-item" href="{{route('admin.profile.profile')}}">
                                <span>Profile</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#/access/signin">Sign out</a>
                        </div></li>
                    <li class="nav-item hidden-md-up"><a class="nav-link"
                                                         data-toggle="collapse" data-target="#collapse"> <i
                                    class="material-icons">&#xe5d4;</i>
                        </a></li>
                </ul>
                <!-- / navbar right -->

                <!-- navbar collapse -->
                <div class="collapse navbar-toggleable-sm" id="collapse">
                    <div class="main-page-heading">
                        <h3>
                            <span>Add Lesson</span>
                        </h3>
                    </div>
                </div>
                <!-- / navbar collapse -->
            </div>
        </div>

        <div class="app-body" id="view">
            <!-- ############ PAGE START-->
            <div class="profile-main padding" id="selectionDepHidden">
                <div class="row details-section">
                    <form action="">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-control-label">Lesson Name</label>
                                <input type="text" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Lesson Description</label>
                                <textarea name="" id="" class="form-control" rows="8"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Coach Name</label>
                                <select name="" id="" class="form-control">
                                    <option value="">Bashir</option>
                                    <option value="">Jamil</option>
                                    <option value="">Sarfraz</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Number of seats available</label>
                                <input type="number" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Lesson Date</label>
                                <input type="date" class="form-control" data-date-inline-picker="false" data-date-open-on-focus="true" />
                            </div>
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
                                <label class="form-control-label">Select Image File</label>
                                <input type="file" class="form-control" />
                            </div>
                            <div class="form-group animated fadeInUp" v-cloak v-if="showMediaVideo">
                                <label class="form-control-label">Link to Youtube/Vimeo Video</label>
                                <input type="url" class="form-control" />
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
    </div>

    @include("admin.__vue_components.autocomplete.autocomplete")
    <script>
        var vue = new Vue({
            el: "#selectionDepHidden",
            data: {
                showMediaImage:false,
                showMediaVideo:false,
                lessonMediaType:'',
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
