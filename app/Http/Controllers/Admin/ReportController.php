<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\SoldPackage;
use App\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function transaction()
    {
        $page_title = 'Transaction Logs';
        $transactions = Transaction::with('owner')->latest()->paginate(getPaginate());
        $empty_message = 'No transactions.';
        return view('admin.reports.transactions', compact('page_title', 'transactions', 'empty_message'));
    }

    public function transactionSearch(Request $request)
    {
        $request->validate(['search' => 'required']);
        $search = $request->search;
        $page_title = 'Transactions Search - ' . $search;
        $empty_message = 'No transactions.';

        $transactions = Transaction::with('owner')->whereHas('owner', function ($owner) use ($search) {
            $owner->where('username', 'like',"%$search%");
        })->orWhere('trx', $search)->paginate(getPaginate());

        return view('admin.reports.transactions', compact('page_title', 'transactions', 'empty_message'));
    }

    public function sales()
    {
        $page_title     = 'Sales Logs';
        $sales          = SoldPackage::where('status', '!=', 0)->with('owner', 'deposit')->latest()->paginate(getPaginate());
        $empty_message  = 'No sales.';
        return view('admin.reports.sales', compact('page_title', 'sales', 'empty_message'));
    }

    public function salesSearch(Request $request)
    {
        $request->validate(['search' => 'required']);
        $search = $request->search;
        $sales = SoldPackage::where('status', '!=', 0)->with('owner')->whereHas('owner', function ($owner) use ($search) {
            $owner->where('username', 'like',"%$search%");
        })->orWhere('order_number', $search)->paginate(getPaginate());

        $empty_message  = 'No sales found.';
        $page_title = 'Sales Search - ' . $search;
        return view('admin.reports.sales', compact('page_title', 'sales', 'empty_message'));
    }
}
