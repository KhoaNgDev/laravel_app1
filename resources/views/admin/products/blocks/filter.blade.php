<div class="card-header">
    <div class="mb-3 bg-light p-3 rounded">
        <div id="filter-errors" class="alert alert-danger d-none">
            <ul class="mb-0" id="filter-errors-list"></ul>
        </div>

        <form id="filter-form" class="row align-items-end">
            <div class="col-md-6 mb-2">
                <label for="filter_name" class="small font-weight-bold">Tên sản phẩm</label>
                <input type="text" name="product_name" class="form-control" id="filter_name"
                    placeholder="Nhập tên sản phẩm">
            </div>

            <div class="col-md-4 mb-2">
                <label for="filter_status" class="small font-weight-bold">Trạng thái</label>
                <select id="filter_status" class="form-control">
                    <option value="">Chọn trạng thái</option>
                    <option value="in_storage">Đang bán</option>
                    <option value="stop_sales">Ngừng bán</option>
                    <option value="out_of_stock">Hết hàng</option>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label class="small font-weight-bold">Giá bán từ</label>
                <input type="text" class="form-control" id="filter_price_min" placeholder="VNĐ" min="0">
            </div>

            <div class="col-md-3 mb-2">
                <label class="small font-weight-bold">Giá bán đến thấp hơn</label>
                <input type="text" class="form-control" id="filter_price_max" placeholder="VNĐ" min="0">
            </div>
            <div class="col-md-6 mb-2"></div>
            <div class="col-md-3 mb-2 d-flex align-items-end">
                <!-- Tìm kiếm -->
                <button type="button" class="btn btn-primary mr-2" id="btn-search">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"
                        id="search-spinner"></span>
                    <span class="search-text"><i class="fas fa-search">Đang tìm...</i> Tìm kiếm</span>
                </button>

                <!-- Xóa tìm -->
                <button type="button" class="btn btn-secondary mr-2" id="btn-reset">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"
                        id="reset-spinner">Đang thực hiện...</span>
                    <span class="reset-text"><i class="fas fa-times"></i> Xóa tìm</span>
                </button>

            </div>
        </form>
    </div>
</div>
