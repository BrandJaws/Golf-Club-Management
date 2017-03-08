@extends('admin.__layouts.admin-layout')
@section('heading')
    Add Lessons
    @endSection
@section('main')
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
