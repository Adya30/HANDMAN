import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const cleanMeta = (name) => {
    const meta = document.querySelector(`meta[name="${name}"]`);
    if (meta) {
        const val = meta.getAttribute('content');
        if (typeof val === 'string') {
            return val.replace(/^["']|["']$/g, '').trim();
        }
        return val;
    }
    return null;
};

const currentUserId = cleanMeta('user-id');
const currentUserRole = cleanMeta('user-role');
const currentUserDeptId = cleanMeta('user-departemen-id');

const reverbKey = cleanMeta('reverb-key');
const reverbHost = cleanMeta('reverb-host') || window.location.hostname;
const reverbPort = cleanMeta('reverb-port') || 8080;
const reverbScheme = cleanMeta('reverb-scheme') || 'http';

console.log('Connecting to Reverb:', { reverbKey, reverbHost, reverbPort, reverbScheme });

const echoInstance = new Echo({
    broadcaster: 'reverb',
    key: reverbKey,
    wsHost: reverbHost,
    wsPort: reverbPort,
    wssPort: reverbPort,
    forceTLS: reverbScheme === 'https',
    enabledTransports: ['ws', 'wss'],
});

if (currentUserId) {
    const toastContainer = document.createElement('div');
    toastContainer.className = 'fixed top-4 right-4 z-50 flex flex-col gap-3 max-w-sm w-full pointer-events-none';
    toastContainer.id = 'realtime-toast-container';
    document.body.appendChild(toastContainer);

    const updateAppBody = () => {
        console.log('Updating application body...');
        fetch(window.location.href, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContainer = doc.getElementById('app-body-container');
            const currentContainer = document.getElementById('app-body-container');
            if (newContainer && currentContainer) {
                currentContainer.innerHTML = newContainer.innerHTML;
                console.log('App body updated successfully');
            }
        })
        .catch(error => {
            console.error('Error fetching real-time body update:', error);
        });
    };

    const showToast = (title, message, type) => {
        const toast = document.createElement('div');
        toast.className = 'pointer-events-auto bg-white border border-gray-200 rounded-2xl shadow-xl p-4 transition-all duration-300 transform translate-y-2 opacity-0 flex items-start gap-3';
        
        let iconHtml = '';
        if (type === 'tugas') {
            iconHtml = '<div class="p-2 bg-indigo-50 text-indigo-600 rounded-xl"><i class="fa-solid fa-clipboard-list"></i></div>';
        } else {
            iconHtml = '<div class="p-2 bg-amber-50 text-amber-600 rounded-xl"><i class="fa-solid fa-file-invoice"></i></div>';
        }

        toast.innerHTML = `
            ${iconHtml}
            <div class="flex-1 min-w-0">
                <h4 class="text-sm font-semibold text-gray-900">${title}</h4>
                <p class="text-xs text-gray-500 mt-1">${message}</p>
                <div class="mt-2.5 flex items-center gap-2">
                    <button id="toast-segarkan-btn" class="bg-[#3B28CC] text-white px-2.5 py-1 rounded-lg text-xs font-semibold hover:bg-[#2c1fa3] transition-colors">Segarkan</button>
                    <button onclick="this.closest('.pointer-events-auto').remove()" class="text-gray-400 hover:text-gray-600 text-xs px-2 py-1">Tutup</button>
                </div>
            </div>
        `;

        const segarkanBtn = toast.querySelector('#toast-segarkan-btn');
        if (segarkanBtn) {
            segarkanBtn.addEventListener('click', () => {
                updateAppBody();
                toast.remove();
            });
        }

        toastContainer.appendChild(toast);
        setTimeout(() => {
            toast.classList.remove('translate-y-2', 'opacity-0');
        }, 10);

        setTimeout(() => {
            if (toast.parentNode) {
                toast.classList.add('opacity-0');
                setTimeout(() => toast.remove(), 300);
            }
        }, 10000);
    };

    const isTaskPage = window.location.pathname.includes('/tugas') || window.location.pathname.includes('/staff/tugas');
    const isReportPage = window.location.pathname.includes('/laporan') || window.location.pathname.includes('/admin/laporan') || window.location.pathname.includes('/staff/laporan');
    const isDashboard = window.location.pathname.includes('/dashboard');

    const handleTugasEvent = (e) => {
        console.log('Tugas event received:', e);
        if (currentUserRole === 'staff' && e.action === 'created') {
            showToast(e.title, e.message, 'tugas');
            if (isTaskPage || isDashboard) {
                updateAppBody();
            }
        } else if (currentUserRole === 'manager' && e.action === 'submitted') {
            showToast(e.title, e.message, 'tugas');
            if (isTaskPage || isDashboard) {
                updateAppBody();
            }
        } else if (currentUserRole === 'staff' && e.action === 'reviewed') {
            showToast(e.title, e.message, 'tugas');
            if (isTaskPage || isDashboard) {
                updateAppBody();
            }
        } else if (e.action === 'updated') {
            showToast(e.title, e.message, 'tugas');
            if (isTaskPage || isDashboard) {
                updateAppBody();
            }
        }
    };

    const handleLaporanEvent = (e) => {
        console.log('Laporan event received:', e);
        if (currentUserRole === 'admin' && e.action === 'created') {
            showToast(e.title, e.message, 'laporan');
            if (isReportPage || isDashboard) {
                updateAppBody();
            }
        } else if (e.action === 'responded' && String(e.userId) === String(currentUserId)) {
            showToast(e.title, e.message, 'laporan');
            if (isReportPage || isDashboard) {
                updateAppBody();
            }
        }
    };

    if (currentUserDeptId) {
        console.log('Subscribed to task channel:', `departemen-${currentUserDeptId}`);
        echoInstance.channel(`departemen-${currentUserDeptId}`)
            .listen('.RealtimeTugasEvent', handleTugasEvent)
            .listen('RealtimeTugasEvent', handleTugasEvent)
            .listen('.App\\Events\\RealtimeTugasEvent', handleTugasEvent);
    }

    console.log('Subscribed to laporan channel');
    echoInstance.channel('laporan')
        .listen('.RealtimeLaporanEvent', handleLaporanEvent)
        .listen('RealtimeLaporanEvent', handleLaporanEvent)
        .listen('.App\\Events\\RealtimeLaporanEvent', handleLaporanEvent);
}
