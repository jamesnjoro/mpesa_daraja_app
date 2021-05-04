<template>
    <div class="container">
        <h1>Users <br/></h1>
        <table class="table table-bordered data-table">
            <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th width="100px">Action</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</template>
<script>
export default {
    name: "users",
    props:['url'],
    created(){
        window.approve =this.approve;
    },
    mounted() {
        $(document).ready(function () {
            $.noConflict();
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: this.url,
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });
    },
    methods:{
        approve:function(id){
            let data = {
                userID: id,
                '_token':document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
            fetch('/approveUser', {
                method: 'POST',
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then((response) =>{
                    location.reload();
                })

        }
    }
}
</script>

