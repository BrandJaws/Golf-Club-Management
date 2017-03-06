<script>
    Vue.component('courses', {
        template: `
            <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                Course Name
                            </th>
                            <th>
                                Open Time
                            </th>
                            <th>
                                Close Time
                            </th>
                            <th>
                                Booking Interval
                            </th>
                            <th>
                                Booking Duration
                            </th>
                            <th>
                                Number of Holes
                            </th>
                            <th>
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="course in courseListData">
                            <td>
                                @{{ course.name }}
                            </td>
                            <td>
                                @{{ course.openTime }}
                            </td>
                            <td>
                                @{{ course.closeTime }}
                            </td>
                            <td>
                                @{{ course.bookingInterval }}
                            </td>
                            <td>
                                @{{ course.bookingDuration }}
                            </td>
                            <td>
                                @{{ course.hole }}
                            </td>
                            <td>
                                <a href="{{Request::url()}}/edit" class="blue-cb" >edit</a>
						        &nbsp;&nbsp;
						        <a href="#." class="del-icon"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>

                    </tbody>
                </table>
        `,
        props: [
            "courses"
        ],
        data: function() {

            return {
                courseListData:this.courses
            }

        },
    });
</script>