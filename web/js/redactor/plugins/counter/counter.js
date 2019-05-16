(function($)
{
	$.Redactor.prototype.counter = function()
	{
		return {
			init: function()
			{
				if (typeof this.opts.callbacks.counter === 'undefined')
				{
					return;
				}
				var $this = $.proxy(this.counter.count, this);
				this.core.editor().on('keyup.redactor-plugin-counter', function() {
					setTimeout($this, 200);
				});
				setTimeout($this, 500);
			},
			count: function()
			{
				var words = 0, characters = 0, spaces = 0;
				var text = redactorPrepareTextForCount(this.code.get());

				if (text !== '')
				{
					var arrWords = text.split(/\s+/);
					var arrSpaces = text.match(/\s/g);

					words = (arrWords) ? arrWords.length : 0;
					spaces = (arrSpaces) ? arrSpaces.length : 0;

					characters = text.length;
					//console.log("C "+ characters);
				}

				this.core.callback('counter', { words: words, characters: characters, spaces: spaces });
			}
		};
	};
})(jQuery);