<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $merchant = Auth::user()->merchantProfile()->firstOrFail();

        $start = $request->date('start_date') ?? Carbon::today()->startOfMonth();
        $end = $request->date('end_date') ?? Carbon::today();

        $ordersQuery = $merchant->orders()
            ->whereBetween('order_date', [$start, $end])
            ->where('status', '!=', 'cancelled');

        $summary = [
            'total_orders' => (clone $ordersQuery)->count(),
            'total_revenue' => (clone $ordersQuery)->sum('total_price'),
            'avg_order' => (clone $ordersQuery)->avg('total_price') ?? 0,
        ];

        $orders = (clone $ordersQuery)->with('customer.user')->latest('order_date')->get();

        $dailyRevenue = (clone $ordersQuery)
            ->selectRaw('DATE(order_date) as date, SUM(total_price) as total')
            ->groupByRaw('DATE(order_date)')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(fn ($row) => [$row->date => (float) $row->total]);

        return view('merchant.report.index', compact('merchant', 'summary', 'orders', 'dailyRevenue', 'start', 'end'));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $merchant = Auth::user()->merchantProfile()->firstOrFail();

        $start = $request->date('start_date') ?? Carbon::today()->startOfMonth();
        $end = $request->date('end_date') ?? Carbon::today();

        $orders = $merchant->orders()
            ->whereBetween('order_date', [$start, $end])
            ->with('customer.user')
            ->get();

        $filename = 'laporan-keuangan-'.$start->format('Y-m-d').'-'.$end->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Tanggal', 'Customer', 'Status', 'Total']);

            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->order_date->format('Y-m-d'),
                    $order->customer?->company_name ?? '-',
                    $order->status,
                    $order->total_price,
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
