(function ($) {

    function updateFieldsetsView() {
        let template = templates[$('#transactionform-transaction_template_id').val()];
        if (template) {
            let speed = 600;
            $('#template_description').text(template.description).show();
            $('#notes_request').text(template.request).show();
            if (template.needs_project!=0) {
                $('#project_fieldset').show(speed);
            }
            else {
                $('#project_fieldset').hide(speed);
            }
            if (template.needs_vendor==1) {
                $('#vendor_fieldset').show(speed);
            }
            else {
                $('#vendor_fieldset').hide(speed);
            }
            if (template.needs_attachment==1) {
                $('#attachments_fieldset').show(speed);
            }
            else {
                $('#attachments_fieldset').hide(speed);
            }
            console.log("passing thru...");
            console.log(template.request);
            if (!!template.request) {
                console.log("showing...");
                $('#notes_request').show(speed);
            }
            else {
                $('#notes_request').hide(speed);
            }
        }
        else {
            $('#template_description').text('').hide();
        }
    }

    $('#transactionform-transaction_template_id').on('change', updateFieldsetsView);
    $(document).ready(updateFieldsetsView);
    
    $('#transactionform-vat_number').on('blur', function() {
        let typed = $(this).val();
        let number = typed;
        let vendor = '';
        
        let index = typed.indexOf(' - ');
        
        if (index > -1) {
            number = typed.substring(0, index);
            vendor = typed.substring(index+3, 9999);
            $(this).val(number);
            if (!$('#transactionform-vendor').val()) {
                $('#transactionform-vendor').val(vendor); // we won't overwrite typed values
            }
        }
        
        let saveButton = $('#save_button');
        let msg = $('#vat_number_check');
        
        if (number=='') {
            msg.html('').hide();
            saveButton.prop('disabled', false);
            return;
        }
        
        let valid = checkVATNumber(number);
        if (valid)
            msg.html(vat_number_messages.ok).removeClass('vat-number-bad').addClass('vat-number-ok').show();
        else
            msg.html(vat_number_messages.wrong).removeClass('vat-number-ok').addClass('vat-number-bad').show();
        
        saveButton.prop('disabled', !valid);
    });
    
    $('#toggle_advanced_view').click(function() {
        $('.advanced_view').toggle();
    });


})(window.jQuery);
