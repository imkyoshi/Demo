<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal<?php echo $user['id']; ?>"
    tabindex="-1" role="dialog"
    aria-labelledby="deleteUserModalLabel<?php echo $user['id']; ?>"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"
                    id="deleteUserModalLabel<?php echo $user['id']; ?>">
                    Delete User
                </h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal">Cancel</button>
                <form method="POST" action="">
                    <input type="hidden" name="deleteUserId"
                        value="<?php echo $user['id']; ?>">
                    <button type="submit" class="btn btn-danger"
                        name="deleteUser">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>