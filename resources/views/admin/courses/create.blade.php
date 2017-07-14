@extends('admin.__layouts.admin-layout')
@section('heading')
    Add Course
    @endSection
@section('main')
    <div class="app-body" id="courseContainer" @click="containerClicked">
    <!-- ############ PAGE START-->
    <div class="profile-main padding" id="selectionDepHidden">
        <div class="row details-section">
            <form action="{{route('admin.courses.store')}}" name="" method="post">
                @if(Session::has('error'))
                    <div class="alert alert-warning" role="alert"> {{Session::get('error')}} </div>
                @endif
                @if(Session::has('success'))
                    <div class="alert alert-success" role="alert"> {{Session::get('success')}} </div>
                @endif
                <input type="hidden" name="_method" value="POST"/>
                {{ csrf_field() }}
                <div class="col-md-8">
                    <div class="form-group {{($errors->has('name'))?'has-error':''}}">
                        <label class="form-control-label">Course Name</label>
                        <input type="text" class="form-control" name="name" value="{{Request::old('name')}}"/>
                        @if($errors->has('name')) <span
                                class="help-block errorProfilePic">{{$errors->first('name') }}</span> @endif
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{($errors->has('openTime'))?'has-error':''}}">
                                <label class="form-control-label">Open Time</label>
                                <input type="time" class="form-control" placeholder="AM" name="openTime"
                                       value="{{Request::old('openTime')}}"/>
                                @if($errors->has('openTime')) <span
                                        class="help-block errorProfilePic">{{$errors->first('openTime') }}</span> @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group {{($errors->has('closeTime'))?'has-error':''}}">
                                <label class="form-control-label">Close Time</label>
                                <input type="time" class="form-control" placeholder="PM" name="closeTime"
                                       value="{{Request::old('closeTime')}}"/>
                                @if($errors->has('closeTime')) <span
                                        class="help-block errorProfilePic">{{$errors->first('closeTime') }}</span> @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group {{($errors->has('bookingInterval'))?'has-error':''}}">
                                <label class="form-control-label">Booking Interval</label>
                                <input type="number" class="form-control" placeholder="Minutes" name="bookingInterval"
                                       value="{{Request::old('bookingInterval')}}"/>
                                @if($errors->has('bookingInterval')) <span
                                        class="help-block errorProfilePic">{{$errors->first('bookingInterval') }}</span> @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group {{($errors->has('bookingDuration'))?'has-error':''}}">
                                <label class="form-control-label">Booking Duration</label>
                                <input type="number" class="form-control" placeholder="Minutes" name="bookingDuration"
                                       value="{{Request::old('bookingDuration')}}"/>
                                @if($errors->has('bookingDuration')) <span
                                        class="help-block errorProfilePic">{{$errors->first('bookingDuration') }}</span> @endif
                            </div>
                            </d iv>
                        </div>
                        <div class="">
                            <div class="col-md-6">
                                <div class="form-group {{($errors->has('numberOfHoles'))?'has-error':''}}">
                                    <label class="form-control-label">Number of Holes</label>
                                    <input type="text" class="form-control" name="numberOfHoles"
                                           value="{{Request::old('numberOfHoles')}}" v-model="numberOfHoles"/>
                                    @if($errors->has('numberOfHoles')) <span
                                            class="help-block errorProfilePic">{{$errors->first('numberOfHoles') }}</span> @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="">
                            <div class="col-md-12">
                                <div class="form-group clearfix">
                                    <div class="checkbox">
                                        <label class="ui-check">
                                            <input type="checkbox" value="open" name="status">
                                            <i class="dark-white"></i>
                                            Is Open?
                                        </label>
                                    </div>
                                    <div class="checkbox-inline">
											<span class="pull-left"><label>

											</span>
                                    </div>
                                </div>
                            </div>

                            <!--Tees HTML-->
                            <div class="col-md-6">
                                <div class="form-group {{($errors->has('numberOfTees'))?'has-error':''}}">
                                    <label class="form-control-label">Number of Tees</label>
                                    <input type="text" class="form-control" name="numberOfTees"
                                           value="{{Request::old('numberOfTees')}}" v-model.number="numberOfTees"/>
                                    @if($errors->has('numberOfTees')) <span
                                            class="help-block errorProfilePic">{{$errors->first('numberOfTees') }}</span> @endif
                                </div>
                            </div>
                            <div class="col-md-6"></div>
                            <div class="col-md-12">
                                <div class="form-group {{($errors->has('configureTees'))?'has-error':''}}">
                                    <label class="form-control-label">Configure Tees</label>
                                    <input type="number" class="form-control" name="configureTees"
                                           value="{{Request::old('configureTees')}}"/>
                                    @if($errors->has('configureTees')) <span
                                            class="help-block errorProfilePic">{{$errors->first('configureTees') }}</span> @endif
                                </div>
                                <div class="row">
                                    <div class="col-sm-12"><label class="form-control-label">Select Tees Color</label>
                                    </div>
                                    <div class="col-sm-3" v-for="(tee,teeIndex) in colorSelectionFieldsForTees">
                                        <div class="form-group">
                                            <select name="tees" id="" class="form-control tees-colors"
                                                    v-model="tee.selectedValue"
                                                    v-on:change="teeColorSelectionChanged(tee)">
                                                <option v-for="color in tee.colors" :class="color.class"
                                                        v-text="color.name" :value="color.name"></option>

                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel panel-tees" v-for="hole in holes">
                                    <div class="inner-header row">
                                        <div>
                                            <div class="col-md-4">
                                                <div class="inner-page-heading text-left">
                                                    <h3>Configure Tees</h3>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="search-form text-right">
                                                    <form action="#." method="post">
                                                        <div class="search-field"><span class="search-box">
                                                <input type="text" name="search" class="search-bar">
                                                <button type="submit" class="search-btn"><i class="fa fa-search"></i>
                                                </button>
                                                </span></div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>

                                    <label class="form-control-label">Hole 1</label>
                                    <ul class="list-tees">
                                        <li v-for="tee in hole.tee_values" :class="tee.cssClass">
                                            <div :class="[tee.cssClass , 'form-group', {{($errors->has('hole1'))?'has-error':''}} ]">
                                                <div class="col-sm-10"><label class="form-control-label" v-text="tee.selectedValue"></label>
                                                </div>
                                                <div class="col-sm-2"><input type="text" class="form-control"
                                                                             name="hole1"
                                                                             v-model="tee.distance"/></div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group {{($errors->has('menHandiCap'))?'has-error':''}}">
                                                <label class="form-control-label">Men's Handicap Tees</label>
                                                <input type="text" class="form-control" name="menHandiCap"
                                                       value="{{Request::old('menHandiCap')}}"/>
                                                @if($errors->has('menHandiCap')) <span
                                                        class="help-block errorProfilePic">{{$errors->first('menHandiCap') }}</span> @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group {{($errors->has('menPar'))?'has-error':''}}">
                                                <label class="form-control-label">Men's Par</label>
                                                <input type="text" class="form-control" name="menPar"
                                                       value="{{Request::old('menPar')}}"/>
                                                @if($errors->has('menPar')) <span
                                                        class="help-block errorProfilePic">{{$errors->first('menPar') }}</span> @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group {{($errors->has('womenHandiCap'))?'has-error':''}}">
                                                <label class="form-control-label">Women's Handicap Tees</label>
                                                <input type="text" class="form-control" name="womenHandiCap"
                                                       value="{{Request::old('womenHandiCap')}}"/>
                                                @if($errors->has('womenHandiCap')) <span
                                                        class="help-block errorProfilePic">{{$errors->first('womenHandiCap') }}</span> @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group {{($errors->has('womenPar'))?'has-error':''}}">
                                                <label class="form-control-label">Women's Par</label>
                                                <input type="text" class="form-control" name="womenPar"
                                                       value="{{Request::old('womenPar')}}"/>
                                                @if($errors->has('menHandiCap')) <span
                                                        class="help-block errorProfilePic">{{$errors->first('womenPar') }}</span> @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group text-right">
                                        <a href="#." class="btn btn-def disabled">
                                            <i class="fa fa-arrow-left"></i> &nbsp;Prev
                                        </a>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="#." class="btn btn-def">
                                            Next &nbsp; <i class="fa fa-arrow-right"></i>
                                        </a>


                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-def"><i class="fa fa-floppy-o"></i> &nbsp;Add
                                        Course
                                    </button>
                                    &nbsp;&nbsp;
                                    <a href="{{route('admin.courses.index')}}"
                                       class="btn btn-outline b-primary text-primary"><i class="fa fa-ban"></i> &nbsp;Cancel</a>
                                </div>
                            </div>


                        </div>
            </form>
        </div>
    </div>
    </div>

    @endSection

    @section('page-specific-scripts')


        <script>

            var vue = new Vue({
                        el: "#courseContainer",
                        data: {
                            numberOfHoles:1,
                            numberOfTees:1,
                            colors:[
                                {name:'Pink', class:'pink'},
                                {name:'Black', class:'black'},
                                {name:'Gold', class:'gold'},
                                {name:'Blue', class:'blue'},
                                {name:'Silver', class:'silver'},
                                {name:'Green', class:'green'},
                                {name:'White', class:'white'},
                                {name:'Purple', class:'purple'},
                                {name:'Orange', class:'orange'},

                            ],
                            courseName:"Test Name",
                            colorSelectionFieldsForTees:[],
                            holes:[],
                         },
                        mounted:function(){
                            this.generateColorSelectionFieldsForTees();
                            this.generateHoles();
                            this.updateTeesForHoles();

                        },
                        watch:{
                            numberOfTees:function(){

                                if(this.numberOfTees < 1 ){

                                    this.numberOfTees = 1;

                                }else if(this.numberOfTees > this.colors.length ){

                                    this.numberOfTees = this.colors.length;

                                }

                                this.generateColorSelectionFieldsForTees();
                                this.updateTeesForHoles();

                            },
                            numberOfHoles:function(){
                                if(this.numberOfHoles < 1 ){

                                    this.numberOfHoles = 1;

                                }

                                this.generateHoles();
                            },

                        },
                        computed:{

                        },
                         methods: {
                            containerClicked:function(){

                            },
                            teeColorSelectionChanged:function(tee){
                                for(colorIndex in tee.colors){
                                    if(tee.selectedValue == tee.colors[colorIndex].name){
                                        tee.cssClass = tee.colors[colorIndex].class;
                                    }
                                }
                                this.resetColorsAvailableForEachTeeSelection();
                                this.updateTeesForHoles();

                            },
                             updateTeesForHoles:function(){
                                 //Adjust tees for each hole if a difference in number of tees is found

                                        for(holeIndex in this.holes){

                                            if(this.holes[holeIndex].tee_values.length == this.numberOfTees){

                                                for(teeIndex in this.holes[holeIndex].tee_values){
                                                    if(this.holes[holeIndex].tee_values[teeIndex].color != this.colorSelectionFieldsForTees[teeIndex].selectedValue){
                                                        this.holes[holeIndex].tee_values[teeIndex].color = this.colorSelectionFieldsForTees[teeIndex].selectedValue;
                                                        this.holes[holeIndex].tee_values[teeIndex].cssClass = this.colorSelectionFieldsForTees[teeIndex].cssClass;
                                                    }
                                                }


                                            }else if(this.holes[holeIndex].tee_values.length > this.numberOfTees){

                                                while(this.holes[holeIndex].tee_values.length > this.numberOfTees){

                                                    this.holes[holeIndex].tee_values.splice(this.holes[holeIndex].tee_values.length-1,1);
                                                }

                                            }else if(this.holes[holeIndex].tee_values.length < this.numberOfTees){


                                                while(this.holes[holeIndex].tee_values.length < this.numberOfTees){

                                                    this.holes[holeIndex].tee_values.push({
                                                        color:this.colorSelectionFieldsForTees[this.holes[holeIndex].tee_values.length].selectedValue,
                                                        cssClass:this.colorSelectionFieldsForTees[this.holes[holeIndex].tee_values.length].cssClass,
                                                        distance:0,
                                                    });

                                                }


                                            }
                                        }


                             },
                             generateHoles:function(){



                                 //Adjust holes for the number of holes entered
                                 if(this.holes.length == this.numberOfHoles){

                                     return;

                                 }else if(this.holes.length > this.numberOfHoles){

                                     while(this.holes.length > this.numberOfHoles){

                                         this.holes.splice(this.holes.length-1,1);
                                     }

                                 }else if(this.holes.length < this.numberOfHoles){

                                     while(this.holes.length < this.numberOfHoles){

                                         var holeData = {
                                             tee_values: [],
                                             mens_handicap: 0,
                                             mens_par: 0,
                                             womens_handicap: 0,
                                             womens_par: 0,
                                         };
                                         for(teeIndex in this.colorSelectionFieldsForTees){
                                           // if(this.colorSelectionFieldsForTees[teeIndex].selectedValue != ''){
                                                holeData.tee_values.push({
                                                    color:this.colorSelectionFieldsForTees[teeIndex].selectedValue,
                                                    cssClass:this.colorSelectionFieldsForTees[teeIndex].cssClass,
                                                    distance:0,
                                                });
                                            //}
                                         }
                                         this.holes.push(holeData);
                                     }
                                 }





                             },
                            generateColorSelectionFieldsForTees:function(){

                                 if(this.colorSelectionFieldsForTees.length == this.numberOfTees){

                                     return;

                                 }else if(this.colorSelectionFieldsForTees.length > this.numberOfTees){

                                     while(this.colorSelectionFieldsForTees.length > this.numberOfTees){

                                         this.colorSelectionFieldsForTees.splice(this.colorSelectionFieldsForTees.length-1,1);
                                     }

                                 }else if(this.colorSelectionFieldsForTees.length < this.numberOfTees){

                                     while(this.colorSelectionFieldsForTees.length < this.numberOfTees){

                                         var fieldData = {};
                                         fieldData.colors = JSON.parse(JSON.stringify(this.colors));
                                         fieldData.selectedValue = "";
                                         fieldData.cssClass = "";
                                         this.colorSelectionFieldsForTees.push(fieldData);
                                     }
                                 }

                                 this.resetColorsAvailableForEachTeeSelection();


                             },
                             resetColorsAvailableForEachTeeSelection:function(){

                                    for(teeFieldIndex1 in this.colorSelectionFieldsForTees ){
                                        this.colorSelectionFieldsForTees[teeFieldIndex1].colors = JSON.parse(JSON.stringify(this.colors));
                                        for(teeFieldIndex2 in this.colorSelectionFieldsForTees ){
                                            if(teeFieldIndex2 != teeFieldIndex1){
                                                if(this.colorSelectionFieldsForTees[teeFieldIndex2].selectedValue != "" ){

                                                    for(colorIndex in this.colorSelectionFieldsForTees[teeFieldIndex1].colors){

                                                        if(this.colorSelectionFieldsForTees[teeFieldIndex1].colors[colorIndex].name == this.colorSelectionFieldsForTees[teeFieldIndex2].selectedValue){

                                                            this.colorSelectionFieldsForTees[teeFieldIndex1].colors.splice(colorIndex,1);
                                                            break;
                                                        }
                                                    }

                                                }
                                            }
                                        }

                                    }
                             },
                         }

            });

        </script>
    @endSection