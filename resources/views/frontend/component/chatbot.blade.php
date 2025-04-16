<span id="get_route_chatbot" data-url="{{ route('chatbot') }}"> </span>
<div class="container-chatbot  shrink" data-theme="light">
    <header class="header px-4 py-1">
        <div class="header-title d-flex justify-content-between">
            <h2 class="d-flex align-items-center">AI Assistant</h2>
            <div class="controls text-end h-80  my-auto ">
                <button class="theme-toggle" aria-label="Toggle theme">
                    <i class="fas fa-moon"></i>
                </button>
                <button class="theme-toggle-hide ms-3">
                    <i class="bi bi-chevron-down d-block"></i>
                </button>
            </div>
        </div>
    </header>

    <div class="chat-container h-75" id="chatContainer-parent">
        <div id="chatContainer">
            <!-- Messages will be added here -->

        </div>

        <div class="typing-indicator">
            <div class="typing-dots">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        </div>
    </div>


    <div class="input-container">
        <div class="input-wrapper">
            <input type="text" class="message-input" placeholder="Type your message..." aria-label="Message input">
            <div class="action-buttons">
                <button class="send-button">
                    <span>Send</span>
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</div>
<a class="btn btn-primary btn-primary-outline-0 btn-md-square ai-chatbot"><i class="fa-solid fa-comments" style="font-size: 1.5rem"></i></a>
