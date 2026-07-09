@extends('layouts.app')

@section('page_title', 'Laporan Keuangan')

@section('content')
<div class="card" style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3 style="font-size: 1.25rem;">Laporan Tahunan</h3>
        
        <select id="yearSelector" class="form-control" style="width: auto; padding: 0.4rem 2rem 0.4rem 1rem;" onchange="loadChartData()">
            @for($i = date('Y'); $i >= 2020; $i--)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
    </div>

    <div style="position: relative; height: 400px; width: 100%;">
        <canvas id="financeChart"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let financeChart = null;

    async function loadChartData() {
        const year = document.getElementById('yearSelector').value;
        try {
            const response = await fetch(`{{ route('reports.data') }}?year=${year}`);
            const data = await response.json();
            
            updateChart(data);
        } catch (error) {
            console.error('Error fetching chart data:', error);
        }
    }

    function updateChart(data) {
        const ctx = document.getElementById('financeChart').getContext('2d');
        
        if (financeChart) {
            financeChart.destroy();
        }

        financeChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: data.income,
                        backgroundColor: 'rgba(16, 185, 129, 0.8)', // secondary-color
                        borderRadius: 4,
                    },
                    {
                        label: 'Pengeluaran',
                        data: data.expense,
                        backgroundColor: 'rgba(239, 68, 68, 0.8)', // danger-color
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
                                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumSignificantDigits: 3 }).format(value);
                            }
                        }
                    }
                }
            }
        });
    }

    // Load initial data
    document.addEventListener('DOMContentLoaded', loadChartData);
</script>
@endsection
