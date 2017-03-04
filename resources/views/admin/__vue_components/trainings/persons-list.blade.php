<script>

    Vue.component('person-list', {

        template: `
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Person Name</th>
                        <th>Person Email</th>
                        <th>Person ID</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="person in personsListData">
                        <td>
                            @{{ person.name }}
                        </td>
                        <td>
                            @{{ person.email }}
                        </td>
                        <td>
                            @{{ person.id }}
                        </td>
                        <td>
                            <a href="#." class="del-icon"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                </tbody>
                </table>
                `,
        props: [
            "personsList"
        ],
        data: function(){
            return {
                personsListData:this.personsList
            }
        },

    });

</script>