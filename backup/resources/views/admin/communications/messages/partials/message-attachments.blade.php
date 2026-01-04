{{-- Message Attachments Partial --}}
@if($message->attachments && count($message->attachments) > 0)
    <div class="message-attachments mt-3">
        <h6 class="mb-2">
            <i class="fas fa-paperclip me-2"></i>
            Attachments ({{ count($message->attachments) }})
        </h6>
        
        <div class="list-group">
            @foreach($message->attachments as $index => $attachment)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        {{-- File Type Icon --}}
                        @php
                            $extension = pathinfo($attachment['name'], PATHINFO_EXTENSION);
                            $iconClass = 'fas fa-file';
                            
                            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'])) {
                                $iconClass = 'fas fa-file-image';
                            } elseif (in_array($extension, ['pdf'])) {
                                $iconClass = 'fas fa-file-pdf';
                            } elseif (in_array($extension, ['doc', 'docx'])) {
                                $iconClass = 'fas fa-file-word';
                            } elseif (in_array($extension, ['xls', 'xlsx'])) {
                                $iconClass = 'fas fa-file-excel';
                            } elseif (in_array($extension, ['ppt', 'pptx'])) {
                                $iconClass = 'fas fa-file-powerpoint';
                            } elseif (in_array($extension, ['txt'])) {
                                $iconClass = 'fas fa-file-alt';
                            } elseif (in_array($extension, ['zip', 'rar', '7z'])) {
                                $iconClass = 'fas fa-file-archive';
                            }
                        @endphp
                        
                        <i class="{{ $iconClass }} me-2 text-muted"></i>
                        
                        <div>
                            <div class="fw-bold">{{ $attachment['name'] }}</div>
                            <small class="text-muted">
                                {{ number_format($attachment['size'] / 1024, 2) }} KB
                                @if(isset($attachment['mime_type']))
                                    • {{ $attachment['mime_type'] }}
                                @endif
                            </small>
                        </div>
                    </div>
                    
                    <div class="btn-group" role="group">
                        {{-- Download Button --}}
                        <a href="{{ route('admin.messages.download-attachment', [$message->id, $index]) }}" 
                           class="btn btn-sm btn-outline-primary" 
                           title="Download">
                            <i class="fas fa-download"></i>
                        </a>
                        
                        {{-- Preview Button (for images and PDFs) --}}
                        @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'pdf']))
                            <button type="button" 
                                    class="btn btn-sm btn-outline-info" 
                                    onclick="previewAttachment('{{ $attachment['name'] }}', '{{ route('admin.messages.download-attachment', [$message->id, $index]) }}')"
                                    title="Preview">
                                <i class="fas fa-eye"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

{{-- Attachment Preview Modal --}}
<div class="modal fade" id="attachmentPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="attachmentPreviewTitle">Attachment Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div id="attachmentPreviewContent">
                    {{-- Content will be loaded here --}}
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="attachmentDownloadLink" class="btn btn-primary" target="_blank">
                    <i class="fas fa-download"></i> Download
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function previewAttachment(fileName, downloadUrl) {
    const modal = document.getElementById('attachmentPreviewModal');
    const title = document.getElementById('attachmentPreviewTitle');
    const content = document.getElementById('attachmentPreviewContent');
    const downloadLink = document.getElementById('attachmentDownloadLink');
    
    title.textContent = `Preview: ${fileName}`;
    downloadLink.href = downloadUrl;
    
    const extension = fileName.split('.').pop().toLowerCase();
    
    if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'].includes(extension)) {
        // Image preview
        content.innerHTML = `<img src="${downloadUrl}" class="img-fluid" alt="${fileName}">`;
    } else if (extension === 'pdf') {
        // PDF preview
        content.innerHTML = `<iframe src="${downloadUrl}" width="100%" height="500px" frameborder="0"></iframe>`;
    } else {
        // Text preview (for text files)
        content.innerHTML = `<div class="text-muted">Preview not available for this file type. Please download to view.</div>`;
    }
    
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}
</script>



