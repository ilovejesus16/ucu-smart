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

console.log(deleteModal);
console.log(deleteForm);
console.log(deleteUserName);

    document.querySelectorAll('.delete-btn').forEach(button => {

        button.addEventListener('click', function () {

            deleteUserName.textContent = button.dataset.name;
            deleteForm.action = `/admin/users/${button.dataset.id}`;

            deleteModal.classList.remove('hidden');
            deleteModal.classList.add('flex');

        });

    });

    document.querySelectorAll('.close-delete-modal').forEach(button => {

        button.addEventListener('click', function () {

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

            const response = await fetch(`/admin/users/${button.dataset.id}`);
            const user = await response.json();

            document.getElementById('viewName').textContent =
                `${user.first_name} ${user.last_name}`;

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

            viewModal.classList.remove('hidden');
            viewModal.classList.add('flex');

        });

    });

    document.querySelectorAll('.close-view-modal').forEach(button => {

        button.addEventListener('click', function () {

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

    document.querySelectorAll('.edit-user').forEach(button => {

        button.addEventListener('click', async function () {

            const response = await fetch(`/admin/users/${button.dataset.id}/edit`);
            const user = await response.json();

            editForm.action = `/admin/users/${user.id}`;

            document.getElementById('editFirstName').value = user.first_name ?? '';
            document.getElementById('editLastName').value = user.last_name ?? '';
            document.getElementById('editEmail').value = user.email ?? '';
            document.getElementById('editStatus').value = user.status ?? '';
            document.getElementById('editDepartment').value =
                user.course ?? user.department ?? '';

            editModal.classList.remove('hidden');
            editModal.classList.add('flex');

        });

    });

    document.querySelectorAll('.close-edit-modal').forEach(button => {

        button.addEventListener('click', function () {

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

            studentModal.classList.remove('hidden');
            studentModal.classList.add('flex');

        });

    });

    document.querySelectorAll('.close-student-modal').forEach(button => {

        button.addEventListener('click', function () {

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

            instructorModal.classList.remove('hidden');
            instructorModal.classList.add('flex');

        });

    });

    document.querySelectorAll('.close-instructor-modal').forEach(button => {

        button.addEventListener('click', function () {

            instructorModal.classList.remove('flex');
            instructorModal.classList.add('hidden');

        });

    });

    /*
    |--------------------------------------------------------------------------
    | Close on Background Click
    |--------------------------------------------------------------------------
    */

    [
        deleteModal,
        viewModal,
        editModal,
        studentModal,
        instructorModal
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
            instructorModal
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

    /*
|--------------------------------------------------------------------------
| Add User Modal
|--------------------------------------------------------------------------
*/

const addUserModal = document.getElementById('addUserModal');

document.querySelectorAll('.open-add-user-modal').forEach(button => {

    button.addEventListener('click', () => {

        addUserModal.classList.remove('hidden');
        addUserModal.classList.add('flex');

    });

});

document.querySelectorAll('.close-add-user-modal').forEach(button => {

    button.addEventListener('click', () => {

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

    const role = roleSelect.value;

    usernameField.classList.add('hidden');
    studentField.classList.add('hidden');
    employeeField.classList.add('hidden');
    courseField.classList.add('hidden');
    departmentField.classList.add('hidden');

    if (role === 'student') {

        studentField.classList.remove('hidden');
        courseField.classList.remove('hidden');

    } else if (role === 'instructor') {

        employeeField.classList.remove('hidden');
        departmentField.classList.remove('hidden');

    } else if (role === 'admin') {

        usernameField.classList.remove('hidden');

    }

}

toggleRoleFields();

roleSelect.addEventListener('change', toggleRoleFields);

});
</script>
@endpush