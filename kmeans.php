<h1 class="h3 mb-4 text-gray-800">Data Uji K-Means</h1>

<div class="row">
    <div class="col">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h3>Accuracy</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <tbody>
                            <tr>
                                <th>Accuracy </th>
                                <td id="accuracy">: </td>
                            </tr>
                            <tr>
                                <th>Pembulatan Akurasi </th>
                                <td id="roundAcc">: </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h3>Confusion Matrix</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <tbody id="conf">
                            <tr>
                                <th>Confusion Matrix: </th>
                                <td> </td>
                                <td> </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div>
                    <h3>Dataset Penjualan Tiap Bulan</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="myTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Label</th>
                                <th>Maret</th>
                                <th>April</th>
                                <th>Mei</th>
                                <th>Cluster</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    var table = null;
    var arrData;

    $(document).ready(function(){
        $.ajax({
            url: "algoProses.php",
            type: 'POST',
            dataType: "JSON",
            data: { 
                sent: "kmeans"
            }
        }).then((res) => {
            // debugger;
            console.log(res);

            $('#accuracy').text(': ' + res.Accuracy + '%')
            $('#roundAcc').text(': ' + Math.round(res.Accuracy) + '%')

            var tbl = $('#conf');
            $.each(res.Confusion, function(i, item) {
                // console.log(item);
                tbl.append($('<tr/>'));
                $.each(item, function(j, itemj) {
                    tbl.append('<td>'+ itemj +'</td>');
                });
                // $('#conf td').append('<td>'+ item +'</td>');
            });

            arrData = JSON.parse(res.Dataframe);
            // arrData = res.Dataframe;
            // arrData = $.parseJSON(res.Dataframe);
            console.log(arrData);
            
            table = $('#myTable').DataTable({
                "processing": true,
                "responsive": true,
                "pagination": true,
                "stateSave": true,
                data: arrData,
                "columns": [
                    {
                        'render': function (data, type, row, meta) {
                            // console.log(meta.row);
                            return meta.row + 1;
                        }
                    },
                    { 'data': 'name' },
                    { 'data': 'label' },
                    { 'data': 'mar_x' },
                    { 'data': 'apr_x' },
                    { 'data': 'mei_x' },
                    { 'data': 'cluster' },
                ],
            });
        });
    });
</script>