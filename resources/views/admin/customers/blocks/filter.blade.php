<div class="card-header">
    <div class="mb-3 bg-light p-3 rounded">
        <div id="filter-errors" class="alert alert-danger d-none">
            <ul class="mb-0" id="filter-errors-list"></ul>
        </div>

        <form id="filter-form" class="row align-items-end">
            <div class="col-md-3 mb-2">
                <label for="filter_name" class="small font-weight-bold">Tên khách hàng</label>
                <input type="text" name="customer_name" class="form-control" id="filter_name"
                    placeholder="Nhập tên khách hàng">
            </div>
            <div class="col-md-3 mb-2">
                <label for="filter_email" class="small font-weight-bold">Email khách hàng</label>
                <input type="text" name="email" class="form-control" id="filter_email"
                    placeholder="Nhập email khách hàng">
            </div>
            <div class="col-md-3 mb-2">
                <label for="filter_address" class="small font-weight-bold">Địa chỉ khách hàng</label>
                <input type="text" name="address" class="form-control" id="filter_address"
                    placeholder="Nhập địa chỉ khách hàng">

            </div>
            <div class="col-md-3 mb-2">
                <label for="filter_status" class="small font-weight-bold">Trạng thái</label>
                <select id="filter_status" class="form-control" name="is_active">
                    <option value="">Chọn trạng thái</option>
                    <option value="1">Hoạt động</option>
                    <option value="0">Không hoạt động</option>
                </select>
            </div>

            <div class="col-md-12 mb-2 d-flex align-items-end">
                <div class="d-flex mb-3">
                    <!-- Nút Tìm kiếm -->
                    <button type="button" class="btn btn-primary mr-2" id="btn-search">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text"><i class="fas fa-search"></i> Tìm kiếm</span>
                    </button>
                    <button type="button" class="btn btn-secondary mr-2" id="btn-reset">
                        <i class="fas fa-times"></i> Xóa tìm
                    </button>

                    <!-- Nút Xuất Excel -->
                    <button type="button" class="btn btn-success mr-2" id="btn-export-excel">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text"><i class="fas fa-file-excel"></i> Xuất Excel</span>
                    </button>

                    <!-- Nút Nhập Excel -->
                    <button type="button" class="btn btn-info" id="btn-import-excel">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text"><i class="fas fa-upload"></i> Nhập Excel</span>
                    </button>
                </div>

            </div>
        </form>
        <form id="import-form" action="{{ route('admin.customers.import') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" id="file-input" accept=".xlsx">
            <button type="button" id="remove-file" class="btn btn-danger btn-sm d-none">Xóa file</button>
            <span class="spinner-border spinner-border-sm d-none"></span>
            <button type="submit" class="btn btn-primary btn-sm">Nhập Excel</button>
        </form>
        @error('file')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror

        <form id="export-form" action="{{ route('admin.customers.export') }}" method="GET" style="display: none;">
            <input type="hidden" name="customer_name">
            <input type="hidden" name="email">
            <input type="hidden" name="address">
            <input type="hidden" name="is_active">
        </form>

    </div>
</div>
