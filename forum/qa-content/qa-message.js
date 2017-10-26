// Protect from multiple private message send

window.addEventListener('DOMContentLoaded', function()
{
	$("#__message-form").submit(function()
	{
		$(this).submit(function()
		{
			return false;
		});
		
		return true;
	});
});