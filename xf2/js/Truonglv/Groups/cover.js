!(function ($, window, document, _undefined) {
    'use strict';

    XF.Groups_CoverEditor = XF.Element.newHandler({
        options: {
            width: null,
            height: null,
            crop: {},
            guideText: null,
            save: null,
            frame: null,
            hammerJs: null,
        },

        $img: null,
        $frame: null,
        $guide: null,

        cropData: {
            w: 0,
            h: 0,
            x: 0,
            y: 0,
            imgW: 0,
            imgH: 0,
        },

        loading: false,

        init: function () {
            this.$frame = XF.findRelativeIf(this.options.frame, this.$target);
            this.$img = this.$frame.find('.groupCover--img');

            if (!this.$img.length) {
                throw new Error('No cover image found.');
            }

            this.$target.bind('click', $.proxy(this, 'saveCrop'));

            if (this.options.guideText) {
                this.$guide = $('<span />').addClass('groupCover--guide').text(this.options.guideText);
                this.$guide.appendTo(this.$frame);
            }

            this.cropData = $.extend(this.cropData, this.options.crop);

            if (this.options.height === null) {
                this.options.height = 200;
            }

            var lastWidth = 0,
                _this = this,
                _resizeTimer;
            $(window).on('resize', function () {
                if (_resizeTimer) {
                    clearTimeout(_resizeTimer);
                    _resizeTimer = 0;
                }

                _resizeTimer = setTimeout(function () {
                    _resizeTimer = 0;

                    var newWidth = _this.$img.width();
                    if (lastWidth !== newWidth) {
                        _this.onResized();
                        lastWidth = newWidth;
                    }
                }, 100);
            });

            if (XF.Feature.has('touchevents') && this.options.hammerJs) {
                XF.Loader.loadJs([this.options.hammerJs], function () {
                    _this.createCropBox();
                });
            } else {
                this.createCropBox();
            }
        },

        saveCrop: function (e) {
            if (this.loading) {
                return;
            }

            this.loading = true;
            var _this = this,
                payload = {
                    crop: this.cropData,
                    reposition: 1,
                };

            XF.ajax('POST', this.options.save, payload, function (data) {
                if (data.redirect) {
                    XF.redirect(data.redirect);
                }
            }).always(function () {
                _this.loading = false;
            });
        },

        setCropData: function (key, value) {
            this.cropData[key] = value;
        },

        onResized: function () {
            if (this.$img.data('cropbox')) {
                this.$img.data('cropbox').update();
            }
        },

        createCropBox: function () {
            var height = this.options.height,
                _this = this;

            height = Math.min(height, _this.getRatio() * (_this.getImgHeight() - height));
            height = Math.max(height, 200);

            this.$frame.height(height);

            this.$img
                .cropbox({
                    width: this.$img.width(),
                    height: height,
                    showControls: 'never',
                    onDragging: function (data) {
                        if (!data) {
                            return true;
                        }

                        var maxTop = _this.getRatio() * (_this.getImgHeight() - _this.options.height);
                        if (Math.abs(data.top) >= maxTop) {
                            console.log('Max top reached. maxTop=' + maxTop + ' data.top=' + data.top);

                            return false;
                        }

                        return true;
                    },
                })
                .on('cropbox', $.proxy(this, 'onCropBox'));
        },

        getImgWidth: function () {
            return this.cropData.imgW < 1 ? this.$img[0].naturalWidth : this.cropData.imgW;
        },

        getImgHeight: function () {
            return this.cropData.imgH < 1 ? this.$img[0].naturalHeight : this.cropData.imgH;
        },

        getRatio: function () {
            return this.$img.width() / this.getImgWidth();
        },

        onCropBox: function (event, result, img) {
            this.setCropData('y', this.$img.css('top'));
            this.setCropData('w', this.$img.width());
            this.setCropData('h', this.$frame.height());

            if (this.cropData.imgH < 1) {
                this.setCropData('imgH', this.$img[0].naturalWidth);
            }

            if (this.cropData.imgW < 1) {
                this.setCropData('imgW', this.$img[0].naturalHeight);
            }

            console.log('cropData: %o', this.cropData);
        },
    });

    XF.Element.register('tlg-cover-editor', 'XF.Groups_CoverEditor');
})(jQuery, this, document);
