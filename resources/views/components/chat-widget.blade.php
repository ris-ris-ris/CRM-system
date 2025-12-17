<!-- Chat Widget -->
<div id="chatWidget" class="fixed bottom-6 right-6 z-50">
    <!-- Chat Button -->
    <button id="chatToggle" class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center group relative">
        <svg id="chatIcon" class="w-6 h-6 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <svg id="closeIcon" class="w-6 h-6 transition-transform duration-300 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        <span id="unreadBadge" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full text-xs flex items-center justify-center font-bold hidden">0</span>
    </button>

    <!-- Chat Window -->
    <div id="chatWindow" class="hidden fixed bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden" style="width: 400px; height: 600px; min-width: 320px; min-height: 450px; max-width: 90vw; max-height: 90vh; bottom: 5rem; right: 1.5rem;">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-semibold text-lg">Общий чат</h3>
                    <p class="text-blue-100 text-xs" id="onlineCount">Общий чат команды</p>
                </div>
            </div>
            <button id="chatMinimize" class="text-white/80 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                </svg>
            </button>
        </div>

        <!-- Messages Container -->
        <div id="chatMessages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 dark:bg-gray-900/50">
            <div class="flex items-center justify-center py-8" id="chatLoading">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="border-t border-gray-200 dark:border-gray-700 p-3 bg-white dark:bg-gray-800 flex-shrink-0">
            <form id="chatForm" class="flex gap-2 items-center">
                <input 
                    type="text" 
                    id="chatInput" 
                    placeholder="Напишите сообщение..." 
                    class="flex-1 px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm"
                    maxlength="1000"
                    autocomplete="off"
                >
                <button 
                    type="submit" 
                    id="chatSendBtn"
                    class="px-4 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl font-medium transition-all shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-1.5 flex-shrink-0"
                    style="min-width: 80px;"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    <span>Отправить</span>
                </button>
            </form>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">Сообщения сохраняются в истории</p>
        </div>
    </div>
</div>

<style>
    /* Custom scrollbar for chat */
    #chatMessages::-webkit-scrollbar {
        width: 6px;
    }
    
    #chatMessages::-webkit-scrollbar-track {
        background: transparent;
    }
    
    #chatMessages::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 3px;
    }
    
    html.dark #chatMessages::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
    }
    
    /* Message animations */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .chat-message {
        animation: slideIn 0.3s ease-out;
    }
    
    /* Glow effect for chat button in dark mode */
    html.dark #chatToggle {
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.4),
                    0 0 40px rgba(59, 130, 246, 0.2);
    }
    
    html.dark #chatToggle:hover {
        box-shadow: 0 0 30px rgba(59, 130, 246, 0.6),
                    0 0 60px rgba(59, 130, 246, 0.3);
    }
    
    /* Resize handle styling */
    .resize-handle {
        z-index: 10;
    }
    
    .resize-handle:hover {
        opacity: 1 !important;
    }
    
    /* Ensure chat window stays within viewport */
    #chatWindow {
        position: fixed !important;
    }
    
    /* Prevent text selection during drag */
    #chatWindow .bg-gradient-to-r {
        user-select: none;
        -webkit-user-select: none;
    }
    
    /* Ensure input area doesn't shrink */
    #chatForm {
        min-width: 0;
    }
    
    #chatInput {
        min-width: 0;
    }
    
    /* Responsive adjustments */
    @media (max-width: 640px) {
        #chatWindow {
            width: calc(100vw - 2rem) !important;
            height: calc(100vh - 8rem) !important;
            right: 1rem !important;
            bottom: 5rem !important;
            left: auto !important;
            top: auto !important;
        }
    }

    /* =========================================================
       CRM theme integration (class-based via html.dark)
       Tailwind CDN "dark:" won't follow html.dark, so we override
       chat colors explicitly to react to the CRM toggle.
       ========================================================= */
    html.dark #chatWindow{
        background: rgba(15, 23, 42, 0.92) !important;
        border-color: rgba(255,255,255,0.12) !important;
        box-shadow: 0 22px 80px rgba(0,0,0,0.65) !important;
    }
    html.dark #chatMessages{
        background: rgba(2, 6, 23, 0.45) !important;
    }
    html.dark #chatWindow .border-gray-200{ border-color: rgba(255,255,255,0.10) !important; }
    html.dark #chatWindow .border-gray-300{ border-color: rgba(255,255,255,0.12) !important; }
    html.dark #chatWindow .text-gray-900{ color: rgba(226,232,240,0.96) !important; }
    html.dark #chatWindow .text-gray-700,
    html.dark #chatWindow .text-gray-600{ color: rgba(203,213,225,0.86) !important; }
    html.dark #chatWindow .text-gray-500,
    html.dark #chatWindow .text-gray-400{ color: rgba(148,163,184,0.92) !important; }

    /* Input area */
    html.dark #chatWindow input[type="text"]{
        background: rgba(15,23,42,0.70) !important;
        border-color: rgba(255,255,255,0.12) !important;
        color: rgba(226,232,240,0.96) !important;
    }
    html.dark #chatWindow input[type="text"]::placeholder{
        color: rgba(148,163,184,0.92) !important;
    }

    /* Message bubbles for non-own messages (Tailwind classes are fixed) */
    html.dark #chatMessages .bg-gray-200{
        background: rgba(51, 65, 85, 0.55) !important;
        color: rgba(226,232,240,0.96) !important;
    }
    html.dark #chatMessages .bg-gray-400{
        background: rgba(100, 116, 139, 0.95) !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatWidget = document.getElementById('chatWidget');
    const chatToggle = document.getElementById('chatToggle');
    const chatWindow = document.getElementById('chatWindow');
    const chatIcon = document.getElementById('chatIcon');
    const closeIcon = document.getElementById('closeIcon');
    const chatMessages = document.getElementById('chatMessages');
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');
    const chatSendBtn = document.getElementById('chatSendBtn');
    const unreadBadge = document.getElementById('unreadBadge');
    const onlineCount = document.getElementById('onlineCount');
    
    let isOpen = false;
    let lastMessageId = 0;
    let unreadCount = 0;
    let pollInterval = null;
    
    // Toggle chat
    chatToggle.addEventListener('click', function() {
        isOpen = !isOpen;
        if (isOpen) {
            chatWindow.classList.remove('hidden');
            chatIcon.classList.add('hidden');
            closeIcon.classList.remove('hidden');
            unreadCount = 0;
            updateUnreadBadge();
            window.chatLoadMessages();
            startPolling();
        } else {
            chatWindow.classList.add('hidden');
            chatIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
            stopPolling();
        }
    });
    
    // Minimize chat
    document.getElementById('chatMinimize')?.addEventListener('click', function() {
        isOpen = false;
        chatWindow.classList.add('hidden');
        chatIcon.classList.remove('hidden');
        closeIcon.classList.add('hidden');
        stopPolling();
    });
    
    // Load messages
    window.chatLoadMessages = async function loadMessages() {
        try {
            const response = await fetch('/api/chat/messages', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });
            
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.error || `HTTP ${response.status}`);
            }
            
            const messages = await response.json();
            
            if (!Array.isArray(messages)) {
                throw new Error('Invalid response format');
            }
            
            // Hide loading indicator
            const loadingEl = document.getElementById('chatLoading');
            if (loadingEl) loadingEl.remove();
            
            renderMessages(messages);
            
            if (messages.length > 0) {
                lastMessageId = messages[messages.length - 1].id;
            }
            
            scrollToBottom();
        } catch (error) {
            console.error('Error loading messages:', error);
            const errorMsg = error.message || 'Ошибка загрузки сообщений';
            chatMessages.innerHTML = `
                <div class="text-center py-8">
                    <div class="text-red-500 dark:text-red-400 mb-2">${escapeHtml(errorMsg)}</div>
                    <button onclick="window.chatLoadMessages && window.chatLoadMessages()" class="text-sm text-blue-500 dark:text-blue-400 hover:underline">
                        Попробовать снова
                    </button>
                </div>
            `;
        }
    }
    
    // Render messages
    function renderMessages(messages) {
        if (!messages || messages.length === 0) {
            chatMessages.innerHTML = '<div class="text-center text-gray-500 dark:text-gray-400 py-8">Пока нет сообщений. Начните общение!</div>';
            return;
        }
        
        chatMessages.innerHTML = messages.map(msg => {
            const isOwn = msg.is_own;
            const initials = msg.user_name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
            const bgColor = isOwn 
                ? 'bg-gradient-to-br from-blue-500 to-indigo-600' 
                : 'bg-gray-200 dark:bg-gray-700';
            const textColor = isOwn ? 'text-white' : 'text-gray-900 dark:text-gray-100';
            const align = isOwn ? 'justify-end' : 'justify-start';
            
            return `
                <div class="flex ${align} chat-message">
                    <div class="flex gap-2 max-w-[80%] ${isOwn ? 'flex-row-reverse' : 'flex-row'}">
                        <div class="w-8 h-8 rounded-full ${isOwn ? 'bg-blue-400' : 'bg-gray-400'} flex items-center justify-center text-xs font-semibold text-white flex-shrink-0">
                            ${initials}
                        </div>
                        <div class="flex flex-col ${isOwn ? 'items-end' : 'items-start'}">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-semibold text-gray-600 dark:text-gray-400">${escapeHtml(msg.user_name)}</span>
                                <span class="text-xs text-gray-400 dark:text-gray-500">${msg.time}</span>
                            </div>
                            <div class="${bgColor} ${textColor} px-4 py-2 rounded-2xl ${isOwn ? 'rounded-tr-sm' : 'rounded-tl-sm'} shadow-sm">
                                <p class="text-sm whitespace-pre-wrap break-words">${escapeHtml(msg.message)}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }
    
    // Send message
    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const message = chatInput.value.trim();
        if (!message) return;
        
        chatSendBtn.disabled = true;
        chatInput.disabled = true;
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            if (!csrfToken) {
                throw new Error('CSRF token not found');
            }
            
            const response = await fetch('/api/chat/messages', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                credentials: 'same-origin',
                body: JSON.stringify({ message })
            });
            
            const responseData = await response.json();
            
            if (!response.ok) {
                throw new Error(responseData.error || `HTTP ${response.status}`);
            }
            
            chatInput.value = '';
            
            // Reload messages to get all updates
            await window.chatLoadMessages();
            
        } catch (error) {
            console.error('Error sending message:', error);
            const errorMsg = error.message || 'Ошибка отправки сообщения';
            alert(errorMsg);
        } finally {
            chatSendBtn.disabled = false;
            chatInput.disabled = false;
            chatInput.focus();
        }
    });
    
    // Polling for new messages
    function startPolling() {
        if (pollInterval) return;
        pollInterval = setInterval(async () => {
            try {
                const response = await fetch(`/api/chat/messages?after=${lastMessageId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                
                if (!response.ok) return;
                
                const messages = await response.json();
                if (messages.length > 0) {
                    if (isOpen) {
                        await window.chatLoadMessages();
                    } else {
                        unreadCount += messages.length;
                        updateUnreadBadge();
                    }
                    lastMessageId = messages[messages.length - 1].id;
                }
            } catch (error) {
                console.error('Error polling messages:', error);
            }
        }, 3000); // Poll every 3 seconds
    }
    
    function stopPolling() {
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }
    }
    
    function updateUnreadBadge() {
        if (unreadCount > 0) {
            unreadBadge.textContent = unreadCount > 99 ? '99+' : unreadCount;
            unreadBadge.classList.remove('hidden');
        } else {
            unreadBadge.classList.add('hidden');
        }
    }
    
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Make chat window resizable and draggable
    function initChatResize() {
        const chatWindow = document.getElementById('chatWindow');
        if (!chatWindow) return;
        
        let resizeType = null; // 'right', 'bottom', 'corner', 'left', 'top'
        let isResizing = false;
        let startX, startY, startWidth, startHeight, startLeft, startTop;
        
        // Create resize handles for all edges
        const handles = {
            right: { pos: 'right', cursor: 'ew-resize', style: 'top: 0; right: 0; width: 4px; height: 100%;' },
            bottom: { pos: 'bottom', cursor: 'ns-resize', style: 'bottom: 0; left: 0; width: 100%; height: 4px;' },
            corner: { pos: 'corner', cursor: 'nwse-resize', style: 'bottom: 0; right: 0; width: 12px; height: 12px;' },
            left: { pos: 'left', cursor: 'ew-resize', style: 'top: 0; left: 0; width: 4px; height: 100%;' },
            top: { pos: 'top', cursor: 'ns-resize', style: 'top: 0; left: 0; width: 100%; height: 4px;' }
        };
        
        Object.keys(handles).forEach(key => {
            const handle = document.createElement('div');
            handle.className = `resize-handle resize-${key} absolute opacity-0 hover:opacity-100 transition-opacity z-50`;
            handle.style.cssText = handles[key].style + `cursor: ${handles[key].cursor};`;
            if (key === 'corner') {
                handle.style.background = 'linear-gradient(135deg, rgba(59, 130, 246, 0.4), rgba(99, 102, 241, 0.4))';
                handle.style.borderRadius = '0 0 1rem 0';
            } else {
                handle.style.background = 'rgba(59, 130, 246, 0.2)';
            }
            chatWindow.appendChild(handle);
            
            handle.addEventListener('mousedown', (e) => {
                e.stopPropagation();
                resizeType = key;
                isResizing = true;
                startX = e.clientX;
                startY = e.clientY;
                const rect = chatWindow.getBoundingClientRect();
                startWidth = rect.width;
                startHeight = rect.height;
                startLeft = rect.left;
                startTop = rect.top;
                e.preventDefault();
            });
        });
        
        document.addEventListener('mousemove', (e) => {
            if (!isResizing || !resizeType) return;
            
            const deltaX = e.clientX - startX;
            const deltaY = e.clientY - startY;
            
            let newWidth = startWidth;
            let newHeight = startHeight;
            let newLeft = startLeft;
            let newTop = startTop;
            
            const minWidth = 320;
            const maxWidth = window.innerWidth * 0.9;
            const minHeight = 450;
            const maxHeight = window.innerHeight * 0.9;
            
            if (resizeType === 'right' || resizeType === 'corner') {
                newWidth = Math.min(Math.max(startWidth + deltaX, minWidth), maxWidth);
            }
            if (resizeType === 'bottom' || resizeType === 'corner') {
                newHeight = Math.min(Math.max(startHeight + deltaY, minHeight), maxHeight);
            }
            if (resizeType === 'left') {
                const widthChange = startWidth - deltaX;
                if (widthChange >= minWidth && widthChange <= maxWidth) {
                    newWidth = widthChange;
                    newLeft = startLeft + deltaX;
                }
            }
            if (resizeType === 'top') {
                const heightChange = startHeight - deltaY;
                if (heightChange >= minHeight && heightChange <= maxHeight) {
                    newHeight = heightChange;
                    newTop = startTop + deltaY;
                }
            }
            
            chatWindow.style.width = newWidth + 'px';
            chatWindow.style.height = newHeight + 'px';
            if (resizeType === 'left') chatWindow.style.left = newLeft + 'px';
            if (resizeType === 'top') chatWindow.style.top = newTop + 'px';
        });
        
        document.addEventListener('mouseup', () => {
            isResizing = false;
            resizeType = null;
        });
        
        // Make window draggable by header
        const chatHeader = chatWindow.querySelector('.bg-gradient-to-r');
        let isDragging = false;
        let dragStartX, dragStartY, dragStartLeft, dragStartTop;
        
        if (chatHeader) {
            chatHeader.style.cursor = 'move';
            chatHeader.addEventListener('mousedown', (e) => {
                if (e.target.closest('button') || e.target.closest('.resize-handle')) return;
                isDragging = true;
                dragStartX = e.clientX;
                dragStartY = e.clientY;
                const rect = chatWindow.getBoundingClientRect();
                dragStartLeft = rect.left;
                dragStartTop = rect.top;
                e.preventDefault();
            });
        }
        
        document.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            
            const deltaX = e.clientX - dragStartX;
            const deltaY = e.clientY - dragStartY;
            
            let newLeft = dragStartLeft + deltaX;
            let newTop = dragStartTop + deltaY;
            
            // Keep window within viewport
            const maxLeft = window.innerWidth - parseInt(chatWindow.style.width || 400);
            const maxTop = window.innerHeight - parseInt(chatWindow.style.height || 600);
            
            newLeft = Math.max(0, Math.min(newLeft, maxLeft));
            newTop = Math.max(0, Math.min(newTop, maxTop));
            
            chatWindow.style.left = newLeft + 'px';
            chatWindow.style.top = newTop + 'px';
            chatWindow.style.right = 'auto';
            chatWindow.style.bottom = 'auto';
        });
        
        document.addEventListener('mouseup', () => {
            isDragging = false;
        });
    }
    
    // Auto-focus input when chat opens
    chatToggle.addEventListener('click', function() {
        if (isOpen) {
            setTimeout(() => {
                chatInput.focus();
                initChatResize();
            }, 100);
        }
    });
    
    // Initialize resize on first open
    let resizeInitialized = false;
    chatToggle.addEventListener('click', function() {
        if (isOpen && !resizeInitialized) {
            setTimeout(() => {
                initChatResize();
                resizeInitialized = true;
            }, 200);
        }
    });
    
    // Enter to send, Shift+Enter for new line
    chatInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });
});
</script>


