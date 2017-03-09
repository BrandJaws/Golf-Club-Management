@extends('admin.__layouts.admin-layout')
@section('heading')
    Add Warnings
    @endSection
@section('main')
        <div ui-view class="app-body" id="view">
            <div class="padding">
                <div class="row notificationsCreateSec">
                    <div class="col-md-8">
                        <form action="#." method="post">
                            <div class="form-group">
                                <label class="form-control-label">Warning Title</label>
                                <input type="text" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Warning Details</label>
                                <textarea name="" id="" class="form-control" rows="8"></textarea>
                            </div>
                            <br/>
                            <div class="form-group">
                                <button class="btn btn-def">
                                    <i class="fa fa-floppy-o"></i> &nbsp;Add Warning
                                </button>
                                &nbsp; &nbsp;
                                <button class="btn btn-outline b-primary text-primary">
                                    <i class="fa fa-ban"></i> &nbsp;Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endSection
