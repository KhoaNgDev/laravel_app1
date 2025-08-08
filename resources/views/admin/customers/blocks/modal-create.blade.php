<div class="modal fade" id="createCustomerModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="form-create-customer">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Thêm khách hàng</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span>&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div id="create-error-msg" class="alert alert-danger d-none"></div>
          <div class="form-group">
            <label>Tên khách hàng</label>
            <input type="text" name="customer_name" placeholder="Vui lòng nhập tên khách hàng tại đây." class="form-control" />
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" placeholder="Vui lòng nhập email khách hàng tại đây."  />
          </div>
          <div class="form-group">
            <label>Điện thoại</label>
            <input type="text" name="tel_num" class="form-control" placeholder="Vui lòng nhập SĐT khách hàng tại đây." />
          </div>
          <div class="form-group">
            <label>Địa chỉ</label>
            <input type="text" name="address" class="form-control" placeholder="Vui lòng nhập địa chỉ khách hàng tại đây."  />
          </div>
          <div class="form-group form-check">
            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" checked />
            <label class="form-check-label" for="is_active">Hoạt động</label>
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
