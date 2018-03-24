window.addEventListener('DOMContentLoaded', function()
{
    $("#__form").submit(function()
    {
        $('#__form-send').attr('disabled', true);
        return true;
    });
});