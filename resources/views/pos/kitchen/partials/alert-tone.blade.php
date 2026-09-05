<script>
(() => {
    const key = 'kitchen-opened-orders-{{ Auth::guard('pos')->id() }}';
    let orderIds = @json($pendingOrderIds).map(Number), context, timer, enabled = false;
    const opened = () => { try { return JSON.parse(localStorage.getItem(key) || '[]').map(Number); } catch { return []; } };
    const unseen = () => orderIds.filter(id => !opened().includes(id));
    const stop = () => { clearInterval(timer); timer = null; };
    const beep = () => { if (!context) return; const o = context.createOscillator(), g = context.createGain(); o.frequency.value = 880; g.gain.setValueAtTime(.12, context.currentTime); g.gain.exponentialRampToValueAtTime(.001, context.currentTime + .35); o.connect(g); g.connect(context.destination); o.start(); o.stop(context.currentTime + .35); };
    const refresh = () => { stop(); if (enabled && unseen().length) { beep(); timer = setInterval(beep, 1800); } };
    document.querySelectorAll('[data-kitchen-order-id]').forEach(link => link.addEventListener('click', () => { const old = opened(), id = Number(link.dataset.kitchenOrderId); if (!old.includes(id)) { old.push(id); localStorage.setItem(key, JSON.stringify(old)); } refresh(); }));
    document.getElementById('enableKitchenTone')?.addEventListener('click', function () { context = new (window.AudioContext || window.webkitAudioContext)(); context.resume(); enabled = true; this.innerHTML = '<i class="fas fa-volume-up"></i> Tone On'; refresh(); });
    setInterval(() => fetch('{{ route('pos.kitchen.alerts') }}', {headers:{Accept:'application/json'}}).then(r => r.ok ? r.json() : null).then(data => { if (!data) return; const oldIds = orderIds; orderIds = data.orders.map(order => Number(order.id)); const newOrder = data.orders.find(order => !oldIds.includes(Number(order.id))); if (newOrder) alert('New order alert: ' + newOrder.order_number); refresh(); }).catch(() => {}), 15000);
})();
</script>
