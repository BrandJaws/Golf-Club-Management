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
                                <!--<div class="form-group {{($errors->has('configureTees'))?'has-error':''}}">
                                    <label class="form-control-label">Configure Tees</label>
                                    <input type="number" class="form-control" name="configureTees"
                                           value="{{Request::old('configureTees')}}"/>
                                    @if($errors->has('configureTees')) <span
                                            class="help-block errorProfilePic">{{$errors->first('configureTees') }}</span> @endif
                                </div>-->
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
                                <div class="col-sm-12"><div class="inner-header row">
                                        <div>
                                            <div class="col-md-4">
                                                <div class="inner-page-heading text-left">
                                                    <h3>Configure Holes</h3>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <select name="tees" id="" class="form-control tees-colors">
                                                <option value="">Search Tess By ID</option>
                                                
                                                </select>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div></div>
                                <div class="panel panel-tees" v-for="hole in holes">
                                    

                                    <ul class="list-tees">
                                        <div class="row">
                                        <li v-for="tee in hole.tee_values" >
                                            <div :class="[tee.cssClass , 'form-group', {{($errors->has('hole1'))?'has-error':''}} ]">
                                                <div class="col-sm-8">
                                               	<!-- <label class="form-control-label label-tees" v-text="tee.selectedValue">Tees Color</label>-->
                                                <label class="form-control-label label-tees" v-text="tee.color != ''? tee.color : 'No Color Selected'"></label>
                                                </div>
                                                <div class="col-sm-4">
                                                <label class="form-control-label">Yard</label>
                                                <div class="input-group">
                                                    <input type="text"  class="form-control" v-model="tee.distance">
                                                    <span :class="[tee.cssClass , 'input-group-addon', {{($errors->has('hole1'))?'has-error':''}} ]"></span>
                                                </div>
                                                
                                                
                                                <!--<input type="text" class="form-control" v-model="tee.distance"/>--></div></div>
                                            
                                        </li>
                                        </div>
                                    </ul>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group {{($errors->has('menHandiCap'))?'has-error':''}}">
                                                <label class="form-control-label">Men's Handicap Tees</label>
                                                <input type="text" class="form-control"
                                                       v-model="hole.mens_handicap"/>
                                                @if($errors->has('menHandiCap')) <span
                                                        class="help-block errorProfilePic">{{$errors->first('menHandiCap') }}</span> @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group {{($errors->has('menPar'))?'has-error':''}}">
                                                <label class="form-control-label">Men's Par</label>
                                                <input type="text" class="form-control" v-model="hole.mens_par"/>
                                                @if($errors->has('menPar')) <span
                                                        class="help-block errorProfilePic">{{$errors->first('menPar') }}</span> @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group {{($errors->has('womenHandiCap'))?'has-error':''}}">
                                                <label class="form-control-label">Women's Handicap Tees</label>
                                                <input type="text" class="form-control" v-model="hole.womens_handicap"/>
                                                @if($errors->has('womenHandiCap')) <span
                                                        class="help-block errorProfilePic">{{$errors->first('womenHandiCap') }}</span> @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group {{($errors->has('womenPar'))?'has-error':''}}">
                                                <label class="form-control-label">Women's Par</label>
                                                <input type="text" class="form-control" v-model="hole.womens_par"/>
                                                @if($errors->has('menHandiCap')) <span
                                                        class="help-block errorProfilePic">{{$errors->first('womenPar') }}</span> @endif
                                            </div>
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
                                        <span class="pull-left">

                                        </span>
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

                        <input type="hidden" v-if="teesDataJson !== null" v-model="teesDataJson" name="teesDataJson"/>
                        <input type="hidden" v-if="holesDataJson !== null" v-model="holesDataJson"  name="holesDataJson"/>

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
                            numberOfHoles:{!! Request::old('numberOfHoles') ? Request::old('numberOfHoles') : (isset($course['numberOfHoles']) ? $course['numberOfHoles'] : 1) !!},
                            numberOfTees:{!! Request::old('numberOfTees') ? Request::old('numberOfTees') : (isset($course['numberOfTees']) ? $course['numberOfTees'] : 1) !!},
                            teesDataReceived:{!! Request::old('teesDataJson') ? Request::old('teesDataJson') : (isset($course['teesDataJson']) ? $course['teesDataJson'] : '[]') !!},
                            holesDataReceived:{!! Request::old('holesDataJson') ? Request::old('holesDataJson') : (isset($course['holesDataJson']) ? $course['holesDataJson'] : '[]') !!},
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
                            this.generateColorSelectionFieldsForTees(this.teesDataReceived);
                            this.generateHoles(this.holesDataReceived);
                            this.updateTeesForHoles();

                        },
                        watch:{
                            numberOfTees:function(){

                                if(this.numberOfTees < 1 ){

                                    this.numberOfTees = 1;

                                }else if(this.numberOfTees > this.colors.length ){

                                    this.numberOfTees = this.colors.length;

                                }

                                this.generateColorSelectionFieldsForTees([]);
                                this.updateTeesForHoles();

                            },
                            numberOfHoles:function(){
                                if(this.numberOfHoles < 1 ){

                                    this.numberOfHoles = 1;

                                }

                                this.generateHoles([]);
                            },

                        },
                        computed:{
                            teesDataJson: function(){
                                var teesData = [];
                                if(this.colorSelectionFieldsForTees.length > 0){
                                    for(teeIndex in this.colorSelectionFieldsForTees){
                                       // if(this.colorSelectionFieldsForTees[teeIndex].selectedValue != '' ){
                                            teesData.push(this.colorSelectionFieldsForTees[teeIndex].selectedValue);
                                       // }
                                    }
                                }


                                if(teesData.length > 0){

                                    return JSON.stringify(teesData);
                                }else{
                                    return null;
                                }

                            },
                            holesDataJson: function(){

                                return JSON.stringify(this.holes);


                            },
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
                             generateHoles:function(holesDataToPrefill){



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
                                           // }
                                         }
                                         this.holes.push(holeData);
                                     }
                                 }


                                 for(holeIndex in holesDataToPrefill){

                                         this.holes[holeIndex].mens_handicap =  holesDataToPrefill[holeIndex].mens_handicap;
                                         this.holes[holeIndex].mens_par =  holesDataToPrefill[holeIndex].mens_par;
                                         this.holes[holeIndex].womens_handicap =  holesDataToPrefill[holeIndex].womens_handicap;
                                         this.holes[holeIndex].womens_par = holesDataToPrefill[holeIndex].womens_par;

                                         for(teeIndex =0;  teeIndex<this.holes[holeIndex].tee_values.length; teeIndex++){
                                       
                                             this.holes[holeIndex].tee_values[teeIndex].color = holesDataToPrefill[holeIndex].tee_values[teeIndex].color;
                                             this.holes[holeIndex].tee_values[teeIndex].cssClass = this.getClassAgainstColorSelected(holesDataToPrefill[holeIndex].tee_values[teeIndex].color);
                                             this.holes[holeIndex].tee_values[teeIndex].distance = holesDataToPrefill[holeIndex].tee_values[teeIndex].distance;

                                         }
                                 }


                             },
                            generateColorSelectionFieldsForTees:function(teeColorsToPrefill){

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

                                 for(teeIndex in teeColorsToPrefill){

                                     this.colorSelectionFieldsForTees[teeIndex].selectedValue =  teeColorsToPrefill[teeIndex];
                                     this.colorSelectionFieldsForTees[teeIndex].cssClass = this.getClassAgainstColorSelected( teeColorsToPrefill[teeIndex]);

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
                             getClassAgainstColorSelected:function(color){
                                 for(colorIndex in this.colors){
                                     if(this.colors[colorIndex].name == color){
                                         return this.colors[colorIndex].class;
                                     }
                                 }
                             },
                         }

            });

        </script>
    @endSection