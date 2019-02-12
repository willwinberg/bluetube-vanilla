function postComment(button, postedBy, videoId, replyTo, container) {
   const textarea = $(button).siblings("textarea");
   const body = textarea.val();

   textarea.val("");

   if (body) {
      $.post("ajax/postComment.php", {
         body,
         postedBy,
         videoId,
         replyTo
      })
         .done(function (comment) {
            if (replyTo) {
               $(button).parent().siblings("." + container).append(comment);
            } else {
               $("." + container).prepend(comment);
            }
         });
   } else {
      alert("Can not post an empty comment");
   }
}
