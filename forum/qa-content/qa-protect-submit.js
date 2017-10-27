// Protect from multiple form submit

window.addEventListener('DOMContentLoaded', function()
{
	$("#__form").submit(function()
	{
		$(this).submit(function()
		{
			return false;
		});
		
		return true;
	});
});