$(document).ready(function () {
    $('body').on('click', '.delete-btn', function () {
        let id = $(this).data('id');
        let url = $(this).data('url');

        if (confirm("Are you sure you want to delete this item?")) {
            $.ajax({
                url: url,
                dataType: "json",
                type: "DELETE",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id
                },
                success: function (response) {
                    if (response.status === 'success') {
                        location.reload();
                    } else if (response.status === 'error') {
                        alert(response.message);
                    } else if (response.status === 'exceptionError') {
                        alert(response.error);
                    }
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    alert("Something went wrong.");
                }
            });
        }
    });
});
