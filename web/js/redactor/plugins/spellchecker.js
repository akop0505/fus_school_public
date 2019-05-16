(function($)
{
	$.Redactor.prototype.spellchecker = function()
	{
		return {
			init: function()
			{
				var btn = this.button.add('spellchecker', 'Spellchecker');
				this.button.addCallback(btn, this.spellchecker.toggle);
			},
			create: function()
			{
				this.spellchecker.spellchecker = new $.SpellChecker(this.$editor, {
					lang: 'en',
					parser: 'html',
					webservice: {
						path: "/profile/spellcheck"
					},
					suggestBox: {
						position: 'below'
					}
				});

				// Bind spellchecker handler functions
				this.spellchecker.spellchecker.on('check.success', function() {
					alert('There are no incorrectly spelt words.');
				});
			},
			toggle: function()
			{
				if (!this.spellchecker.spellchecker) {
					this.button.get('spellchecker').addClass('redactor-act-my');
					this.spellchecker.create();
					this.spellchecker.spellchecker.check();
				} else {
					this.button.get('spellchecker').removeClass('redactor-act-my');
					this.spellchecker.spellchecker.destroy();
					this.spellchecker.spellchecker = null;
				}
			},
			disable(){
                if (this.spellchecker.spellchecker) {
                    this.button.get('spellchecker').removeClass('redactor-act-my');
                    this.spellchecker.spellchecker.destroy();
                    this.spellchecker.spellchecker = null;
				}
			},
			getState(){
				return this.spellchecker.spellchecker;
			}
		};
	};
})(jQuery);