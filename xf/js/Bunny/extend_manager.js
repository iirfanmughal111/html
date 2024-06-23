XF.Element.extend("attachment-manager", {
  uploadQueryParams: function () {
    var isBunnyUpload = $("[name=isBunnyUpload]").val();
    return {
      _xfToken: XF.config.csrf,
      _xfResponseType: "json",
      _xfWithData: 1,
      isBunnyUpload: isBunnyUpload,
    };
  },
});
