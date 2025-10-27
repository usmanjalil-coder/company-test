@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Company Records</h5>
                <table id="companiesTable" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Company Name</th>

                            <!-- NEW: logo columns -->
                            <th>Logo</th>
                            <th>Accreditors</th>

                            <th>Address</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                            <th>Solicitor</th>
                            <th>Regulated By</th>
                            <th>Reg Number</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#companiesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('company.index') }}",
                columns: [
                    { data: 'id' },
                    { data: 'company_name' },

                    // NEW: these columns will receive HTML <img> from server
                    { data: 'company_logo', orderable: false, searchable: false },
                    { data: 'accreditor_logos', orderable: false, searchable: false },

                    { data: 'company_address' },
                    { data: 'contact_number' },
                    { data: 'email_address' },
                    { data: 'solicitor_name' },
                    { data: 'regulated_by' },
                    { data: 'company_reg_number' },
                    {
                        data: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Delete confirmation
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this company?')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: '{{ route('company.delete', ':id') }}'.replace(':id', id),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('#companiesTable').DataTable().ajax.reload();
                            alert(response.success);
                        }
                    });
                }
            });
        });
    </script>
@stop