<!-- 📌 공지사항 / 패치노트 섹션 -->
<div class="max-w-6xl mx-auto mt-12 grid grid-cols-1 md:grid-cols-2 gap-6">

  <!-- 📢 공지사항 카드 -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow border p-4 sm:p-6">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-white">📢 공지사항</h3>
      <a href="{{ route('notices.index') }}" class="text-xs sm:text-sm text-blue-600 hover:underline font-semibold">+ 자세히 보기</a>
    </div>
    @if($notices->count())
      <div class="overflow-x-auto">
        <table class="min-w-full text-xs sm:text-sm text-center text-gray-600 dark:text-gray-300">
          <thead class="border-b text-gray-500 text-[11px] sm:text-xs">
            <tr>
              <th class="py-2">종류</th>
              <th class="py-2">중요도</th>
              <th class="py-2">제목</th>
              <th class="py-2">날짜</th>
            </tr>
          </thead>
          <tbody>
            @foreach($notices as $notice)
              <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 border-b">
                <td class="py-2 whitespace-nowrap">{{ $notice->category }}</td>
                <td class="py-2">
                  @if($notice->importance === '높음')
                    <span class="text-red-500 font-semibold">🔴 {{ $notice->importance }}</span>
                  @elseif($notice->importance === '보통')
                    <span class="text-yellow-500">🟡 {{ $notice->importance }}</span>
                  @else
                    <span class="text-gray-500">⚪ {{ $notice->importance }}</span>
                  @endif
                </td>
                <td class="py-2">
                  <button onclick="openNoticeModal({{ $notice->id }})"
                          class="text-blue-600 hover:underline font-medium truncate max-w-[140px] sm:max-w-none">
                    {{ $notice->title }}
                  </button>
                </td>
                <td class="py-2 whitespace-nowrap">{{ $notice->created_at->format('Y-m-d H:i') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <p class="text-gray-500 dark:text-gray-400 text-sm mt-2">등록된 공지사항이 없습니다.</p>
    @endif
  </div>

  <!-- 🛠 패치노트 카드 -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow border p-4 sm:p-6">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-white">🛠 패치노트</h3>
      <a href="https://s-organization-887.gitbook.io/hostyle-web/undefined-3/undefined"
         class="text-xs sm:text-sm text-blue-600 hover:underline font-semibold">+ 자세히 보기</a>
    </div>
    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
      기능 업데이트 및 변경 사항을 <br class="sm:hidden">정기적으로 확인해 주세요.
    </p>
  </div>
</div>

<!-- 📦 공지사항 모달 -->
<div id="noticeModal"
     class="fixed inset-0 flex items-center justify-center bg-black/50 z-50 hidden px-4">
  <div class="bg-white dark:bg-gray-900 rounded-lg p-4 sm:p-6 
              max-w-sm sm:max-w-md md:max-w-xl lg:max-w-2xl xl:max-w-3xl w-full 
              relative shadow-xl max-h-[90vh] overflow-y-auto">

    <!-- 닫기 버튼 -->
    <button onclick="closeNoticeModal()" class="absolute top-2 right-2 text-gray-600 hover:text-black dark:text-white text-lg sm:text-xl">✕</button>

    <!-- 제목 -->
    <h2 class="text-base sm:text-xl font-bold text-gray-800 dark:text-white" id="modalTitle">공지 제목</h2>

    <!-- 구분선 -->
    <hr class="my-4 border-t border-gray-300 dark:border-gray-600" />

    <!-- 본문 -->
    <div id="modalContent"
         class="text-sm text-gray-700 dark:text-gray-200 prose dark:prose-invert max-w-none overflow-y-auto"
         style="max-height: 65vh;">
      로딩 중...
    </div>
  </div>
</div>


<!-- fetch + parser -->
<script>
function openNoticeModal(id) {
  fetch(`/api/notices/${id}`)
    .then(res => res.json())
    .then(data => {
      document.getElementById('modalTitle').textContent = data.title;
      const contentJSON = JSON.parse(data.content);

      let html = '';
      contentJSON.blocks.forEach(block => {
        switch (block.type) {
          case 'header':
            html += `<h${block.data.level}>${block.data.text}</h${block.data.level}>`;
            break;
          case 'paragraph':
            html += `<p>${block.data.text}</p>`;
            break;
          case 'list':
            const tag = block.data.style === 'ordered' ? 'ol' : 'ul';
            html += `<${tag}>${block.data.items.map(item => `<li>${item}</li>`).join('')}</${tag}>`;
            break;
          case 'image':
            html += `<img src="${block.data.file.url}" alt="${block.data.caption || ''}" class="max-w-full h-auto rounded mt-3">`;
            break;
          default:
            break;
        }
      });

      document.getElementById('modalContent').innerHTML = html;
      document.getElementById('noticeModal').classList.remove('hidden');
    });
}

function closeNoticeModal() {
  document.getElementById('noticeModal').classList.add('hidden');
}
</script>
