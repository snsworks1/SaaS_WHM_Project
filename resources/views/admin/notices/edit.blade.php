@extends('layouts.admin')

@php
    $editorContent = $notice->content ?: '{}';
@endphp


@section('content')
<div class="max-w-4xl mx-auto py-10 px-6 bg-white dark:bg-gray-900 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold mb-6">✏️ 공지사항 수정</h2>

    <form id="noticeForm" method="POST" action="{{ route('admin.notices.update', $notice) }}">
        @csrf
        @method('PUT')

        {{-- 중요도 --}}
        <div class="mb-4">
            <label class="font-semibold block mb-1">📌 중요도</label>
            <select name="importance" class="w-full border rounded px-4 py-2" required>
                @foreach (['높음', '보통', '낮음'] as $level)
                    <option value="{{ $level }}" @selected($notice->importance === $level)>{{ $level }}</option>
                @endforeach
            </select>
        </div>

        {{-- 종류 --}}
        <div class="mb-4">
            <label class="font-semibold block mb-1">📂 종류</label>
            <select name="category" class="w-full border rounded px-4 py-2" required>
                @foreach (['점검', '긴급점검', '이벤트', '안내'] as $category)
                    <option value="{{ $category }}" @selected($notice->category === $category)>{{ $category }}</option>
                @endforeach
            </select>
        </div>

        {{-- 제목 --}}
        <div class="mb-4">
            <label class="font-semibold block mb-1">📝 제목</label>
            <input type="text" name="title" class="w-full border rounded px-4 py-2" value="{{ $notice->title }}" required>
        </div>

        {{-- 고정 여부 --}}
        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_pinned" value="1" class="mr-2"
                       @checked($notice->is_pinned)>
                🔝 고정 공지로 등록
            </label>
        </div>

        

        {{-- Editor.js 내용 --}}
        <div class="mb-6">
            <label class="block font-semibold mb-2">📄 내용</label>
            <div id="editorjs" class="bg-white border rounded p-4 min-h-[300px]"></div>
            <input type="hidden" name="content" id="editorContent">
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">저장</button>
            <a href="{{ route('admin.notices.index') }}" class="ml-auto text-blue-600 hover:underline">← 목록으로 돌아가기</a>
        </div>
    </form>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@2.27.2"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/header@2.6.1"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/list@1.7.0"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/image@2.8.1"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editor = new EditorJS({
        holder: 'editorjs',
        tools: {
            header: Header,
            list: List,
            image: {
                class: ImageTool,
                config: {
                    endpoints: {
                        byFile: '/admin/uploads/editorjs',
                    },
                    additionalRequestHeaders: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    caption: true,
                     actions: [
            {
                name: 'size-small',
                icon: 'S',
                title: '작게',
                toggle: true,
                action: (tool, block) => block.data.size = 'small'
            },
            {
                name: 'size-medium',
                icon: 'M',
                title: '보통',
                toggle: true,
                action: (tool, block) => block.data.size = 'medium'
            },
            {
                name: 'size-large',
                icon: 'L',
                title: '크게',
                toggle: true,
                action: (tool, block) => block.data.size = 'large'
            },
        ]
                }
                
            }
        },
        placeholder: '내용을 입력하세요...',
        data: {!! $editorContent !!}
    });

    const form = document.getElementById('noticeForm');
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        editor.save().then((outputData) => {
            document.getElementById('editorContent').value = JSON.stringify(outputData);
            form.submit();
        }).catch((error) => {
            alert('에디터 저장 중 오류 발생: ' + error.message);
        });
    });
});
</script>

