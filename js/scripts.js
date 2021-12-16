//Discuss.php Show / Hide posts
//Get parent container and hide by changing height value
function togglePost(e) {
  let post = e.parentNode.parentNode.parentNode;

  if (post.style.height != "50px") {
    post.style.height = "50px";
    e.textContent = "[ + ]";
    e.style.fontSize = "0.86em";
  } else {
    post.style.height = "auto";
    e.textContent = "[ - ]";
    e.style.fontSize = "1em";
  }
}

function showReplyForm(e, username) {
  let form = document.getElementById('replyForm')
  let formReply = document.getElementById('replyTo')

  parentDiv = e.parentElement;
  userID = e.id.substring(1); //Remove first U letter from ID
  for (var i = 0; i < 2; i++) { //Getting upmost parent container
    parentDiv = parentDiv.parentElement;
  }

  form.getElementsByTagName('label')[0].textContent = "Replying to: " + username;
  //Show form and set reply value
  formReply.value = userID;
  form.style.cssText = parentDiv.style.cssText;
  form.style.height = 'auto';
  form.style.display = 'block';
  form.scrollIntoView({ behavior: 'smooth' });
}

function closeReplyForm() {
  let form = document.getElementById('replyForm');
  form.style.height = 0;
  form.style.display = 'none';
}

//Like Posts
function likePost(e, postID, userID) {
  //Create AJAX request to like a post
  $.ajax({
    url: "likePost.php",
    type: "get", //send it through get method
    data: {
      postId: postID,
      userId: userID
  },
    success: function (response) {
      //Check if post is already liked or not
      if (e.classList.contains('postLiked')) { //Remove Like
        e.classList.remove('postLiked')
        e.children[1].textContent = parseInt(e.children[1].textContent) - 1; //Decrement like number

        //Check if likes = 0 and remove the 0 from showing
        if (e.children[1].textContent = "0") {
          e.children[1].textContent = "";
        }
      } else { //Add Like
        e.classList.add('postLiked')
        e.children[1].textContent = parseInt(e.children[1].textContent) + 1; //Increment like number

        //If it's first like for post it will be NaN, set automatically to 1
        if (isNaN(e.children[1].textContent)) {
          e.children[1].textContent = 1;
        }
      }
    },
    error: function (xhr) {
      if (xhr.status == "403") {
        //Ignore because user is forbidden from liking
        console.log("forbidden");
      } else {
        //Do Something to handle error
        console.log("error");
      }
    }
  }); 
}