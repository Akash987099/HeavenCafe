$(document).ready(function () {
    $('.category_by').on('change', function () {
        var id = $(this).val();
        var url = $(this).data('url');

        // ID को सही करो
        $('#service_list').html('<option value="">--Select Service--</option>');

        if (!id) return;

        $.ajax({
            url: url,
            type: "GET",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: id
            },
            success: function (response) {
                if (response.status === 'success') {
                    let html = '<option value="">--Select Service--</option>';

                    response.data.forEach(function (doc) {
                        html += `<option value="${doc.id}">${doc.name}</option>`;
                    });

                    $('#service_list').html(html);
                } else {
                    $('#service_list').html('<option value="">No services found</option>');
                }
            },
            error: function () {
                $('#service_list').html('<option value="">Something went wrong!</option>');
            }
        });
    });
});
