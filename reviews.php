<?php 
// Include config file
require_once "config.php";

// Initialize the session
if (session_id() == "")
  session_start();

//Get dynamic header
require_once "header.php"; 
?>
 
<body>
    <?php require_once "navbar.php" ?>

    <div class="loader">
      <img src="./images/loading.gif" alt="Loading..." />
    </div>

    <div class="container">
      <section class="gallery-box">
        <div class="images"></div>
      </section>
      <div class="loading-dots hide">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>
    </div>
    <script>
        class photoGallery {
  divimages = document.querySelector(".images");

  add_imgs_to_DOM(img_data) {
    // Adds new images to DOM
    let divs = "";

    img_data.forEach(
      (img) => (divs += `<img src="${img.urls.regular}" alt="">`)
    );
    this.divimages.innerHTML += divs;
  }

  async get_images(img_cnt) {
    // API call from Unsplash
    const response = await fetch(
      `https://api.unsplash.com/photos/random?client_id=ffPJdmU2s-KeYWsCgOIxmAPW35eXJxWGKUzLodKlY3w&count=${img_cnt}`
    );

    const imgData = await response.json();
    this.add_imgs_to_DOM(imgData);
  }
}

//Loading handlers
const loader = document.querySelector(".loader");
const loadingDots = document.querySelector(".loading-dots");

//Fetch images on pageLoad
const init_gallery = new photoGallery();
window.onload = () => {
  init_gallery
    .get_images(10)
    .then(() => {
      loader.classList.add("hidden");
    })
    .catch((err) => {
      alert("OOPS! Try Again Later");
      console.log(err);
    });
};

//show Loading dots and fetch images on scroll
window.addEventListener("scroll", () => {
  const { scrollTop, scrollHeight, clientHeight } = document.documentElement;
  if (clientHeight + scrollTop >= scrollHeight) {
    loadingDots.classList.remove("hide");
    init_gallery
      .get_images(10)
      .then()
      .catch((err) => alert("OOPS! Please Try Again Later"));
  }
});
    </script>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>

<style>
    * {
  margin: 0;
  padding: 0;
  font-family: "Poppins", sans-serif;
  box-sizing: border-box;
}

.loader {
  position: fixed;
  z-index: 99;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: white;
  display: flex;
  justify-content: center;
  align-items: center;
}

.loader > img {
  width: 80px;
}

.loader.hidden {
  animation: fadeOut 1s;
  animation-fill-mode: forwards;
}

@keyframes fadeOut {
  100% {
    opacity: 0;
    visibility: hidden;
  }
}

.container {
  width: 90%;
  margin: 50px auto;
  padding: 20px;
}

.gallery-box div {
  columns: 3;
  column-gap: 15px;
}

.gallery-box img {
  width: 100%;
  height: auto;
  margin-bottom: 1rem;
  border-radius: 1%;
  object-fit: cover;
  object-position: 50% 50%;
  transition: 0.5s ease-in-out;
  animation: fade-in 1.2s cubic-bezier(0.39, 0.575, 0.565, 1) both;
}

.gallery-box img:hover {
  filter: brightness(70%);
}

@-webkit-keyframes fade-in {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}
@keyframes fade-in {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}

.loading-dots {
  display: inline-block;
  left: 50%;
  position: relative;
  transform: translateX(-50%);
  width: 80px;
  height: 80px;
}

.loading-dots.hide {
  opacity: 0;
}
.loading-dots div {
  position: absolute;
  top: 33px;
  width: 13px;
  height: 13px;
  border-radius: 50%;
  background: #333;
  animation-timing-function: cubic-bezier(0, 1, 1, 0);
}
.loading-dots div:nth-child(1) {
  left: 8px;
  animation: loading-dots1 0.6s infinite;
}
.loading-dots div:nth-child(2) {
  left: 8px;
  animation: loading-dots2 0.6s infinite;
}
.loading-dots div:nth-child(3) {
  left: 32px;
  animation: loading-dots2 0.6s infinite;
}
.loading-dots div:nth-child(4) {
  left: 56px;
  animation: loading-dots3 0.6s infinite;
}
@keyframes loading-dots1 {
  0% {
    transform: scale(0);
  }
  100% {
    transform: scale(1);
  }
}
@keyframes loading-dots3 {
  0% {
    transform: scale(1);
  }
  100% {
    transform: scale(0);
  }
}
@keyframes loading-dots2 {
  0% {
    transform: translate(0, 0);
  }
  100% {
    transform: translate(24px, 0);
  }
}

@media only screen and (max-width: 768px) {
  .gallery-box div{
    columns: 2;
  }
}

@media only screen and (max-width: 400px) {
  .gallery-box div{
    columns: 1;
  }
}
</style>