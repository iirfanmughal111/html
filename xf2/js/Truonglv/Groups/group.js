/* global XF, jQuery */
!(function ($, window, document, _undefined) {
    'use strict';

    XF.Groups_CoverSetup = XF.Element.newHandler({
        options: {
            crop: null,
            forceHeight: null,
            src: null,
            width: null,
            height: null,
            debug: false,
        },

        $imgParent: null,
        isTooltip: false,
        setupClass: 'groupCoverFrame--setup',

        init: function () {
            var _resizeTimer = 0,
                _oldWidth = 0,
                _this = this;

            if (!this.options.crop || !this.options.crop.w) {
                return;
            }

            var $isEditor = this.$target.closest('.groupCover-editor');
            if ($isEditor.length) {
                return;
            }

            this.$imgParent = this.$target.closest('.groupCoverFrame');
            if (!this.$imgParent.length) {
                console.error('No cover frame element.');
                return;
            }
            this.$imgParent.addClass(this.setupClass);

            this.isTooltip = this.$target.closest('.tooltip').length > 0;
            if (this.isTooltip) {
                this.$imgParent.width(this.$target.closest('.tooltip').width());
            }

            $(window).on('resize orientationchange', function () {
                if (_resizeTimer) {
                    clearTimeout(_resizeTimer);
                    _resizeTimer = 0;
                }

                _resizeTimer = setTimeout(function () {
                    _resizeTimer = 0;

                    var newWidth = _this.$target.width();
                    if (newWidth !== _oldWidth) {
                        _oldWidth = newWidth;
                        _this.calcPosition();
                    }
                }, 10);
            });

            this.calcPosition();
        },

        calcPosition: function () {
            this.debug('Cover begin calc position...', 'isTooltip', this.isTooltip);

            var frameWidth = this.$imgParent.width(),
                frameHeight = this.$imgParent.height();
            this.debug('Cover frame dimensions', 'width', frameWidth, 'height', frameHeight);

            var imageWidth = this.options.width,
                imageHeight = this.options.height;
            this.debug('imageWidth', imageWidth, 'imageHeight', imageHeight);

            var cropFrameWidth = parseInt(this.options.crop.w, 10) || imageWidth,
                cropFrameHeight = parseInt(this.options.crop.h, 10) || imageHeight,
                cropPositionY = parseFloat(this.options.crop.y) || 0;
            this.debug(
                'cropFrameWidth',
                cropFrameWidth,
                'cropFrameHeight',
                cropFrameHeight,
                'cropPositionY',
                cropPositionY
            );

            var ratio = frameWidth / cropFrameWidth;
            var newImageWidth = ratio * imageWidth,
                newImageHeight = ratio * imageHeight;
            this.debug('newImageWidth', newImageWidth, 'newImageHeight', newImageHeight);

            var newPositionY = (cropPositionY / imageHeight) * newImageHeight;
            this.debug('newPositionY', newPositionY);
            if (Math.abs(newPositionY) + frameHeight > newImageHeight) {
                this.debug('Adjust position Y');
                newPositionY = frameHeight - newImageHeight;
            }

            this.$target.css({ top: newPositionY }).removeClass(this.setupClass);
            if (this.options.src) {
                this.$target.attr('src', this.options.src);
            }

            this.debug('Calc done...');
        },

        debug: function () {
            if (!this.options.debug) {
                return;
            }

            console.log.apply(this, arguments);
        },
    });

    XF.Groups_CommentLoader = XF.Element.newHandler({
        options: {
            href: null,
            container: null,
            messageSelector: null,
            isAfter: false,
            loaded: null,
        },

        link: null,
        loading: false,
        $container: null,
        loaded: [],

        init: function () {
            this.link = this.options.href || this.$target.attr('href');
            if (!this.link) {
                throw new Error('Invalid link to load.');
            }

            this.$container = XF.findRelativeIf(this.options.container, this.$target);
            if (!this.$container.length) {
                throw new Error('Invalid container.');
            }

            var _this = this;
            $(this.$container)
                .find(this.options.messageSelector)
                .each(function (index, node) {
                    var $node = $(node);
                    _this.loaded.push($node.data('row-id'));
                });

            if (this.options.loaded) {
                this.options.loaded.forEach(function (value) {
                    _this.loaded.push(value);
                });
            }

            this.$target.bind('click', XF.proxy(this, 'onClick'));
        },

        onClick: function (e) {
            e.preventDefault();

            if (this.loading) {
                console.log('Previous loading active.');

                return;
            }

            this.loading = true;
            var _this = this;

            XF.ajax('POST', this.link, this.getRequestData(), $.proxy(this, 'onResponse')).always(function () {
                _this.loading = false;
            });
        },

        getRequestData: function () {
            return {
                loaded: this.loaded,
            };
        },

        onResponse: function (data) {
            if (!data.html) {
                return;
            }

            this.loaded = data.loaded;
            var _this = this;
            if (!data.hasMore) {
                this.$target.remove();
            }

            XF.setupHtmlInsert(data.html, function ($html) {
                if (_this.options.isAfter) {
                    $html.appendTo(_this.$container);
                } else {
                    $html.prependTo(_this.$container);
                }
            });
        },
    });

    XF.Groups_ReplyClick = XF.Click.newHandler({
        eventNameSpace: 'Groups_ReplyClick',
        options: {
            author: null,
            level: null,
            target: null,
            focusOnly: false,
        },

        $form: null,
        $editor: null,
        formVisible: false,

        init: function () {
            this.$form = XF.findRelativeIf(this.options.target, this.$target);
            this.$editor = XF.getEditorInContainer(this.$form);
        },

        click: function (e) {
            e.preventDefault();
            var _this = this;

            if (this.options.focusOnly) {
                this.$form.find('.editorPlaceholder').trigger('click');
                setTimeout(function () {
                    XF.focusEditor(_this.$form);
                }, 150);

                return;
            }

            if (this.formVisible) {
                this.$form.hide();
                this.formVisible = false;
                XF.clearEditorContent(this.$form);
            } else {
                this.$form.show();
                this.formVisible = true;

                XF.replaceEditorContent(
                    _this.$form,
                    '@' + _this.options.author + '&nbsp;',
                    '[USER]' + _this.options.author + '[/USER]&nbsp;'
                );

                setTimeout(function () {
                    XF.focusEditor(_this.$form);
                }, 250);
            }
        },
    });

    XF.Element.register('tlg-comment-loader', 'XF.Groups_CommentLoader');
    XF.Element.register('tlg-cover-setup', 'XF.Groups_CoverSetup');
    XF.Event.register('click', 'tlg-reply', 'XF.Groups_ReplyClick');
})(jQuery, this, document);
