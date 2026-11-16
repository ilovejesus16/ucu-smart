@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    /*
    |--------------------------------------------------------------------------
    | Delete Modal
    |--------------------------------------------------------------------------
    */

    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const deleteUserName = document.querySelector('#deleteModal #deleteUserName');

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            if (!deleteModal || !deleteForm || !deleteUserName) return;

            deleteUserName.textContent = button.dataset.name;
            deleteForm.action = `/admin/users/${button.dataset.id}`;

            deleteModal.classList.remove('hidden');
            deleteModal.classList.add('flex');
        });
    });

    document.querySelectorAll('.close-delete-modal').forEach(button => {
        button.addEventListener('click', function () {
            if (!deleteModal) return;

            deleteModal.classList.remove('flex');
            deleteModal.classList.add('hidden');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | View Modal
    |--------------------------------------------------------------------------
    */

    const viewModal = document.getElementById('viewModal');

    document.querySelectorAll('.view-user').forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();

            try {
                const response = await fetch(`/admin/users/${button.dataset.id}`);

                if (!response.ok) throw new Error('Unable to load user.');

                const user = await response.json();

                document.getElementById('viewName').textContent =
                    `${user.first_name ?? ''} ${user.last_name ?? ''}`.trim();

                document.getElementById('viewUsername').textContent =
                    user.username ?? '-';

                document.getElementById('viewEmail').textContent =
                    user.email ?? '-';

                document.getElementById('viewRole').textContent =
                    user.role ?? '-';

                document.getElementById('viewDepartment').textContent =
                    user.course ?? user.department ?? '-';

                document.getElementById('viewStatus').textContent =
                    user.status ?? '-';

                if (viewModal) {
                    viewModal.classList.remove('hidden');
                    viewModal.classList.add('flex');
                }

            } catch (error) {
                console.error(error);
                alert('Unable to load user information.');
            }
        });
    });

    document.querySelectorAll('.close-view-modal').forEach(button => {
        button.addEventListener('click', function () {
            if (!viewModal) return;

            viewModal.classList.remove('flex');
            viewModal.classList.add('hidden');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Edit Modal
    |--------------------------------------------------------------------------
    */

    const editModal = document.getElementById('editModal');
    const editForm = document.getElementById('editForm');
    const editAdminPermissionsSection =
        document.getElementById('editAdminPermissionsSection');

    document.querySelectorAll('.edit-user').forEach(button => {
        button.addEventListener('click', async function () {

            try {
                const response = await fetch(
                    `/admin/users/${button.dataset.id}/edit`
                );

                if (!response.ok) throw new Error('Unable to load user.');

                const user = await response.json();

                editForm.action = `/admin/users/${user.id}`;

                document.getElementById('editFirstName').value =
                    user.first_name ?? '';

                document.getElementById('editLastName').value =
                    user.last_name ?? '';

                document.getElementById('editEmail').value =
                    user.email ?? '';

                document.getElementById('editStatus').value =
                    user.status ?? '';

                document.getElementById('editDepartment').value =
                    user.course ?? user.department ?? '';

                /*
                |--------------------------------------------------------------------------
                | Administrator Permissions
                |--------------------------------------------------------------------------
                */

                const isAdmin = user.role === 'admin';

                if (editAdminPermissionsSection) {
                    editAdminPermissionsSection.classList.toggle(
                        'hidden',
                        !isAdmin
                    );
                }

                if (isAdmin) {

                    // Supports either permissions or admin_permission.permissions.
                    const savedPermissions =
                        user.admin_permission?.permissions ??
                        user.adminPermission?.permissions ??
                        user.permissions ??
                        {};

                    document.querySelectorAll(
                        '#editAdminPermissionsSection select[name^="permissions["]'
                    ).forEach(select => {

                        const match = select.name.match(
                            /^permissions\[(.+)\]$/
                        );

                        if (!match) return;

                        const feature = match[1];

                        select.value = savedPermissions[feature] ?? 'none';
                    });
                }

                if (editModal) {
                    editModal.classList.remove('hidden');
                    editModal.classList.add('flex');
                }

            } catch (error) {
                console.error(error);
                alert('Unable to load user information.');
            }
        });
    });

    document.querySelectorAll('.close-edit-modal').forEach(button => {
        button.addEventListener('click', function () {
            if (!editModal) return;

            editModal.classList.remove('flex');
            editModal.classList.add('hidden');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Student Import Modal
    |--------------------------------------------------------------------------
    */

    const studentModal = document.getElementById('studentImportModal');

    document.querySelectorAll('.open-student-modal').forEach(button => {
        button.addEventListener('click', function () {
            if (!studentModal) return;

            studentModal.classList.remove('hidden');
            studentModal.classList.add('flex');
        });
    });

    document.querySelectorAll('.close-student-modal').forEach(button => {
        button.addEventListener('click', function () {
            if (!studentModal) return;

            studentModal.classList.remove('flex');
            studentModal.classList.add('hidden');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Instructor Import Modal
    |--------------------------------------------------------------------------
    */

    const instructorModal = document.getElementById('instructorImportModal');

    document.querySelectorAll('.open-instructor-modal').forEach(button => {
        button.addEventListener('click', function () {
            if (!instructorModal) return;

            instructorModal.classList.remove('hidden');
            instructorModal.classList.add('flex');
        });
    });

    document.querySelectorAll('.close-instructor-modal').forEach(button => {
        button.addEventListener('click', function () {
            if (!instructorModal) return;

            instructorModal.classList.remove('flex');
            instructorModal.classList.add('hidden');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Add User Modal
    |--------------------------------------------------------------------------
    */

    const addUserModal = document.getElementById('addUserModal');

    document.querySelectorAll('.open-add-user-modal').forEach(button => {
        button.addEventListener('click', () => {
            if (!addUserModal) return;

            addUserModal.classList.remove('hidden');
            addUserModal.classList.add('flex');
        });
    });

    document.querySelectorAll('.close-add-user-modal').forEach(button => {
        button.addEventListener('click', () => {
            if (!addUserModal) return;

            addUserModal.classList.remove('flex');
            addUserModal.classList.add('hidden');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Add User Role Toggle
    |--------------------------------------------------------------------------
    */

    const roleSelect = document.getElementById('roleSelect');

    const usernameField = document.getElementById('usernameField');
    const studentField = document.getElementById('studentField');
    const employeeField = document.getElementById('employeeField');
    const courseField = document.getElementById('courseField');
    const departmentField = document.getElementById('departmentField');

    function toggleRoleFields() {

        if (!roleSelect) return;

        const role = roleSelect.value;

        [
            usernameField,
            studentField,
            employeeField,
            courseField,
            departmentField
        ].forEach(field => {
            if (field) field.classList.add('hidden');
        });

        if (role === 'student') {

            studentField?.classList.remove('hidden');
            courseField?.classList.remove('hidden');

        } else if (role === 'instructor') {

            employeeField?.classList.remove('hidden');
            departmentField?.classList.remove('hidden');

        } else if (role === 'admin') {

            usernameField?.classList.remove('hidden');

        }
    }

    if (roleSelect) {
        toggleRoleFields();
        roleSelect.addEventListener('change', toggleRoleFields);
    }

    /*
    |--------------------------------------------------------------------------
    | Close Modals on Background Click
    |--------------------------------------------------------------------------
    */

    [
        deleteModal,
        viewModal,
        editModal,
        studentModal,
        instructorModal,
        addUserModal
    ].forEach(modal => {

        if (!modal) return;

        modal.addEventListener('click', function (e) {

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

    document.addEventListener('keydown', function (e) {

        if (e.key !== 'Escape') return;

        [
            deleteModal,
            viewModal,
            editModal,
            studentModal,
            instructorModal,
            addUserModal
        ].forEach(modal => {

            if (!modal) return;

            modal.classList.remove('flex');
            modal.classList.add('hidden');

        });

    });

    /*
    |--------------------------------------------------------------------------
    | Template Dropdown
    |--------------------------------------------------------------------------
    */

    const templateBtn = document.getElementById('templateDropdownBtn');
    const templateMenu = document.getElementById('templateDropdown');

    if (templateBtn && templateMenu) {

        templateBtn.addEventListener('click', function (e) {

            e.stopPropagation();
            templateMenu.classList.toggle('hidden');

        });

        document.addEventListener('click', function () {
            templateMenu.classList.add('hidden');
        });

        templateMenu.addEventListener('click', function (e) {
            e.stopPropagation();
        });

    }

});
</script>
@endpush
