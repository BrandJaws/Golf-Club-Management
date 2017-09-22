@extends('admin.__layouts.admin-layout')
@section('heading')
    Orders
@endSection
@section('main')
    <div ui-view class="app-body" id="view">
        <!-- ############ PAGE START-->
        <div id="orders-list-table" class="segments-main padding">
            <div class="row">
                <div class="segments-inner">
                    <div class="box">
                        <div class="inner-header">
                            <div class="">
                                <div class="col-md-8">
                                    <div class="search-form">
                                        <h3>In Process Orders</h3>
                                    </div>
                                </div>
                                <div class="col-md-4 text-right">

                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        @if(Session::has('error'))
                            <div class="alert alert-warning" role="alert"> {{Session::get('error')}} </div>
                        @endif
                        @if(Session::has('success'))
                            <div class="alert alert-success" role="alert"> {{Session::get('success')}} </div>
                        @endif
                        <orders-table-cotainer> <orders-table :orders-list="ordersList"></orders-table> </orders-table-cotainer>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-specific-scripts')
    @include("admin.__vue_components.restaurant.orders")
    <script>
        //var baseUrl = "{{url('admin/member')}}";
        var orders = [{
            member_name: "Member 1",
            in_process: "NO",
            is_ready: "NO",
            is_served: "NO",
            gross_total: 1234.12,
            created_at: "21/09/2017"
        }, {
            member_name: "Member 1",
            in_process: "YES",
            is_ready: "YES",
            is_served: "NO",
            gross_total: 1234.12,
            created_at: "21/09/2017"
        }, {
            member_name: "Member 1",
            in_process: "YES",
            is_ready: "YES",
            is_served: "YES",
            gross_total: 1234.12,
            created_at: "21/09/2017"
        }] ;
        var vue = new Vue({
            el: "#orders-list-table",
            data: {
                ordersList:orders,
                ajaxRequestInProcess:false,
            }
        });
    </script>
@endSection
