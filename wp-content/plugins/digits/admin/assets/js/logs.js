jQuery(function () {

    var data_table = jQuery('#digits_message_logs');
    data_table.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url": digmeslog.ajax_url,
            "data": function (d) {
                d.action = 'digits_message_log_data';
                d.nonce = data_table.data('nonce');

            },
            "type": "POST"
        },
        order: [[1, "ASC"]],
        pageLength: 15,
        searching: false,
        lengthChange: false,
        ordering: false,
        columns: [
            {data: 'date_time'},
            {data: 'to'},
            {data: 'route'},
            {data: 'action'},
            {data: 'content'},
        ],
        language: {
            paginate: {
                next: '<span class="digits-log-arrow digits-log-arrow_right"></span>',
                previous: '<span class="digits-log-arrow digits-log-arrow_left"></i>'
            }
        }
    });

});