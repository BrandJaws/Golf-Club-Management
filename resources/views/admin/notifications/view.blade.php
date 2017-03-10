@extends('admin.__layouts.admin-layout')
@section('heading')
    Notifications
    @endSection
@section('main')
        <div ui-view class="app-body" id="view">
            <div class="padding">
                <div class="row notificationsCreateSec">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-4">
                                <p class="notiViewInfo">Title:</p>
                            </div>
                            <div class="col-md-8">
                                <p class="notiViewData">
                                    Fantastic Start to Sunday Lunch Promotion
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="notiViewInfo">Sent on:</p>
                            </div>
                            <div class="col-md-8">
                                <p class="notiViewData">
                                    Jan 25, 2017
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="notiViewInfo">Status:</p>
                            </div>
                            <div class="col-md-8">
                                <p class="notiViewData statusSuccess">
                                    Sent
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="notiViewInfo">Description:</p>
                            </div>
                            <div class="col-md-8">
                                <p class="notiViewData">
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi atque dolorem doloremque excepturi laborum, necessitatibus officia officiis repellendus! Distinctio fugit, ipsum mollitia nobis odio perspiciatis tempore veritatis? A fugiat, pariatur?
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn-def btn">
                                <i class="fa fa-repeat"></i> &nbsp;Resend
                            </button>
                            <button class="btn-def btn">
                                <i class="fa fa-ban"></i> &nbsp;Cancel
                            </button>
                            <a href="{{route("admin.notifications.notifications")}}" class="btn btn-outline b-primary text-primary">Return to Notifications</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        {{--<input type="date" class="hide-replaced" />--}}
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('page-specific-scripts')
    <script>
        $( function() {
            $( "#datePicker" ).datepicker();
        } );
    </script>
    @endSection
