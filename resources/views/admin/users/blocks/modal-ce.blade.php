<div class="modal fade" id="user-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="user-form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="user-id">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Thêm người dùng</h5>

                    <button type="button" class="btn-close btn btn-danger" data-dismiss="modal"
                        aria-label="Close">X</button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-6 mb-3">
                        <label>Username người dùng</label>
                        <input type="text" id="user_name" name="name" class="form-control" required
                            placeholder="Nhập tên người dùng (không chứa số và kí tự đặc biệt)" pattern="^[A-Za-zÀ-ỹ\s]+$">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Email người dùng</label>
                        <input type="email" id="user_email" name="email" class="form-control" required
                            placeholder="Nhập địa chỉ email">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Số điện thoại</label>
                        <input type="tel" id="phone" name="phone" class="form-control" required
                            pattern="^(0|\+84)(\d{9})$" maxlength="13" placeholder="VD: 0935769312 hoặc +84935769312">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Mật khẩu</label>
                        <input type="password" id="user_password" name="password" class="form-control" required
                            minlength="6" placeholder="Tối thiểu 6 ký tự">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Nhập lại mật khẩu</label>
                        <input type="password" id="user_password_confirmation" name="password_confirmation"
                            class="form-control" required minlength="6" placeholder="Nhập lại mật khẩu để xác nhận">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Nhóm người dùng</label>
                        <select name="group_role" id="group_role" class="form-control" required>
                            <option value="">-- Chọn nhóm quyền --</option>
                            <option value="Admin">Quản trị viên</option>
                            <option value="Reviewer">Người nhận xét</option>
                            <option value="Editor">Người chỉnh sửa</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Trạng thái</label>
                        <select name="is_active" id="user_is_active" class="form-control" required>
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="active">Đang hoạt động</option>
                            <option value="inactive">Ngưng hoạt động</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Hình ảnh</label>
                        <input type="file" name="photo" class="form-control" id="user_image" >
                        <img id="preview-image-user" src="" style="width: 100px; margin-top: 5px; display: none;">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

