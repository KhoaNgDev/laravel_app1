  <div class="card-header">
      <div class="mb-3 bg-light p-3 rounded">
          <div id="filter-errors" class="alert alert-danger d-none">
              <ul class="mb-0" id="filter-errors-list"></ul>
          </div>

          <form id="filter-form" class="row align-items-end">
              <div class="col-md-6 mb-2">
                  <label for="filter_name" class="small font-weight-bold">Tên người dùng</label>
                  <input type="text" name="name" class="form-control" id="filter_name"
                      placeholder="Nhập tên người dùng">
              </div>

              <div class="col-md-6 mb-2">
                  <label for="filter_email" class="small font-weight-bold">Email</label>
                  <input type="email" name="email" class="form-control" id="filter_email"
                      placeholder="Nhập email người dùng">
              </div>

              <div class="col-md-4 mb-2">
                  <label for="filter_is_active" class="small font-weight-bold">Trạng thái</label>
                  <select id="filter_is_active" class="form-control">
                      <option value="">Chọn trạng thái</option>
                      <option value="active">Hoạt động</option>
                      <option value="inactive">Ngưng hoạt động</option>
                  </select>
              </div>
              <div class="col-md-4 mb-2">
                  <label for="filter_group_role" class="small font-weight-bold">Nhóm người dùng</label>
                  <select id="filter_group_role" class="form-control">
                      <option value="">Chọn trạng thái</option>
                      <option value="Admin">Quản trị viên</option>
                      <option value="Reviewer">Người nhận xét</option>
                      <option value="Editor">Người chỉnh sửa</option>

                  </select>
              </div>


              <div class="col-md-6 mb-2"></div>
              <div class="col-md-3 mb-2 d-flex align-items-end">
                  <button type="button" class="btn btn-primary mr-2" id="btn-search">
                      <i class="fas fa-search"></i> Tìm kiếm
                  </button>
                  <button type="button" class="btn btn-secondary mr-2" id="btn-reset">
                      <i class="fas fa-times"></i> Xóa tìm
                  </button>

              </div>
          </form>
      </div>
  </div>
