<script>
    Vue.component('warnings', {
       template: `
            <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                Warning Details
                            </th>
                            <th>
                                Issued Date
                            </th>
                            <th>
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="warning in warningsList">
                            <td>
                                <div class="section-1 sec-style">
                                    <h3>@{{warning.name}}</h3>

                                </div>
                            </td>
                            <td>
                                <div class="section-2 sec-style">
                                    <p>@{{warning.date}}</p>
                                </div>
                            </td>
                            <td>
                                <div class="section-3 sec-style">
                                    <p>
                                        <span><a href="#." class="del-icon"><i class="fa fa-trash"></i></a></span>
                                    </p>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
        `,
        props: [
            "warnings"
        ],
        data: function() {

            return {
                warningsList:this.warnings
            }

        },
    });
</script>