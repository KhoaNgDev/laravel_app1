<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        @if (session('message'))
            Swal.fire({
                icon: "{{ session('alert-type', 'success') }}",
                title: "{{ session('alert-type') == 'error' ? 'Lỗi!' : 'Thành công!' }}",
                text: "{{ session('message') }}",
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
        @endif
        @if (session('import_success'))
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: "{{ session('import_success') }}",
                confirmButtonText: 'Đóng'
            });
        @endif

        @if (session('import_failures'))
            let htmlErrorList = `<ul style="text-align: left;">`;
            @foreach (session('import_failures') as $error)
                htmlErrorList += `<li>{!! $error !!}</li>`;
            @endforeach
            htmlErrorList += `</ul>`;

            Swal.fire({
                icon: 'error',
                title: 'Import thất bại',
                html: htmlErrorList,
                confirmButtonText: 'Đóng'
            });
        @endif


    });
    $(document).on('click', '.btn-delete', function() {
        const name = $(this).data('name');
        const url = $(this).data('url');

        Swal.fire({
            title: 'Nhắc nhở',
            text: `Bạn có muốn xoá dữ liệu "${name}" không?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'OK',
            cancelButtonText: 'Hủy bỏ',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Đã xoá!',
                            text: res.message || 'Dữ liệu đã được xoá.',
                            timer: 2000
                        });

                        const table = $(this).closest('table').DataTable();
                        table.ajax.reload(null, false);
                    }.bind(this),
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Không thể xoá dữ liệu. Vui lòng thử lại.'
                        });
                    }
                });
            }
        });
    });
</script>
