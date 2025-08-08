<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
    var table;
    $(function() {
        function setLoading($btn, isLoading, loadingText = 'Đang xử lý...') {
            const $spinner = $btn.find('.spinner-border');
            const $text = $btn.find('.btn-text');

            if (isLoading) {
                $btn.prop('disabled', true);
                $spinner.removeClass('d-none');
                $text.text(loadingText);
            } else {
                $btn.prop('disabled', false);
                $spinner.addClass('d-none');
                $text.html($btn.data('original-text'));
            }
        }

        $(document).ready(function() {
            ['#btn-search', '#btn-export', '#btn-reset'].forEach(selector => {
                const $btn = $(selector);
                const original = $btn.find('.btn-text').html();
                $btn.data('original-text', original);
            });
        });

        if (!$.fn.DataTable.isDataTable('#customers-table')) {
            table = $('#customers-table').DataTable({
                lengthMenu: [
                    [20, 40, 50, -1],
                    [20, 40, 50, "All"]
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.customers.list') }}",
                    data: function(d) {
                        d.customer_name = $('#filter_name').val();
                        d.email = $('#filter_email').val();
                        d.is_active = $('#filter_status').val();
                        d.address = $('#filter_address').val();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            let html = '<ul class="text-left">';
                            for (const key in errors) {
                                html += `<li>${errors[key][0]}</li>`;
                            }
                            html += '</ul>';
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi tìm kiếm',
                                html: html
                            });
                            $('#filter-form')[0].reset();
                            table.draw();
                        }
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'customer_name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'address'
                    },
                    {
                        data: 'tel_num'
                    },
                    {
                        data: 'is_active'
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
        $('#btn-search').off('click').on('click', function() {
            const $btn = $(this);
            const name = $('#filter_name').val();

            if (name && !/^[\p{L}\s]+$/u.test(name)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi tìm kiếm',
                    text: 'Tên khách hàng chỉ được chứa ký tự chữ.'
                });
                return;
            }

            setLoading($btn, true, 'Đang tìm...');
            table.ajax.reload(null, false);

            table.one('draw', function() {
                setLoading($btn, false);
            });
        });

        $('#btn-reset').off('click').on('click', function() {
            const $btn = $(this);
            setLoading($btn, true, 'Đang xóa...');

            $('#filter-form')[0].reset();
            table.ajax.reload(null, false);

            table.one('draw', function() {
                setLoading($btn, false);
            });
        });


        $('#btn-export-excel').off('click').on('click', function(e) {
            e.preventDefault();
            const $btn = $(this);

            setLoading($btn, true, 'Đang xuất...');

            $('#export-form input[name="customer_name"]').val($('#filter_name').val());
            $('#export-form input[name="email"]').val($('#filter_email').val());
            $('#export-form input[name="address"]').val($('#filter_address').val());
            $('#export-form input[name="is_active"]').val($('#filter_status').val());

            setTimeout(() => {
                $('#export-form')[0].submit();
                setLoading($btn, false);
            }, 500);
        });

        $('#btn-import-excel').on('click', function() {
            $('#file-input').click();
        });

        $('#file-input').on('change', function() {
            if (this.files.length > 0) {
                const $btn = $('#btn-import-excel');
                setLoading($btn, true, 'Đang nhập...');
                $('#remove-file').removeClass('d-none'); // Hiện nút xóa

                const formData = new FormData($('#import-form')[0]);

                $.ajax({
                    url: $('#import-form').attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        Swal.fire('Thành công', res.message || 'Đã nhập dữ liệu',
                        'success');
                        $('#file-input').val('');
                        $('#remove-file').addClass('d-none');
                        table.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        Swal.fire('Lỗi', xhr.responseJSON?.message || 'Không thể nhập file',
                            'error');
                        setLoading($btn, false);
                    },
                    complete: function() {
                        setLoading($btn, false);
                    }
                });
            }
        });

        $(document).on('click', '.btn-edit', function() {
            const row = $(this).closest('tr');
            const id = $(this).data('id');

            const oldData = {
                name: row.find('td:eq(1)').text().trim(),
                email: row.find('td:eq(2)').text().trim(),
                address: row.find('td:eq(3)').text().trim(),
                phone: row.find('td:eq(4)').text().trim(),
                status: row.find('td:eq(5)').text().includes('Hoạt') ? 1 : 0
            };

            row.find('td:eq(1)').html(
                `<input class="form-control form-control-sm" value="${oldData.name}">`);
            row.find('td:eq(2)').html(
                `<input class="form-control form-control-sm" value="${oldData.email}">`);
            row.find('td:eq(3)').html(
                `<input class="form-control form-control-sm" value="${oldData.address}">`);
            row.find('td:eq(4)').html(
                `<input class="form-control form-control-sm" value="${oldData.phone}">`);
            row.find('td:eq(5)').html(`
        <select class="form-control form-control-sm">
            <option value="1" ${oldData.status == 1 ? 'selected' : ''}>Hoạt động</option>
            <option value="0" ${oldData.status == 0 ? 'selected' : ''}>Không hoạt động</option>
        </select>`);

            row.find('td:eq(6)').html(`
        <button class="btn btn-sm btn-success btn-save" data-id="${id}">Lưu</button>
        <button class="btn btn-sm btn-secondary btn-cancel">Hủy</button>
    `);
        });
        $(document).off('click', '.btn-save').on('click', '.btn-save', function() {
            const row = $(this).closest('tr');
            const id = $(this).data('id');

            const data = {
                _token: '{{ csrf_token() }}',
                customer_name: row.find('td:eq(1) input').val(),
                email: row.find('td:eq(2) input').val(),
                address: row.find('td:eq(3) input').val(),
                tel_num: row.find('td:eq(4) input').val(),
                is_active: row.find('td:eq(5) select').val()
            };

            $.ajax({
                url: `/admin/customers/${id}`,
                method: 'PUT',
                data,
                success: function() {
                    Swal.fire('Thành công!', 'Đã cập nhật khách hàng.', 'success');
                    table.ajax.reload(null, false);
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON.errors;
                    let msg = '';
                    for (const field in errors) {
                        msg += `${errors[field][0]}<br>`;
                    }
                    Swal.fire('Lỗi!', msg, 'error');
                  setLoading($btn, false);
                }
            });
        });
        $(document).on('click', '.btn-cancel', function() {
            table.ajax.reload(null, false);
        });
        $('#btn-import').on('click', function() {
            $('#file-input').click(); // Mở cửa sổ chọn file
        });

    });
    $(document).ready(function() {
        $('#form-create-customer').off('submit').on('submit', function(e) {
            e.preventDefault();
            const $form = $(this);
            const formData = $form.serialize();

            $.ajax({
                url: '{{ route('admin.customers.store') }}',
                method: 'POST',
                data: formData,
                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: res.message || 'Đã thêm khách hàng',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    $('#createCustomerModal').modal('hide');

                    $form[0].reset();
                    $('#create-error-msg').addClass('d-none').html('');

                    table.ajax.reload(null, false);
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let html = '<ul>';
                        $.each(errors, function(key, val) {
                            html += `<li>${val[0]}</li>`;
                        });
                        html += '</ul>';
                        $('#create-error-msg').removeClass('d-none').html(html);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: xhr.responseJSON.message || 'Vui lòng thử lại sau'
                        });
                    }
                }
            });
        });
    });

    $('#file-input').on('change', function() {
        if (this.files.length > 0) {
            const $btn = $('#btn-import');
            setLoading($btn, true, 'Đang nhập...');

            const formData = new FormData($('#import-form')[0]);

            $.ajax({
                url: $('#import-form').attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    Swal.fire('Thành công', res.message || 'Đã nhập dữ liệu', 'success');
                    $('#file-input').val('');
                    $('#remove-file').addClass('d-none');
                    table.ajax.reload(null, false);
                },
                error: function(xhr) {
                    Swal.fire('Lỗi', xhr.responseJSON?.message || 'Không thể nhập file', 'error');
                },
                complete: function() {
                    setLoading($btn, false);
                }
            });
        }
    });

    $('#remove-file').on('click', function() {
        $('#file-input').val('');
        $(this).addClass('d-none');
    });
</script>
