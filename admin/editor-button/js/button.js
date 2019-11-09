(function($){
    $(function(){
        $('body').on('grav-editor-ready', function() {
            var Instance = Grav.default.Forms.Fields.EditorField.Instance;
            Instance.addButton({
                redcircle: {
                    identifier: 'redcircle-podcast',
                    title: 'RedCircle Podcast',
                    label: '<i class="fa fa-fw fa-podcast"></i>',
                    modes: ['gfm', 'markdown'],
                    action: function(_ref) {
                        var codemirror = _ref.codemirror, button = _ref.button, textarea = _ref.textarea;
                        button.on('click.editor.redcircle', function() {
                            var podcastURL = prompt("Enter the RedCircle Podcast URL, e.g.: https://api.podcache.net/embedded-player/sh/272df5be-5896-44b8-9a73-159e42a6588c/ep/063d5bda-40b5-4453-88ed-2620522b1505");

                            if (podcastURL) {
                                var text = '[plugin:redcircle](' + podcastURL + ')';

                                //Add text to the editor
                                var pos     = codemirror.getDoc().getCursor(true);
                                var posend  = codemirror.getDoc().getCursor(false);

                                for (var i=pos.line; i<(posend.line+1);i++) {
                                    codemirror.replaceRange(text+codemirror.getLine(i), { line: i, ch: 0 }, { line: i, ch: codemirror.getLine(i).length });
                                }

                                codemirror.setCursor({ line: posend.line, ch: codemirror.getLine(posend.line).length });
                                codemirror.focus();
                            }
                        });
                    }
                }
            });
        });
    });
})(jQuery);
