<template>
    <div class="container">
        <h1>Mpesa Transactions <br/></h1>
        <table class="table table-bordered data-table" width="100%">
            <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Transaction Type</th>
                <th>Transaction ID</th>
                <th>Amount</th>
                <th>Phone number</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: "transactions",
    props:['url'],
    mounted() {
        $(document).ready(function () {
            $.noConflict();
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: this.url,
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'Name', searchable: true},
                    {data: 'TransactionType', name: 'Transaction Type', searchable: true},
                    {data: 'TransID', name: 'Transaction ID',searchable: true},
                    {data: 'TransAmount', name: 'Amount'},
                    {data: 'MSISDN', name: 'Phone number',searchable: true},
                    {data: 'date', name: 'Date'}
                ]
            });
        });
    },
}
</script>
