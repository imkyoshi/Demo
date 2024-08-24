<?php
require_once '../app/config/db.php';
require_once '../app/controller/UserController.php';

// Instantiate database and UserController
$database = new Database();
$db = $database->connect();
$userController = new UserController($db);
$userController->redirectIfNotAdmin();

// Handle form submissions
$addErrorMessage = $userController->handleAddUser();
$updateMessage = $userController->handleUpdateUser();
$deleteMessage = $userController->handleDeleteUser();

// Get users to display
$users = $userController->getAllUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">User Management</h1>

    <!-- Add User Form -->
    <div class="mb-4">
        <h2>Add New User</h2>
        <?php if ($addErrorMessage): ?>
            <div class="alert alert-danger"><?=htmlspecialchars($addErrorMessage)?></div>
        <?php endif;?>
        <form method="POST">
            <div class="mb-3">
                <label for="fullName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullName" name="fullName" required>
            </div>
            <div class="mb-3">
                <label for="phoneNumber" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="mb-3">
                <label for="dateOfBirth" class="form-label">Date of Birth</label>
                <input type="date" class="form-control" id="dateOfBirth" name="dateOfBirth" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role">
                    <option value="USER">User</option>
                    <option value="ADMIN">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="addUser">Add User</button>
        </form>
    </div>

    <!-- User Table -->
    <h2 class="mb-4">Users</h2>
    <?php if ($updateMessage): ?>
        <div class="alert alert-success"><?=htmlspecialchars($updateMessage)?></div>
    <?php endif;?>
    <?php if ($deleteMessage): ?>
        <div class="alert alert-success"><?=htmlspecialchars($deleteMessage)?></div>
    <?php endif;?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Address</th>
                <th>Gender</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?=htmlspecialchars($user['id'])?></td>
                    <td><?=htmlspecialchars($user['fullname'])?></td>
                    <td><?=htmlspecialchars($user['address'])?></td>
                    <td><?=htmlspecialchars($user['gender'])?></td>
                    <td><?=htmlspecialchars($user['phone_number'])?></td>
                    <td><?=htmlspecialchars($user['email'])?></td>
                    <td>
                        <!-- Update Form -->
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal<?=htmlspecialchars($user['id'])?>">Update</button>

                        <!-- Delete Form -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="deleteUserId" value="<?=htmlspecialchars($user['id'])?>">
                            <button type="submit" class="btn btn-danger" name="deleteUser">Delete</button>
                        </form>

                        <!-- Update User Modal -->
                        <div class="modal fade" id="updateModal<?=htmlspecialchars($user['id'])?>" tabindex="-1" aria-labelledby="updateModalLabel<?=htmlspecialchars($user['id'])?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="updateModalLabel<?=htmlspecialchars($user['id'])?>">Update User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST">
                                            <input type="hidden" name="editUserId" value="<?=htmlspecialchars($user['id'])?>">
                                            <div class="mb-3">
                                                <label for="editFullName<?=htmlspecialchars($user['id'])?>" class="form-label">Full Name</label>
                                                <input type="text" class="form-control" id="editFullName<?=htmlspecialchars($user['id'])?>" name="editFullName" value="<?=htmlspecialchars($user['fullname'])?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editPhoneNumber<?=htmlspecialchars($user['id'])?>" class="form-label">Phone Number</label>
                                                <input type="text" class="form-control" id="editPhoneNumber<?=htmlspecialchars($user['id'])?>" name="editPhoneNumber" value="<?=htmlspecialchars($user['phone_number'])?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editAddress<?=htmlspecialchars($user['id'])?>" class="form-label">Address</label>
                                                <input type="text" class="form-control" id="editAddress<?=htmlspecialchars($user['id'])?>" name="editAddress" value="<?=htmlspecialchars($user['address'])?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editDateOfBirth<?=htmlspecialchars($user['id'])?>" class="form-label">Date of Birth</label>
                                                <input type="date" class="form-control" id="editDateOfBirth<?=htmlspecialchars($user['id'])?>" name="editDateOfBirth" value="<?=htmlspecialchars($user['date_of_birth'])?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editEmail<?=htmlspecialchars($user['id'])?>" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="editEmail<?=htmlspecialchars($user['id'])?>" name="editEmail" value="<?=htmlspecialchars($user['email'])?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editPassword<?=htmlspecialchars($user['id'])?>" class="form-label">Password</label>
                                                <input type="password" class="form-control" id="editPassword<?=htmlspecialchars($user['id'])?>" name="editPassword">
                                            </div>
                                            <div class="mb-3">
                                                <label for="editRole<?=htmlspecialchars($user['id'])?>" class="form-label">Role</label>
                                                <select class="form-select" id="editRole<?=htmlspecialchars($user['id'])?>" name="editRole">
                                                    <option value="USER" <?=$user['role'] === 'USER' ? 'selected' : ''?>>User</option>
                                                    <option value="ADMIN" <?=$user['role'] === 'ADMIN' ? 'selected' : ''?>>Admin</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary" name="updateUser">Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
</body>
</html>
