let schedule = [];
let isRunning = false;

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
            playBell(item.file_path);
        }
    });
}

function playBell(path) {
    if (!path) return;
    console.log("Memutar bel:", path);
    const audio = new Audio(path);
    audio.play().catch(e => {
        console.error("Gagal memutar audio. Interaksi user diperlukan.", e);
        alert("Gagal memutar bel otomatis. Harap klik 'Mulai Bel' lagi.");
    });
}

document.getElementById('start-btn').onclick = () => {
    isRunning = true;
    document.getElementById('status-text').innerHTML = '<span style="color: #4ade80;">● Bel Otomatis Sedang Aktif</span>';
    document.getElementById('start-btn').style.display = 'none';
    
    // Enable audio context by playing a silent sound
    const audio = new Audio();
    audio.play().catch(() => {});
    
    console.log("Sistem Bel Dimulai...");
};

// Initial calls
setInterval(updateClock, 1000);
fetchSchedule();
updateClock();
