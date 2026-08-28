@extends('layout.app')

@section('content')
@php
    $formatInr = function ($amount) {
        return 'INR ' . number_format((float) $amount, 2);
    };
@endphp

<style>
  .dashboard-page {
      background: #f8fafc;
      min-height: 100vh;
  }
  .dashboard-card {
      border: 1px solid #e5e7eb;
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
  }
  .dashboard-stat {
      height: 100%;
  }
  .dashboard-stat .stat-label {
      font-size: 0.75rem;
      font-weight: 700;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      color: #64748b;
      margin-bottom: 0.4rem;
  }
  .dashboard-stat .stat-value {
      font-size: 1.75rem;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 0.35rem;
      line-height: 1.2;
  }
  .dashboard-stat .stat-meta {
      color: #64748b;
      font-size: 0.9rem;
      margin-bottom: 0;
  }
  .dashboard-kpi-icon {
      width: 46px;
      height: 46px;
      border-radius: 12px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 1rem;
  }
  .summary-grid {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 1rem;
  }
  .summary-item {
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      padding: 1rem;
      background: #fff;
  }
  .summary-item .label {
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: #64748b;
      margin-bottom: 0.35rem;
  }
  .summary-item .value {
      font-size: 1.1rem;
      font-weight: 700;
      color: #111827;
      margin-bottom: 0.2rem;
  }
  .summary-item .meta {
      font-size: 0.85rem;
      color: #6b7280;
      margin: 0;
  }
  .chart-wrap {
      position: relative;
      height: 320px;
  }
  .insight-list {
      display: grid;
      gap: 0.85rem;
  }
  .insight-row {
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      padding: 0.9rem 1rem;
      background: #fff;
  }
  .insight-row .title {
      font-size: 0.82rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: #64748b;
      font-weight: 700;
      margin-bottom: 0.3rem;
  }
  .insight-row .value {
      font-size: 1.1rem;
      color: #111827;
      font-weight: 700;
      margin-bottom: 0.15rem;
  }
  .insight-row .meta {
      font-size: 0.88rem;
      color: #6b7280;
      margin: 0;
  }
  .table thead th {
      font-size: 0.72rem;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: #64748b;
      border-bottom-width: 1px;
  }
  .product-thumb {
      width: 42px;
      height: 42px;
      border-radius: 10px;
      object-fit: cover;
  }
  .badge-soft-success {
      background: #dcfce7;
      color: #166534;
  }
  .badge-soft-warning {
      background: #fef3c7;
      color: #92400e;
  }
  .badge-soft-danger {
      background: #fee2e2;
      color: #991b1b;
  }
  .dashboard-breakdown-card .card-header {
      display: block;
  }
  .dashboard-breakdown-card .card-header h5,
  .dashboard-breakdown-card .card-header p {
      display: block;
  }
  .dashboard-breakdown-card .table-responsive {
      border-top: 1px solid #e5e7eb;
  }
  @media (max-width: 1199px) {
      .summary-grid {
          grid-template-columns: repeat(2, minmax(0, 1fr));
      }
  }
  @media (max-width: 767px) {
      .summary-grid {
          grid-template-columns: 1fr;
      }
      .chart-wrap {
          height: 260px;
      }
  }
</style>

<div class="container-fluid py-4 dashboard-page">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">Dashboard</h4>
            <p class="text-sm text-secondary mb-0">Business overview for {{ $periodLabel ?? '' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('run.cron') }}" class="btn btn-primary btn-sm mb-0">Run Cron</a>
            <a href="{{ route('wallets') }}" class="btn btn-outline-primary btn-sm mb-0">Wallet Records</a>
            <a href="{{ route('stocks') }}" class="btn btn-outline-danger btn-sm mb-0">Stock Review</a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card dashboard-stat">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="stat-label">Total Revenue</div>
                            <div class="stat-value">{{ $formatInr($totalSales ?? 0) }}</div>
                            <p class="stat-meta">Weekly revenue: {{ $formatInr($weeklySales ?? 0) }}</p>
                        </div>
                        <span class="dashboard-kpi-icon bg-gradient-success">
                            <i class="ni ni-chart-bar-32"></i>
                        </span>
                    </div>
                    <p class="text-sm text-secondary mb-0">Today: {{ $formatInr($todaySales ?? 0) }}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card dashboard-stat">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="stat-label">Orders</div>
                            <div class="stat-value">{{ $totalOrders ?? 0 }}</div>
                            <p class="stat-meta">{{ $weeklyOrders ?? 0 }} orders in the last 7 days</p>
                        </div>
                        <span class="dashboard-kpi-icon bg-gradient-info">
                            <i class="ni ni-cart"></i>
                        </span>
                    </div>
                    <p class="text-sm text-secondary mb-0">Today: {{ $todayOrders ?? 0 }} | Avg/day: {{ $averageDailyOrders ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card dashboard-stat">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="stat-label">Customers</div>
                            <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
                            <p class="stat-meta">{{ $weeklyUsers ?? 0 }} new users in the last 7 days</p>
                        </div>
                        <span class="dashboard-kpi-icon bg-gradient-primary">
                            <i class="ni ni-single-02"></i>
                        </span>
                    </div>
                    <p class="text-sm text-secondary mb-0">Today: {{ $todayUsers ?? 0 }} new registrations</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card dashboard-stat">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="stat-label">Inventory Health</div>
                            <div class="stat-value">{{ $stockHealth ?? 0 }}%</div>
                            <p class="stat-meta">{{ $outOfStockProducts ?? 0 }} products need attention</p>
                        </div>
                        <span class="dashboard-kpi-icon bg-gradient-danger">
                            <i class="ni ni-box-2"></i>
                        </span>
                    </div>
                    <p class="text-sm text-secondary mb-0">{{ $totalProducts ?? 0 }} products across {{ $totalCategories ?? 0 }} categories</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card dashboard-card mb-4">
        <div class="card-body p-4">
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="label">Average Order Value</div>
                    <div class="value">{{ $formatInr($averageOrderValue ?? 0) }}</div>
                    <p class="meta">Based on the last 7 days</p>
                </div>
                <div class="summary-item">
                    <div class="label">Peak Sales Day</div>
                    <div class="value">{{ $peakSalesDay['date'] ?? 'N/A' }}</div>
                    <p class="meta">{{ $formatInr($peakSalesDay['sales'] ?? 0) }}</p>
                </div>
                <div class="summary-item">
                    <div class="label">Peak Orders Day</div>
                    <div class="value">{{ $peakOrdersDay['date'] ?? 'N/A' }}</div>
                    <p class="meta">{{ $peakOrdersDay['orders'] ?? 0 }} orders</p>
                </div>
                <div class="summary-item">
                    <div class="label">Wallet Processing</div>
                    <div class="value">{{ $walletProcessingRate ?? 0 }}%</div>
                    <p class="meta">{{ $processedWallets ?? 0 }} processed / {{ $pendingWallets ?? 0 }} pending</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div>
                            <h5 class="mb-1">Revenue Trend</h5>
                            <p class="text-sm text-secondary mb-0">Daily sales for the last 7 days</p>
                        </div>
                        <div class="text-end">
                            <h6 class="mb-1">{{ $formatInr($weeklySales ?? 0) }}</h6>
                            <p class="text-sm text-secondary mb-0">Average per day: {{ $formatInr($averageDailySales ?? 0) }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="chart-wrap">
                        <canvas id="chart-line"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h5 class="mb-1">Operational Summary</h5>
                    <p class="text-sm text-secondary mb-0">Key supporting metrics</p>
                </div>
                <div class="card-body p-4">
                    <div class="insight-list">
                        <div class="insight-row">
                            <div class="title">Wallet Points</div>
                            <div class="value">{{ number_format($totalWalletPoints ?? 0, 2) }}</div>
                            <p class="meta">Total points issued</p>
                        </div>
                        <div class="insight-row">
                            <div class="title">Weekly Sales Share</div>
                            <div class="value">{{ $weeklySalesShare ?? 0 }}%</div>
                            <p class="meta">Share of total revenue contributed this week</p>
                        </div>
                        <div class="insight-row">
                            <div class="title">Catalog Depth</div>
                            <div class="value">{{ $totalSubCategories ?? 0 }} subcategories</div>
                            <p class="meta">{{ $totalBrands ?? 0 }} active brands</p>
                        </div>
                        <div class="insight-row">
                            <div class="title">Orders Today</div>
                            <div class="value">{{ $todayOrders ?? 0 }}</div>
                            <p class="meta">{{ $formatInr($todaySales ?? 0) }} in sales today</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-5 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div>
                            <h5 class="mb-1">Orders Trend</h5>
                            <p class="text-sm text-secondary mb-0">Daily order count for the last 7 days</p>
                        </div>
                        <div class="text-sm text-secondary">Range: {{ $periodLabel ?? '' }}</div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="chart-wrap" style="height: 280px;">
                        <canvas id="chart-bars"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-7 mb-4">
            <div class="card dashboard-card h-100 dashboard-breakdown-card">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h5 class="mb-1">Daily Breakdown</h5>
                    <p class="text-sm text-secondary mb-0">Users, orders, and revenue by day</p>
                </div>
                <div class="card-body p-0 pt-3">
                    <div class="table-responsive" data-disable-table-search="true">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Date</th>
                                    <th class="text-center">Users</th>
                                    <th class="text-center">Orders</th>
                                    <th class="text-end pe-4">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salesTableDetails ?? [] as $detail)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="py-2">
                                                <div class="fw-bold text-dark">{{ $detail['date'] }}</div>
                                                <div class="text-xs text-secondary">{{ $detail['full_date'] }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="py-2">
                                                @forelse($detail['customer_entries'] ?? [] as $customer)
                                                    <div class="mb-2">
                                                        <div class="fw-bold text-dark text-sm">{{ $customer['name'] }}</div>
                                                        <div class="text-xs text-secondary">{{ $customer['email'] }}</div>
                                                    </div>
                                                @empty
                                                    <div class="text-sm text-secondary">No customer details</div>
                                                @endforelse
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $detail['orders'] }}</td>
                                        <td class="text-end pe-4">{{ $formatInr($detail['sales']) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-secondary">No data available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="mb-1">Recent Wallet Activity</h5>
                        <p class="text-sm text-secondary mb-0">Latest wallet processing records</p>
                    </div>
                    <a href="{{ route('wallets') }}" class="btn btn-outline-primary btn-sm mb-0">View All</a>
                </div>
                <div class="card-body p-0 pt-3">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>User ID</th>
                                    <th>Order ID</th>
                                    <th>Points</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($wallets as $key => $item)
                                    <tr>
                                        <td class="ps-4">{{ $key + 1 }}</td>
                                        <td>{{ $item->user_id }}</td>
                                        <td>{{ $item->order_id }}</td>
                                        <td>{{ number_format($item->points, 2) }}</td>
                                        <td>
                                            @if($item->is_processed == 1)
                                                <span class="badge badge-soft-success">Processed</span>
                                            @else
                                                <span class="badge badge-soft-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">{{ $item->created_at }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-secondary">No wallet records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="mb-1">Low Stock Products</h5>
                        <p class="text-sm text-secondary mb-0">Products with the lowest available stock</p>
                    </div>
                    <a href="{{ route('stocks') }}" class="btn btn-outline-danger btn-sm mb-0">View All</a>
                </div>
                <div class="card-body p-0 pt-3">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Product</th>
                                    <th>SKU</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Stock</th>
                                    <th class="text-center pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productStocks ?? [] as $product)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center py-2">
                                                @php
                                                    $productImage = !empty($product->image)
                                                        ? (filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : asset(ltrim($product->image, '/')))
                                                        : "data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='42' height='42' viewBox='0 0 42 42'%3E%3Crect width='42' height='42' rx='10' fill='%23e5e7eb'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='Arial' font-size='12' fill='%236b7280'%3EN%2FA%3C/text%3E%3C/svg%3E";
                                                @endphp
                                                <img src="{{ $productImage }}" alt="{{ $product->name }}" class="product-thumb me-3" onerror="this.onerror=null;this.src=`data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='42' height='42' viewBox='0 0 42 42'%3E%3Crect width='42' height='42' rx='10' fill='%23e5e7eb'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='Arial' font-size='12' fill='%236b7280'%3EN%2FA%3C/text%3E%3C/svg%3E`;">
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $product->name }}</div>
                                                    <div class="text-xs text-secondary">Inventory item</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $product->sku_code }}</td>
                                        <td class="text-center">{{ $formatInr($product->price) }}</td>
                                        <td class="text-center">{{ $product->stock }}</td>
                                        <td class="text-center pe-4">
                                            @if($product->in_stock == 1 && $product->stock > 0)
                                                <span class="badge badge-soft-success">In Stock</span>
                                            @elseif($product->stock > 0)
                                                <span class="badge badge-soft-warning">Limited</span>
                                            @else
                                                <span class="badge badge-soft-danger">Out of Stock</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-secondary">No product records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card dashboard-card">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div>
                            <h5 class="mb-1">Online Users</h5>
                            <p class="text-sm text-secondary mb-0">Users recently active through authenticated API requests</p>
                        </div>
                        <span class="badge bg-gradient-success">{{ count($onlineUsers ?? []) }} Active</span>
                    </div>
                </div>
                <div class="card-body p-0 pt-3">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">User</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Last Seen</th>
                                    <th class="text-end pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($onlineUsers ?? [] as $onlineUser)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="py-2">
                                                <div class="fw-bold text-dark">{{ $onlineUser['name'] ?: 'User #' . $onlineUser['id'] }}</div>
                                                <div class="text-xs text-secondary">User ID: {{ $onlineUser['id'] }}</div>
                                            </div>
                                        </td>
                                        <td>{{ $onlineUser['email'] ?: 'N/A' }}</td>
                                        <td>{{ $onlineUser['phone'] ?: 'N/A' }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $onlineUser['last_seen_at'] ?? 'N/A' }}</div>
                                            <div class="text-xs text-secondary">{{ $onlineUser['last_seen_label'] ?? '' }}</div>
                                        </td>
                                        <td class="text-end pe-4">
                                            <span class="badge badge-soft-success">Online</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-secondary">No active API users found right now.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
      const salesDetails = {!! json_encode($salesDetails ?? []) !!};
      const inrFormatter = new Intl.NumberFormat('en-IN', {
          style: 'currency',
          currency: 'INR',
          maximumFractionDigits: 2
      });

      const lineCtx = document.getElementById('chart-line').getContext('2d');
      const lineGradient = lineCtx.createLinearGradient(0, 0, 0, 320);
      lineGradient.addColorStop(0, 'rgba(37, 99, 235, 0.22)');
      lineGradient.addColorStop(1, 'rgba(37, 99, 235, 0.02)');

      new Chart(lineCtx, {
          type: 'line',
          data: {
              labels: {!! json_encode($salesLabels ?? []) !!},
              datasets: [{
                  label: 'Revenue',
                  data: {!! json_encode($salesValues ?? []) !!},
                  borderColor: '#2563eb',
                  backgroundColor: lineGradient,
                  fill: true,
                  tension: 0.35,
                  borderWidth: 3,
                  pointRadius: 4,
                  pointHoverRadius: 6,
                  pointBackgroundColor: '#2563eb',
                  pointBorderColor: '#ffffff',
                  pointBorderWidth: 2
              }]
          },
          options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                  legend: { display: false },
                  tooltip: {
                      callbacks: {
                          label: function(context) {
                              return 'Revenue: ' + inrFormatter.format(context.raw || 0);
                          },
                          afterLabel: function(context) {
                              const detail = salesDetails[context.dataIndex] || {};
                              return [
                                  'Orders: ' + (detail.orders || 0),
                                  'Users: ' + (detail.users || 0)
                              ];
                          }
                      }
                  }
              },
              scales: {
                  y: {
                      beginAtZero: true,
                      ticks: {
                          color: '#64748b',
                          callback: function(value) {
                              return inrFormatter.format(value);
                          }
                      },
                      grid: {
                          color: 'rgba(148, 163, 184, 0.15)',
                          drawBorder: false
                      }
                  },
                  x: {
                      ticks: { color: '#64748b' },
                      grid: { display: false }
                  }
              }
          }
      });

      const barCtx = document.getElementById('chart-bars').getContext('2d');
      new Chart(barCtx, {
          type: 'bar',
          data: {
              labels: {!! json_encode($ordersLabels ?? []) !!},
              datasets: [{
                  label: 'Orders',
                  data: {!! json_encode($ordersValues ?? []) !!},
                  backgroundColor: '#0ea5e9',
                  borderRadius: 8,
                  borderSkipped: false,
                  maxBarThickness: 30
              }]
          },
          options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                  legend: { display: false },
                  tooltip: {
                      callbacks: {
                          label: function(context) {
                              return 'Orders: ' + context.raw;
                          },
                          afterLabel: function(context) {
                              const detail = salesDetails[context.dataIndex] || {};
                              return 'Revenue: ' + inrFormatter.format(detail.sales || 0);
                          }
                      }
                  }
              },
              scales: {
                  y: {
                      beginAtZero: true,
                      ticks: { color: '#64748b', precision: 0 },
                      grid: {
                          color: 'rgba(148, 163, 184, 0.15)',
                          drawBorder: false
                      }
                  },
                  x: {
                      ticks: { color: '#64748b' },
                      grid: { display: false }
                  }
              }
          }
      });
  });
</script>
@endsection
