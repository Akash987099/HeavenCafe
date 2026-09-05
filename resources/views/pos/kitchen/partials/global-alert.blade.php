<script>
(() => {
    let knownOrderIds = [], initialized = false, queuedOrders = [], activeOrder, audioContext, toneTimer, speechTimer;
    const stopTone = () => { clearInterval(toneTimer); clearInterval(speechTimer); toneTimer = null; speechTimer = null; window.speechSynthesis?.cancel(); navigator.vibrate?.(0); };
    const beep = () => {
        if (!audioContext) return;
        const oscillator = audioContext.createOscillator(), gain = audioContext.createGain();
        oscillator.type = 'square';
        oscillator.frequency.value = 1180;
        gain.gain.setValueAtTime(.9, audioContext.currentTime);
        gain.gain.exponentialRampToValueAtTime(.001, audioContext.currentTime + .55);
        oscillator.connect(gain); gain.connect(audioContext.destination);
        oscillator.start(); oscillator.stop(audioContext.currentTime + .55);
    };
    const announce = () => {
        if (!activeOrder || !window.speechSynthesis) return;
        window.speechSynthesis.cancel();
        const message = new SpeechSynthesisUtterance('New kitchen order. Order number ' + activeOrder.order_number);
        message.volume = 1; message.rate = .85; message.pitch = 1.2;
        window.speechSynthesis.speak(message);
    };
    const startTone = () => {
        stopTone();
        try {
            audioContext = audioContext || new (window.AudioContext || window.webkitAudioContext)();
            audioContext.resume().then(() => { beep(); toneTimer = setInterval(beep, 1000); }).catch(() => {});
        } catch (error) {}
        announce();
        speechTimer = setInterval(announce, 7000);
        navigator.vibrate?.([500, 200, 500, 200, 500]);
    };
    const showNextPopup = () => {
        if (activeOrder || !queuedOrders.length) return;
        activeOrder = queuedOrders.shift();
        const popup = document.createElement('div');
        popup.className = 'fixed inset-0 z-[10000] flex items-center justify-center bg-black/50 p-4';
        popup.innerHTML = '<div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl"><div class="bg-orange-500 p-5 text-white"><div class="flex items-center gap-3"><i class="fas fa-bell text-xl animate-pulse"></i><div><p class="text-xs font-semibold uppercase">New Kitchen Order</p><h2 class="text-xl font-bold">' + activeOrder.order_number + '</h2></div></div></div><div class="p-5"><p class="text-sm text-slate-600">A new confirmed order has arrived.</p><a href="{{ route('pos.kitchen.orders') }}/' + activeOrder.id + '" class="mt-5 block w-full rounded-xl bg-slate-800 py-3 text-center text-sm font-bold text-white">View Order</a></div></div>';
        popup.querySelector('a').addEventListener('click', stopTone);
        document.body.appendChild(popup);
        startTone();
    };
    const poll = () => fetch('{{ route('pos.kitchen.alerts') }}', {headers: {Accept: 'application/json'}})
        .then(response => response.ok ? response.json() : null)
        .then(data => {
            if (!data) return;
            const ids = data.orders.map(order => Number(order.id));
            if (initialized) {
                queuedOrders.push(...data.orders.filter(order => !knownOrderIds.includes(Number(order.id))).reverse());
                showNextPopup();
            }
            knownOrderIds = ids;
            initialized = true;
        }).catch(() => {});
    poll();
    setInterval(poll, 5000);
})();
</script>
