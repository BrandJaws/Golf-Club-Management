@extends('admin.__layouts.admin-layout')
@section('heading')
    Courses
    @endSection
@section('main')
    <div class="app-body" id="view">
        <!-- ############ PAGE START-->
        <div class="profile-main padding" id="selectionDepHidden">
            <div class="row inner-header">
                <div class="col-md-6">
                    <div class="inner-page-heading text-left"><h3>Courses Listing</h3></div>
                </div>
                <div class="col-md-6 text-right">
                    <a href="" class="btn-def btn"><i class="fa fa-plus-circle"></i> &nbsp;Add new courses</a>
                </div>
            </div>
            <div class="row bg-white">
                <div class="col-md-12 padding-none">
                    <courses :courses="courses"></courses>
                </div>
            </div>
        </div>
    </div>

    @include("admin.__vue_components.autocomplete.autocomplete")
    @include("admin.__vue_components.courses.courses-table");
    <script>

        var baseUrl = "{{url('')}}";
        _courses = [{name:'FORES',openTime:'9AM',closeTime:'03PM',bookingInterval:'15 Min',bookingDuration:'12 Min',hole:'4'},
            {name:'CHORES',openTime:'4AM',closeTime:'04PM',bookingInterval:'19 Min',bookingDuration:'15 Min',hole:'5'},
            {name:'LOLS',openTime:'3AM',closeTime:'05PM',bookingInterval:'16 Min',bookingDuration:'13 Min',hole:'7'},
            {name:'GRO',openTime:'7AM',closeTime:'03PM',bookingInterval:'15 Min',bookingDuration:'12 Min',hole:'3'},
            {name:'LAPOO',openTime:'6AM',closeTime:'06PM',bookingInterval:'14 Min',bookingDuration:'11 Min',hole:'6'},
            {name:'DIPLO',openTime:'5AM',closeTime:'07PM',bookingInterval:'13 Min',bookingDuration:'15 Min',hole:'3'},
            {name:'FANTO',openTime:'4AM',closeTime:'05PM',bookingInterval:'12 Min',bookingDuration:'16 Min',hole:'2'},
            {name:'LOPEE',openTime:'3AM',closeTime:'03PM',bookingInterval:'17 Min',bookingDuration:'14 Min',hole:'8'},
        ];

        var vue = new Vue({
            el: "#selectionDepHidden",
            data: {
                showParentSelector:false,
                selectedId: '',
                courses:[],
                latestPageLoaded:0,
                ajaxRequestInProcess:false,
            },
            methods: {
                loadNextPage:function() {
                    //add sample data to array to check scroll functionality
                    if (this.latestPageLoaded == 0) {
                        for (x = 0; x < _courses.length; x++) {
                            this.courses.push(_courses[x]);
                        }

                    }
                    return;
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
    @endSection
