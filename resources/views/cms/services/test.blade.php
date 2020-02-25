{{--@extends('cms.layouts.app')--}}

{{--@section('content')--}}





{{--    <div class="container">--}}

{{--        <table class="table table-bordered data-table">--}}
{{--            <thead>--}}
{{--            <tr>--}}
{{--                <th>Name</th>--}}
{{--                --}}{{--                <th>Email</th>--}}
{{--                <th> #</th>--}}
{{--                <th width="100px">Action</th>--}}
{{--            </tr>--}}
{{--            </thead>--}}
{{--            <tbody>--}}
{{--            </tbody>--}}
{{--        </table>--}}
{{--    </div>--}}
{{--@endsection--}}




{{--@push('js')--}}
{{--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>--}}
{{--    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>--}}
{{--    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>--}}
{{--    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>--}}

{{--    <script type="text/javascript">--}}
{{--        $(function () {--}}

{{--            var table = $('.data-table').DataTable({--}}
{{--                processing: true,--}}
{{--                serverSide: true,--}}
{{--                ajax: "{{ route('service.index') }}",--}}
{{--                columns: [--}}
{{--                    {data: 'name', name: 'name'},--}}
{{--                    {data: 'users', name: '#users', render: function(data){--}}
{{--                        return data.length;--}}
{{--                        }--}}
{{--                    },--}}
{{--                    {--}}
{{--                        data: 'action', name: 'action', orderable: false, searchable: false--}}
{{--                    },--}}
{{--                ]--}}
{{--            });--}}

{{--        });--}}
{{--    </script>--}}
{{--@endpush--}}
{{--@push('css')--}}
{{--    <meta name="csrf-token" content="{{ @csrf_token()}}">--}}
{{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css"/>--}}
{{--    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">--}}
{{--    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">--}}
{{--@endpush--}}


@extends('cms.layouts.app')

@section('content')


<div class="container">
    <br />
    <h3 align="center">How to Delete or Remove Data From Mysql in Laravel 6 using Ajax</h3>
    <br />
    <div align="right">
        <button type="button" name="create_record" id="create_record" class="btn btn-success btn-sm">Create Record</button>
    </div>
    <br />
    <div class="table-responsive">
        <table id="user_table" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th width="35%">First Name</th>
                <th width="35%">Last Name</th>
                <th width="30%">Action</th>
            </tr>
            </thead>
        </table>
    </div>
    <br />
    <br />
</div>
<div id="formModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Record</h4>
            </div>
            <div class="modal-body">
                <span id="form_result"></span>
                <form method="post" id="sample_form" class="form-horizontal">
                    @csrf
                    <div class="form-group">
                        <label class="control-label col-md-4" >First Name : </label>
                        <div class="col-md-8">
                            <input type="text" name="first_name" id="first_name" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">Last Name : </label>
                        <div class="col-md-8">
                            <input type="text" name="last_name" id="last_name" class="form-control" />
                        </div>
                    </div>
                    <br />
                    <div class="form-group" align="center">
                        <input type="hidden" name="action" id="action" value="Add" />
                        <input type="hidden" name="hidden_id" id="hidden_id" />
                        <input type="submit" name="action_button" id="action_button" class="btn btn-warning" value="Add" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="confirmModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Confirmation</h2>
            </div>
            <div class="modal-body">
                <h4 align="center" style="margin:0;">Are you sure you want to remove this data?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">OK</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

@endsection
@push('js')
<script>
    $(document).ready(function(){

        $('#user_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('service.index') }}",
            },
            columns: [
                {
                    data: 'name',
                    name: 'name'
                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ]
        });

        $('#create_record').click(function(){
            $('.modal-title').text('Add New Record');
            $('#action_button').val('Add');
            $('#action').val('Add');
            $('#form_result').html('');

            $('#formModal').modal('show');
        });

        $('#sample_form').on('submit', function(event){
            event.preventDefault();
            var action_url = '';

            if($('#action').val() == 'Add')
            {
                action_url = "{{ route('service.store') }}";
            }

            if($('#action').val() == 'Edit')
            {
                action_url = "{{ route('service.update') }}";
            }

            $.ajax({
                url: action_url,
                method:"POST",
                data:$(this).serialize(),
                dataType:"json",
                success:function(data)
                {
                    var html = '';
                    if(data.errors)
                    {
                        html = '<div class="alert alert-danger">';
                        for(var count = 0; count < data.errors.length; count++)
                        {
                            html += '<p>' + data.errors[count] + '</p>';
                        }
                        html += '</div>';
                    }
                    if(data.success)
                    {
                        html = '<div class="alert alert-success">' + data.success + '</div>';
                        $('#sample_form')[0].reset();
                        $('#user_table').DataTable().ajax.reload();
                    }
                    $('#form_result').html(html);
                }
            });
        });

        $(document).on('click', '.edit', function(){
            var id = $(this).attr('id');
            $('#form_result').html('');
            $.ajax({
                url :"/service/"+id+"/edit",
                dataType:"json",
                success:function(data)
                {
                    $('#first_name').val(data.result.first_name);
                    $('#last_name').val(data.result.last_name);
                    $('#hidden_id').val(id);
                    $('.modal-title').text('Edit Record');
                    $('#action_button').val('Edit');
                    $('#action').val('Edit');
                    $('#formModal').modal('show');
                }
            })
        });

        var user_id;

        $(document).on('click', '.delete', function(){
            user_id = $(this).attr('id');
            $('#confirmModal').modal('show');
        });

        $('#ok_button').click(function(){
            $.ajax({
                url:"service/destroy/"+user_id,
                beforeSend:function(){
                    $('#ok_button').text('Deleting...');
                },
                success:function(data)
                {
                    setTimeout(function(){
                        $('#confirmModal').modal('hide');
                        $('#user_table').DataTable().ajax.reload();
                        alert('Data Deleted');
                    }, 2000);
                }
            })
        });

    });
</script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    @endpush

@push('css')

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
    @endpush
