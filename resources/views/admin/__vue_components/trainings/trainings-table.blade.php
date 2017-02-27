<script>

    Vue.component('trainings', {

        template: `
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Lesson Name</th>
                        <th>Instructor</th>
                        <th>Total Seats</th>
                        <th>Seats Reserved</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="training in trainingListData">
                        <td>
                            @{{ training.name }}
                        </td>
                        <td>
                            @{{ training.instructor }}
                        </td>
                        <td>
                            @{{ training.seats }}
                        </td>
                        <td>
                            @{{ training.seatsReserved }}
                        </td>
                        <td>
                            <a href="{{route("admin.trainings.edit")}}" class="blue-cb">edit</a>
                            &nbsp;&nbsp;
                            <a href="#." class="del-icon"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                </tbody>
                </table>
                `,
        props: [
            "trainingsList"
        ],
        data: function(){
            return {
                trainingListData:this.trainingsList
            }
        },

    });

</script>