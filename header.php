<?php
//Get page name to change active menu links and more
$pageName = basename($_SERVER['PHP_SELF']);
$metaDescription = "";

switch ($pageName) {
    case 'index.php':
      $pageTitle = "Discussion on Movies, TV Shows, News and Reviews";
      $metaDescription = "Discussion on movies, series and news within this community";
    break;
    case 'movies.php':
      $pageTitle = "Movie Discussion";
      $metaDescription = "Join the discussion about your favorite movie or clip";
    break;
    case 'series.php':
      $pageTitle = "Series Discussion";
      $metaDescription = "Have a discussion about your favorite show or join an interesting debate";
    break;
    case 'news.php':
      $pageTitle = "News Discussion";
      $metaDescription = "Keep up with discussion and interesting news in the entertainment industry";
    break;
    case 'reviews.php':
      $pageTitle = "Read & Write Reviews";
      $metaDescription = "Read some of the best user submitted reviews of movies, shows and entertainment or write your own!";
    break;
    case 'profile.php':
      $pageTitle = "Profile Page";
      $metaDescription = "Movie discussion site user profile page";
    break;
    default:
    $pageTitle = "Discussion on Movies, TV Shows, News and Reviews";
    $metaDescription = "Discussion on movies, series and news within this community";
}
?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $metaDescription ?>">
    <meta name="keywords" content="Discuss, Movies, Films, Series, TV Shows, Reviews">
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tiny.cloud/1/rta56yptfl3ip9i4w8su10avcx96ai09g361no65l5y4r04r/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="js/scripts.js"></script>
    <script>
        tinymce.init({
        selector: 'textarea.editor',
        height: 300,
        plugins: [
        'advlist autolink lists link image preview fullscreen',
        'media paste imagetools'
        ],
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
        deprecation_warnings: false
    });
    </script>
    <title> <?php echo $pageTitle ?> </title>
</head>