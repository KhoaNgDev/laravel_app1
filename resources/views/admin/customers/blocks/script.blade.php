<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
    var table;
    $(function() {
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
            const name = $('#filter_name').val();
            if (name && !/^[\p{L}\s]+$/u.test(name)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi tìm kiếm',
                    text: 'Tên khách hàng chỉ được chứa ký tự chữ.'
                });
                return;
            }
            table.ajax.reload(null, false);
        });
        $('#btn-reset').off('click').on('click', function() {
            $('#filter-form')[0].reset();
            table.ajax.reload(null, false);
        });

        $('#btn-export').click(function(e) {
            e.preventDefault();
            $('#export-form input[name="customer_name"]').val($('#filter_name').val());
            $('#export-form input[name="email"]').val($('#filter_email').val());
            $('#export-form input[name="address"]').val($('#filter_address').val());
            $('#export-form input[name="is_active"]').val($('#filter_status').val());
            $('#export-form')[0].submit();
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
                }
            });
        });
        $(document).on('click', '.btn-cancel', function() {
            table.ajax.reload(null, false);
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
            $('#remove-file').removeClass('d-none');
        } else {
            $('#remove-file').addClass('d-none');
        }
    });

    $('#remove-file').on('click', function() {
        $('#file-input').val('');
        $(this).addClass('d-none');
    });
</script>
