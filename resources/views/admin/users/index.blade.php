{{-- Admin: Manage Users --}}
@extends('layouts.admin')
@section('title', 'User Management')

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.45.1/dist/apexcharts.min.css" />
    <style>
        .charts-container {
            max-width: 72rem;
            margin: 0 auto 1rem auto;
            display: flex;
            gap: 1rem;
            width: 100%;
        }
        .chart-card {
            background: #1f2937;
            border: 1px solid #374151;
            border-radius: 0.75rem;
            padding: 1rem;
            color: #f9fafb;
        }
        .chart-card h3 {
            margin: 0 0 0.5rem 0;
            font-size: 0.875rem;
            font-weight: 600;
            color: #fff;
        }
        .chart-card .chart-subtitle {
            margin: 0 0 0.75rem 0;
            font-size: 0.75rem;
            color: #6b7280;
        }
        .chart-bar { flex: 2; }
        .chart-pie { flex: 1; }
        .chart-bar .apexcharts-canvas,
        .chart-pie .apexcharts-canvas {
            width: 100% !important;
        }
        .chart-center {
            padding-top: 2rem;
            padding-bottom: 1rem;
            text-align: start;
            max-width: 72rem;
            margin: 0 auto 1rem auto;
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="space-y-6">
        <div class="chart-center">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">User Management</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">All registered users in the learning management system.</p>
                </div>
                <a href="{{ route('admin.users.create') }}" class="flex items-center gap-2 px-4 py-2 bg-primary-700 hover:bg-primary-800 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewbox="0 0 20 20"><path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" /></svg>
                     Add Account
                </a>
            </div>
        </div>

        <div class="charts-container">
            <div class="chart-card chart-bar">
                <h3>Users by Role</h3>
                <p class="chart-subtitle">Total users per role</p>
                <div id="chart-users-role"></div>
            </div>
            <div class="chart-card chart-pie">
                <h3>Users by Status</h3>
                <p class="chart-subtitle">Distribution by status</p>
                <div id="chart-users-status"></div>
            </div>
        </div>

        <x-admin.latest-users-table :users="$users" />
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.1/dist/apexcharts.min.js"></script>
    <script>
        const usersByRoleData = {!! json_encode($usersByRole) !!};
        const usersByStatusData = {!! json_encode($usersByStatus) !!};

        document.addEventListener('DOMContentLoaded', function() {
            // Chart 1: Users by Role (Bar chart)
            const roleLabels = usersByRoleData.map(x => x.role);
            const roleCounts = usersByRoleData.map(x => x.total);

            new ApexCharts(document.getElementById('chart-users-role'), {
                chart: { type: 'bar', height: 250, fontFamily: 'Instrument Sans', background: 'transparent', toolbar: { show: false } },
                plotOptions: { bar: { borderRadius: 6, columnWidth: '50%' } },
                xaxis: { categories: roleLabels, labels: { style: { colors: '#c4b5fd', fontSize: '11px' } }, axisBorder: { show: false }, axisTicks: { color: '#374151' } },
                yaxis: { labels: { style: { colors: '#c4b5fd', fontSize: '11px' } } },
                colors: ['#8b5cf6'],
                series: [{ name: 'Users', data: roleCounts }],
                legend: { show: false },
                tooltip: { theme: 'dark' },
                responsive: [{ breakpoint: 640, options: { chart: { height: 200 } } }]
            }).render();

            // Chart 2: Users by Status (Pie chart)
            const statusLabels = usersByStatusData.map(x => x.status);
            const statusCounts = usersByStatusData.map(x => x.total);

            new ApexCharts(document.getElementById('chart-users-status'), {
                chart: { type: 'pie', height: 250, fontFamily: 'Instrument Sans', background: 'transparent', toolbar: { show: false } },
                labels: statusLabels,
                series: statusCounts,
                colors: ['#10b981', '#f59e0b', '#ef4444', '#3b82f6', '#8b5cf6'],
                legend: { position: 'bottom', labels: { colors: '#c4b5fd', fontSize: '11px' } },
                noData: { text: 'No status data', align: 'center', style: { color: '#c4b5fd', fontSize: '14px' } },
                responsive: [{ breakpoint: 640, options: { chart: { height: 200 } } }]
            }).render();
        });
    </script>
@endsection
