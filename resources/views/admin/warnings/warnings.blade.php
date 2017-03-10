@extends('admin.__layouts.admin-layout')
@section('heading')
    Warnings
    @endSection
@section('main')
        <div class="app-body" id="view">
            <!-- ############ PAGE START-->
            <div class="profile-main padding" id="selectionDepHidden">
                <div class="row inner-header">
                    <div class="col-md-6">
                        <div class="inner-page-heading text-left"><h3>Warnings Listing</h3></div>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{route('admin.warnings.create')}}" class="btn-def btn"><i class="fa fa-plus-circle"></i> &nbsp;Add new warnings</a>
                    </div>
                </div>
                <div class="row bg-white">
                    <div class="col-md-12 padding-none">
                        <warnings :warnings="warnings"></warnings>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('page-specific-scripts')
    @include("admin.__vue_components.autocomplete.autocomplete")
    @include("admin.__vue_components.warnings.warnings");
    <script>

        var baseUrl = "{{url('')}}";
        _warnings = [{name:'FORES',description:'Lorem impsul dolar esmit...',date:'Dec 9 2016 - 2:13:00 AM'},
            {name:'NINE',description:'Lorem impsul dolar esmit...',date:'Dec 6 2016 - 2:13:00 AM'},
            {name:'SOD',description:'Lorem impsul dolar esmit...',date:'Dec 2 2016 - 2:13:00 AM'},
            {name:'APRON',description:'Lorem impsul dolar esmit...',date:'Jan 9 2017 - 2:13:00 AM'},
            {name:'PAR',description:'Lorem impsul dolar esmit...',date:'Jan 4 2017 - 2:13:00 AM'},
            {name:'PLAYBY',description:'Lorem impsul dolar esmit...',date:'Jan 10 2017 - 2:13:00 AM'},
            {name:'TEE',description:'Lorem impsul dolar esmit...',date:'Jan 12 2017 - 2:13:00 AM'},
            {name:'ROUGH',description:'Lorem impsul dolar esmit...',date:'Jan 19 2017 - 2:13:00 AM'},
        ];

        var vue = new Vue({
            el: "#selectionDepHidden",
            data: {
                showParentSelector:false,
                selectedId: '',
                warnings:[],
                latestPageLoaded:0,
                ajaxRequestInProcess:false,
            },
            methods: {
                affiliate:function() {
                    if (this.memberType == 'affiliate') {
                        this.showParentSelector = true;
                    }
                    else {
                        this.showParentSelector = false;
                    }
                },
                loadNextPage:function() {
                    //add sample data to array to check scroll functionality
                    if (this.latestPageLoaded == 0) {
                        for (x = 0; x < _warnings.length; x++) {
                            this.warnings.push(_warnings[x]);
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
