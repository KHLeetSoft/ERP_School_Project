@extends('admin.layout.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <i class="bx bx-bot me-2"></i> AI Chatbot
        </div>
        <div class="card-body" id="chat-box" style="height:400px; overflow-y:auto;">
            <!-- Messages will load here -->
        </div>
        <div class="card-footer d-flex">
            <input type="text" id="chat-input" class="form-control me-2" placeholder="Type your question...">
            <button id="send-btn" class="btn btn-primary"><i class="bx bx-send"></i></button>
        </div>
    </div>
</div>

<script>
document.getElementById('send-btn').addEventListener('click', function() {
    let input = document.getElementById('chat-input');
    let message = input.value;
    if (!message) return;

    let chatBox = document.getElementById('chat-box');
    chatBox.innerHTML += `<div><b>You:</b> ${message}</div>`;

    fetch("{{ route('admin.ai.chatbot.message') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ message })
    })
    .then(res => res.json())
    .then(data => {
        chatBox.innerHTML += `<div class='text-primary'><b>Bot:</b> ${data.reply}</div>`;
        chatBox.scrollTop = chatBox.scrollHeight;
    });

    input.value = "";
});
</script>
@endsection
