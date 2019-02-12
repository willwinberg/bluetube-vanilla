function postComment(button, username, videoId, replyComment, container) {
   const textarea = $(button).siblings("textarea");
   const text = textarea.val();

   textarea.val("");

   if (text) {
      $.post("ajax/commentActions.php", {
         text,
         username,
         videoId,
         replyComment
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
