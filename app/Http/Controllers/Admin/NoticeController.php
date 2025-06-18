<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::latest()->paginate(15);
        return view('admin.notices.index', compact('notices'));
    }

    public function create()
    {
        return view('admin.notices.create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'importance' => 'required',
        'category' => 'required',
        'title' => 'required',
        'content' => 'nullable',
    ]);

    $validated['is_pinned'] = $request->has('is_pinned');

    Notice::create($validated);

    return redirect()
        ->route('admin.notices.index')
        ->with('success', '공지사항이 등록되었습니다.');
}



    public function edit(Notice $notice)
    {
        return view('admin.notices.edit', [
    'notice' => $notice,
]);
    }

    public function update(Request $request, Notice $notice)
    {
        $request->validate([
            'importance' => 'required',
            'category' => 'required',
            'title' => 'required',
            'content' => 'nullable',
        ]);

        $notice->update($request->only('importance', 'category', 'title', 'content'));

        return redirect()->route('admin.notices.index')->with('success', '공지사항이 수정되었습니다.');
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();

        return redirect()->route('admin.notices.index')->with('success', '공지사항이 삭제되었습니다.');
    }

    public function show(Notice $notice)
{
    return view('admin.notices.show', compact('notice'));
}
}
