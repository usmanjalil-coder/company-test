@extends('layouts.app')

@section('content')
<div class="content">
    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title">Covering Letters</h5>
            <a href="{{ route('authority-letters.create') }}" class="btn btn-primary mb-3">Add New Covering Letters</a>
            <table id="authorityLettersTable" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client Name</th>
                        <th>Content</th>
                        <th>Date</th>
                        <th>File</th>
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
        $('#authorityLettersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('authority-letters.index') }}",
            columns: [
                { data: 'id' },
                { data: 'client_name' },
                { data: 'content', render: function(data) { return data.substring(0, 50) + (data.length > 50 ? '...' : ''); } },
                { data: 'date' },
                {
                    data: 'file_path',
                    render: function(data) {
                        return data ? '<a href="' + "{{ Storage::url('') }}" + data + '" target="_blank">View File</a>' : 'No File';
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return '<a href="' + '{{ route('authority-letters.edit', ':id') }}'.replace(':id', row.id) + '" class="btn btn-sm btn-primary">Edit</a> ' +
                               '<a href="#" class="btn btn-sm btn-danger delete-btn" data-id="' + row.id + '">Delete</a>';
                    }
                }
            ]
        });

        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this authority letter?')) {
                var id = $(this).data('id');
                $.ajax({
                    url: '{{ route('authority-letters.destroy', ':id') }}'.replace(':id', id),
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        $('#authorityLettersTable').DataTable().ajax.reload();
                        alert(response.success);
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseJSON.message);
                    }
                });
            }
        });
    });
</script>
@endsection