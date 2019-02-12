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

function likeComment(button, commentId, videoId) {
   $.post("ajax/likeComment.php", { commentId, videoId })
      .done(function (likesUpdate) {
         const likeButton = $(button);
         const dislikeButton = $(button).siblings(".dislikeButton");

         likeButton.addClass("active");
         dislikeButton.removeClass("active");

         const likesCount = $(button).siblings(".likesCount");
         updateValue(likesCount, likesUpdate);

         if (likesUpdate < 0) {
            likeButton.removeClass("active");
            likeButton.find("img").attr("src", "assets/images/icons/thumb-up.png");
         } else {
            likeButton.find("img").attr("src", "assets/images/icons/thumb-up-active.png")
         }

         dislikeButton.find("img").attr("src", "assets/images/icons/thumb-down.png");
      });
}

function dislikeComment(button, commentId, videoId) {
   $.post("ajax/dislikeComment.php", { commentId, videoId })
      .done(function (dislikesUpdate) {
         const dislikeButton = $(button);
         const likeButton = $(button).siblings(".likeButton");

         dislikeButton.addClass("active");
         likeButton.removeClass("active");

         const likesCount = $(button).siblings(".likesCount");
         updateValue(likesCount, dislikesUpdate);

         if (dislikesUpdate > 0) {
            dislikeButton.removeClass("active");
            dislikeButton.find("img").attr("src", "assets/images/icons/thumb-down.png");
         } else {
            dislikeButton.find("img").attr("src", "assets/images/icons/thumb-down-active.png")
         }

         likeButton.find("img").attr("src", "assets/images/icons/thumb-up.png");
      });
}

function updateValue(element, num) {
   const count = element.text() || 0;
   element.text(parseInt(count) + parseInt(num));
}

function getReplies(button, commentId, videoId) {
   $.post("ajax/getCommentReplies.php", { commentId, videoId })
      .done(function (commentsHTML) {
         const replies = $("<div>").addClass("repliesSection");

         replies.append(commentsHTML);
         $(button).replaceWith(replies);
      });
}

function toggleReply(button) {
   const parent = $(button).closest(".itemContainer");
   const commentForm = parent.find(".commentForm").first();

   commentForm.toggleClass("hidden");
}