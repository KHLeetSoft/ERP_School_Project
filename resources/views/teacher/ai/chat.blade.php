@extends('teacher.layout.app')

@section('title', 'AI Chat Assistant')
@section('page-title', 'AI Teaching Assistant')
@section('page-description', 'Get instant help with your teaching tasks and questions')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card modern-card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-robot text-white"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">AI Teaching Assistant</h5>
                        <p class="text-muted mb-0">Ask me anything about teaching, lesson planning, or classroom management</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <!-- Chat Container -->
                <div class="chat-container" style="height: 600px; display: flex; flex-direction: column;">
                    <!-- Chat Messages -->
                    <div class="chat-messages flex-grow-1 p-4" id="chatMessages" style="overflow-y: auto; background: #f8f9fa;">
                        <!-- Welcome Message -->
                        <div class="message bot-message mb-3">
                            <div class="message-content">
                                <div class="message-avatar">
                                    <i class="fas fa-robot"></i>
                                </div>
                                <div class="message-bubble">
                                    <p class="mb-0">Hello! I'm your AI teaching assistant. I can help you with:</p>
                                    <ul class="mb-0 mt-2">
                                        <li>Lesson planning and curriculum development</li>
                                        <li>Creating assessments and quizzes</li>
                                        <li>Classroom management strategies</li>
                                        <li>Student engagement techniques</li>
                                        <li>Educational technology recommendations</li>
                                    </ul>
                                    <p class="mb-0 mt-2">What would you like to know?</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Suggestions -->
                    <div class="quick-suggestions p-3 border-top bg-white">
                        <h6 class="mb-2">Quick Suggestions:</h6>
                        <div class="suggestion-chips" id="suggestionChips">
                            <button class="btn btn-outline-primary btn-sm me-2 mb-2 suggestion-chip" data-message="How can I improve student engagement in my classroom?">
                                Student Engagement
                            </button>
                            <button class="btn btn-outline-primary btn-sm me-2 mb-2 suggestion-chip" data-message="Help me create a lesson plan for mathematics">
                                Lesson Planning
                            </button>
                            <button class="btn btn-outline-primary btn-sm me-2 mb-2 suggestion-chip" data-message="What are some effective classroom management strategies?">
                                Classroom Management
                            </button>
                            <button class="btn btn-outline-primary btn-sm me-2 mb-2 suggestion-chip" data-message="How do I create engaging quiz questions?">
                                Assessment Ideas
                            </button>
                        </div>
                    </div>

                    <!-- Chat Input -->
                    <div class="chat-input p-3 border-top bg-white">
                        <form id="chatForm" class="d-flex">
                            <div class="flex-grow-1 me-2">
                                <input type="text" id="messageInput" class="form-control" placeholder="Ask me anything about teaching..." autocomplete="off">
                            </div>
                            <button type="submit" class="btn btn-primary" id="sendButton">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mb-0">AI is thinking...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chatMessages');
    const messageInput = document.getElementById('messageInput');
    const chatForm = document.getElementById('chatForm');
    const sendButton = document.getElementById('sendButton');
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

    // Auto-scroll to bottom
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Add message to chat
    function addMessage(message, isBot = false, timestamp = null) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${isBot ? 'bot-message' : 'user-message'} mb-3`;
        
        const time = timestamp || new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        messageDiv.innerHTML = `
            <div class="message-content d-flex ${isBot ? '' : 'flex-row-reverse'}">
                <div class="message-avatar ${isBot ? 'me-3' : 'ms-3'}">
                    <i class="fas ${isBot ? 'fa-robot' : 'fa-user'}"></i>
                </div>
                <div class="message-bubble ${isBot ? 'bg-white' : 'bg-primary text-white'} p-3 rounded-3 shadow-sm">
                    <div class="message-text">${message}</div>
                    <div class="message-time text-muted small mt-1 ${isBot ? '' : 'text-white-50'}">${time}</div>
                </div>
            </div>
        `;
        
        chatMessages.appendChild(messageDiv);
        scrollToBottom();
    }

    // Send message
    async function sendMessage(message) {
        if (!message.trim()) return;

        // Add user message
        addMessage(message, false);
        messageInput.value = '';

        // Show loading
        loadingModal.show();
        sendButton.disabled = true;

        try {
            const response = await fetch('{{ route("teacher.ai.chat.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();
            
            if (data.success) {
                addMessage(data.reply, true, data.timestamp);
            } else {
                addMessage(data.reply || 'Sorry, I encountered an error. Please try again.', true);
            }
        } catch (error) {
            console.error('Error:', error);
            addMessage('Sorry, I encountered a network error. Please try again.', true);
        } finally {
            loadingModal.hide();
            sendButton.disabled = false;
            messageInput.focus();
        }
    }

    // Form submission
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = messageInput.value.trim();
        if (message) {
            sendMessage(message);
        }
    });

    // Suggestion chips
    document.querySelectorAll('.suggestion-chip').forEach(chip => {
        chip.addEventListener('click', function() {
            const message = this.getAttribute('data-message');
            messageInput.value = message;
            sendMessage(message);
        });
    });

    // Load more suggestions
    async function loadSuggestions(context = 'general') {
        try {
            const response = await fetch(`{{ route("teacher.ai.chat.suggestions") }}?context=${context}`);
            const data = await response.json();
            
            const suggestionChips = document.getElementById('suggestionChips');
            suggestionChips.innerHTML = '';
            
            data.suggestions.forEach(suggestion => {
                const chip = document.createElement('button');
                chip.className = 'btn btn-outline-primary btn-sm me-2 mb-2 suggestion-chip';
                chip.textContent = suggestion;
                chip.setAttribute('data-message', suggestion);
                chip.addEventListener('click', function() {
                    messageInput.value = suggestion;
                    sendMessage(suggestion);
                });
                suggestionChips.appendChild(chip);
            });
        } catch (error) {
            console.error('Error loading suggestions:', error);
        }
    }

    // Focus input on load
    messageInput.focus();
});
</script>

<style>
.chat-container {
    border: 1px solid #e9ecef;
    border-radius: 0.375rem;
}

.message-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    color: #6c757d;
    font-size: 1.1rem;
}

.user-message .message-avatar {
    background: #0d6efd;
    color: white;
}

.message-bubble {
    max-width: 70%;
    word-wrap: break-word;
}

.message-bubble ul {
    margin-bottom: 0;
}

.suggestion-chip {
    transition: all 0.2s ease;
}

.suggestion-chip:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.chat-messages::-webkit-scrollbar {
    width: 6px;
}

.chat-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.chat-messages::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.chat-messages::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

#messageInput:focus {
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    border-color: #86b7fe;
}
</style>
@endsection
