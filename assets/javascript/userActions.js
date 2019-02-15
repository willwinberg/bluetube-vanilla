function subscribe(button, toUsername, fromUsername) {
   console.log("subscribe() called");
   if (toUsername === fromUsername) {
      return alert("Sorry, but you can't subscribe to yourself.");
   }

   $.post("ajax/userActions.php", { toUsername, fromUsername })
      .done(function (count) {
         console.log("subscribe() done");
         if (count !== null) {
            $(button).toggleClass("subscribe unsubscribe");

            const text = $(button).hasClass("subscribe") ? "SUBSCRIBE" : "SUBSCRIBED";

            $(button).text(text + " " + count);
         } else {
            console.log("Error with subscribe()");
         }
      });
}