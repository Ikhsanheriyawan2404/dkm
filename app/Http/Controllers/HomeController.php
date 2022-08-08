<?php

namespace App\Http\Controllers;

use App\Models\{Member, Transaction};
use Yajra\DataTables\Facades\DataTables;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home', [
            'title' => 'Dashboard',
            'transactions' => Transaction::get(),
        ]);
    }

    public function dataTableMembers()
    {
        if (request()->ajax()) {
            $members = Member::latest()->get();
            return DataTables::of($members)
                ->addIndexColumn()
                ->editColumn('image', function (Member $member) {
                    return '<img src="'. $member->takeImage .'" width="100px" class="img-fluid" />';
                })
                ->rawColumns(['image'])
                ->make(true);
        }
    }

    public function dataTableTransactions()
    {
        if (request()->ajax()) {
            $transactions = Transaction::orderBy('date', 'desc')->get();
            return DataTables::of($transactions)
                ->addIndexColumn()
                ->editColumn('debit', function (Transaction $transaction) {
                    return number_format($transaction->debit);
                })
                ->editColumn('credit', function (Transaction $transaction) {
                    return number_format($transaction->credit);
                })
                ->editColumn('date', function (Transaction $transaction) {
                    return date('d-m-Y', strtotime($transaction->date));
                })
                ->make(true);
        }
    }
}
