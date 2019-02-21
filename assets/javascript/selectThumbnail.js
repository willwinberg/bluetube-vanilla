function selectThumbnail(element, thumbnailId, videoId) {
   $.post("ajax/updateThumbnail.php", { videoId, thumbnailId })
      .done(function () {
         const ele = $(element);
         const eleClass = ele.attr("class");

         $("." + eleClass).removeClass("selected");

         ele.addClass("selected");
         console.log("Thumbnail changed");
      });
}