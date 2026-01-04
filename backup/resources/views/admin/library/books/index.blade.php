@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <form id="bulk-delete-form" method="POST" action="{{ route('admin.library.books.bulk-delete') }}" onsubmit="return confirm('Delete selected books?')">
            @csrf
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-book-open me-2 text-primary"></i>Books</h4>
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.library.books.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Add Book
                    </a>
                    <a href="{{ route('admin.library.books.export') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-file-export me-1"></i> Export CSV
                    </a>
                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="fas fa-file-import me-1"></i> Import CSV
                    </button>
                    <button type="submit" form="bulk-form" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete selected books?')">
                        <i class="fas fa-trash-alt me-1"></i> Bulk Delete
                    </button>
                </div>
            </div>


            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table id="books-table" class="table table-striped table-bordered table-hover align-middle w-100">
                    <thead class="table-dark">
                            <tr>
                                <th><input type="checkbox" id="select-all"></th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Genre</th>
                                <th>Year</th>
                                <th>ISBN</th>
                                <th>Status</th>
                                <th>Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Books</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.library.books.import') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Upload File (.xlsx, .csv)</label>
                            <input type="file" name="file" accept=".xlsx,.csv" required class="form-control">
                        </div>
                        <small class="text-muted">Expected columns: title, author, genre, published_year, isbn, description, stock_quantity, shelf_location, status</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteBookModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header"><h5 class="modal-title">Confirm Delete</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body">Are you sure you want to delete this book?</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <form id="bookDeleteForm" method="POST" action="">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger">Delete</button>
            </form>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    let table;
    function loadTable(status = '') {
        if (table) table.destroy();

        table = $('#books-table').DataTable({
        
        processing: true,
        serverSide: true,
        order: [[1, 'asc']],
        ajax: {
            url: "{{ route('admin.library.books.index') }}",
            type: 'GET',
            data: function (d) {
                d._token = "{{ csrf_token() }}";
            }
        },
        dom:
            '<"row mb-3 align-items-center"' +
                '<"col-md-6"l>' +             // Left side: Show
                '<"col-md-6 text-end"f>' +    // Right side: Search
            '>' +
            '<"row mb-3"' +
                '<"col-12 text-end"B>' +      // Next line: Buttons full width right aligned
            '>' +
            '<"row"<"col-sm-12"tr>>' +
            '<"row mt-3"<"col-sm-5"i><"col-sm-7"p>>',
        
              buttons: [
              {
                extend: 'csv',
                className: 'btn btn-success btn-sm rounded-pill',
                text: '<i class="fas fa-file-csv me-1"></i> CSV'
              },
              {
                extend: 'pdf',
                className: 'btn btn-danger btn-sm rounded-pill',
                text: '<i class="fas fa-file-pdf me-1"></i> PDF'
              },
              {
                extend: 'print',
                className: 'btn btn-warning btn-sm rounded-pill',
                text: '<i class="fas fa-print me-1"></i> Print'
                
              },
              {
                extend: 'copy',
                className: 'btn btn-info btn-sm rounded-pill',
                text: '<i class="fas fa-copy me-1"></i> Copy'
              }
            ],
        columns: [
            { data: 'select', name: 'select', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'author', name: 'author' },
            { data: 'genre', name: 'genre' },
            { data: 'published_year', name: 'published_year' },
            { data: 'isbn', name: 'isbn' },
            { data: 'status', name: 'status' },
            { data: 'stock_quantity', name: 'stock_quantity' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
}


    // Single row delete -> open modal and set action
   
    $(document).ready(function(){
        loadTable(); 

        $(document).on('click', '.delete-book-btn', function() {
            const id = $(this).data('id');
            const actionUrl = `{{ url('admin/library/books') }}/${id}`;
            $('#bookDeleteForm').attr('action', actionUrl);
            const modal = new bootstrap.Modal(document.getElementById('deleteBookModal'));
            modal.show();
        });
         // Select all toggler
            document.getElementById('select-all').addEventListener('change', function(e) {
                const checked = e.target.checked;
                $('#books-table tbody input.row-select').prop('checked', checked);
            });

            // Ensure selected IDs are posted on bulk delete
            document.getElementById('bulk-delete-form').addEventListener('submit', function(e) {
                // Remove existing hidden ids
                this.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
                // Append current page selections
                $('#books-table tbody input.row-select:checked').each((_, el) => {
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'ids[]';
                    hidden.value = el.value;
                    document.getElementById('bulk-delete-form').appendChild(hidden);
                });
            });
    });
</script>

@endsection


