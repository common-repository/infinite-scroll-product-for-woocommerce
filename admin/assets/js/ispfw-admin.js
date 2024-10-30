jQuery(document).ready(function($) {
    $('#export-settings-button').on('click', function(e) {
        e.preventDefault();
        // Trigger the AJAX request for exporting settings

        let nonce = ispfwAjax.nonce;
        $.ajax({
            url: ispfwAjax.ajax_url, // The AJAX URL
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'ispfw_export_settings', 
                security: nonce 
            },
            success: function(response) {
                if (response) {
                    // Trigger the download of the JSON file
                    window.location.href = ispfwAjax.ajax_url + '?action=ispfw_export_settings';
                } else {
                    alert(response.data);
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
    });
});

jQuery(document).ready(function($) {
    $('.ispfw_settings_import').on('click', function(e) {
        e.preventDefault();

        // Get the uploaded file
        var fileInput = $('#ispfw_settings_import_file')[0].files[0];
        let nonce = ispfwAjax.nonce;

        if (!fileInput) {
            alert('Please select a file to import.');
            return;
        }

        var formData = new FormData();
        formData.append('import_file', fileInput);
        formData.append('security', nonce);
        formData.append('action', 'ispfw_plugin_import_settings'); // The AJAX action

        // Send the AJAX request
        $.ajax({
            url: ispfwAjax.ajax_url, // The AJAX URL
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    alert('Settings imported successfully!');
                    location.reload(); // Reload the page after successful import
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
});