<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Product;
use App\Models\PosOrder;
use App\Models\Pos;
use App\Models\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

class AdminController extends Controller
{
    protected $user;
    protected $order;

    public function __construct()
    {
        $this->user = new User();
        $this->order = new Order();
    }

    public function index(Request $request)
    {
        $filters = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'store_id' => ['nullable', 'integer', 'exists:store,id'],
        ]);
        $selectedFrom = $filters['from'] ?? today()->toDateString();
        $selectedTo = $filters['to'] ?? today()->toDateString();
        $selectedStoreId = $filters['store_id'] ?? null;
        $periodStart = Carbon::parse($selectedFrom)->startOfDay();
        $periodEnd = Carbon::parse($selectedTo)->endOfDay();
        $stores = Store::query()->orderBy('name')->get(['id', 'name']);

        $websiteOrders = Order::query()->whereBetween('created_at', [$periodStart, $periodEnd]);
        $posOrders = PosOrder::query()
            ->whereBetween('created_at', [$periodStart, $periodEnd])
            ->when($selectedStoreId, function ($query) use ($selectedStoreId) {
                $query->whereIn('pos_user_id', Pos::query()->where('store_id', $selectedStoreId)->pluck('id'));
            });

        $todayUsers = $this->user->whereBetween('created_at', [$periodStart, $periodEnd])->count();
        $totalUsers = $this->user->count();

        $todayOrders = (clone $websiteOrders)->count();
        $totalOrders = (clone $websiteOrders)->count();
        $todaySales = (float) (clone $websiteOrders)->sum('final_amount');
        $totalSales = (float) (clone $websiteOrders)->sum('final_amount');

        $todayPosOrders = (clone $posOrders)->count();
        $totalPosOrders = (clone $posOrders)->count();
        $pendingPosOrders = (clone $posOrders)->where('status', 'pending')->count();
        $todayPosSales = (float) (clone $posOrders)
            ->where('status', 'completed')
            ->sum('grand_total');
        $totalPosSales = (float) (clone $posOrders)
            ->where('status', 'completed')
            ->sum('grand_total');
        $recentPosOrders = (clone $posOrders)
            ->latest('id')
            ->take(8)
            ->get([
                'id',
                'order_number',
                'customer_name',
                'customer_phone',
                'grand_total',
                'payment_method',
                'payment_status',
                'status',
                'created_at',
            ]);

        // Combined website + POS reporting. Existing individual dashboard data remains unchanged.
        $todayCombinedOrders = $todayOrders + $todayPosOrders;
        $totalCombinedOrders = $totalOrders + $totalPosOrders;
        $todayCombinedSales = $todaySales + $todayPosSales;
        $totalCombinedSales = $totalSales + $totalPosSales;

        $recentWebsiteOrders = (clone $websiteOrders)
            ->latest('id')
            ->take(8)
            ->get([
                'id',
                'order_no',
                'final_amount',
                'payment_method',
                'payment_status',
                'status',
                'created_at',
            ]);
        $recentCombinedOrders = collect($recentWebsiteOrders)
            ->map(fn ($order) => [
                'source' => 'Website',
                'order_number' => $order->order_no ?: 'WEB-' . $order->id,
                'amount' => (float) $order->final_amount,
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'status' => $order->status,
                'created_at' => $order->created_at,
            ])
            ->merge(collect($recentPosOrders)->map(fn ($order) => [
                'source' => 'POS',
                'order_number' => $order->order_number,
                'amount' => (float) $order->grand_total,
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'status' => $order->status,
                'created_at' => $order->created_at,
            ]))
            ->sortByDesc('created_at')
            ->take(10)
            ->values();

        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSubCategories = SubCategory::count();
        $totalBrands = Brand::count();
        $outOfStockProducts = Product::where(function ($query) {
            $query->where('in_stock', 0)
                ->orWhere('stock', '<=', 0);
        })->count();

        $productStocks = Product::select('id', 'name', 'sku_code', 'stock', 'in_stock', 'image', 'price')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $dateRange = collect(range(0, $periodStart->diffInDays($periodEnd)))->map(function ($dayOffset) use ($periodStart) {
            return $periodStart->copy()->addDays($dayOffset);
        });

        $salesData = (clone $websiteOrders)->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(final_amount) as total')
        )
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $ordersData = (clone $websiteOrders)->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $usersData = $this->user->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->whereBetween('created_at', [$periodStart, $periodEnd])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $orderUsersData = Order::query()
            ->leftJoin('users', 'users.id', '=', 'orders.user_id')
            ->select(
                DB::raw('DATE(orders.created_at) as date'),
                DB::raw("GROUP_CONCAT(DISTINCT CONCAT(COALESCE(NULLIF(users.name, ''), 'User'), '||', COALESCE(NULLIF(users.email, ''), 'N/A')) ORDER BY users.id DESC SEPARATOR '##') as customer_list")
            )
            ->whereBetween('orders.created_at', [$periodStart, $periodEnd])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $salesMap = $salesData->keyBy('date');
        $ordersMap = $ordersData->keyBy('date');
        $usersMap = $usersData->keyBy('date');
        $orderUsersMap = $orderUsersData->keyBy('date');

        $salesLabels = [];
        $salesValues = [];
        $ordersLabels = [];
        $ordersValues = [];
        $salesDetails = [];

        foreach ($dateRange as $date) {
            $dateKey = $date->toDateString();
            $label = $date->format('M d');
            $salesValue = (float) optional($salesMap->get($dateKey))->total;
            $ordersValue = (int) optional($ordersMap->get($dateKey))->count;
            $usersValue = (int) optional($usersMap->get($dateKey))->count;
            $customerList = collect(explode('##', (string) optional($orderUsersMap->get($dateKey))->customer_list))
                ->filter()
                ->map(function ($customer) {
                    [$name, $email] = array_pad(explode('||', $customer), 2, 'N/A');

                    return [
                        'name' => $name ?: 'User',
                        'email' => $email ?: 'N/A',
                    ];
                })
                ->values()
                ->all();

            $salesLabels[] = $label;
            $salesValues[] = round($salesValue, 2);
            $ordersLabels[] = $label;
            $ordersValues[] = $ordersValue;
            $salesDetails[] = [
                'date' => $label,
                'full_date' => $date->format('d M Y'),
                'sales' => round($salesValue, 2),
                'orders' => $ordersValue,
                'users' => $usersValue,
                'customer_entries' => $customerList,
                'customer_count' => count($customerList),
            ];
        }

        $weeklyUsers = array_sum(array_column($salesDetails, 'users'));
        $weeklyOrders = array_sum($ordersValues);
        $weeklySales = round(array_sum($salesValues), 2);
        $averageOrderValue = $weeklyOrders > 0 ? round($weeklySales / $weeklyOrders, 2) : 0;
        $averageDailySales = round($weeklySales / max(count($salesDetails), 1), 2);
        $averageDailyOrders = round($weeklyOrders / max(count($salesDetails), 1), 1);

        $peakSalesDay = collect($salesDetails)->sortByDesc('sales')->first();
        $peakOrdersDay = collect($salesDetails)->sortByDesc('orders')->first();
        $maxOrders = max($ordersValues ?: [0]);
        $maxSales = max($salesValues ?: [0]);

        $wallets = \App\Models\Wallet::latest()->take(8)->get();
        $pendingWallets = \App\Models\Wallet::where('is_processed', 0)->count();
        $processedWallets = \App\Models\Wallet::where('is_processed', 1)->count();
        $totalWalletPoints = (float) \App\Models\Wallet::sum('points');
        $stockHealth = $totalProducts > 0 ? round((($totalProducts - $outOfStockProducts) / $totalProducts) * 100) : 0;
        $walletProcessingRate = ($pendingWallets + $processedWallets) > 0
            ? round(($processedWallets / ($pendingWallets + $processedWallets)) * 100)
            : 0;
        $weeklySalesShare = $totalSales > 0 ? round(($weeklySales / $totalSales) * 100) : 0;
        $periodLabel = $periodStart->format('d M') . ' - ' . $periodEnd->format('d M Y');
        $salesTableDetails = array_reverse($salesDetails);

        $onlineUserRegistry = collect(Cache::get('online-users-registry', []))->unique()->values();
        $onlineUsers = $onlineUserRegistry
            ->map(function ($userId) {
                return Cache::get('online-user-' . $userId);
            })
            ->filter(function ($onlineUser) {
                return is_array($onlineUser) && !empty($onlineUser['last_seen_unix']);
            })
            ->sortByDesc('last_seen_unix')
            ->map(function ($onlineUser) {
                $onlineUser['last_seen_label'] = Carbon::createFromTimestamp($onlineUser['last_seen_unix'])->diffForHumans();
                return $onlineUser;
            })
            ->values();

        $dashboardHighlights = [
            [
                'title' => 'New Users',
                'value' => $weeklyUsers,
                'subtitle' => 'Joined in the last 7 days',
                'progress' => $totalUsers > 0 ? min(100, round(($weeklyUsers / $totalUsers) * 100)) : 0,
                'progress_label' => 'of total users',
                'icon' => 'ni ni-single-02',
                'icon_class' => 'bg-gradient-primary',
            ],
            [
                'title' => 'Weekly Orders',
                'value' => $weeklyOrders,
                'subtitle' => 'Orders received in the last 7 days',
                'progress' => $maxOrders > 0 ? min(100, round(($todayOrders / $maxOrders) * 100)) : 0,
                'progress_label' => 'today vs peak day',
                'icon' => 'ni ni-cart',
                'icon_class' => 'bg-gradient-info',
            ],
            [
                'title' => 'Weekly Revenue',
                'value' => 'INR ' . number_format($weeklySales, 2),
                'subtitle' => 'Average order value: INR ' . number_format($averageOrderValue, 2),
                'progress' => $maxSales > 0 ? min(100, round((($salesValues[count($salesValues) - 1] ?? 0) / $maxSales) * 100)) : 0,
                'progress_label' => 'today vs best sales day',
                'icon' => 'ni ni-chart-bar-32',
                'icon_class' => 'bg-gradient-warning',
            ],
            [
                'title' => 'Stock Health',
                'value' => $stockHealth . '%',
                'subtitle' => $outOfStockProducts . ' products out of stock',
                'progress' => $stockHealth,
                'progress_label' => 'catalog available',
                'icon' => 'ni ni-box-2',
                'icon_class' => 'bg-gradient-danger',
            ],
        ];

        return view('welcome', compact(
            'todayUsers',
            'totalUsers',
            'todayOrders',
            'totalOrders',
            'todaySales',
            'totalSales',
            'todayPosOrders',
            'totalPosOrders',
            'pendingPosOrders',
            'todayPosSales',
            'totalPosSales',
            'recentPosOrders',
            'todayCombinedOrders',
            'totalCombinedOrders',
            'todayCombinedSales',
            'totalCombinedSales',
            'recentCombinedOrders',
            'stores',
            'selectedFrom',
            'selectedTo',
            'selectedStoreId',
            'totalProducts',
            'totalCategories',
            'totalSubCategories',
            'totalBrands',
            'productStocks',
            'salesLabels',
            'salesValues',
            'ordersLabels',
            'ordersValues',
            'wallets',
            'salesDetails',
            'salesTableDetails',
            'weeklyUsers',
            'weeklyOrders',
            'weeklySales',
            'averageOrderValue',
            'averageDailySales',
            'averageDailyOrders',
            'peakSalesDay',
            'peakOrdersDay',
            'outOfStockProducts',
            'pendingWallets',
            'processedWallets',
            'totalWalletPoints',
            'stockHealth',
            'walletProcessingRate',
            'weeklySalesShare',
            'periodLabel',
            'dashboardHighlights',
            'onlineUsers'
        ));
    }
    public function wallets()
    {
        $wallets = \App\Models\Wallet::orderBy('id', 'desc')
            ->paginate(config('constants.pagination_limit'));
        return view('admin.wallets', compact('wallets'));
    }

        public function stocks()
        {

         $productStocks = Product::select('id', 'name', 'sku_code', 'stock', 'in_stock', 'image', 'price')
            ->orderByDesc('id')
            ->paginate(config('constants.pagination_limit'));
            return view('admin.stocks', compact('productStocks'));
        }

    public function runCron()
    {
        Artisan::call('wallet:process-points');

        return redirect()->back()->with('success', 'Cron executed successfully');
    }
}
// test pushh code in server
