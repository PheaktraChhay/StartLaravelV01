<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ajax</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .action-btn {
            width: 160px;
        }

        input,
        input:hover,
        input:focus,
        input:active,
        button,
        button:hover,
        button:focus,
        button:active,
        select,
        select:hover,
        select:focus,
        select:active,
        a,
        a:hover,
        a:focus,
        a:active {
            box-shadow: none !important;
        }

        div.dataTables_wrapper div.dataTables_filter input {
            max-width: 300px !important;
        }

    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Ajax Form CRUD</div>
                    <div class="card-body">
                        <form id="postFormCreate" method="post" enctype="multipart/form-data" class="form-group">
                            <input type="text" name="name" id="name" placeholder="Enter name" class="form-control fname"
                                autocomplete="off">
                            <button type="button" class="btn btn-primary form-control mt-2 addNew">Add</button>
                            <button type="button" class="btn btn-primary form-control mt-2 updatebtn">Update</button>
                        </form>
                        <br>
                        <table id="dt-basic" class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyTable">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
</body>
<script>
    {
        $('.updatebtn').hide();
        var CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
        var idCondition = null;
        var obj = null;

        $(document).ready(function() {


            getdata();

        });

        function getdata() {
            $.ajax({
                url: "/getdata",
                type: 'GET',
                datatype: 'JSON',
                success: function(response) {
                    // console.log(response);
                    initPostList(response.posts);
                    obj = response.posts; ////////// get new data

                }
            });
        }

        function initPostList(data) {
            var cols = [{
                    "data": "name",
                    "name": "name",
                    "searchable": true,
                    "orderable": true,
                    "visible": true,
                }, //1
                {
                    "data": null,
                    "name": "Action",
                    "searchable": false,
                    "orderable": true,
                    "visible": true,
                    "class": "dt-center action-btn",
                    render: function(data, type, row) {
                        return  "<button type='button' onclick='fnShow(" + data.id + ")' class='btn btn-info btn-sm'>show</button>" +
                                "<button type='button' onclick='fnDelete(" + data.id + ")' class='btn btn-danger btn-sm'>Delete</button>" +
                                "<button type='button' onclick='fnUpdate(" + data.id + ")' class='btn btn-primary btn-sm'>Update</button>";
                    }
                }, //3
            ];
            var btn = [{
                extend: 'print',
                text: 'Print',
                titleAttr: 'Print Table',
                className: 'btn-outline-primary btn-sm'
            }];
            if ($.fn.DataTable.isDataTable('#dt-basic')) {
                $('#dt-basic').DataTable().clear();
                $('#dt-basic').DataTable().destroy();
            }
            //////INT TABLE//////
            var table = $('#dt-basic').DataTable({
                "data": data,
                "columns": cols,
                "buttons": btn,
                "order": [1, 'asc'],
                "rowId": "id",
                "responsive": "true",
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            });
            //////INT TABLE//////
        }
        $(".addNew").click(function() {
            let name = $(".fname").val();
            let formData = new FormData();
            formData.append('name', name);
            formData.append('_token', CSRF_TOKEN);

            $.ajax({
                url: "/store",
                type: 'POST',
                data: formData,
                datatype: 'JSON',
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#postFormCreate').find('input').val('');
                    initPostList(response.posts);
                    $('.updatebtn').fadeOut(300);
                    obj = response.posts; ////////// get new data
                }
            });
        });

        function fnUpdate(id) {
            let data_obj = obj.filter(v => v.id == id); //////////// get data from database using ajx jquery
            $('.fname').val(data_obj[0].name); //////////// click update button to show data in input tage
            $('.updatebtn').fadeIn(300);
            console.log(data_obj[0].name);

            $(".updatebtn").click(function() {
                let name = $(".fname").val();
                let formData = new FormData();
                formData.append('name', name);
                formData.append('_token', CSRF_TOKEN);

                $.ajax({
                    url: "/update/" + id,
                    type: 'POST',
                    data: formData,
                    datatype: 'JSON',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#postFormCreate').find('input').val('');
                        $('.updatebtn').fadeOut(300);
                        initPostList(response.posts);
                        obj = response.posts; ////////// get new data
                        /////////// sweet alert
                        Toast.fire({
                            icon: 'success',
                            title: 'Successful'
                        })
                    }
                });
            });
        }

        function fnDelete(id) {
            Swal.fire({
                title: 'Do you want to delete?',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'Yes',
                denyButtonText: `No`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/destroy/" + id,
                        type: "GET",
                        data: { "id": id },
                        success: function(response) {
                            $('#postFormCreate').find('input').val('');
                            $('.updatebtn').fadeOut(300);
                            initPostList(response.posts);
                            Toast.fire({
                                icon: 'success',
                                title: 'Successful'
                            })
                            obj = response.posts; ////////// get new data
                        }
                    });
                }
            })
        }

        function fnShow(id) {
            let data_obj = obj.filter(v => v.id == id);
            // console.log(data_obj[0].name);
            Swal.fire({
                title: data_obj[0].name,
            })
            
        }
        /////////// Sweet alert connectivity
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
    }
</script>

</html>
