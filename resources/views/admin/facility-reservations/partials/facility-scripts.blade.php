<script>
    function openAddFacilityModal() {
        const modal = document.getElementById('addFacilityModal');

        if (!modal) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeAddFacilityModal() {
        const modal = document.getElementById('addFacilityModal');

        if (!modal) return;

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    function openEditFacilityModal(button) {
        const modal = document.getElementById('editFacilityModal');
        const form = document.getElementById('editFacilityForm');

        if (!modal || !form) return;

        try {
            const facility = JSON.parse(button.dataset.facility);

            document.getElementById('editFacilityName').value = facility.name ?? '';
            document.getElementById('editFacilityLocation').value = facility.location ?? '';
            document.getElementById('editFacilityCapacity').value = facility.capacity ?? '';
            document.getElementById('editFacilityDescription').value = facility.description ?? '';

            const updateUrl = @json(route(
                'admin.facility-reservations.facilities.update',
                ['facility' => '__FACILITY__']
            ));

            form.action = updateUrl.replace('__FACILITY__', encodeURIComponent(facility.id));

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');

        } catch (error) {
            console.error('Unable to load facility details:', error);
            alert('Unable to open the facility editor. Please refresh the page and try again.');
        }
    }

    function closeEditFacilityModal() {
        const modal = document.getElementById('editFacilityModal');

        if (!modal) return;

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAddFacilityModal();
            closeEditFacilityModal();
        }
    });

    document.addEventListener('click', function(event) {
        if (event.target.id === 'addFacilityModal') {
            closeAddFacilityModal();
        }

        if (event.target.id === 'editFacilityModal') {
            closeEditFacilityModal();
        }
    });
</script>
