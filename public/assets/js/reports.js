// report.js
document.addEventListener("DOMContentLoaded", () => {
  const chartCanvas = document.getElementById("myGraph");
  const exportBtn   = document.getElementById("btnExportPdf");
  const chartInput  = document.getElementById("chartImage");
  const exportForm  = document.getElementById("exportPdfForm");
  const filterForm  = document.getElementById("filterForm");

  // 1. Inisialisasi Chart
  if (chartCanvas && window.chartData && window.chartData.length) {
    const labels = window.chartData.map(item => item.order_date);
    const values = window.chartData.map(item => item.total_sales);

    window.myChart = new Chart(chartCanvas, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Total Sales',
          data: values,
          fill: true,
          tension: 0.4,
          backgroundColor: 'rgba(54, 162, 235, 0.2)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 2,
          pointRadius: 3
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: true } },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: value => 'Rp ' + value.toLocaleString('id-ID')
            }
          }
        }
      }
    });
  }

  // 2. Export PDF dengan Chart
  if (exportBtn && exportForm && chartInput) {
    exportBtn.addEventListener("click", () => {
      if (!chartCanvas) {
        alert("Chart belum siap");
        return;
      }
      chartInput.value = chartCanvas.toDataURL("image/png");
      exportForm.submit();
    });
  }

  // 3. Filter form submit (opsional untuk fetch via AJAX)
  if (filterForm) {
    filterForm.addEventListener("submit", e => {
      // kalau mau pakai AJAX:
      // e.preventDefault();
      // fetchData(filterForm)
      // else biar submit biasa, jangan di prevent
    });
  }
});
