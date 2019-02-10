
function likeVideo(button, videoId) {
   console.log(button);
   $.post("ajax/likeVideo.php", { videoId: videoId })
      .done(function (response) {
         console.log("likeVideo called");
         // console.log(response);


      });
}

function updateLikesValue(element, num) {
   var likesCountVal = element.text() || 0;
   element.text(parseInt(likesCountVal) + parseInt(num));
}