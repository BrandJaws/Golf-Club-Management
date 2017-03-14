@extends('admin.__layouts.admin-layout')
@section('heading')
	Create Social Posts
	@endSection
@section('main')
	<div ui-view class="app-body" id="view">
		<!-- ############ PAGE START-->
		<div class="padding">
			<div class="notificationsCreateSec">
				<div class="row">
					<div class="col-md-2">
						<h3 class="createSocialPost">Social</h3>
					</div>
					<div class="col-md-10 text-right">
						<ul class="socialMediaConnected">
                            <li class="facebook">
                                <a href="#."><i class="fa fa-facebook fa-lg"></i> &nbsp;@jacob</a>
                            </li>
                            <li class="twitter">
                                <a href="#."><i class="fa fa-twitter fa-lg"></i> &nbsp;@jacob</a>
                            </li>
                            <li class="instagram">
                                <a href="#."><i class="fa fa-instagram fa-lg"></i> &nbsp;@jacob</a>
                            </li>
						</ul>
					</div>
				</div>
				<div class="row" id="vueContainer">
					<div class="col-md-8 col-xs-12">
						<form>
							<div class="form-group">
								<label class="form-control-label">Facebook Message</label>
								<textarea name="" v-on:keyup="substrFbMsg" v-model="fbMessage" id="" cols="30" rows="6" class="form-control"></textarea>
                                <p style="text-align: right;">@{{ fbMessage.length }} / 63,206</p>
							</div>
							<div class="form-group">
								<label class="form-control-label">Twitter Message</label>
								<textarea v-on:keyup="substrTwitterMsg" v-model="twitterMessage" name="" id="" cols="30" rows="3" class="form-control"></textarea>
                                <p style="text-align: right;">@{{ twitterMessage.length }} / 140</p>
							</div>
							<div class="form-group">
								<label class="form-control-label">Instagram Message</label>
								<textarea name="" v-on:keyup="substrInstaMsg" id="" v-model="instaMessage" cols="30" rows="3" class="form-control"></textarea>
                                <p style="text-align: right;">@{{ instaMessage.length }} / 2200</p>
							</div>
							<div class="form-group">
								<label class="form-control-label">Image</label> <input
									type="file" class="form-control" />
							</div>
							<div class="row row-sm">
								<div class="col-md-4">
									<div class="form-group">
										<label class="ui-check ui-check-md"> <input type="checkbox"
											class="has-value" checked /> <i class="dark-white"></i>
											Facebook
										</label>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="ui-check ui-check-md"> <input type="checkbox"
											class="has-value" checked /> <i class="dark-white"></i>
											Twitter
										</label>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="ui-check ui-check-md"> <input type="checkbox"
											class="has-value" checked /> <i class="dark-white"></i>
											Instagram
										</label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<button class="btn-def btn">
									<i class="fa fa-paper-plane-o"></i> &nbsp;Post
								</button>
								<a href="{{route('admin.social.social')}}" class="btn btn-outline b-primary text-primary">
									<i class="fa fa-ban"></i> &nbsp;Cancel
								</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('page-specific-scripts')
    <script>
        var vm = new Vue({
            el: "#vueContainer",
            data: {
                twitterMessage: '',
                fbMessage: '',
                instaMessage: '',
            },
			methods: {
				substrTwitterMsg:function() {
                    this.twitterMessage = this.twitterMessage.substr(0, 140);
//                    console.log(this.twitterMessage.length);
				},
                substrInstaMsg:function() {
                    this.instaMessage = this.instaMessage.substr(0, 2200);
//                    console.log(this.instaMessage.length);
                },
                substrFbMsg:function() {
                    this.fbMessage = this.fbMessage.substr(0, 63206);
//                    console.log(this.fbMessage.length);
                }
			}

        })
    </script>
<script>
        $( function() {
            $( "#datePicker" ).datepicker();
        } );
    </script>
@endSection
