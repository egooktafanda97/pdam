<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GatewayLog;

class GatewayLogController extends Controller
{
    public function index()
    {
        $logs = GatewayLog::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.gateway_logs.index', compact('logs'));
    }
}
