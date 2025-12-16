@extends('layouts.app')

@section('title', 'MAMANG - Chat Assistant')

@push('styles')
<style>
    .chat-container {
        height: calc(100vh - 200px);
        min-height: 400px;
        display: flex;
        flex-direction: column;
        background: linear-gradient(180deg, #f0f4ff 0%, #f8fafc 100%);
        border-radius: 0 0 12px 12px;
    }
    
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
    }
    
    .message {
        margin-bottom: 1.25rem;
        display: flex;
        align-items: flex-start;
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .message.user {
        flex-direction: row-reverse;
    }
    
    .message-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .message.bot .message-avatar {
        background: linear-gradient(135deg, #1e40af, #7c3aed);
        color: white;
    }
    
    .message.user .message-avatar {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }
    
    .message-content {
        max-width: 75%;
        padding: 1rem 1.25rem;
        border-radius: 18px;
        margin: 0 0.75rem;
        line-height: 1.5;
    }
    
    .message.bot .message-content {
        background-color: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border-bottom-left-radius: 4px;
    }
    
    .message.user .message-content {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border-bottom-right-radius: 4px;
    }
    
    .chat-input-container {
        padding: 1rem 1.5rem;
        background: white;
        border-top: 1px solid #e5e7eb;
        border-radius: 0 0 12px 12px;
    }
    
    .chat-input {
        display: flex;
        gap: 0.75rem;
    }
    
    .chat-input input {
        border-radius: 24px;
        padding: 0.75rem 1.25rem;
        border: 2px solid #e5e7eb;
        transition: all 0.3s;
    }
    
    .chat-input input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .chat-input button {
        border-radius: 50%;
        width: 48px;
        height: 48px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .quick-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }
    
    .quick-action {
        border-radius: 20px;
        font-size: 0.85rem;
        padding: 0.4rem 0.9rem;
        transition: all 0.2s;
        border: 2px solid #e5e7eb;
        background: white;
    }
    
    .quick-action:hover {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
    }
    
    .typing-indicator {
        display: none;
        padding: 0.5rem 0;
    }
    
    .typing-indicator.active {
        display: flex;
        align-items: center;
    }
    
    .typing-dots {
        display: flex;
        gap: 4px;
        padding: 0.75rem 1rem;
        background: white;
        border-radius: 18px;
        margin-left: 0.75rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .typing-dots span {
        height: 8px;
        width: 8px;
        background: linear-gradient(135deg, #1e40af, #7c3aed);
        border-radius: 50%;
        animation: bounce 1.4s infinite ease-in-out both;
    }
    
    .typing-dots span:nth-child(1) { animation-delay: -0.32s; }
    .typing-dots span:nth-child(2) { animation-delay: -0.16s; }
    
    @keyframes bounce {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }
    
    .chat-header {
        background: linear-gradient(135deg, #1e40af 0%, #7c3aed 100%);
        color: white;
        padding: 1.25rem 1.5rem;
        border-radius: 12px 12px 0 0;
    }
    
    .status-online {
        width: 10px;
        height: 10px;
        background: #22c55e;
        border-radius: 50%;
        display: inline-block;
        margin-right: 0.5rem;
        animation: blink 2s infinite;
    }
    
    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    @media (max-width: 768px) {
        .chat-container {
            height: calc(100vh - 180px);
        }
        
        .message-content {
            max-width: 85%;
            padding: 0.875rem 1rem;
        }
        
        .chat-messages {
            padding: 1rem;
        }
        
        .quick-action {
            font-size: 0.8rem;
            padding: 0.35rem 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-10 col-xl-8 mx-auto">
        <div class="card mb-0" style="overflow: hidden;">
            <div class="chat-header">
                <div class="d-flex align-items-center">
                    <div class="me-3" style="font-size: 2.5rem;">
                        <i class="bi bi-robot"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold">MAMANG AI</h4>
                        <small><span class="status-online"></span>Asisten Virtual Cerdas SIMAMANG</small>
                    </div>
                </div>
            </div>
            
            <div class="chat-container">
                <div class="chat-messages" id="chatMessages">
                    <div class="message bot">
                        <div class="message-avatar">
                            <i class="bi bi-robot"></i>
                        </div>
                        <div class="message-content">
                            <p class="mb-2"><strong>Halo! Saya MAMANG</strong> 👋</p>
                            <p class="mb-2">Asisten virtual cerdas untuk membantu Anda dengan proses magang di POLSRI. Saya dapat membantu:</p>
                            <ul class="mb-2 ps-3">
                                <li>Mengecek status pengajuan magang</li>
                                <li>Memberikan panduan langkah-langkah pengajuan</li>
                                <li>Informasi dokumen dan persyaratan</li>
                                <li>Estimasi waktu proses</li>
                                <li>Menjawab pertanyaan seputar magang</li>
                            </ul>
                            <p class="mb-0 small" style="opacity: 0.8;">
                                <i class="bi bi-lightbulb"></i> Coba tanyakan sesuatu atau gunakan tombol cepat di bawah!
                            </p>
                        </div>
                    </div>
                    
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
                    
                    <div class="typing-indicator" id="typingIndicator">
                        <div class="message-avatar" style="background: linear-gradient(135deg, #1e40af, #7c3aed); color: white; width: 42px; height: 42px;">
                            <i class="bi bi-robot"></i>
                        </div>
                        <div class="typing-dots">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
                
                <div class="chat-input-container">
                    <form id="chatForm" class="chat-input">
                        @csrf
                        <input type="text" id="messageInput" class="form-control" 
                               placeholder="Ketik pertanyaan Anda di sini..." autocomplete="off" required>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </form>
                    
                    <div class="quick-actions">
                        <button class="btn quick-action" data-message="Cek status pengajuan saya">
                            <i class="bi bi-search"></i> Status Saya
                        </button>
                        <button class="btn quick-action" data-message="Bagaimana cara mengajukan magang?">
                            <i class="bi bi-question-circle"></i> Cara Mengajukan
                        </button>
                        <button class="btn quick-action" data-message="Apa saja dokumen yang diperlukan?">
                            <i class="bi bi-file-earmark"></i> Dokumen
                        </button>
                        <button class="btn quick-action" data-message="Berapa lama proses pengajuan magang?">
                            <i class="bi bi-clock"></i> Timeline
                        </button>
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
    
    document.querySelectorAll('.quick-action').forEach(btn => {
        btn.addEventListener('click', function() {
            messageInput.value = this.dataset.message;
            chatForm.dispatchEvent(new Event('submit'));
        });
    });
    
    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;
        
        addMessage(message, 'user');
        messageInput.value = '';
        
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
            
            await new Promise(resolve => setTimeout(resolve, 500 + Math.random() * 500));
            
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
        
        const formattedText = text
            .replace(/\n/g, '<br>')
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>');
        
        messageDiv.innerHTML = `
            <div class="message-avatar">
                <i class="bi bi-${sender === 'bot' ? 'robot' : 'person'}"></i>
            </div>
            <div class="message-content">
                ${formattedText}
            </div>
        `;
        
        chatMessages.insertBefore(messageDiv, typingIndicator);
        scrollToBottom();
    }
    
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    scrollToBottom();
});
</script>
@endpush
