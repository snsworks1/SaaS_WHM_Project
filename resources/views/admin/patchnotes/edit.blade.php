@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/trix@2.0.4/dist/trix.css">
<script src="https://unpkg.com/trix@2.0.4/dist/trix.umd.min.js"></script>

<div class="max-w-3xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">✏️ 패치노트 수정</h2>

    <form action="{{ route('admin.patchnotes.update', $patchnote->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-semibold mb-1">제목</label>
            <input type="text" name="title" class="w-full border rounded px-4 py-2" value="{{ $patchnote->title }}" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">주요 패치 기능</label>
            <input type="text" name="summary" class="w-full border rounded px-4 py-2" value="{{ $patchnote->summary }}" required>
        </div>

        <div class="mb-4">
            <label class="inline-flex items-center space-x-2">
                <input type="checkbox" name="is_pinned" value="1" class="rounded" {{ $patchnote->is_pinned ? 'checked' : '' }}>
                <span>🔝 고정 공지로 등록</span>
            </label>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">내용</label>
            <input id="content" type="hidden" name="content" value="{{ $patchnote->content }}">
            <trix-editor input="content" class="trix-content bg-white rounded border border-gray-300 shadow-sm"></trix-editor>
        </div>

        <div class="flex space-x-2 mt-6">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">수정</button>
            <button type="button" onclick="insertTemplate()" class="bg-gray-200 px-3 py-2 rounded border">📄 템플릿 삽입</button>
            <button type="button" onclick="previewPost()" class="bg-green-600 text-white px-3 py-2 rounded">👁 미리보기</button>
        </div>
    </form>
</div>

<script>
function insertTemplate() {
    const template = "<h2>■ 주요 내용</h2><ul><li>항목 1</li><li>항목 2</li></ul><p><br></p>";
    document.querySelector("trix-editor").editor.insertHTML(template);
}
function previewPost() {
    const content = document.querySelector("input[name='content']").value;
    const title = document.querySelector("input[name='title']").value;
    const previewWindow = window.open('', '_blank');
    previewWindow.document.write(`
        <html><head><title>${title}</title></head>
        <body style='font-family:sans-serif;padding:2rem;'>
            <h1>${title}</h1><hr>${content}
        </body></html>
    `);
}
</script>
@endsection
