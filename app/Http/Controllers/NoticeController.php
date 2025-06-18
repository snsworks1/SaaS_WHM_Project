<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notice;

class NoticeController extends Controller
{
    public function index()
{
    $notices = Notice::latest()->paginate(10); // 페이징도 가능
    return view('notices.index', compact('notices'));
}

public function show($id)
{
    $notice = Notice::findOrFail($id);
    return view('notices.show', compact('notice'));
}
}
