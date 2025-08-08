<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        const previewImage = document.getElementById('preview-image-user');
        const password = document.getElementById('user_password');
        const confirmPassword = document.getElementById('user_password_confirmation');

        function resetUserForm() {
            const form = document.getElementById('user-form');
            form.reset();
            document.getElementById('user-id').value = '';
            previewImage.src = '';
            previewImage.style.display = 'none';
            password.required = true;
            confirmPassword.required = true;
        }

        document.getElementById('user_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewImage.src = event.target.result;
                    previewImage.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewImage.src = '';
                previewImage.style.display = 'none';
            }
        });

        $('#btn-reset').off('click').on('click', function() {
            const $btn = $(this);
            const $original = $btn.html();

            $btn.html(
                `<span><span class="spinner-border spinner-border-sm mr-1" role="status"></span> Đang xóa tìm...</span>`
            );
            $btn.prop('disabled', true);

            $('#filter-form')[0].reset();
            $('#users-table').DataTable().ajax.reload(() => {
                $btn.html($original);
                $btn.prop('disabled', false);
            });
        });
        let searchTimeout;

        $('#btn-search').off('click').on('click', function() {
            const $btn = $(this);
            const $original = $btn.html();

            $btn.html(
                `<span><span class="spinner-border spinner-border-sm mr-1" role="status"></span> Đang tìm kiếm...</span>`
            );
            $btn.prop('disabled', true);

            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                $('#users-table').DataTable().ajax.reload(() => {
                    $btn.html($original);
                    $btn.prop('disabled', false);
                });
            }, 300);
        });


        $('#btn-add-user').click(function() {
            resetUserForm();
            $('#userModalLabel').text('Thêm người dùng');
            $('#user-modal').modal('show');
        });

        $(document).on('click', '.btn-user-edit', function() {
            const user = $(this).data();
            $('#userModalLabel').text('Cập nhật người dùng');
            $('#user-id').val(user.id);
            $('#user_name').val(user.name);
            $('#user_email').val(user.email);
            $('#phone').val(user.phone);
            $('#user_is_active').val(user.is_active);
            $('#group_role').val(user.group_role);

            if (user.photo) {
                $('#preview-image-user').attr('src', user.photo).show();
            } else {
                $('#preview-image-user').attr('src', '').hide();
            }

            password.required = false;
            confirmPassword.required = false;
            password.value = '';
            confirmPassword.value = '';

            $('#user-modal').modal('show');
        });

        let isSubmitting = false;
        $(document).off('submit', '#user-form').on('submit', '#user-form', function(e) {
            e.preventDefault();
            if (isSubmitting) return;
            isSubmitting = true;

            const formData = new FormData(this);
            const userId = $('#user-id').val();
            const url = userId ? `/admin/users/update/${userId}` : `/admin/users/store`;

            if (userId) formData.append('_method', 'PUT');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function() {
                    $('#user-modal').modal('hide');
                    resetUserForm();
                    $('#users-table').DataTable().ajax.reload(null, false);
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: userId ? 'Cập nhật thành công' : 'Thêm mới thành công'
                    });
                },
                error: function(xhr) {
                    let msg = 'Đã xảy ra lỗi. Vui lòng thử lại!';
                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        msg = '<ul style="text-align:left;">';
                        for (const field in xhr.responseJSON.errors) {
                            xhr.responseJSON.errors[field].forEach(error => {
                                msg += `<li>${error}</li>`;
                            });
                        }
                        msg += '</ul>';
                    } else if (xhr.responseJSON?.message) {
                        msg =
                            `<strong>Lỗi hệ thống:</strong><br>${xhr.responseJSON.message}`;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        html: msg
                    });
                },
                complete: function() {
                    isSubmitting = false;
                }
            });
        });

        if (!$.fn.DataTable.isDataTable('#users-table')) {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                lengthMenu: [
                    [20, 40, 50, -1],
                    [20, 40, 50, "Tất cả"]
                ],
                ajax: {
                    url: "{{ route('admin.users.list') }}",
                    data: function(d) {
                        d.name = $('#filter_name').val();
                        d.email = $('#filter_email').val();
                        d.is_active = $('#filter_is_active').val();
                        d.group_role = $('#filter_group_role').val();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            let html = '<ul style="text-align:left;">';
                            for (const field in errors) {
                                html += `<li>${errors[field][0]}</li>`;
                            }
                            html += '</ul>';
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi tìm kiếm',
                                html: html,
                                confirmButtonText: 'Đóng'
                            });
                            $('#filter-form')[0].reset();
                            $('#users-table').DataTable().ajax.reload();
                        }
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'id',
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'is_active'
                    },
                    {
                        data: 'group_role'
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
        $(document).on('click', '.btn-toggle-status', function() {
            const name = $(this).data('name');
            const url = $(this).data('url');
            const isActive = $(this).data('status') === 'active';


            Swal.fire({
                title: 'Nhắc nhở',
                text: `Bạn có muốn ${isActive ? 'khóa' : 'mở khóa'} thành viên ${name} không?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'OK',
                cancelButtonText: 'Hủy bỏ',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(url, {
                        _token: '{{ csrf_token() }}'
                    }, function(res) {
                        Swal.fire('Thành công', res.message, 'success');
                        $('#users-table').DataTable().ajax.reload();
                    }).fail(function() {
                        Swal.fire('Lỗi', 'Có lỗi xảy ra!', 'error');
                    });
                }
            });
        });
        $(document).on('click', '.btn-delete-user', function() {
            const name = $(this).data('name');
            const url = $(this).data('url');

            Swal.fire({
                title: 'Nhắc nhở',
                text: `Bạn có muốn xoá thành viên ${name} không?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'OK',
                cancelButtonText: 'Hủy bỏ',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(url, {
                        _token: '{{ csrf_token() }}'
                    }, function(res) {
                        Swal.fire('Thành công', res.message, 'success');
                        $('#users-table').DataTable().ajax.reload();
                    }).fail(function() {
                        Swal.fire('Lỗi', 'Không thể xoá người dùng!', 'error');
                    });
                }
            });
        });
        document.getElementById('user_name').addEventListener('input', function() {
            this.value = this.value.replace(/\d/g, '');
        });

        $(document).on('mouseenter', '.user-hover', function() {
            const imageUrl = $(this).data('img');
            $(this).css('--hover-img', `url(${imageUrl})`);
        });
    });
</script>
