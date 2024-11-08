document.addEventListener('DOMContentLoaded', function () {
    var table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/users/fetch',
            type: 'GET',
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'email' },
            { data: 'role' },
            { data: 'created_at' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function () {
                    return '<button class="btn btn-danger deleteUserBtn">Delete</button>';
                }
            }
        ],
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        dom: "<'row mb-3'<'col-sm-6'l><'col-sm-6'f>>" +
            "<'row'<'col-sm-12't>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>"
    });

    function refreshTable() {
        table.draw();
    }

    // New User
    const newUserModal = new bootstrap.Modal(document.getElementById('newUserModal'));

    document.getElementById('showNewUserBtn').addEventListener('click', function (event) {
        newUserModal.show()
        document.getElementById('newUserForm').reset();
    });

    document.getElementById('newUserForm').addEventListener('submit', function (event) {
        event.preventDefault();

        var newUserData = {
            name: document.getElementById('newUserName').value,
            email: document.getElementById('newUserEmail').value,
            password: document.getElementById('newUserPassword').value,
            password_confirmation: document.getElementById('newUserConfirmPassword').value,
            role: document.querySelector('input[name="role"]:checked').value
        };

        axios.post('/users', newUserData)
            .then(response => {
                newUserModal.hide()
                console.log(response);
                refreshTable();
            })
            .catch(error => console.error(error));
    });

    // Delete User
    const deleteUserModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));

    document.getElementsByTagName('tbody')[0].addEventListener('click', function (event) {
        if (event.target.closest('.deleteUserBtn')) {
            var row = table.row(event.target.closest('tr'));
            var data = row.data();
            document.getElementById('confirmDeleteUserBtn').dataset.id = data.id;
            deleteUserModal.show()
        }
    });

    document.getElementById('confirmDeleteUserBtn').addEventListener('click', function () {
        var userId = document.getElementById('confirmDeleteUserBtn').dataset.id;

        axios.delete(`/users/${userId}`)
            .then(response => {
                deleteUserModal.hide();
                console.log(response);
                refreshTable();
            })
            .catch(error => console.error(error));
    });
});
