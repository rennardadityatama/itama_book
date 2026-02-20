document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('chat-form');
    const input = document.getElementById('message-input');
    const list = document.getElementById('message-list');
    const history = document.getElementById('chat-history');

    if (!form || !CURRENT_ROOM_ID) return;

    /* ===============================
       TRACK LAST MESSAGE ID
    =============================== */
    let lastMessageId = 0;

    document.querySelectorAll('#message-list li').forEach(li => {
        const id = parseInt(li.dataset.id);
        if (id > lastMessageId) lastMessageId = id;
    });

    function scrollToBottom() {
        setTimeout(() => {
            history.scrollTo({
                top: history.scrollHeight,
                behavior: 'smooth'
            });
        }, 50);
    }

    scrollToBottom();

    /* ===============================
       SEND MESSAGE
    =============================== */
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const message = input.value.trim();
        if (!message) return;

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

            lastMessageId = res.data.message_id;

            appendMessage({
                id: res.data.message_id,
                sender_id: CURRENT_USER_ID,
                message: message,
                created_at: new Date().toISOString(),
                sender_avatar: window.USER_AVATAR || null
            });

            input.value = '';
        })
        .catch(err => {
            console.error(err);
            alert('Network error');
        });
    });

    /* ===============================
       REALTIME RECEIVE (POLLING)
    =============================== */
    function fetchNewMessages() {
        fetch(`${CHAT_BASE_URL}&m=fetchNewMessages&room_id=${CURRENT_ROOM_ID}&last_id=${lastMessageId}`)
            .then(res => res.json())
            .then(res => {
                if (res.status !== 'success') return;

                res.data.forEach(msg => {
                    lastMessageId = msg.id;
                    appendMessage(msg);
                });
            });
    }

    setInterval(fetchNewMessages, 2000);

    /* ===============================
       APPEND MESSAGE UI
    =============================== */
    function appendMessage(msg) {
        const isMe = msg.sender_id == CURRENT_USER_ID;

        const li = document.createElement('li');
        li.className = 'mb-3 clearfix';
        li.dataset.id = msg.id;

        li.innerHTML = `
        <div class="message d-inline-block ${isMe ? 'my-message float-end text-end' : 'other-message float-start'}">
            <div class="d-flex ${isMe ? 'flex-row-reverse' : 'flex-row'} align-items-start">
                <img class="rounded-circle chat-user-img img-30 mx-2"
                    src="${msg.sender_avatar ? BASE_URL + '/uploads/avatars/' + msg.sender_avatar : BASE_URL + '/assets/images/default-avatar.png'}">
                <div>
                    <div class="message-content">${escapeHtml(msg.message)}</div>
                    <div class="message-data-time text-muted small mt-1 ${isMe ? 'text-end' : ''}">
                        ${new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                    </div>
                </div>
            </div>
        </div>
        `;

        list.appendChild(li);
        scrollToBottom();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.innerText = text;
        return div.innerHTML.replace(/\n/g, '<br>');
    }

});