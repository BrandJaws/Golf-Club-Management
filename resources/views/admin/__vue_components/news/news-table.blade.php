<script>
	Vue.component('news-table-cotainer', {
		template: `
			<table class="table table-hover">
				<thead>
	          		<tr>
	          			<th>
	          				title
	          			</th>
	          			
	          			<th>
	          				description
	          			</th>
	          			<th>
	          				Actions
	          			</th>
	          		</tr>
	          	</thead>
				<slot></slot>
			</table>
		`,
	});
	Vue.component('news-table', {
		template: `
			<tbody>
				<tr v-for="(news,newsIndex) in newsListData">
					<td>@{{ news.title }}</td>
					<td>@{{ news.description }}</td>
					<td>
						<a :href="generateEditMemberRoute('{{Request::url()}}',news.id)" class="blue-cb" >edit</a>
						&nbsp;&nbsp;
						<a href="#." class="del-icon" @click="deleteNews('{{Request::url()}}',news.id,newsIndex)"><i class="fa fa-trash"></i></a>
					</td>
				</tr>
			</tbody>
		`,
		props: [
			"newsList"
		],
		computed: {
			newsListData: function () {
				return this.newsList;
			}
		},
		methods: {
			generateEditMemberRoute: function(baseRouteToCurrentPage,id){
				return baseRouteToCurrentPage+'/edit/'+id;
			},
			deleteNews:function(baseRouteToCurrentPage,id,index){
				_url = baseRouteToCurrentPage+'/'+id
				var request = $.ajax({
					url: _url,
					method: "POST",
					headers: {
						'X-CSRF-TOKEN': '{{csrf_token()}}',
					},
					data:{
						_method:"DELETE",
						_token: "{{ csrf_token() }}",
					},
					success:function(msg){
						if(msg=="success"){
							this.newsListData.splice(index,1);
						}else{

						}
					}.bind(this),
					error: function(jqXHR, textStatus ) {
						this.ajaxRequestInProcess = false;
							$("body").append(jqXHR.responseText);
							//Error code to follow
					}.bind(this)

				}); 
			}
		}
	});
</script>