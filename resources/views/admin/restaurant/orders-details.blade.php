@extends('admin.__layouts.admin-layout')
@section('heading')
    Order
@endSection
@section('main')
    <div ui-view class="app-body" id="view">
        <!-- ############ PAGE START-->
        <div id="order-view" class="segments-main padding">
            <div class="row">
                <div class="segments-inner">
                    <div class="box">
                        <div class="inner-header">
                            <div class="">
                                <div class="col-md-8">
                                    <div class="search-form">
                                        <h3>Order Details</h3>
                                    </div>
                                </div>
                                <div class="col-md-4 text-right">
                                    <a href="" class="btn btn-def pull-right hidden-print" onclick="window.print();"> <i class="fa fa-print"></i> Print</a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="padding">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <p class="text-sm">Customer Name:</p>
                                        <h2>Apple Inc.</h2>
                                        <p class="m-b-lg"></p>
                                    </div>
                                    <div class="col-xs-6 text-right">
                                        <p class="text-md">#9048392</p>
                                    </div>
                                </div>
                                <p>
                                    <span class="m-b-10">Order date: <strong>26th Mar 2013</strong></span>
                                    <br />
                                    <span class="m-b-10 hidden-print">
                                        Order status:
                                        <button class="btn btn-sm btn-outline rounded b-primary text-primary">In Process</button>
                                        <!--<button class="btn btn-sm rounded rounded red">In Process</button>-->
                                        <button class="btn btn-sm btn-outline rounded b-info text-info">Is Ready</button>
                                        <!--<button class="btn btn-sm rounded rounded blue">Is Ready</button>-->
                                        {{--<button class="btn btn-sm btn-outline rounded b-success text-success">Is Served</button>--}}
                                        <button class="btn btn-sm rounded rounded green">Is Served</button>
                                    </span>
                                    <br />
                                    <span class="m-b-10">
                                        Order ID: <strong>#9399034</strong>
                                    </span>
                                </p>
                                <div class="table-responsive">
                                    <table class="table table-striped white b-a">
                                        <thead>
                                        <tr>
                                            <th style="width: 60px">QTY</th>
                                            <th>DESCRIPTION</th>
                                            <th style="width: 140px">UNIT PRICE</th>
                                            <th style="width: 90px">TOTAL</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>iPhone 5 32GB White &amp; Silver (GSM) Unlocked</td>
                                            <td>$749.00</td>
                                            <td>$749.00</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>iPad mini with Wi-Fi 32GB - White &amp; Silver</td>
                                            <td>$429.00</td>
                                            <td>$858.00</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right"><strong>Subtotal</strong></td>
                                            <td>$1607.00</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right no-border"><strong>VAT Included in Total</strong></td>
                                            <td>$0.00</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right no-border"><strong>Total</strong></td>
                                            <td><strong>$1607.00</strong></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-specific-scripts')
    <script>
        //var baseUrl = "{{url('admin/member')}}";
        var order = {
            id: 1,
            member_name: "Member 1",
            in_process: "YES",
            is_ready: "YES",
            is_served: "NO",
            gross_total: 12345.43,
            created_at: "",
            order_details: [{
                id: 1,
                restaurant_product_name: "Product 1",
                quantity: 1,
                sale_total: 120
            }, {
                id: 2,
                restaurant_product_name: "Product 2",
                quantity: 2,
                sale_total: 131
            }, {
                id: 3,
                restaurant_product_name: "Product 3",
                quantity: 1,
                sale_total: 120
            }, ]
        };
        var vue = new Vue({
            el: "#order-view",
            data: {
                orderDetail:order
            }
        });
    </script>
@endSection
