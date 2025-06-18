@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <h2 class="text-2xl font-bold mb-6">📢 새 공지사항 등록</h2>

    <form id="noticeForm" method="POST" action="{{ route('admin.notices.store') }}">
        @csrf

        <div class="mb-4">
            <label class="font-semibold">중요도</label>
            <select name="importance" class="w-full border rounded px-4 py-2" required>
                <option value="높음">높음</option>
                <option value="보통">보통</option>
                <option value="낮음">낮음</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="font-semibold">종류</label>
            <select name="category" class="w-full border rounded px-4 py-2" required>
                <option value="점검">점검</option>
                <option value="긴급점검">긴급점검</option>
                <option value="이벤트">이벤트</option>
                <option value="안내">안내</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="font-semibold">제목</label>
            <input type="text" name="title" class="w-full border rounded px-4 py-2" required>
        </div>

        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_pinned" value="1" class="mr-2">
                🔝 고정 공지로 등록
            </label>
        </div>

        <div class="mb-6">
            <label class="block font-semibold mb-2">내용</label>
            <div id="editorjs" class="bg-white border rounded p-4 min-h-[300px]"></div>
            <input type="hidden" name="content" id="editorContent">
        </div>

        <div class="flex gap-2 flex-wrap">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">저장</button>
            <button type="button" onclick="insertTemplate('maintenance')" class="bg-gray-200 px-4 py-2 rounded border">🛠 점검용</button>
            <button type="button" onclick="insertTemplate('urgent')" class="bg-gray-200 px-4 py-2 rounded border">🚨 긴급점검</button>
            <button type="button" onclick="insertTemplate('event')" class="bg-gray-200 px-4 py-2 rounded border">🎉 이벤트</button>
            <button type="button" onclick="insertTemplate('notice')" class="bg-gray-200 px-4 py-2 rounded border">📢 안내</button>
            <button type="button" onclick="previewContent()" class="bg-green-600 text-white px-4 py-2 rounded">👁 미리보기</button>
        </div>
    </form>
</div>

<!-- Editor.js Core & Tools -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@2.27.2"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/header@2.6.1"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/list@1.7.0"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/image@2.8.1"></script>

<script>
    let editor;

    document.addEventListener('DOMContentLoaded', function () {
        editor = new EditorJS({
            holder: 'editorjs',
            tools: {
                header: Header,
                list: List,
                image: {
                    class: ImageTool,
                    config: {
                        endpoints: {
                            byFile: '/admin/uploads/editorjs', // 실제 업로드 라우트
                        },
                        additionalRequestHeaders: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        caption: true
                    }
                }
            },
            placeholder: '공지사항 내용을 입력하세요...',
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

    function insertTemplate(type) {
        editor.blocks.clear();

        const templates = {
            maintenance: [
                { type: 'header', data: { text: '🔧 점검 안내', level: 2 } },
                { type: 'paragraph', data: { text: '안녕하세요. 아래와 같이 서비스 점검이 예정되어 있습니다.' }},
                { type: 'list', data: { style: 'unordered', items: [
                    '🕒 점검 일시: 2025년 06월 20일(금) 02:00 ~ 05:00',
                    '💡 점검 내용: 서버 안정화 및 기능 개선',
                    '⚠️ 점검 영향: 점검 시간 중 서비스 일시 중단'
                ]}},
                { type: 'paragraph', data: { text: '더 나은 서비스 제공을 위한 점검이오니 양해 부탁드립니다.' }},
            ],
            urgent: [
                { type: 'header', data: { text: '🚨 긴급 점검 공지', level: 2 } },
                { type: 'paragraph', data: { text: '예기치 못한 이슈로 인해 아래와 같이 긴급 점검을 진행합니다.' }},
                { type: 'list', data: { style: 'unordered', items: [
                    '🕒 점검 일시: 2025년 06월 18일(수) 00:00 ~ 01:30',
                    '🧯 사유: 장애 대응 및 보안 패치',
                    '⚠️ 영향: 해당 시간 동안 일부 기능 접속 불가'
                ]}},
                { type: 'paragraph', data: { text: '불편을 드려 죄송하며 빠르게 정상화될 수 있도록 노력하겠습니다.' }},
            ],
            event: [
                { type: 'header', data: { text: '🎉 신규 이벤트 안내', level: 2 } },
                { type: 'paragraph', data: { text: '풍성한 혜택이 가득한 이벤트가 진행됩니다!' }},
                { type: 'list', data: { style: 'unordered', items: [
                    '📅 이벤트 기간: 2025년 06월 18일 ~ 06월 30일',
                    '🎁 참여 방법: 공지사항 댓글 또는 신청서 작성',
                    '🏆 보상: 추첨을 통해 기프티콘 증정'
                ]}},
                { type: 'paragraph', data: { text: '많은 참여 부탁드립니다!' }},
            ],
            notice: [
                { type: 'header', data: { text: '📢 서비스 이용 안내', level: 2 } },
                { type: 'paragraph', data: { text: '서비스 이용에 참고하실 주요 변경 사항을 안내드립니다.' }},
                { type: 'list', data: { style: 'unordered', items: [
                    '🔄 기능 업데이트: 회원가입 시 이메일 인증 강화',
                    '💬 고객센터 운영시간 변경: 평일 10:00 ~ 17:00',
                    '📌 기타: 일부 정책 수정 사항은 별도 공지 예정'
                ]}},
                { type: 'paragraph', data: { text: '감사합니다.' }},
            ],
        };

        templates[type].forEach(block => editor.blocks.insert(block.type, block.data));
    }

    function previewContent() {
        editor.save().then((data) => {
            const html = data.blocks.map(block => {
                switch (block.type) {
                    case 'header':
                        return `<h${block.data.level}>${block.data.text}</h${block.data.level}>`;
                    case 'paragraph':
                        return `<p>${block.data.text}</p>`;
                    case 'list':
                        const tag = block.data.style === 'ordered' ? 'ol' : 'ul';
                        const items = block.data.items.map(item => `<li>${item}</li>`).join('');
                        return `<${tag}>${items}</${tag}>`;
                    case 'image':
                        return `<img src="${block.data.file.url}" alt="${block.data.caption || ''}" style="max-width:100%;">`;
                    default:
                        return '';
                }
            }).join('');
            const win = window.open('', '_blank');
            win.document.write('<html><head><title>미리보기</title></head><body style="padding:20px;font-family:sans-serif;">' + html + '</body></html>');
            win.document.close();
        });
    }
</script>
@endsection
