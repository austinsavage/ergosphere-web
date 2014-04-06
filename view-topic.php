
<!DOCTYPE html>
<!-- saved from url=(0043)http://getbootstrap.com/examples/jumbotron/ -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="http://getbootstrap.com/assets/ico/favicon.ico">

    <title>Ergosphere</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  <style type="text/css"></style></head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.html">Ergosphere</a>
        </div>
        <div class="navbar-collapse collapse">
        </div><!--/.navbar-collapse -->
      </div>
    </div>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <?php

        if(isset($_GET['id'])){

        $url = "https://ergosphere.co/api/topics/".$_GET["id"];

        $response = file_get_contents($url);
        
        $response = json_decode($response);

        $build=<<<BUILD

          <h1>$response->topic_name Decks</h1>


BUILD;

        echo $build;

        $url = "https://ergosphere.co/api/topics/".$_GET["id"]."/decks";

        $response = file_get_contents($url);
        
        $response = json_decode($response);

        $build = "";
        
        ?>
      </div>
    </div>

    <div class="container" id="topics">
      <!-- Example row of columns -->
      <?php

        foreach ($response->decks as $key => $deck) {
         
          $random1 = rand(10, 30);
          $random2 = rand(10, 30);
          $random3 = rand(10, 30);

          $build = <<<BUILD
          
            <div class="col-md-4">
            
              <h2>$deck->deck_name</h2>
           
              <div class="progress">
                <div class="progress-bar progress-bar-success" style="width: $random1%">
                  <span class="sr-only">35% Complete (success)</span>
                </div>
                <div class="progress-bar progress-bar-warning" style="width: $random2%">
                  <span class="sr-only">20% Complete (warning)</span>
                </div>
                <div class="progress-bar progress-bar-danger" style="width: $random3%">
                  <span class="sr-only">10% Complete (danger)</span>
                </div>
              </div>

              <p><a class="btn btn-default" href="review-deck.php?id=$deck->deck_id" role="button">Study Now! »</a></p>
           
            </div>
BUILD;

echo $build;



        }
      }else{

        $build = <<<BUILD

          <div class="alert alert-warning">Oops! Looks like the ID is not set.</div>


BUILD;

        echo $build;

      }

      ?>

      <hr>

      <footer>
        <p>© Company 2014</p>
      </footer>
    </div> <!-- /container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>  

</body></html>