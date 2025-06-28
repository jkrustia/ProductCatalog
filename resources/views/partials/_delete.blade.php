<div id="deleteModal" class="delete-modal">
    <div class="delete-modal-content">
        <div class="delete-img">&#10071;</div>
        <h2 class="fw-bold mt-3 mb-1">OOPS!</h2>
        <p class="mb-3 fw-bold">Are you sure you want<br>to delete this product?</p>
        <form id="deleteModalForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="d-flex justify-content-center gap-3 mt-3">
                <button class="check" type="submit">&#10003;</button>
                <button class="cross" type="button" onclick="closeDeleteModal()">&#10005;</button>
            </div>
        </form>
    </div>
</div>
<script>
    function openDeleteModal(action) {
        document.getElementById('deleteModalForm').action = action;
        document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
</script>
