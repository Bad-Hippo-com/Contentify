(function (window, $) {
    'use strict';

    var instances = [];
    var flags = ['eu', 'at', 'de', 'dk', 'es', 'fi', 'fr', 'gr', 'it', 'nl', 'pl', 'pt', 'ru', 'se', 'uk', 'us'];

    function labels(locale) {
        if (locale === 'de') {
            return {
                images: 'Bilder',
                insertImages: 'Bilder einfügen',
                templates: 'Vorlagen',
                insertTemplate: 'Vorlage einfügen',
                flags: 'Flaggen',
                insertFlag: 'Flagge einfügen',
                search: 'Suchen'
            };
        }

        return {
            images: 'Images',
            insertImages: 'Insert images',
            templates: 'Templates',
            insertTemplate: 'Insert template',
            flags: 'Flags',
            insertFlag: 'Insert flag',
            search: 'Search'
        };
    }

    function assetUrl(path) {
        return contentify.assetUrl.replace(/\/+$/, '') + '/' + path.replace(/^\/+/, '');
    }

    function cleanHtml(html) {
        var template = document.createElement('template');

        template.innerHTML = html;
        Array.prototype.forEach.call(template.content.querySelectorAll('*'), function (element) {

            Array.prototype.slice.call(element.attributes).forEach(function (attribute) {
                if (attribute.name.indexOf('data-se-') === 0) {
                    element.removeAttribute(attribute.name);
                }
            });

            if (element.classList) {
                Array.prototype.slice.call(element.classList).forEach(function (className) {
                    if (className.indexOf('se-') === 0 || className.indexOf('__se__') === 0) {
                        element.classList.remove(className);
                    }
                });

                if (!element.className) {
                    element.removeAttribute('class');
                }
            }
        });

        return template.innerHTML;
    }

    function currentHtml(instance) {
        var frame = instance.editor.$.frameContext;

        if (frame.get('isCodeView')) {
            instance.editor.$.viewer.codeView(false);
        }

        return cleanHtml(instance.editor.$.html.get());
    }

    function sync(instance) {
        instance.textarea.value = currentHtml(instance);
    }

    function rememberSelection(instance) {
        var range = instance.editor.$.selection.getRange();

        instance.range = range ? range.cloneRange() : null;
    }

    function restoreSelection(instance) {
        var range = instance.range;

        if (!range || !document.contains(range.startContainer) || !document.contains(range.endContainer)) {
            return;
        }

        instance.editor.$.selection.setRange(
            range.startContainer,
            range.startOffset,
            range.endContainer,
            range.endOffset
        );
    }

    function insertHtml(instance, html) {
        restoreSelection(instance);
        instance.editor.$.html.insert(html, { selectInserted: false });
        sync(instance);
        instance.range = null;
    }

    function requestFailed(response) {
        contentify.alertRequestFailed(response);
    }

    function openImages(instance, text) {
        rememberSelection(instance);

        $.ajax({
            url: contentify.baseUrl + 'editor-images',
            type: 'GET'
        }).done(function (data) {
            var $data = $(data);

            function bindImages($scope) {
                $scope.find('.image').off('click.contentifyEditor').on('click.contentifyEditor', function () {
                    var image = document.createElement('img');
                    image.src = $(this).attr('data-src');
                    insertHtml(instance, image.outerHTML);
                    contentify.closeModal();
                });
            }

            function submitSearch(event) {
                if (event) {
                    event.preventDefault();
                }

                $.ajax({
                    url: contentify.baseUrl + 'editor-images',
                    type: 'POST',
                    data: { tag: $data.find('.editor-images input[type=text]').val() }
                }).done(function (result) {
                    var $images = $data.find('.editor-images .images');
                    $images.html(result);
                    bindImages($images);
                }).fail(requestFailed);
            }

            contentify.modal(text.images, $data);
            bindImages($data);
            $data.find('button').off('click.contentifyEditor').on('click.contentifyEditor', submitSearch);
            $data.find('input').off('keypress.contentifyEditor').on('keypress.contentifyEditor', function (event) {
                if (event.which === 13) {
                    submitSearch(event);
                }
            });
        }).fail(requestFailed);
    }

    function openTemplates(instance, text) {
        rememberSelection(instance);

        $.ajax({
            url: contentify.baseUrl + 'editor-templates',
            type: 'GET'
        }).done(function (data) {
            var $data = $(data);
            var $insert = $('<button type="button" class="btn btn-primary">').text(text.insertTemplate);

            function insertTemplate() {
                var id = $data.find('select').val();

                if (!id) {
                    return;
                }

                $.ajax({
                    url: contentify.baseUrl + 'editor-templates/' + encodeURIComponent(id),
                    type: 'GET'
                }).done(function (html) {
                    insertHtml(instance, html);
                    contentify.closeModal();
                }).fail(requestFailed);
            }

            $insert.on('click.contentifyEditor', insertTemplate);
            $data.find('select').on('dblclick.contentifyEditor', insertTemplate);
            contentify.modal(text.templates, $data, $insert);
        }).fail(requestFailed);
    }

    function openFlags(instance, text) {
        var $flags = $('<div class="editor-flags">');

        rememberSelection(instance);
        flags.forEach(function (flag) {
            var image = document.createElement('img');
            image.src = assetUrl('uploads/countries/' + flag + '.png');
            image.title = flag;
            image.alt = flag;
            $flags.append(image);
        });

        $flags.find('img').on('click.contentifyEditor', function () {
            insertHtml(instance, this.outerHTML);
            contentify.closeModal();
        });
        contentify.modal(text.flags, $flags);
    }

    function addContentifyToolbar(instance, text) {
        var $toolbar = $('<div class="contentify-editor-toolbar" role="toolbar">');
        var buttons = [
            { label: text.insertImages, icon: 'fa-image', action: openImages },
            { label: text.insertTemplate, icon: 'fa-file-text-o', action: openTemplates },
            { label: text.insertFlag, icon: 'fa-flag', action: openFlags }
        ];

        buttons.forEach(function (definition) {
            var $button = $('<button type="button" class="btn btn-default btn-sm">')
                .attr('title', definition.label)
                .attr('aria-label', definition.label)
                .append($('<i class="fa" aria-hidden="true">').addClass(definition.icon))
                .append(document.createTextNode(' ' + definition.label));

            $button.on('mousedown.contentifyEditor', function () {
                rememberSelection(instance);
            });
            $button.on('click.contentifyEditor', function () {
                definition.action(instance, text);
            });
            $toolbar.append($button);
        });

        $(instance.textarea).before($toolbar);
    }

    function editorOptions(textarea, locale) {
        var desktop = window.innerWidth > 768;
        var options = {
            plugins: SUNEDITOR.plugins,
            lang: window.SUNEDITOR_LANG && window.SUNEDITOR_LANG[locale]
                ? window.SUNEDITOR_LANG[locale]
                : undefined,
            width: '100%',
            minHeight: desktop ? '320px' : '240px',
            resizingBar: true,
            charCounter: false,
            strictMode: {
                tagFilter: false,
                formatFilter: false,
                classFilter: false,
                textStyleTagFilter: false,
                attrFilter: false,
                styleFilter: false
            },
            buttonList: desktop ? [
                ['undo', 'redo'],
                ['blockStyle', 'font', 'fontSize'],
                ['bold', 'underline', 'italic', 'strike', 'subscript', 'superscript', 'removeFormat'],
                ['fontColor', 'backgroundColor'],
                ['align', 'list_bulleted', 'list_numbered', 'outdent', 'indent'],
                ['link', 'image', 'video', 'table', 'hr'],
                ['codeView', 'fullScreen', 'preview']
            ] : [
                ['undo', 'redo'],
                ['bold', 'underline', 'italic', 'strike'],
                ['align', 'list_bulleted', 'list_numbered'],
                ['link', 'image'],
                ['codeView', 'fullScreen']
            ],
            events: {}
        };

        options.events.onChange = function (event) {
            textarea.value = event.data;
        };

        return options;
    }

    function initialize(textarea) {
        var locale = contentify.locale || 'en';
        var instance = {
            textarea: textarea,
            editor: SUNEDITOR.create(textarea, editorOptions(textarea, locale)),
            range: null
        };

        instances.push(instance);
        addContentifyToolbar(instance, labels(locale));

        $(textarea.form).off('submit.contentifyEditor').on('submit.contentifyEditor', function () {
            instances.filter(function (item) {
                return item.textarea.form === textarea.form;
            }).forEach(sync);
        });
    }

    $(document).ready(function () {
        if (!window.SUNEDITOR) {
            return;
        }

        $('textarea.editor').each(function () {
            initialize(this);
        });
    });

    window.contentifyEditor = {
        instances: instances,
        sync: sync,
        cleanHtml: cleanHtml
    };
})(window, jQuery);
