<?php

namespace App\Http\Controllers;

use App\Models\ReceiptLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $folders = Storage::directories('/logs');

        dd($folders);

        return view('vendor.laravel-log-viewer.log');
    }
}
