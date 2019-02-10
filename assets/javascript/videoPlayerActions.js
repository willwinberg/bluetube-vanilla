
function likeVideo(button, videoId) {
   // console.log(button);
   $.post("ajax/videoPlayerActions.php", { videoId, action: "like" })
      .done(function (response) {
         console.log("likeVideo called");

      });
}

function dislikeVideo(button, videoId) {
   // console.log(button);
   $.post("ajax/videoPlayerActions.php", { videoId, action: "dislike" })
      .done(function (response) {
         console.log("dislikeVideo called");

      });
}

function updateLikesValue(element, num) {
   var likesCountVal = element.text() || 0;
   element.text(parseInt(likesCountVal) + parseInt(num));
}