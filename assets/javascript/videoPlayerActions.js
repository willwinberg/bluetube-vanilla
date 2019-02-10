
function likeVideo(button, videoId) {
   $.post("ajax/videoPlayerActions.php", { videoId, action: "like" })
      .done(function (response) {
         console.log("likeVideo called");

         var likeButton = $(button);
         var dislikeButton = $(button).siblings(".dislikeButton");

         likeButton.addClass("active");
         dislikeButton.removeClass("active");

         var result = JSON.parse(response);
         updateValue(likeButton.find(".text"), result.likes);
         updateValue(dislikeButton.find(".text"), result.dislikes);

         if (result.likes < 0) {
            likeButton.removeClass("active");
            likeButton.find("img").attr("src", "assets/images/icons/thumb-up.png");
         }
         else {
            likeButton.find("img").attr("src", "assets/images/icons/thumb-up-active.png")
         }

         dislikeButton.find("img").attr("src", "assets/images/icons/thumb-down.png");
      }
      );
}




function dislikeVideo(button, videoId) {
   // console.log(button);
   $.post("ajax/videoPlayerActions.php", { videoId, action: "dislike" })
      .done(function (response) {
         console.log("dislikeVideo called");

         var dislikeButton = $(button);
         var likeButton = $(button).siblings(".likeButton");

         dislikeButton.addClass("active");
         likeButton.removeClass("active");

         var result = JSON.parse(response);
         updateValue(likeButton.find(".text"), result.likes);
         updateValue(dislikeButton.find(".text"), result.dislikes);

         if (result.dislikes < 0) {
            dislikeButton.removeClass("active");
            dislikeButton.find("img").attr("src", "assets/images/icons/thumb-down.png");
         }
         else {
            dislikeButton.find("img").attr("src", "assets/images/icons/thumb-down-active.png")
         }

         likeButton.find("img").attr("src", "assets/images/icons/thumb-up.png");
      }
      );
}

function updateValue(element, num) {
   var likesCountVal = element.text() || 0;
   element.text(parseInt(likesCountVal) + parseInt(num));
}