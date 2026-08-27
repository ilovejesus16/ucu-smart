@push('scripts')
<script>

document.addEventListener('DOMContentLoaded', () => {

    /*
    |--------------------------------------------------------------------------
    | Add Building Modal
    |--------------------------------------------------------------------------
    */

    const addModal = document.getElementById('addBuildingModal');

    document.querySelectorAll('.open-add-modal').forEach(button => {

        button.addEventListener('click', () => {

            addModal.classList.remove('hidden');
            addModal.classList.add('flex');

        });

    });

    document.querySelectorAll('.close-add-modal').forEach(button => {

        button.addEventListener('click', () => {

            addModal.classList.remove('flex');
            addModal.classList.add('hidden');

        });

    });

    /*
    |--------------------------------------------------------------------------
    | Import Modal
    |--------------------------------------------------------------------------
    */

    const importModal = document.getElementById('importBuildingModal');

    document.querySelectorAll('.open-import-modal').forEach(button => {

        button.addEventListener('click', () => {

            importModal.classList.remove('hidden');
            importModal.classList.add('flex');

        });

    });

    document.querySelectorAll('.close-import-modal').forEach(button => {

        button.addEventListener('click', () => {

            importModal.classList.remove('flex');
            importModal.classList.add('hidden');

        });

    });

    /*
    |--------------------------------------------------------------------------
    | View Building
    |--------------------------------------------------------------------------
    */

    const viewModal = document.getElementById('viewBuildingModal');

    document.querySelectorAll('.view-building').forEach(button => {

        button.addEventListener('click', async function () {

            const response = await fetch(`/admin/buildings/${button.dataset.id}`);
            const building = await response.json();

            document.getElementById('viewBuildingName').textContent =
                building.building_name;

            document.getElementById('viewBuildingRooms').textContent =
                building.rooms + ' Rooms';

            document.getElementById('viewBuildingCreated').textContent =
                building.created_at;

            document.getElementById('viewBuildingUpdated').textContent =
                building.updated_at;

            const image = document.getElementById('viewBuildingImage');
            const placeholder = document.getElementById('viewBuildingPlaceholder');

            if (building.image) {

                image.src = building.image;
                image.classList.remove('hidden');
                placeholder.classList.add('hidden');

            } else {

                image.classList.add('hidden');
                placeholder.classList.remove('hidden');

            }

            viewModal.classList.remove('hidden');
            viewModal.classList.add('flex');

        });

    });

    document.querySelectorAll('.close-view-building').forEach(button => {

        button.addEventListener('click', () => {

            viewModal.classList.remove('flex');
            viewModal.classList.add('hidden');

        });

    });

    /*
    |--------------------------------------------------------------------------
    | Edit Building
    |--------------------------------------------------------------------------
    */

    const editModal = document.getElementById('editBuildingModal');
    const editForm = document.getElementById('editBuildingForm');

    document.querySelectorAll('.edit-building').forEach(button => {

        button.addEventListener('click', async function () {

            const response = await fetch(`/admin/buildings/${button.dataset.id}/edit`);
            const building = await response.json();

            editForm.action = `/admin/buildings/${building.id}`;

            document.getElementById('editBuildingName').value =
                building.building_name;

            const preview = document.getElementById('editBuildingPreview');
            const placeholder = document.getElementById('editBuildingPlaceholder');

            if (building.image) {

                preview.src = building.image;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');

            } else {

                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');

            }

            editModal.classList.remove('hidden');
            editModal.classList.add('flex');

        });

    });

    document.querySelectorAll('.close-edit-building').forEach(button => {

        button.addEventListener('click', () => {

            editModal.classList.remove('flex');
            editModal.classList.add('hidden');

        });

    });

    /*
    |--------------------------------------------------------------------------
    | Delete Building
    |--------------------------------------------------------------------------
    */

    const deleteModal = document.getElementById('deleteBuildingModal');
    const deleteForm = document.getElementById('deleteBuildingForm');

    document.querySelectorAll('.delete-building').forEach(button => {

        button.addEventListener('click', () => {

            document.getElementById('deleteBuildingName').textContent =
                button.dataset.name;

            deleteForm.action =
                `/admin/buildings/${button.dataset.id}`;

            deleteModal.classList.remove('hidden');
            deleteModal.classList.add('flex');

        });

    });

    document.querySelectorAll('.close-delete-building').forEach(button => {

        button.addEventListener('click', () => {

            deleteModal.classList.remove('flex');
            deleteModal.classList.add('hidden');

        });

    });

    /*
    |--------------------------------------------------------------------------
    | Live Search
    |--------------------------------------------------------------------------
    */

    const search = document.getElementById('searchBuilding');

    if (search) {

        search.addEventListener('keyup', function () {

            const value = this.value.toLowerCase();

            document.querySelectorAll('#buildingTable tr').forEach(row => {

                row.style.display = row.innerText.toLowerCase().includes(value)
                    ? ''
                    : 'none';

            });

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Close Modals
    |--------------------------------------------------------------------------
    */

    [
        addModal,
        importModal,
        viewModal,
        editModal,
        deleteModal
    ].forEach(modal => {

        if (!modal) return;

        modal.addEventListener('click', e => {

            if (e.target === modal) {

                modal.classList.remove('flex');
                modal.classList.add('hidden');

            }

        });

    });

    /*
    |--------------------------------------------------------------------------
    | Escape Key
    |--------------------------------------------------------------------------
    */

    document.addEventListener('keydown', e => {

        if (e.key !== 'Escape') return;

        [
            addModal,
            importModal,
            viewModal,
            editModal,
            deleteModal
        ].forEach(modal => {

            if (!modal) return;

            modal.classList.remove('flex');
            modal.classList.add('hidden');

        });

    });

});
</script>
@endpush