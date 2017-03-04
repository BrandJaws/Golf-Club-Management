@extends('admin.__layouts.admin-layout')
@section('heading')
    Lessons
    @endSection
@section('main')
        <div ui-view class="app-body" id="view">
            <!-- ############ PAGE START-->
            <div id="trainings-list-table" class="segments-main padding">
                <div class="row">
                    <div class="segments-inner">
                        <div class="box">
                            <div class="inner-header">
                                <div class="">
                                    <div class="col-md-8">
                                        <h3>
                                            <span>Lessons List</span>
                                        </h3>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <a class="btn-def btn" href="{{route("admin.trainings.create")}}"><i
                                                    class="fa fa-plus-circle"></i>&nbsp;Add New Lesson</a>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <!-- inner header -->
                            <trainings :trainings-list="trainingsList"> </trainings>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @include("admin.__vue_components.trainings.trainings-table")
    <script>
        var baseUrl = "{{url('')}}";
        _trainingsList = [{name:'Fantastic Start to Sunday Lunch Promotion',instructor:'Bootstrap Bill',seats:'12',seatsReserved:'08'},
            {name:'Our best ever full membership offer!!',instructor:'General Swan',seats:'12',seatsReserved:'08'},
            {name:'Mother Day Sunday Lunch offer',instructor:'Captain Barbosa',seats:'12',seatsReserved:'08'},
            {name:'Our best ever full membership offer!!',instructor:'Jack The Monkey',seats:'12',seatsReserved:'08'},
            {name:'Fantastic Start to Sunday Lunch Promotion',instructor:'Captain Jack Sparrow',seats:'12',seatsReserved:'08'},
            {name:'Fantastic Start to Sunday Lunch Promotion',instructor:'Bootstrap Bill',seats:'12',seatsReserved:'08'},
            {name:'Our best ever full membership offer!!',instructor:'General Swan',seats:'12',seatsReserved:'08'},
            {name:'Mother Day Sunday Lunch offer',instructor:'Captain Barbosa',seats:'12',seatsReserved:'08'},
            {name:'Our best ever full membership offer!!',instructor:'Jack The Monkey',seats:'12',seatsReserved:'08'},
            {name:'Fantastic Start to Sunday Lunch Promotion',instructor:'Captain Jack Sparrow',seats:'12',seatsReserved:'08'},
            {name:'Fantastic Start to Sunday Lunch Promotion',instructor:'Bootstrap Bill',seats:'12',seatsReserved:'08'},
            {name:'Our best ever full membership offer!!',instructor:'General Swan',seats:'12',seatsReserved:'08'},
            {name:'Mother Day Sunday Lunch offer',instructor:'Captain Barbosa',seats:'12',seatsReserved:'08'},
            {name:'Our best ever full membership offer!!',instructor:'Jack The Monkey',seats:'12',seatsReserved:'08'},
            {name:'Fantastic Start to Sunday Lunch Promotion',instructor:'Captain Jack Sparrow',seats:'12',seatsReserved:'08'},
            {name:'Our best ever full membership offer!!',instructor:'John Smith',seats:'12',seatsReserved:'08'}];

        var vue = new Vue({
            el: "#trainings-list-table",
            data: {
                trainingsList:[],
                latestPageLoaded:0,
                ajaxRequestInProcess:false
            },
            methods: {
                loadNextPage:function(){
                    if(this.latestPageLoaded == 0){
                        for(x=0; x<_trainingsList.length; x++){
                            this.trainingsList.push(_trainingsList[x]);
                        }

                    }
                    return;
                }
            },
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

    @endSection
