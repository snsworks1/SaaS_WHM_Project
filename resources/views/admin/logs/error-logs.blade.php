@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold mb-6">에러 로그 모니터링</h2>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-3">
        <div class="flex flex-wrap gap-2">
            <input type="text" id="searchInput" placeholder="검색어 입력"
                class="border border-gray-300 rounded px-3 py-2 text-sm w-60">
            <select id="levelFilter" class="border border-gray-300 rounded px-2 py-2 text-sm">
                <option value="">전체 레벨</option>
                <option value="low">low</option>
                <option value="medium">medium</option>
                <option value="high">high</option>
            </select>
            <select id="typeFilter" class="border border-gray-300 rounded px-2 py-2 text-sm">
                <option value="">전체 타입</option>
                <option value="server">server</option>
                <option value="연동오류">연동오류</option>
            </select>
            <select id="resolvedFilter" class="border border-gray-300 rounded px-2 py-2 text-sm">
                <option value="">해결 여부</option>
                <option value="1">✅ 해결됨</option>
                <option value="0">❌ 미해결</option>
            </select>
        </div>

        <button id="exportCsvBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
            CSV 내보내기
        </button>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg border">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3">레벨</th>
                    <th class="px-4 py-3">타입</th>
                    <th class="px-4 py-3">제목</th>
                    <th class="px-4 py-3">발생 시각</th>
                    <th class="px-4 py-3">WHM 유저</th>
                    <th class="px-4 py-3 text-center">해결</th>
                    <th class="px-4 py-3 text-center">상세</th>
                </tr>
            </thead>
            <tbody id="errorLogTable"></tbody>
        </table>
    </div>

    <div class="mt-6 text-center">
        <nav class="inline-flex rounded-md shadow-sm" id="pagination"></nav>
    </div>
</div>

<!-- 모달: 중앙 정렬 및 반응형 개선 -->
<div id="detailModal"
     class="fixed inset-0 z-50 bg-black/50 hidden"
     style="display: flex; align-items: center; justify-content: center;">
  <div class="bg-white max-w-2xl w-[90%] p-6 rounded shadow-lg relative">
    <button onclick="closeModal()"
            class="absolute top-2 right-2 text-gray-500 hover:text-red-500 text-xl">✕</button>
    <h3 class="text-lg font-semibold mb-3" id="modalTitle">상세 로그</h3>
    <pre id="modalContent"
         class="text-sm bg-gray-100 p-3 rounded max-h-[70vh] overflow-auto whitespace-pre-wrap font-mono"></pre>
  </div>
</div>

<script>
let logs = [];
let currentPage = 1;
let perPage = 10;

async function fetchLogs() {
    try {
        const res = await fetch("{{ route('admin.error-logs.json') }}");
        logs = await res.json();
        renderLogs();
    } catch (e) {
        console.error('불러오기 실패:', e);
    }
}

function renderLogs() {
    const tbody = document.getElementById('errorLogTable');
    const search = document.getElementById('searchInput').value.toLowerCase();
    const level = document.getElementById('levelFilter').value;
    const type = document.getElementById('typeFilter').value;
    const resolved = document.getElementById('resolvedFilter').value;

    let filtered = logs.filter(log =>
        (!search || log.title.toLowerCase().includes(search)) &&
        (!level || log.level === level) &&
        (!type || log.type === type) &&
        (!resolved || String(log.resolved) === resolved)
    );

    const start = (currentPage - 1) * perPage;
    const paginated = filtered.slice(start, start + perPage);

    tbody.innerHTML = '';
    paginated.forEach(log => {
        const row = document.createElement('tr');
        row.classList.add('border-t');
        row.innerHTML = `
            <td class="px-4 py-3 font-semibold ${getLevelColor(log.level)}">${log.level}</td>
            <td class="px-4 py-3">${log.type}</td>
            <td class="px-4 py-3 truncate max-w-[320px]">${log.title}</td>
            <td class="px-4 py-3">${formatDate(log.occurred_at)}</td>
            <td class="px-4 py-3">${log.whm_username || '-'}</td>
            <td class="px-4 py-3 text-center">
                <button onclick="toggle(${log.id})">${log.resolved ? '✅' : '❌'}</button>
            </td>
            <td class="px-4 py-3 text-center">
                <button onclick="openModal(${log.id})" class="text-blue-600 underline">상세</button>
            </td>
        `;
        tbody.appendChild(row);
    });

    renderPagination(filtered.length);
}

function getLevelColor(level) {
    if (level === 'high') return 'text-red-500';
    if (level === 'medium') return 'text-yellow-500';
    return 'text-gray-500';
}

function formatDate(str) {
    const d = new Date(str);
    return d.toLocaleString('ko-KR', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' });
}

function renderPagination(total) {
    const pagination = document.getElementById('pagination');
    const pageCount = Math.ceil(total / perPage);
    pagination.innerHTML = '';

    for (let i = 1; i <= pageCount; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = `px-3 py-1 mx-1 rounded ${i === currentPage ? 'bg-blue-600 text-white' : 'bg-gray-200'}`;
        btn.onclick = () => {
            currentPage = i;
            renderLogs();
        };
        pagination.appendChild(btn);
    }
}

function openModal(id) {
    const log = logs.find(l => l.id === id);
    document.getElementById('modalContent').innerText = `
[제목] ${log.title}
[경로] ${log.file_path}
[발생시각] ${formatDate(log.occurred_at)}
[내용]
${log.message || '(내용 없음)'}`;
    const modal = document.getElementById('detailModal');
    modal.style.display = 'flex';
    modal.classList.remove('hidden');
}

function closeModal() {
    const modal = document.getElementById('detailModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
}
async function toggle(id) {
    await fetch(`/admin/error-logs/${id}/toggle`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });
    fetchLogs();
}

function exportCSV() {
    let csv = 'ID,Level,Type,Title,Path,Time,User\n';
    logs.forEach(log => {
        csv += `"${log.id}","${log.level}","${log.type}","${log.title}","${log.file_path}","${formatDate(log.occurred_at)}","${log.whm_username}"\n`;
    });

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'error_logs.csv';
    link.click();
}

document.getElementById('searchInput').addEventListener('input', () => {
    currentPage = 1;
    renderLogs();
});
document.getElementById('levelFilter').addEventListener('change', () => {
    currentPage = 1;
    renderLogs();
});
document.getElementById('typeFilter').addEventListener('change', () => {
    currentPage = 1;
    renderLogs();
});
document.getElementById('resolvedFilter').addEventListener('change', () => {
    currentPage = 1;
    renderLogs();
});
document.getElementById('exportCsvBtn').addEventListener('click', exportCSV);

fetchLogs();
</script>
@endsection
