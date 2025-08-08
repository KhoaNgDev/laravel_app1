<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
    var tableProduct;
    $(function() {
        const $form = $('#product-form');
        const $modal = $('#product-modal');
        const $previewImage = $('#preview-image-prd');
        const $productId = $('#product-id');
        if (!$.fn.DataTable.isDataTable('#products-table')) {
            tableProduct = $('#products-table').DataTable({
                lengthMenu: [
                    [20, 40, 50, -1],
                    [20, 40, 50, "All"]
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.products.list') }}",
                    data: function(d) {
                        d.product_name = $('#filter_name').val();
                        d.is_sales = $('#filter_status').val();
                        d.price_min = $('#filter_price_min').val();
                        d.price_max = $('#filter_price_max').val();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            let html = '<ul style="text-align:left">';
                            for (const field in errors) {
                                html += `${errors[field][0]}`;
                            }
                            html += '</ul>';

                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi tìm kiếm',
                                html: html,
                                confirmButtonText: 'Đóng'
                            });

                            $('#filter-form')[0].reset();
                            tableProduct.draw();
                        }
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'product_id'
                    },
                    {
                        data: 'product_name'
                    },
                    {
                        data: 'product_price',
                        render: (data, type) => type === 'sort' ? data : new Intl.NumberFormat(
                            'vi-VN').format(data) + ' VNĐ'
                    },
                    {
                        data: 'is_sales'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    url: "/js/datatables/i18n/vi.json"
                }
            });
        }
        $('#btn-search').off('click').on('click', () => {
            const $btn = $('#btn-search');
            const $spinner = $('#search-spinner');
            const $text = $btn.find('.search-text');

            $spinner.removeClass('d-none');
            $text.addClass('d-none');
            $btn.prop('disabled', true);

            tableProduct.ajax.reload(() => {
                $spinner.addClass('d-none');
                $text.removeClass('d-none');
                $btn.prop('disabled', false);
            });
        });

        $('#btn-reset').off('click').on('click', () => {
            const $btn = $('#btn-reset');
            const $spinner = $('#reset-spinner');
            const $text = $btn.find('.reset-text');

            $spinner.removeClass('d-none');
            $text.addClass('d-none');
            $btn.prop('disabled', true);

            $('#filter-form')[0].reset();
            tableProduct.ajax.reload(() => {
                $spinner.addClass('d-none');
                $text.removeClass('d-none');
                $btn.prop('disabled', false);
            });
        });

        $(document).on('mouseenter', '.product-hover', function() {
            const imageUrl = $(this).data('img');
            if (imageUrl) {
                $(this).css('--hover-img', `url(${imageUrl})`);
            }
        });
        $('#btn-add-product').on('click', () => {
            $form[0].reset();
            $previewImage.attr('src', '');
            $('#image-preview-wrapper').hide();
            $productId.val('');
            $('#product-modal-title').text('Thêm sản phẩm');
            forceResetModal($modal);
            $modal.modal('show');
        });
        $(document).off('click', '.btn-edit').on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            $.get(`/admin/products/edit/${id}/`, function(res) {
                console.log(res.product_image_url);
                console.log(res);

                $('#product-modal-title').text('Chỉnh sửa sản phẩm');
                $('#product_name').val(res.product_name);
                $('#product_price').val(res.product_price);
                $('#product_price_format').val(
                    new Intl.NumberFormat('vi-VN').format(res.product_price) + ' VNĐ'
                );
                $('#product_description').val(res.product_description);
                $('#is_sales').val(res.is_sales);

                if (res.product_image_url) {
                    $('#preview-image-prd').attr('src', res.product_image_url).show();
                    $('#image-preview-wrapper').show();
                } else {
                    $('#preview-image-prd').attr('src', '').hide();
                    $('#image-preview-wrapper').hide();
                }


                $productId.val(id);
                forceResetModal($modal);
                $('#remove-image-flag').val(0);
                $modal.modal('show');
            });
        });
        $form.off('submit').on('submit', function(e) {
            e.preventDefault();
            const id = $productId.val();
            const url = id ? `/admin/products/update/${id}` : "{{ route('admin.products.store') }}";
            const formData = new FormData(this);
            if (id) formData.append('_method', 'PUT');

            const rawPrice = $('#product_price_format').val().replace(/[^\d]/g, '');
            $('#product_price').val(rawPrice);

            const $saveBtn = $('#save-btn');
            $saveBtn.prop('disabled', true);
            $('#save-spinner').removeClass('d-none');
            $('.save-text').text('Đang lưu...');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function() {
                    Swal.fire({
                        icon: 'success',
                        title: id ? 'Cập nhật thành công' : 'Thêm mới thành công',
                        confirmButtonText: 'Đóng'
                    });
                    $modal.modal('hide');
                    $form[0].reset();
                    $previewImage.attr('src', '').hide();
                    $productId.val('');
                    tableProduct.ajax.reload();
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let html = '<ul>';
                        $.each(errors, (key, val) => {
                            html += `<li style="list-style:none; ">${val[0]}</li>`;
                        });
                        html += '</ul>';

                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi xác thực',
                            html: html,
                            confirmButtonText: 'Đóng'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi xử lý',
                            text: 'Có lỗi xảy ra. Vui lòng thử lại!',
                            confirmButtonText: 'Đóng'
                        });
                    }
                },
                complete: function() {
                    $saveBtn.prop('disabled', false);
                    $('#save-spinner').addClass('d-none');
                    $('.save-text').text('Lưu');
                }
            });
        });


        function forceResetModal($modal) {
            $modal.removeClass('show').removeAttr('style');
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css('padding-right', '');
            $modal.removeAttr('aria-hidden');
        }
        const fileInput = document.getElementById('product-image-prd');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $previewImage.attr('src', e.target.result);
                        $('#image-preview-wrapper').show();

                    };
                    reader.readAsDataURL(file);
                } else {
                    $previewImage.attr('src', '').hide();
                }
            });
        }
    });
    document.addEventListener("DOMContentLoaded", function() {
        const formatCurrency = (number) => {
            return new Intl.NumberFormat('vi-VN').format(number);
        };
        const sanitize = (value) => value.replace(/\D/g, '').replace(/^0+/, '');
        const attachCurrencyInput = (input, hiddenInput = null) => {
            if (!input) return;

            input.addEventListener('input', function() {
                const raw = sanitize(this.value);
                this.value = formatCurrency(raw);
                if (hiddenInput) hiddenInput.value = raw;
            });

            input.addEventListener('keypress', function(e) {
                const invalidChars = ['-', '+', 'e', '.', ',', 'E'];
                if (invalidChars.includes(e.key) || isNaN(parseInt(e.key))) {
                    e.preventDefault();
                }
            });

            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                const raw = sanitize(pastedText);
                this.value = formatCurrency(raw);
                if (hiddenInput) hiddenInput.value = raw;
            });
        };
        attachCurrencyInput(
            document.getElementById('product_price_format'),
            document.getElementById('product_price')
        );
        attachCurrencyInput(document.getElementById('filter_price_min'));
        attachCurrencyInput(document.getElementById('filter_price_max'));


        $('#remove-image-btn').on('click', function() {
            $('#product-image-prd').val('');
            $('#preview-image-prd').attr('src', '');
            $('#image-preview-wrapper').hide();
            $('#remove-image-flag').val(1);
        });
        $('#cancel-btn').on('click', function() {
            const $btn = $(this);
            $btn.prop('disabled', true);
            $('#cancel-spinner').removeClass('d-none');
            $('.cancel-text').text('Đang hủy...');

            setTimeout(() => {
                $('#product-modal').modal('hide');
                $btn.prop('disabled', false);
                $('#cancel-spinner').addClass('d-none');
                $('.cancel-text').text('Hủy');
            }, 500);
        });

    });
</script>
