<div class="modal fade" id="product-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="product-form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="product_id" id="product-id">
                <div class="modal-header">
                    <h5 class="modal-title" id="product-modal-title">Thêm sản phẩm</h5>
                    <button type="button" class="btn-close btn btn-danger" data-dismiss="modal"
                        aria-label="Close">X</button>
                </div>

                <div class="modal-body row">
                    <div class="col-md-6 mb-3">
                        <label>Tên sản phẩm</label>
                        <input type="text" id="product_name" placeholder="Nhập thông tin tên sản phẩm ở đây."
                            name="product_name" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Giá bán</label>
                        <input type="text" id="product_price_format" class="form-control"
                            placeholder=" Nhập thông tin giá sản phẩm ở đây (VNĐ)" />
                        <input type="hidden" name="product_price" id="product_price" />
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Trạng thái</label>
                        <select name="is_sales" id="is_sales" class="form-control" required>
                            <option value="">Chọn trạng thái</option>
                            <option value="in_storage">Đang bán</option>
                            <option value="stop_sales">Ngừng bán</option>
                            <option value="out_of_stock">Hết hàng</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Hình ảnh</label>
                        <input type="file" name="product_image" class="form-control" id="product-image-prd"
                            accept="image/*">

                        <div id="image-preview-wrapper" style="margin-top: 5px; display: none;">
                            <img id="preview-image-prd" src="" style="width: 100px;">
                            <button type="button" class="btn btn-sm btn-danger mt-1" id="remove-image-btn">Xoá
                                ảnh</button>
                            <input type="hidden" name="remove_image" id="remove-image-flag" value="0">
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <label>Mô tả</label>
                        <textarea name="product_description" id="product_description" placeholder="Nhập thông tin mô tả sản phẩm ở dây."
                            class="form-control" rows="3"></textarea>
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
