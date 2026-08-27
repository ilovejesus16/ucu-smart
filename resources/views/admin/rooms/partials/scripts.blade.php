<script>

document.addEventListener('DOMContentLoaded', () => {

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    function openModal(modal) {

        modal.classList.remove('hidden');
        modal.classList.add('flex');

    }

    function closeModal(modal) {

        modal.classList.remove('flex');
        modal.classList.add('hidden');

    }

    /*
    |--------------------------------------------------------------------------
    | Modals
    |--------------------------------------------------------------------------
    */

    const addModal = document.getElementById('addRoomModal');
    const editModal = document.getElementById('editRoomModal');
    const viewModal = document.getElementById('viewRoomModal');
    const deleteModal = document.getElementById('deleteRoomModal');

    /*
    |--------------------------------------------------------------------------
    | Add
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.open-add-modal').forEach(button => {

        button.addEventListener('click', () => {

            addModal.querySelector('form').reset();

            openModal(addModal);

        });

    });

    document.querySelectorAll('.close-add-modal').forEach(button => {

        button.addEventListener('click', () => closeModal(addModal));

    });

/*
|--------------------------------------------------------------------------
| Import
|--------------------------------------------------------------------------
*/

const importModal = document.getElementById('importRoomModal');

document.querySelectorAll('.open-import-modal').forEach(button => {

    button.addEventListener('click', () => {

        if (importModal) {
            openModal(importModal);
        }

    });

});

document.querySelectorAll('.close-import-modal').forEach(button => {

    button.addEventListener('click', () => {

        if (importModal) {
            closeModal(importModal);
        }

    });

});

    /*
    |--------------------------------------------------------------------------
    | View
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.view-room-btn').forEach(button => {

        button.addEventListener('click', async () => {

            const response = await fetch(`/admin/rooms/${button.dataset.id}`);

            const room = await response.json();

            document.getElementById('viewRoomName').textContent = room.room_name;
            document.getElementById('viewRoomNumber').textContent = "Room " + room.room_number;
            document.getElementById('viewRoomBuilding').textContent = room.building;
            document.getElementById('viewRoomCapacity').textContent = room.capacity + " Seats";
            document.getElementById('viewRoomFloor').textContent = "Floor " + room.floor;
            document.getElementById('viewRoomCreated').textContent = room.created_at;
            document.getElementById('viewRoomUpdated').textContent = room.updated_at;

            openModal(viewModal);

        });

    });

    document.querySelectorAll('.close-view-modal').forEach(button => {

        button.addEventListener('click', () => closeModal(viewModal));

    });

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    const editForm = document.getElementById('editRoomForm');

    document.querySelectorAll('.edit-room-btn').forEach(button => {

        button.addEventListener('click', async () => {

            const response = await fetch(`/admin/rooms/${button.dataset.id}/edit`);

            const room = await response.json();

            editForm.action = `/admin/rooms/${room.id}`;

            document.getElementById('edit_building_id').value = room.building_id;
            document.getElementById('edit_room_number').value = room.room_number;
            document.getElementById('edit_room_name').value = room.room_name;
            document.getElementById('edit_capacity').value = room.capacity;
            document.getElementById('edit_floor').value = room.floor;

            openModal(editModal);

        });

    });

    document.querySelectorAll('.close-edit-modal').forEach(button => {

        button.addEventListener('click', () => closeModal(editModal));

    });

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    const deleteForm = document.getElementById('deleteRoomForm');

    document.querySelectorAll('.delete-room-btn').forEach(button => {

        button.addEventListener('click', () => {

            document.getElementById('deleteRoomName').textContent = button.dataset.name;

            deleteForm.action = `/admin/rooms/${button.dataset.id}`;

            openModal(deleteModal);

        });

    });

    document.querySelectorAll('.close-delete-modal').forEach(button => {

        button.addEventListener('click', () => closeModal(deleteModal));

    });

    /*
    |--------------------------------------------------------------------------
    | ESC closes modals
    |--------------------------------------------------------------------------
    */

    document.addEventListener('keydown', e => {

        if (e.key === 'Escape') {

            [
    addModal,
    editModal,
    viewModal,
    deleteModal,
    importModal
].forEach(modal => {

                if (modal) {

                    closeModal(modal);

                }

            });

        }

    });

    /*
    |--------------------------------------------------------------------------
    | Click outside
    |--------------------------------------------------------------------------
    */

    [
    addModal,
    editModal,
    viewModal,
    deleteModal,
    importModal
].forEach(modal => {

        if (!modal) return;

        modal.addEventListener('click', e => {

            if (e.target === modal) {

                closeModal(modal);

            }

        });

    });

});

</script>