document.addEventListener('DOMContentLoaded', () => {
  const data = window.__DASHBOARD_DATA__ || {};
  const names = {
    services: 'الخدمات',
    projects: 'المشاريع',
    gallery: 'المعرض',
    blog: 'المدونة',
    testimonials: 'آراء العملاء',
    skills: 'المهارات',
  };
  const keys = Object.keys(names).filter((k) => data.counts && k in data.counts);
  const labels = keys.map((k) => names[k]);
  const counts = keys.map((k) => data.counts[k]);
  const percentages = keys.map((k) => (data.percentages && k in data.percentages ? data.percentages[k] : 0));
  const pieEl = document.getElementById('chart-pie');
  const barEl = document.getElementById('chart-bar');
  if (window.Chart && pieEl && labels.length) {
    new Chart(pieEl.getContext('2d'), {
      type: 'pie',
      data: {
        labels,
        datasets: [
          {
            data: percentages,
            backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6610f2', '#20c997'],
          },
        ],
      },
    });
  }
  if (window.Chart && barEl && labels.length) {
    new Chart(barEl.getContext('2d'), {
      type: 'bar',
      data: {
        labels,
        datasets: [
          {
            label: 'عدد العناصر',
            data: counts,
            backgroundColor: '#0d6efd',
          },
        ],
      },
      options: {
        responsive: true,
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
      },
    });
  }
  const loginAlert = document.getElementById('login-alert');
  if (loginAlert && window.bootstrap) {
    const alert = new bootstrap.Alert(loginAlert);
    setTimeout(() => {
      try {
        alert.close();
      } catch {}
    }, 4000);
  }
  const filter = document.getElementById('message-filter');
  const list = document.getElementById('messages-list');
  if (filter && list) {
    filter.addEventListener('change', () => {
      const val = filter.value;
      list.querySelectorAll('li').forEach((li) => {
        const s = li.getAttribute('data-subject') || '';
        li.style.display = val === 'all' || s === val ? '' : 'none';
      });
    });
  }
});
