@extends('layouts.app')

@section('title', 'MAMANG - Chat Assistant')

@push('styles')
<style>
    .chat-container {
        height: calc(100vh - 250px);
        display: flex;
        flex-direction: column;
    }
    
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
        background-color: #f8fafc;
        border-radius: 12px;
    }
    
    .message {
        margin-bottom: 1rem;
        display: flex;
        align-items: flex-start;
    }
    
    .message.user {
        flex-direction: row-reverse;
    }
    
    .message-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    
    .message.bot .message-avatar {
        background: linear-gradient(135deg, #1e40af, #7c3aed);
        color: white;
    }
    
    .message.user .message-avatar {
        background-color: #e5e7eb;
        color: #374151;
    }
    
    .message-content {
        max-width: 70%;
        padding: 1rem;
        border-radius: 12px;
        margin: 0 1rem;
    }
    
    .message.bot .message-content {
        background-color: white;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .message.user .message-content {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }
    
    .chat-input {
        margin-top: 1rem;
        display: flex;
        gap: 0.5rem;
    }
    
    .typing-indicator {
        display: none;
        padding: 1rem;
    }
    
    .typing-indicator.active {
        display: flex;
        align-items: center;
    }
    
    .typing-indicator span {
        height: 8px;
        width: 8px;
        background-color: #9ca3af;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
        animation: bounce 1.4s infinite ease-in-out both;
    }
    
    .typing-indicator span:nth-child(1) {
        animation-delay: -0.32s;
    }
    
    .typing-indicator span:nth-child(2) {
        animation-delay: -0.16s;
    }
    
    @keyframes bounce {
        0%, 80%, 100% {
            transform: scale(0);
        }
        40% {
            transform: scale(1);
        }
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #1e40af, #7c3aed); color: white;">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-robot" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">MAMANG</h5>
                        <small>Asisten Virtual SIMAMANG - Rule-Based Chatbot</small>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="chat-container">
                    <div class="chat-messages" id="chatMessages">
                        <!-- Welcome Message -->
                        <div class="message bot">
                            <div class="message-avatar">
                                <i class="bi bi-robot"></i>
                            </div>
                            <div class="message-content">
                                <p class="mb-2"><strong>Halo! Saya MAMANG 👋</strong></p>
                                <p class="mb-0">Asisten virtual untuk membantu Anda dengan proses magang. Saya dapat membantu:</p>
                                <ul class="mb-0 mt-2">
                                    <li>Cek status pengajuan magang</li>
                                    <li>Panduan cara mengajukan magang</li>
                                    <li>Informasi dokumen yang diperlukan</li>
                                    <li>Estimasi waktu proses</li>
                                </ul>
                                <p class="mt-2 mb-0 small text-muted">
                                    <i class="bi bi-info-circle"></i> Silakan ketik pertanyaan Anda!
                                </p>
                            </div>
                        </div>
                        
                        <!-- Previous Messages -->
                        @foreach($messages as $msg)
                            <div class="message user">
                                <div class="message-avatar">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div class="message-content">
                                    {{ $msg->message }}
                                </div>
                            </div>
                            
                            <div class="message bot">
                                <div class="message-avatar">
                                    <i class="bi bi-robot"></i>
                                </div>
                                <div class="message-content">
                                    {!! nl2br(e($msg->response)) !!}
                                </div>
                            </div>
                        @endforeach
                        
                        <!-- Typing Indicator -->
                        <div class="typing-indicator" id="typingIndicator">
                            <div class="message-avatar" style="background: linear-gradient(135deg, #1e40af, #7c3aed); color: white;">
                                <i class="bi bi-robot"></i>
                            </div>
                            <div class="ms-3">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-3 border-top">
                        <form id="chatForm" class="chat-input">
                            @csrf
                            <input type="text" id="messageInput" class="form-control" 
                                   placeholder="Ketik pertanyaan Anda..." autocomplete="off" required>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i>
                            </button>
                        </form>
                        
                        <!-- Quick Actions -->
                        <div class="mt-2 d-flex flex-wrap gap-2">
                            <button class="btn btn-sm btn-outline-secondary quick-action" data-message="status">
                                Status magang saya
                            </button>
                            <button class="btn btn-sm btn-outline-secondary quick-action" data-message="cara">
                                Cara mengajukan
                            </button>
                            <button class="btn btn-sm btn-outline-secondary quick-action" data-message="dokumen">
                                Dokumen yang diperlukan
                            </button>
                            <button class="btn btn-sm btn-outline-secondary quick-action" data-message="timeline">
                                Berapa lama prosesnya?
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const chatMessages = document.getElementById('chatMessages');
    const typingIndicator = document.getElementById('typingIndicator');
    
    // Quick actions
    document.querySelectorAll('.quick-action').forEach(btn => {
        btn.addEventListener('click', function() {
            messageInput.value = this.dataset.message;
            chatForm.dispatchEvent(new Event('submit'));
        });
    });
    
    // Send message
    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;
        
        // Add user message
        addMessage(message, 'user');
        messageInput.value = '';
        
        // Show typing indicator
        typingIndicator.classList.add('active');
        scrollToBottom();
        
        try {
            const response = await fetch('{{ route("chatbot.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ message: message })
            });
            
            const data = await response.json();
            
            // Hide typing indicator
            typingIndicator.classList.remove('active');
            
            if (data.success) {
                addMessage(data.message.response, 'bot');
            } else {
                addMessage('Maaf, terjadi kesalahan. Silakan coba lagi.', 'bot');
            }
        } catch (error) {
            typingIndicator.classList.remove('active');
            addMessage('Maaf, terjadi kesalahan koneksi. Silakan coba lagi.', 'bot');
        }
    });
    
    function addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}`;
        
        messageDiv.innerHTML = `
            <div class="message-avatar">
                <i class="bi bi-${sender === 'bot' ? 'robot' : 'person'}"></i>
            </div>
            <div class="message-content">
                ${text.replace(/\n/g, '<br>').replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')}
            </div>
        `;
        
        chatMessages.insertBefore(messageDiv, typingIndicator);
        scrollToBottom();
    }
    
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // Initial scroll
    scrollToBottom();
});
</script>
@endpush