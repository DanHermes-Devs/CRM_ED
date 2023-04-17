<?php

namespace App\Http\Controllers;

use App\Models\ReceiptLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $logs = ReceiptLog::with('user')->get();
        
        if(request()->ajax()){
            return DataTables()
                ->of($logs)
                ->escapeColumns([])
                ->make(true);
        }

        return view('crm.logs.index', compact('logs'));
    }
}
