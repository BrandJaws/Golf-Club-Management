@extends('admin.__layouts.admin-layout')
@section('heading')
    Edit Member
    @endSection
@section('main')
        <div class="app-body" id="view">
            <!-- ############ PAGE START-->
            <div class="profile-main padding" id="selectionDepHidden">
                <div class="row details-section">
                    <form name="" action="{{route('admin.member.update',$member['id'])}}" method="post" enctype="multipart/form-data">
                    	@if(Session::has('error'))
                    <div class="alert alert-warning" role="alert"> {{Session::get('error')}} </div>
                    @endif
                    @if(Session::has('success'))
                    <div class="alert alert-success" role="alert"> {{Session::get('success')}} </div>
                    @endif
                    	<input type="hidden" name="_method" value="PUT" />
                    	{{ csrf_field() }}
                        <div class="col-md-8">
                            <div class="form-group {{($errors->has('firstName'))?'has-error':''}}">
                                <label class="form-control-label">First Name</label> 
                                <input type="text" name="firstName"  class="form-control" value="{{$member['firstName']}} " />
                                @if($errors->has('firstName')) <span class="help-block errorProfilePic">{{$errors->first('firstName') }}</span> @endif
                            </div>
                            <div class="form-group {{($errors->has('lastName'))?'has-error':''}}">
                                <label class="form-control-label">Last Name</label> 
                                <input type="text" name="lastName" class="form-control" value="{{$member['lastName']}} " />
                                @if($errors->has('lastName')) <span class="help-block errorProfilePic">{{$errors->first('lastName') }}</span> @endif
                            </div>
                            <div class="form-group {{($errors->has('email'))?'has-error':''}}">
                                <label class="form-control-label">Email</label> 
                                <input type="email" class="form-control" name="email" value="{{$member['email']}}" />
                                @if($errors->has('email')) <span class="help-block errorProfilePic">{{$errors->first('email') }}</span> @endif
                            </div>
                            <div class="form-group {{($errors->has('phone'))?'has-error':''}}">
							<label class="form-control-label">Contact Number</label> 
							<input type="tel" class="form-control" name="phone" value="{{$member['phone']}}"/>
							 @if($errors->has('phone')) <span class="help-block errorProfilePic">{{$errors->first('phone') }}</span> @endif
							</div>
                            <div class="form-group {{($errors->has('password'))?'has-error':''}}">
    							<label class="form-control-label">Password</label> 
    							<input type="password" class="form-control" name="password" />
    							 @if($errors->has('password')) <span class="help-block errorProfilePic">{{$errors->first('password') }}</span> @endif
							</div>
                            <div class="row row-sm">
                                <div class="col-md-3">
                                    <div class="form-group form-group-inline">
                                        <label class="form-control-label">Gender</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row row-sm">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="radio">
                                                    <label class="ui-check"> 
                                                    	<input type="radio" name="gender" value="{{Config::get('global.gender.male')}}" class="has-value" {{($member['gender'] == Config::get('global.gender.male'))?'checked':''}}> <i class="dark-white"></i> Male
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="radio">
                                                <label class="ui-check">
													<input type="radio" name="gender"  value="{{Config::get('global.gender.female')}}" class="has-value" {{($member['gender'] == Config::get('global.gender.female'))?'checked':''}}> <i class="dark-white"></i>
                                                    Female
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row row-sm">
                                <div class="col-md-3">
                                    <div class="form-group form-group-inline">
                                        <label class="form-control-label">Member Type</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row row-sm">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="radio">
                                                    <label class="ui-check"> <input v-model="memberType"  type="radio" name="relation" value="parent"  class="has-value" /> <i class="dark-white"></i> Parent
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="radio">
                                                    <label class="ui-check"> 
                                                    <input v-model="memberType"  type="radio" name="relation" value="affiliate"  class="has-value"  /> <i
                                                                class="dark-white"></i> Affiliate Member
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row row-sm animated fadeInUp" v-cloak
                                 v-if="showParentSelector">
                                <div class="col-md-3"></div>
                                <div class="col-md-9">
                                    <div class="row row-sm">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-control-label">Select Parent Member</label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group {{($errors->has('parentMember'))?'has-error':''}}" id="membersPageAutoCom">
                                                <auto-complete-box url="{{url('admin/member/search-list')}}" property-for-id="member_id" property-for-name="member_name"
                                                                   filtered-from-source="true" include-id-in-list="true"
                                                                   v-model="selectedId" :initial-text-value="initialTextForParentInput" search-query-key="search" field-name="parentMember"> </auto-complete-box>
                                            	@if($errors->has('parentMember')) <span class="help-block errorProfilePic">{{$errors->first('parentMember') }}</span> @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <div class="form-group">
                                <button type="submit"  class="btn btn-def"><i class="fa fa-floppy-o"></i>&nbsp;Update Member</button> &nbsp;&nbsp; <a
                                        href="{{route('admin.member.index')}}"
                                        class="btn btn-outline b-primary text-primary"><i
                                            class="fa fa-ban"></i> &nbsp;Cancel</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
							<img src="{{(isset($member['profilePic']) && $member['profilePic'])? asset($member['profilePic']): asset('assets/images/user.png')}}" class="img-responsive img-circle defaultImg" />
							<div class="form-group {{($errors->has('profilePic'))?'has-error':''}}">
								<label class="form-control-label">Add Image</label> 
								<input type="file" name="profilePic" class="form-control" />
								@if($errors->has('profilePic')) <span class="help-block errorProfilePic">{{$errors->first('profilePic') }}</span> @endif
							</div>
						</div>
                        </div>
                    </form>
                </div>
                <div class="padding-small"></div>
                <div class="row bg-white">
                    <div class="col-md-12">
                        <div class="main-page-heading">
                            <h3>
                                <span>Warnings</span>
                            </h3>
                        </div>
                    </div>
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

        var main_member = {!!  $member['main_member'] != null ? json_encode($member['main_member']): "''" !!} ;
        var parentSelectionError =  "{{$errors->has('parentMember')  ? $errors->first('parentMember'): '' }}" ;

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

                memberType:'{{($member['main_member_id'] != 0 || $errors->has('parentMember')  )?'affiliate':'parent'}}',
                selectedId: main_member != '' ? main_member.id :false,
                warnings:[],
                latestPageLoaded:0,
                ajaxRequestInProcess:false,
                initialTextForParentInput: main_member != '' ? main_member.firstName+' '+main_member.lastName :'',
            },
            computed:{
                showParentSelector:function(){
                    if (this.memberType == 'affiliate') {
                        return true;
                    }
                    else {
                        return false;
                    }
                }
            },
            methods: {

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

        console.log(parent);
    </script>
    <script>


        $( function() {
            $( "#datePicker" ).datepicker();
        } );
    </script>
    @endSection
