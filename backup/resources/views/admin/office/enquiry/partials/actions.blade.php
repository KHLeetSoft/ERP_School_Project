<div class="d-flex justify-content-start">
  <a href="{{ route('admin.office.enquiry.show', $data->id) }}" class="text-info me-2" title="View"><i class="bx bx-show"></i></a>
  <a href="{{ route('admin.office.enquiry.edit', $data->id) }}" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>
  <form action="{{ route('admin.office.enquiry.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Delete this enquiry?')" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-link p-0 text-danger" title="Delete"><i class="bx bx-trash"></i></button>
  </form>
</div> 