
<script>
document.addEventListener('DOMContentLoaded', function () {

    const addModal = document.getElementById('addReservationModal');
    const editModal = document.getElementById('editReservationModal');
    const viewModal = document.getElementById('viewReservationModal');

    function openModal(modal) {
        if (!modal) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal(modal) {
        if (!modal) return;

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        if (
            !document.querySelector(
                '[id$="Modal"].flex'
            )
        ) {
            document.body.classList.remove('overflow-hidden');
        }
    }

    document.getElementById('openAddReservationModal')
        ?.addEventListener('click', function () {
            openModal(addModal);
        });

    document.querySelectorAll('.close-reservation-modal')
        .forEach(button => {
            button.addEventListener('click', function () {
                [addModal, editModal, viewModal].forEach(closeModal);
            });
        });

    [addModal, editModal, viewModal].forEach(modal => {
        modal?.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeModal(modal);
            }
        });
    });

    document.querySelectorAll('.edit-reservation-btn')
        .forEach(button => {
            button.addEventListener('click', function () {

                const id = this.dataset.id;

                document.getElementById('editReservationForm').action =
                    `/admin/facility-reservations/${id}`;

                document.getElementById('edit_facility_id').value =
                    this.dataset.facility;

                document.getElementById('edit_event_name').value =
                    this.dataset.event;

                document.getElementById('edit_description').value =
                    this.dataset.description || '';

                document.getElementById('edit_reservation_date').value =
                    this.dataset.date;

                document.getElementById('edit_start_time').value =
                    this.dataset.start;

                document.getElementById('edit_end_time').value =
                    this.dataset.end;

                document.getElementById('edit_requesting_office').value =
                    this.dataset.office;

                openModal(editModal);
            });
        });

    document.querySelectorAll('.view-reservation-btn')
        .forEach(button => {
            button.addEventListener('click', function () {

                const fields = {
                    view_event: this.dataset.event,
                    view_facility: this.dataset.facility,
                    view_date: this.dataset.date,
                    view_time: `${this.dataset.start} – ${this.dataset.end}`,
                    view_office: this.dataset.office,
                    view_description: this.dataset.description || 'No description provided.',
                    view_status: this.dataset.status
                };

                Object.entries(fields).forEach(([id, value]) => {
                    document.getElementById(id).textContent = value;
                });

                openModal(viewModal);
            });
        });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            [addModal, editModal, viewModal].forEach(closeModal);
        }
    });

});
</script>
