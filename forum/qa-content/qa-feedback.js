// Protect from multiple feedback send

window.addEventListener('DOMContentLoaded', function()
{
	$("#__feedback-form").submit(function()
	{
		$(this).submit(function()
		{
			return false;
		});
		
		return true;
	});
});