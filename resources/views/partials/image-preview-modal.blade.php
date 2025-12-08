<div id="image-preview-modal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50">
    <div class="max-w-3xl w-full p-4">
        <div class="bg-white rounded-lg overflow-hidden">
            <div class="p-2 text-right">
                <button onclick="closePreviewModal()" class="text-gray-600 hover:text-gray-800">Cerrar</button>
            </div>
            <div class="p-4 flex items-center justify-center">
                <img id="image-preview-img" src="" alt="Preview" class="max-h-[80vh] w-auto object-contain" />
            </div>
        </div>
    </div>
</div>

<script>
function openPreviewModal(url) {
    const modal = document.getElementById('image-preview-modal');
    const img = document.getElementById('image-preview-img');
    img.src = url;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closePreviewModal() {
    const modal = document.getElementById('image-preview-modal');
    const img = document.getElementById('image-preview-img');
    img.src = '';
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
// Close on ESC
document.addEventListener('keydown', function(e){ if(e.key === 'Escape') closePreviewModal(); });
</script>