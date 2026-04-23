let schedule = [];
let isRunning = false;

// Base64 silent audio to keep the tab alive
const silentAudio = "data:audio/wav;base64,UklGRigAAABXQVZFZm10IBAAAAABAAEARKwAAIhYAQACABAAZGF0YQAAAAA=";

function updateClock() {
    const now = new Date();
    
    // Update digital clock
    const h = String(now.getHours()).padStart(2, '0');
    const m = String(now.getMinutes()).padStart(2, '0');
    const s = String(now.getSeconds()).padStart(2, '0');
    document.getElementById('digital-clock').innerText = `${h}:${m}:${s}`;
    
    // Update date display
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('date-display').innerText = now.toLocaleDateString('id-ID', options);
    
    // 1. Stale Data Fix: Refresh schedule at midnight (00:00:01)
    if (h === '00' && m === '00' && s === '01') {
        console.log("Pergantian hari terdeteksi. Memperbarui jadwal...");
        fetchSchedule();
    }

    // Check schedule every minute (at 00 seconds)
    if (isRunning && s === '00') {
        checkSchedule(h, m);
    }
}

async function fetchSchedule() {
    try {
        const res = await fetch('api/get_jadwal.php');
        const data = await res.json();
        schedule = data;
        renderSchedule();
    } catch (e) {
        console.error("Gagal memuat jadwal:", e);
        document.getElementById('schedule-list').innerHTML = '<li>Gagal memuat jadwal. Pastikan MySQL aktif.</li>';
    }
}

function renderSchedule() {
    const list = document.getElementById('schedule-list');
    list.innerHTML = schedule.length ? '' : '<li>Tidak ada jadwal aktif untuk hari ini.</li>';
    schedule.forEach(item => {
        const timeFormatted = item.waktu.substring(0, 5); // Take HH:mm
        list.innerHTML += `
            <li>
                <span>${item.kegiatan}</span>
                <strong>${timeFormatted}</strong>
            </li>
        `;
    });
}

function checkSchedule(h, m) {
    const timeNow = `${h}:${m}:00`;
    schedule.forEach(item => {
        // Compare HH:mm:ss
        if (item.waktu === timeNow && item.status === 'aktif') {
            playBell(item.file_path, item.kegiatan);
        }
    });
}

function playBell(path, kegiatan) {
    const statusText = document.getElementById('status-text');
    
    if (!path) {
        console.error("Gagal memutar bel: Path kosong.");
        statusText.innerHTML = `<span style="color: #f87171;">⚠️ Gagal memutar bel "${kegiatan}": File tidak ditemukan!</span>`;
        return;
    }

    console.log("Memutar bel:", path);
    const audio = new Audio(path);
    audio.play().then(() => {
        statusText.innerHTML = `<span style="color: #60a5fa;">🔔 Sedang memutar: ${kegiatan}</span>`;
        // Reset status after 10 seconds
        setTimeout(() => {
            if (isRunning) statusText.innerHTML = '<span style="color: #4ade80;">● Bel Otomatis Sedang Aktif</span>';
        }, 10000);
    }).catch(e => {
        console.error("Gagal memutar audio. Interaksi user diperlukan.", e);
        statusText.innerHTML = `<span style="color: #f87171;">⚠️ Error Autoplay: Klik 'Mulai Bel' lagi.</span>`;
    });
}

// 2. Tab Sleeping Preventer: Heartbeat silent audio every 15 minutes
function keepAlive() {
    if (isRunning) {
        console.log("Heartbeat: Mencegah browser menonaktifkan tab...");
        const audio = new Audio(silentAudio);
        audio.volume = 0.01;
        audio.play().catch(() => {});
    }
}

document.getElementById('start-btn').onclick = () => {
    isRunning = true;
    document.getElementById('status-text').innerHTML = '<span style="color: #4ade80;">● Bel Otomatis Sedang Aktif</span>';
    document.getElementById('start-btn').style.display = 'none';
    
    // Enable audio context by playing a silent sound
    const audio = new Audio(silentAudio);
    audio.play().catch(() => {});
    
    console.log("Sistem Bel Dimulai...");
};

// Initial calls
setInterval(updateClock, 1000);
setInterval(keepAlive, 15 * 60 * 1000); // Heartbeat every 15 mins
fetchSchedule();
updateClock();
