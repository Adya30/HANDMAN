function fetchRealTimeIndex(tableContainerId, fetchUrl) {
    const container = document.getElementById(tableContainerId);
    if (!container) return;

    fetch(fetchUrl, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.text();
    })
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newContent = doc.getElementById(tableContainerId);

        if (newContent) {
            container.innerHTML = newContent.innerHTML;
        }
    })
    .catch(error => {
        console.error('Error fetching real-time data:', error);
    });
}

function initDeleteRowAjax(tableContainerId, fetchUrl) {
    document.addEventListener('submit', function (e) {
        const form = e.target;

        if (form && form.action && form.querySelector('input[name="_method"]')?.value === 'DELETE') {
            e.preventDefault();

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    fetchRealTimeIndex(tableContainerId, fetchUrl);
                } else {
                    alert('Gagal menghapus data. Silakan coba lagi.');
                }
            })
            .catch(error => {
                console.error('Error deleting data:', error);
            });
        }
    });
}

window.fetchRealTimeIndex = fetchRealTimeIndex;
window.initDeleteRowAjax = initDeleteRowAjax;
