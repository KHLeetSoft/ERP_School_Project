@extends('superadmin.app')

@section('title', 'AI Chatbot')

@section('content')
<div class="container-fluid p-0">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg bg-gradient-success text-white overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 mb-2 fw-bold">
                                <i class="fas fa-robot me-3"></i>AI Chatbot
                            </h1>
                            <p class="mb-0 opacity-75 fs-5">Intelligent assistant for system queries and analysis</p>
                        </div>
                        <div class="text-end">
                            <div class="h4 mb-0">Always Online</div>
                            <small class="opacity-75">24/7 Support</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold">
                                <i class="fas fa-robot me-2 text-success"></i>AI Assistant
                            </h5>
                            <p class="text-muted mb-0">Ask me anything about your system</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="clearChat()">
                                <i class="fas fa-trash me-1"></i>Clear Chat
                            </button>
                            <button class="btn btn-outline-success btn-sm" onclick="loadSuggestions()">
                                <i class="fas fa-lightbulb me-1"></i>Suggestions
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="chatContainer" class="chat-container" style="height: 500px; overflow-y: auto; padding: 20px;">
                        <!-- Welcome Message -->
                        <div class="ai-message mb-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-start">
                                <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-robot text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">AI Assistant</h6>
                                    <p class="mb-0 small">Hello! I'm your AI assistant. I can help you analyze data, generate reports, and answer questions about your system. What would you like to know?</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Chat Input -->
                    <div class="chat-input p-3 border-top">
                        <form id="chatForm">
                            @csrf
                            <div class="input-group">
                                <input type="text" class="form-control" id="chatInput" placeholder="Type your message here..." autocomplete="off">
                                <button class="btn btn-success" type="submit">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Quick Actions</h5>
                    <p class="text-muted mb-0">Common queries and tasks</p>
                </div>
                <div class="card-body p-4">
                    <div class="quick-actions">
                        <button class="btn btn-outline-primary w-100 mb-2 text-start" onclick="sendQuickMessage('Show me schools whose plan will expire in 10 days')">
                            <i class="fas fa-calendar-exclamation me-2"></i>Expiring Plans
                        </button>
                        <button class="btn btn-outline-success w-100 mb-2 text-start" onclick="sendQuickMessage('What is the revenue trend for last quarter?')">
                            <i class="fas fa-chart-line me-2"></i>Revenue Analysis
                        </button>
                        <button class="btn btn-outline-warning w-100 mb-2 text-start" onclick="sendQuickMessage('Which schools have the highest user activity?')">
                            <i class="fas fa-users me-2"></i>User Activity
                        </button>
                        <button class="btn btn-outline-info w-100 mb-2 text-start" onclick="sendQuickMessage('Show me plan upgrade recommendations')">
                            <i class="fas fa-arrow-up me-2"></i>Upgrade Suggestions
                        </button>
                        <button class="btn btn-outline-danger w-100 mb-2 text-start" onclick="sendQuickMessage('Show me schools at risk of churning')">
                            <i class="fas fa-exclamation-triangle me-2"></i>Churn Risk
                        </button>
                        <button class="btn btn-outline-secondary w-100 mb-2 text-start" onclick="sendQuickMessage('What are the system performance metrics?')">
                            <i class="fas fa-server me-2"></i>System Health
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-lg mt-4">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">AI Capabilities</h5>
                    <p class="text-muted mb-0">What I can help you with</p>
                </div>
                <div class="card-body p-4">
                    <div class="capabilities">
                        <div class="capability-item mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-chart-bar text-primary me-2"></i>
                                <span class="small fw-bold">Data Analysis</span>
                            </div>
                        </div>
                        <div class="capability-item mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-school text-success me-2"></i>
                                <span class="small fw-bold">School Management</span>
                            </div>
                        </div>
                        <div class="capability-item mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-rupee-sign text-warning me-2"></i>
                                <span class="small fw-bold">Revenue Insights</span>
                            </div>
                        </div>
                        <div class="capability-item mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-users text-info me-2"></i>
                                <span class="small fw-bold">User Analytics</span>
                            </div>
                        </div>
                        <div class="capability-item mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-cog text-secondary me-2"></i>
                                <span class="small fw-bold">System Monitoring</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let chatHistory = [];

document.getElementById('chatForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const input = document.getElementById('chatInput');
    const message = input.value.trim();
    
    if (message) {
        sendMessage(message);
        input.value = '';
    }
});

function sendMessage(message) {
    // Add user message to chat
    addMessage(message, 'user');
    
    // Show typing indicator
    addTypingIndicator();
    
    // Send to AI
    $.ajax({
        url: '{{ route("superadmin.ai-automation.chatbot") }}',
        method: 'POST',
        data: {
            query: message,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            removeTypingIndicator();
            addMessage(response.message, 'ai', response.data);
        },
        error: function() {
            removeTypingIndicator();
            addMessage('Sorry, I encountered an error processing your request. Please try again.', 'ai');
        }
    });
}

function sendQuickMessage(message) {
    document.getElementById('chatInput').value = message;
    sendMessage(message);
}

function addMessage(message, sender, data = null) {
    const chatContainer = document.getElementById('chatContainer');
    const messageDiv = document.createElement('div');
    
    if (sender === 'user') {
        messageDiv.className = 'user-message mb-3 p-3 bg-primary text-white rounded';
        messageDiv.innerHTML = `
            <div class="d-flex align-items-start">
                <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                    <i class="fas fa-user text-white"></i>
                </div>
                <div>
                    <h6 class="mb-1 fw-bold">You</h6>
                    <p class="mb-0 small">${message}</p>
                </div>
            </div>
        `;
    } else {
        messageDiv.className = 'ai-message mb-3 p-3 bg-light rounded';
        let content = `<p class="mb-0 small">${message}</p>`;
        
        if (data && data.length > 0) {
            content += `
                <div class="mt-3">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    ${Object.keys(data[0]).map(key => `<th>${key}</th>`).join('')}
                                </tr>
                            </thead>
                            <tbody>
                                ${data.map(row => `
                                    <tr>
                                        ${Object.values(row).map(value => `<td>${value}</td>`).join('')}
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        }
        
        messageDiv.innerHTML = `
            <div class="d-flex align-items-start">
                <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                    <i class="fas fa-robot text-success"></i>
                </div>
                <div>
                    <h6 class="mb-1 fw-bold">AI Assistant</h6>
                    ${content}
                </div>
            </div>
        `;
    }
    
    chatContainer.appendChild(messageDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;
    
    // Store in history
    chatHistory.push({ message, sender, timestamp: new Date() });
}

function addTypingIndicator() {
    const chatContainer = document.getElementById('chatContainer');
    const typingDiv = document.createElement('div');
    typingDiv.id = 'typingIndicator';
    typingDiv.className = 'ai-message mb-3 p-3 bg-light rounded';
    typingDiv.innerHTML = `
        <div class="d-flex align-items-start">
            <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                <i class="fas fa-robot text-success"></i>
            </div>
            <div>
                <h6 class="mb-1 fw-bold">AI Assistant</h6>
                <div class="typing-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    `;
    chatContainer.appendChild(typingDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function removeTypingIndicator() {
    const typingIndicator = document.getElementById('typingIndicator');
    if (typingIndicator) {
        typingIndicator.remove();
    }
}

function clearChat() {
    const chatContainer = document.getElementById('chatContainer');
    chatContainer.innerHTML = `
        <div class="ai-message mb-3 p-3 bg-light rounded">
            <div class="d-flex align-items-start">
                <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                    <i class="fas fa-robot text-success"></i>
                </div>
                <div>
                    <h6 class="mb-1 fw-bold">AI Assistant</h6>
                    <p class="mb-0 small">Hello! I'm your AI assistant. I can help you analyze data, generate reports, and answer questions about your system. What would you like to know?</p>
                </div>
            </div>
        </div>
    `;
    chatHistory = [];
}

function loadSuggestions() {
    const suggestions = [
        'Show me schools whose plan will expire in 10 days',
        'What is the revenue trend for last quarter?',
        'Which schools have the highest user activity?',
        'Show me plan upgrade recommendations',
        'What are the system performance metrics?',
        'Show me schools at risk of churning'
    ];
    
    const randomSuggestion = suggestions[Math.floor(Math.random() * suggestions.length)];
    document.getElementById('chatInput').value = randomSuggestion;
}

// Auto-focus on input
document.getElementById('chatInput').focus();
</script>

<style>
.chat-container {
    background: #f8f9fa;
}

.user-message {
    margin-left: 20%;
}

.ai-message {
    margin-right: 20%;
}

.typing-dots {
    display: inline-block;
}

.typing-dots span {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #6c757d;
    margin: 0 2px;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-dots span:nth-child(1) {
    animation-delay: -0.32s;
}

.typing-dots span:nth-child(2) {
    animation-delay: -0.16s;
}

@keyframes typing {
    0%, 80%, 100% {
        transform: scale(0);
        opacity: 0.5;
    }
    40% {
        transform: scale(1);
        opacity: 1;
    }
}
</style>
@endsection
