(function($)
{
	$.Redactor.prototype.limiter = function()
	{
		return {
			init: function()
			{
				if (!this.opts.limiter)
				{
					return;
				}

				this.core.editor().on('keydown.redactor-plugin-limiter', $.proxy(function(e)
				{
					var key = e.which;
					var ctrl = e.ctrlKey || e.metaKey;

					if (key === this.keyCode.BACKSPACE
					   	|| key === this.keyCode.DELETE
					    || key === this.keyCode.ESC
					    || key === this.keyCode.SHIFT
						|| key === this.keyCode.HOME
						|| key === this.keyCode.END
						|| key === this.keyCode.LEFT
						|| key === this.keyCode.RIGHT
						|| key === this.keyCode.UP
						|| key === this.keyCode.DOWN
					    || (ctrl && key === 65)
					    || (ctrl && key === 82)
					    || (ctrl && key === 116)
					)
					{
						return;
					}

					var text = redactorPrepareTextForCount(this.code.get());

					var count = text.length;
					//console.log("L "+ count);
					if (count >= this.opts.limiter)
					{
						return false;
					}


				}, this));

			}
		};
	};
})(jQuery);