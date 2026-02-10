document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('chat-form');
    const input = document.getElementById('message-input');
    const list = document.getElementById('message-list');
    const history = document.getElementById('chat-history');

    if (!form) return;

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

            // append message ke UI
            const li = document.createElement('li');
            li.className = 'mb-3';

            li.innerHTML = `
                <div class="message my-message">
                    <img class="rounded-circle float-start chat-user-img img-30 me-2"
                         src="${window.USER_AVATAR || ''}">
                    <div class="message-data text-end">
                        <span class="message-data-time text-muted small">
                            ${new Date().toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})}
                        </span>
                    </div>
                    <div class="message-content">
                        ${escapeHtml(message)}
                    </div>
                </div>
            `;

            list.appendChild(li);
            input.value = '';

            // scroll ke bawah
            history.scrollTop = history.scrollHeight;
        })
        .catch(err => {
            console.error(err);
            alert('Network error');
        });
    });

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.innerText = text;
        return div.innerHTML.replace(/\n/g, '<br>');
    }
});
