<script>

    Vue.component('notifications', {

        template: `
            <table class="table table-hover">
                <tbody>
                    <tr v-for="notification in notificationsListData">
                        <td>
                            <div class="section-1 sec-style">
                                <h3>@{{ notification.name }}</h3>
                                <p>@{{ notification.details }}</p>
                            </div>
                        </td>
                        <td>
                            <div class="section-2 sec-style">
                                <span :class="notification.status == 'sent' ? 'notificationSent' : 'notificationSchedule'">@{{ notification.status }}</span>
                                {{--<span :class="" v-if="notification.status == 'schedule'">@{{ notification.status }}</span>--}}
                            </div>
                        </td>
                        <td>
                            <div class="section-3 sec-style">
                                @{{ notification.date }}
                            </div>
                        </td>
                        <td>
                            <div class="section-3 sec-style">
                                <a href="{{route('admin.notifications.view')}}" class="blue-cb" v-if="notification.status == 'sent'">view</a>
                                <a href="{{route('admin.notifications.edit')}}" class="blue-cb" v-if="notification.status == 'schedule'">edit</a>
                                &nbsp;&nbsp;
                                <a href="#." class="del-icon"><i class="fa fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        `,
        props: [
            "notificationsList"
        ],
        data: function(){
            return {
                notificationsListData:this.notificationsList
            }
        },

    });

</script>