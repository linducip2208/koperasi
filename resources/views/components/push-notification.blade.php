{{-- Push Notification PWA Setup --}}
<script>
if ('serviceWorker' in navigator && 'PushManager' in window) {
    Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
            navigator.serviceWorker.ready.then(reg => {
                reg.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: urlBase64ToUint8Array('{{ config('services.vapid.public_key', '') }}')
                }).then(sub => {
                    fetch('/api/push-subscribe', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify(sub)
                    });
                }).catch(e => console.warn('Push subscribe failed:', e));
            });
        }
    });
}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; i++) { outputArray[i] = rawData.charCodeAt(i); }
    return outputArray;
}
</script>
