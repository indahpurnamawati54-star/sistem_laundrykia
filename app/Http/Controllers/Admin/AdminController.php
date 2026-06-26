<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    private function checkAdmin()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
    }


    public function dashboard()
    {
        $this->checkAdmin();


        $todayIncome = Transaction::whereDate('created_at', today())
            ->where('is_paid', true)
            ->sum('total_amount');


        $todayTransactions = Transaction::whereDate('created_at', today())
            ->count();


        $pendingTransactions = Transaction::where(
            'status',
            Transaction::STATUS_DITERIMA
        )->count();


        $processingTransactions = Transaction::where(
            'status',
            Transaction::STATUS_DALAM_PROSES
        )->count();


        $totalUsers = User::count();


        $recentTransactions = Transaction::with([
            'customer',
            'service'
        ])
        ->latest()
        ->limit(5)
        ->get();



        $popularServices = Service::withCount('transactions')
            ->orderByDesc('transactions_count')
            ->limit(5)
            ->get();



        $monthlyIncomeData = Transaction::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('is_paid', true)
            ->whereYear('created_at', now()->year)
            ->groupBy(
                DB::raw('MONTH(created_at)')
            )
            ->orderBy('month')
            ->get();



        return view('admin.dashboard', compact(
            'todayIncome',
            'todayTransactions',
            'pendingTransactions',
            'processingTransactions',
            'totalUsers',
            'recentTransactions',
            'popularServices',
            'monthlyIncomeData'
        ));
    }




    public function reports(Request $request)
    {
        $this->checkAdmin();



        $startDate = $request->input(
            'start_date',
            now()->startOfMonth()->format('Y-m-d')
        );


        $endDate = $request->input(
            'end_date',
            now()->format('Y-m-d')
        );



        $transactions = Transaction::with([
            'customer',
            'service',
            'cashier'
        ])
        ->whereBetween('created_at', [
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59'
        ])
        ->orderByDesc('created_at')
        ->get();



        $summary = [

            'total_transactions' => $transactions->count(),

            'total_income' => $transactions
                ->where('is_paid', true)
                ->sum('total_amount'),


            'paid_transactions' => $transactions
                ->where('is_paid', true)
                ->count(),


            'unpaid_transactions' => $transactions
                ->where('is_paid', false)
                ->count(),


            'completed_transactions' => $transactions
                ->where(
                    'status',
                    Transaction::STATUS_SELESAI
                )
                ->count(),


            'picked_transactions' => $transactions
                ->where(
                    'status',
                    Transaction::STATUS_DIAMBIL
                )
                ->count(),

        ];




        $monthlyReport = Transaction::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('is_paid', true)
            ->whereYear('created_at', now()->year)
            ->groupBy(
                DB::raw('MONTH(created_at)')
            )
            ->orderBy('month')
            ->get();




        return view(
            'admin.reports.index',
            [
                'transactions' => $transactions,
                'summary' => $summary,
                'monthlyReport' => $monthlyReport,
                'startDate' => $startDate,
                'endDate' => $endDate
            ]
        );
    }

}
