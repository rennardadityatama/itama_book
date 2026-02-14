document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('chat-form');
    const input = document.getElementById('message-input');
    const list = document.getElementById('message-list');
    const history = document.getElementById('chat-history');

    if (!form || !CURRENT_ROOM_ID) return;

    /* =========================
       AUTO SCROLL TO BOTTOM
    ========================== */
    function scrollToBottom() {
        history.scrollTop = history.scrollHeight;
    }

    scrollToBottom();


    /* =========================
       SEND MESSAGE (NO RELOAD)
    ========================== */
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const message = input.value.trim();
        if (message === '') return;

        const formData = new FormData(form);

        fetch(CHAT_BASE_URL + '&m=sendMessage', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(res => {

            if (res.status !== 'success') {
                alert(res.message || 'Failed to send message');
                return;
            }

            appendMessage(res.data, true);
            input.value = '';
            scrollToBottom();
        })
        .catch(err => {
            console.error(err);
            alert('Network error');
        });
    });


    /* =========================
       APPEND MESSAGE FUNCTION
    ========================== */
    function appendMessage(data, isMe = false) {

        const li = document.createElement('li');
        li.className = 'mb-3 clearfix';

        li.innerHTML = `
            <div class="message d-inline-block ${isMe ? 'my-message float-end text-end' : 'other-message float-start'}">
                <div class="message-content">
                    ${escapeHtml(data.message)}
                </div>
                <div class="message-data-time text-muted small mt-1">
                    ${formatTime(data.created_at)}
                </div>
            </div>
        `;

        list.appendChild(li);
    }


    /* =========================
       AUTO LOAD NEW MESSAGE
       (POLLING EVERY 3 SEC)
    ========================== */
    let lastMessageId = null;

    function loadNewMessages() {

        fetch(CHAT_BASE_URL + '&m=loadMessages&room_id=' + CURRENT_ROOM_ID)
            .then(res => res.json())
            .then(res => {

                if (res.status !== 'success') return;

                const messages = res.data.messages;

                if (!messages.length) return;

                const newest = messages[messages.length - 1];

                if (lastMessageId === newest.id) return;

                list.innerHTML = '';
                messages.forEach(msg => {
                    appendMessage(msg, msg.sender_id == CURRENT_USER_ID);
                });

                lastMessageId = newest.id;
                scrollToBottom();
            });
    }

    setInterval(loadNewMessages, 10000);


    /* =========================
       HELPERS
    ========================== */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.innerText = text;
        return div.innerHTML.replace(/\n/g, '<br>');
    }

    function formatTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

});
