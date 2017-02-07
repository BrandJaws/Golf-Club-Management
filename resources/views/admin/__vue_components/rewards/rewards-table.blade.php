<script>
    Vue.component('offers', {
       template: `
            <table class="table table-hover b-t">
                <tbody>
                    <tr v-for="reward in rewardsListData">
                        <td>
                            <div class="section-1 sec-style">
                                <h3>@{{reward.name}}</h3>
                                <p>@{{reward.description}}</p>
                            </div>
                        </td>
                         <td>
                            <div class="section-1 sec-style">
                                <h3>@{{reward.segmentName}}</h3>
                                <p>@{{reward.segmentDescription}}</p>
                            </div>
                        </td>
                        <td>
                            <div class="section-2 sec-style">
                                <h3>@{{reward.coverage}}</h3>
                                <p>Potential Reach</p>
                            </div>
                        </td>
                        <td>
                            <div class="section-2 sec-style">
                                <h3>@{{reward.redeemed}}</h3>
                                <p>Redeemed</p>
                            </div>
                        </td>
                        <td>
                            <div class="section-2 sec-style">
                                <h3>@{{reward.claimed}}</h3>
                                <p>Claimed</p>
                            </div>
                        </td>
                        <td>
                            <div class="section-2 sec-style">
                                <h3>@{{reward.expired}}</h3>
                                <p>Expired</p>
                            </div>
                        </td>
                        <td>
                            <div class="section-3 sec-style">
                                <p>
                                    <span><a href="#." class="blue-cb">edit</a></span>&nbsp;&nbsp;&nbsp;
                                    <span><a href="#." class="del-icon"><i class="fa fa-trash"></i></a></span>
                                </p>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        `,
        props: [
            "rewardsList"
        ],
        data: function() {
          return {
              rewardsListData:this.rewardsList
          }
        },
        methods: {

        }
    });
</script>