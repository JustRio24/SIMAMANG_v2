@extends('layouts.app')

@section('title', 'MAMANG - AI Assistant')

@push('styles')
<style>
    :root {
        --chat-primary: #00A19C;
        --chat-primary-gradient: linear-gradient(135deg, #00A19C 0%, #4facfe 100%);
        --chat-bg: #f4f7f6;
        --chat-bubble-user: #00A19C;
        --chat-bubble-bot: #ffffff;
        --chat-text-bot: #2d3748;
        --chat-text-user: #ffffff;
    }

    /* Layout Adjustments */
    body {
        background-color: #f0f2f5; 
    }

    .main-chat-wrapper {
        height: calc(100vh - 140px); /* Adjust based on your navbar height */
        min-height: 500px;
        max-width: 900px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        overflow: hidden;
        position: relative;
    }

    /* Header */
    .chat-header {
        padding: 1.25rem 1.5rem;
        background: rgba(255, 255, 255, 0.9);
        border-bottom: 1px solid #edf2f7;
        backdrop-filter: blur(10px);
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .bot-profile {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .bot-avatar-large {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: var(--chat-primary-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 161, 156, 0.2);
    }

    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        background: #e6fffa;
        color: #00A19C;
        border-radius: 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        background: #00A19C;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    /* Chat Area */
    .chat-body {
        flex: 1;
        overflow-y: auto;
        padding: 2rem;
        background-image: radial-gradient(#e1e5ea 1px, transparent 1px);
        background-size: 20px 20px;
        background-color: #fafafa;
        scroll-behavior: smooth;
    }

    /* Messages */
    .message-row {
        display: flex;
        margin-bottom: 1.5rem;
        animation: slideIn 0.3s ease;
    }

    .message-row.user {
        justify-content: flex-end;
    }

    .message-row.bot {
        justify-content: flex-start;
    }

    .avatar-small {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1rem;
    }
    
    .message-row.bot .avatar-small {
        background: white;
        color: var(--chat-primary);
        border: 1px solid #e2e8f0;
        margin-right: 12px;
    }

    .message-bubble {
        max-width: 75%;
        padding: 1rem 1.25rem;
        font-size: 0.95rem;
        line-height: 1.6;
        position: relative;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }

    /* Bot Bubble Style */
    .message-row.bot .message-bubble {
        background: var(--chat-bubble-bot);
        color: var(--chat-text-bot);
        border-radius: 0 20px 20px 20px;
        border: 1px solid #edf2f7;
    }

    /* User Bubble Style */
    .message-row.user .message-bubble {
        background: var(--chat-primary-gradient);
        color: var(--chat-text-user);
        border-radius: 20px 0 20px 20px;
        box-shadow: 0 4px 15px rgba(0, 161, 156, 0.2);
    }

    /* Footer / Input Area */
    .chat-footer {
        background: white;
        padding: 1rem 1.5rem 1.5rem;
        border-top: 1px solid #edf2f7;
    }

    /* Quick Action Chips */
    .chips-container {
        display: flex;
        gap: 0.75rem;
        overflow-x: auto;
        padding-bottom: 0.75rem;
        scrollbar-width: none; /* Firefox */
    }
    .chips-container::-webkit-scrollbar {
        display: none; /* Chrome/Safari */
    }

    .chip-btn {
        white-space: nowrap;
        background: #f7fafc;
        border: 1px solid #edf2f7;
        color: #4a5568;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .chip-btn:hover {
        background: #e6fffa;
        border-color: #00A19C;
        color: #00A19C;
        transform: translateY(-2px);
    }

    /* Input Group */
    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        background: #f7fafc;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        padding: 0.5rem;
        transition: all 0.3s;
    }

    .input-wrapper:focus-within {
        background: white;
        border-color: #00A19C;
        box-shadow: 0 0 0 4px rgba(0, 161, 156, 0.1);
    }

    .chat-input {
        border: none;
        background: transparent;
        width: 100%;
        padding: 0.75rem 1rem;
        outline: none;
        font-size: 1rem;
        color: #2d3748;
    }

    .btn-send {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: var(--chat-primary-gradient);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.2s;
        flex-shrink: 0;
    }

    .btn-send:hover {
        transform: scale(1.05);
    }
    
    .btn-send:disabled {
        background: #cbd5e0;
        cursor: not-allowed;
        transform: none;
    }

    /* Typing Animation */
    .typing-indicator {
        display: none;
        padding: 10px 20px;
        background: white;
        border-radius: 0 20px 20px 20px;
        border: 1px solid #edf2f7;
        width: fit-content;
        margin-bottom: 1.5rem;
        margin-left: 48px; /* Offset for avatar */
    }

    .typing-indicator.active {
        display: block;
        animation: fadeIn 0.3s;
    }

    .dot-flashing {
        position: relative;
        width: 6px;
        height: 6px;
        border-radius: 5px;
        background-color: #00A19C;
        color: #00A19C;
        animation: dot-flashing 1s infinite linear alternate;
        animation-delay: 0.5s;
    }
    .dot-flashing::before, .dot-flashing::after {
        content: "";
        display: inline-block;
        position: absolute;
        top: 0;
        width: 6px;
        height: 6px;
        border-radius: 5px;
        background-color: #00A19C;
        color: #00A19C;
        animation: dot-flashing 1s infinite alternate;
    }
    .dot-flashing::before { left: -10px; animation-delay: 0s; }
    .dot-flashing::after { left: 10px; animation-delay: 1s; }

    @keyframes dot-flashing {
        0% { background-color: #00A19C; }
        50%, 100% { background-color: rgba(0, 161, 156, 0.2); }
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(0, 161, 156, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(0, 161, 156, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(0, 161, 156, 0); }
    }

    /* Content Styling inside Bot Bubbles */
    .message-bubble ul, .message-bubble ol { margin: 0.5rem 0 0.5rem 1.2rem; padding: 0; }
    .message-bubble li { margin-bottom: 0.25rem; }
    .message-bubble strong { color: #00A19C; font-weight: 700; }
    .message-row.user .message-bubble strong { color: white; }
    
    /* Responsive */
    @media (max-width: 768px) {
        .main-chat-wrapper {
            height: calc(100vh - 80px);
            border-radius: 0;
            max-width: 100%;
        }
        .chat-body { padding: 1rem; }
        .message-bubble { max-width: 85%; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0 px-md-3">
    <div class="main-chat-wrapper">
        
        <div class="chat-header">
            <div class="bot-profile">
                <div class="bot-avatar-large">
                    <i class="bi bi-robot"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold text-dark">MAMANG AI</h5>
                    <small class="text-muted">Asisten Virtual Magang</small>
                </div>
            </div>
            <div class="status-badge">
                <div class="status-dot"></div> Online
            </div>
        </div>

        <div class="chat-body" id="chatMessages">
            
            <div class="message-row bot">
                <div class="avatar-small">
                    <i class="bi bi-robot"></i>
                </div>
                <div class="message-bubble">
                    <p class="mb-2"><strong>Halo, Selamat Datang! 👋</strong></p>
                    <p class="mb-2">Saya MAMANG, siap membantu proses magang Anda. Tanyakan saya tentang:</p>
                    <ul class="mb-0 small">
                        <li>Status pengajuan Anda</li>
                        <li>Syarat & Dokumen magang</li>
                        <li>Alur pendaftaran</li>
                    </ul>
                </div>
            </div>

            @foreach($messages as $msg)
                <div class="message-row user">
                    <div class="message-bubble">
                        {{ $msg->message }}
                    </div>
                </div>
                
                <div class="message-row bot">
                    <div class="avatar-small">
                        <i class="bi bi-robot"></i>
                    </div>
                    <div class="message-bubble bot-content-area" data-raw="{{ $msg->response }}">
                        </div>
                </div>
            @endforeach

            <div class="typing-indicator" id="typingIndicator">
                <div class="dot-flashing"></div>
            </div>

        </div>

        <div class="chat-footer">
            
            <div class="chips-container mb-2">
                <button class="chip-btn" data-message="Cek status pengajuan saya">
                    <i class="bi bi-search"></i> Cek Status
                </button>
                <button class="chip-btn" data-message="Bagaimana alur pendaftarannya?">
                    <i class="bi bi-map"></i> Alur Pendaftaran
                </button>
                <button class="chip-btn" data-message="Apa saja dokumen persyaratannya?">
                    <i class="bi bi-file-earmark-text"></i> Dokumen Syarat
                </button>
                <button class="chip-btn" data-message="Berapa lama proses verifikasi?">
                    <i class="bi bi-clock-history"></i> Estimasi Waktu
                </button>
            </div>

            <form id="chatForm" class="input-wrapper">
                @csrf
                <input type="text" id="messageInput" class="chat-input" 
                       placeholder="Ketik pesan Anda..." autocomplete="off" required>
                <button type="submit" class="btn-send">
                    <i class="bi bi-send-fill"></i>
                </button>
            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.6/dist/purify.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const sendBtn = chatForm.querySelector('button[type="submit"]');
    const chatMessages = document.getElementById('chatMessages');
    const typingIndicator = document.getElementById('typingIndicator');
    let isRequesting = false;
    
    // 1. Handle Quick Chips
    document.querySelectorAll('.chip-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            messageInput.value = this.dataset.message;
            chatForm.dispatchEvent(new Event('submit'));
        });
    });
    
    // 2. Handle Form Submit
    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (isRequesting) return;
        
        const message = messageInput.value.trim();
        if (!message) return;
        
        // Add User Message UI
        addMessageUI(message, 'user');
        messageInput.value = '';
        
        // UI State: Loading
        isRequesting = true;
        sendBtn.disabled = true;
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
            
            // Artificial delay for natural feel
            await new Promise(resolve => setTimeout(resolve, 600)); 
            
            typingIndicator.classList.remove('active');
            
            if (data.success) {
                addMessageUI(data.message.response, 'bot');
            } else {
                addMessageUI('Maaf, saya mengalami kesalahan. Silakan coba lagi.', 'bot');
            }
        } catch (error) {
            typingIndicator.classList.remove('active');
            addMessageUI('Koneksi terputus. Mohon periksa internet Anda.', 'bot');
            console.error('Chat error:', error);
        } finally {
            isRequesting = false;
            sendBtn.disabled = false;
            messageInput.focus();
        }
    });

    // 3. Render Markdown History
    function renderHistory() {
        document.querySelectorAll('.bot-content-area').forEach(el => {
            const rawText = el.getAttribute('data-raw');
            if (rawText && rawText.trim() !== "") {
                el.innerHTML = parseMarkdown(rawText);
            }
        });
        scrollToBottom();
    }

    // 4. Helper: Add Message to UI
    function addMessageUI(text, type) {
        const div = document.createElement('div');
        div.className = `message-row ${type}`;
        
        let content = text;
        let avatar = '';

        if (type === 'bot') {
            content = parseMarkdown(text);
            avatar = `<div class="avatar-small"><i class="bi bi-robot"></i></div>`;
        }
        
        div.innerHTML = `
            ${avatar}
            <div class="message-bubble">
                ${content}
            </div>
        `;
        
        chatMessages.insertBefore(div, typingIndicator);
        scrollToBottom();
    }

    // 5. Helper: Parse Markdown safely
    function parseMarkdown(text) {
        try {
            const rawHtml = marked.parse(text);
            return DOMPurify.sanitize(rawHtml);
        } catch (e) {
            return text.replace(/\n/g, '<br>');
        }
    }

    // 6. Helper: Scroll
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Initialize
    renderHistory();
});
</script>
@endpush