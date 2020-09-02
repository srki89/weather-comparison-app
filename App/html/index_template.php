<!doctype html>
<html lang="en">
  <head>
    <title>Weather App</title>
    <link rel="Weather App Icon" type="image/jpg" href="img/sun.png"/>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?=$this->homeUrl.$this->customCSSpath?>">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  </head>
  <body>
	<div class="container d-flex justify-content-center">
    <div class="col-lg-8 col-sm-12">
      <div class="col-sm-12 my-2 bg-secondary text-white">
        <h2><a href="/" class="text-decoration-none text-white">Weather App</a></h2>
      </div>
      <form class="py-4" method="post" action="">
        <div class="col-sm-12 d-flex justify-content-center row">
          <?php
              if( isset($_SESSION['VALIDATION_ERROR']) ){
                  foreach($_SESSION['VALIDATION_ERROR'] as $message){
                    ?>
                    <div class="w-100 alert alert-danger py-1 my-1 small" role="alert">
                      <?php echo $message; ?>
                    </div>
                    <?php
                  }
              }
              if( isset($_SESSION['GET_DATA_ERROR']) ){
                  foreach($_SESSION['GET_DATA_ERROR'] as $message){
                    ?>
                    <div class="w-100 alert alert-warning py-1 my-1 small" role="alert">
                      <?php echo $message; ?>
                    </div>
                    <?php
                  }
              }
              if( isset($_SESSION['GEOCODING_DATA_ERROR']) ){
                  foreach($_SESSION['GEOCODING_DATA_ERROR'] as $message){
                    ?>
                    <div class="w-100 alert alert-warning py-1 my-1 small" role="alert">
                      <?php echo $message; ?>
                    </div>
                    <?php
                  }
              }
          ?>
          <div class="col-lg-5 col-sm-12 bg-light p-2 m-1">
            <h3>Departure</h3>
            <div class="radio">
              <?php
                if(!isset($_SESSION['use_dep'])){
              ?>
              <label><input id="use-geo-dep" type="radio" name="use_dep" value="1" checked> Use Geolocation</label>
              <?php
                }else{
              ?>
              <label><input id="use-geo-dep" type="radio" name="use_dep" value="1" <?php echo $_SESSION['use_dep'] == 1 ? 'checked' : ''; ?>> Use Geolocation</label>
              <?php
                }
              ?>
            </div>
            <div class="form-group input-group-sm">
              <input type="text" name="latitude_dep" value="<?php echo isset($_SESSION['latitude_dep']) ? $_SESSION['latitude_dep'] : ''; ?>" class="form-control geoloc-dep <?php echo isset($_SESSION['VALIDATION_ERROR']["LAT_DEP_ERROR"]) ? 'border border-danger ' : ''; ?>" placeholder="Latitude (0.000000)">
            </div>
            <div class="form-group input-group-sm">
              <input type="text" name="longitude_dep" value="<?php echo isset($_SESSION['longitude_dep']) ? $_SESSION['longitude_dep'] : ''; ?>" class="form-control geoloc-dep <?php echo isset($_SESSION['VALIDATION_ERROR']["LON_DEP_ERROR"]) ? 'border border-danger ' : ''; ?>" placeholder="Longitude (0.000000)">
            </div>
            <p> ... OR</p>
            <div class="radio">
              <?php
                if(!isset($_SESSION['use_dep'])){
              ?>
              <label><input id="use-geo-dep" type="radio" name="use_dep" value="2"> Use Address</label>
              <?php
                }else{
              ?>
              <label><input id="use-geo-dep" type="radio" name="use_dep" value="2" <?php echo $_SESSION['use_dep'] == 2 ? 'checked' : ''; ?>> Use Address</label>
              <?php
                }
              ?>
            </div>
            <div class="form-group input-group-sm">
              <input type="text" name="address_dep" value="<?php echo isset($_SESSION['address_dep']) ? $_SESSION['address_dep'] : ''; ?>" class="form-control address-dep <?php echo isset($_SESSION['VALIDATION_ERROR']["ADDRESS_DEP_ERROR"]) ? 'border border-danger ' : ''; ?>" placeholder="Departure Address">
            </div>
          </div>
          <div class="col-lg-5 col-sm-12 bg-light p-2 m-1">
            <h3>Destination</h3>
            <div class="radio">
              <?php
                if(!isset($_SESSION['use_des'])){
              ?>
              <label><input type="radio" name="use_des" value="1" checked> Use Geolocation</label>
              <?php
                }else{
              ?>
              <label><input type="radio" name="use_des" value="1" <?php echo $_SESSION['use_des'] == 1 ? 'checked' : ''; ?>> Use Geolocation</label>
              <?php
                }
              ?>
            </div>
            <div class="form-group input-group-sm">
              <input type="text" name="latitude_des" value="<?php echo isset($_SESSION['latitude_des']) ? $_SESSION['latitude_des'] : ''; ?>" class="form-control geoloc-des <?php echo isset($_SESSION['VALIDATION_ERROR']["LAT_DES_ERROR"]) ? 'border border-danger ' : ''; ?>" placeholder="Latitude (0.000000)">
            </div>
            <div class="form-group input-group-sm">
              <input type="text" name="longitude_des" value="<?php echo isset($_SESSION['longitude_des']) ? $_SESSION['longitude_des'] : ''; ?>" class="form-control geoloc-des <?php echo isset($_SESSION['VALIDATION_ERROR']["LON_DES_ERROR"]) ? 'border border-danger ' : ''; ?>" placeholder="Longitude (0.000000)">
            </div>
            <p> ... OR</p>
            <div class="radio">
              <?php
                if(!isset($_SESSION['use_des'])){
              ?>
              <label><input type="radio" name="use_des" value="2"> Use Address</label>
              <?php
                }else{
              ?>
              <label><input type="radio" name="use_des" value="2" <?php echo $_SESSION['use_des'] == 2 ? 'checked' : ''; ?>> Use Address</label>
              <?php
                }
              ?>
            </div>
            <div class="form-group input-group-sm">
              <input type="text" name="address_des" value="<?php echo isset($_SESSION['address_des']) ? $_SESSION['address_des'] : ''; ?>" class="form-control address-des <?php echo isset($_SESSION['VALIDATION_ERROR']["ADDRESS_DES_ERROR"]) ? 'border border-danger ' : ''; ?>" placeholder="Destination Address">
            </div>
          </div>
          <div class="ml-1">
            <button type="submit" name="submit" class="btn btn-sm btn-primary">Weather Information</button>
          </div>
        </div>

      </form>
      <div class="col-sm-12 row">
        <div class="col-lg-6 col-sm-12 px-1 mb-3">
            <?php
              if(isset($_SESSION['depData'])){
            ?>
              <div class="col-sm-12 bg-light-blue p-3 mb-2">
                <p class="mb-2">Departure Weather</p>
                <p class="py-0 my-0"><span class="font-weight-bold">Dew Point: </span><span><?=$_SESSION['depData']["dewPointTemp"]?></span>&#8451;</p>
                <p class="py-0 my-0"><span class="font-weight-bold">Humidity: </span><span><?=$_SESSION['depData']["humidity"]?></span>%</p>
                <p class="py-0 my-0"><span class="font-weight-bold">Temperature: </span><span><?=$_SESSION['depData']["temp"]?></span>&#8451;</p>
              </div>
              <div class="col-sm-12 row px-0 mx-0">
                <div class="col-sm-3 bg-sky-blue text-white px-0 border border-white">
                  <p class="pt-1 my-0 text-center"><span><?=$_SESSION['depData']["fog"]["value"]?></span>%</p>
                  <p class="my-0 small text-center block-titles">Fog</p>
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['depData']['fog']['styleParameters']['sun']['padding-bottom']?>px;opacity: <?=$_SESSION['depData']['fog']['styleParameters']['sun']['opacity']?>;" src="img/sun.png" />
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['depData']['fog']['styleParameters']['fog']['padding-bottom']?>px;opacity: <?=$_SESSION['depData']['fog']['styleParameters']['fog']['opacity']?>;" src="img/fog.png" />
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['depData']['fog']['styleParameters']['cloud']['padding-bottom']?>px;opacity: <?=$_SESSION['depData']['fog']['styleParameters']['cloud']['opacity']?>;" src="img/cloud.png" />
                </div>
                <div class="col-sm-3 bg-sky-blue text-white px-0 border border-white">
                  <p class="pt-1 my-0 text-center"><span><?=$_SESSION['depData']["lowClouds"]["value"]?></span>%</p>
                  <p class="my-0 small text-center block-titles">Low</p>
                  <p class="my-0 small text-center block-titles">Clouds</p>
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['depData']['lowClouds']['styleParameters']['sun']['padding-bottom']?>px;opacity: <?=$_SESSION['depData']['lowClouds']['styleParameters']['sun']['opacity']?>;" src="img/sun.png" />
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['depData']['lowClouds']['styleParameters']['fog']['padding-bottom']?>px;opacity: <?=$_SESSION['depData']['lowClouds']['styleParameters']['fog']['opacity']?>;" src="img/fog.png" />
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['depData']['lowClouds']['styleParameters']['cloud']['padding-bottom']?>px;opacity: <?=$_SESSION['depData']['lowClouds']['styleParameters']['cloud']['opacity']?>;" src="img/cloud.png" />
                </div>
                <div class="col-sm-3 bg-sky-blue text-white px-0 border border-white">
                  <p class="pt-1 my-0 text-center"><span><?=$_SESSION['depData']["mediumClouds"]["value"]?></span>%</p>
                  <p class="my-0 small text-center block-titles">Medium</p>
                  <p class="my-0 small text-center block-titles">Clouds</p>
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['depData']['mediumClouds']['styleParameters']['sun']['padding-bottom']?>px;opacity: <?=$_SESSION['depData']['mediumClouds']['styleParameters']['sun']['opacity']?>;" src="img/sun.png" />
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['depData']['mediumClouds']['styleParameters']['fog']['padding-bottom']?>px;opacity: <?=$_SESSION['depData']['mediumClouds']['styleParameters']['fog']['opacity']?>;" src="img/fog.png" />
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['depData']['mediumClouds']['styleParameters']['cloud']['padding-bottom']?>px;opacity: <?=$_SESSION['depData']['mediumClouds']['styleParameters']['cloud']['opacity']?>;" src="img/cloud.png" />
                </div>
                <div class="col-sm-3 bg-sky-blue text-white px-0 border border-white">
                  <p class="pt-1 my-0 text-center"><span><?=$_SESSION['depData']["highClouds"]["value"]?></span>%</p>
                  <p class="my-0 small text-center block-titles">High</p>
                  <p class="my-0 small text-center block-titles">Clouds</p>
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['depData']['highClouds']['styleParameters']['sun']['padding-bottom']?>px;opacity: <?=$_SESSION['depData']['highClouds']['styleParameters']['sun']['opacity']?>;" src="img/sun.png" />
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['depData']['highClouds']['styleParameters']['fog']['padding-bottom']?>px;opacity: <?=$_SESSION['depData']['highClouds']['styleParameters']['fog']['opacity']?>;" src="img/fog.png" />
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['depData']['highClouds']['styleParameters']['cloud']['padding-bottom']?>px;opacity: <?=$_SESSION['depData']['highClouds']['styleParameters']['cloud']['opacity']?>;" src="img/cloud.png" />
                </div>
              </div>
            <?php
              }
            ?>
        </div>
        <div class="col-lg-6 col-sm-12 px-1 mb-3">
            <?php
              if(isset($_SESSION['desData'])){
            ?>
              <div class="col-sm-12 bg-light-blue p-3 mb-2">
                <p class="mb-2">Destination Weather</p>
                <p class="py-0 my-0"><span class="font-weight-bold">Dew Point: </span><span><?=$_SESSION['desData']["dewPointTemp"]?></span>&#8451;</p>
                <p class="py-0 my-0"><span class="font-weight-bold">Humidity: </span><span><?=$_SESSION['desData']["humidity"]?></span>%</p>
                <p class="py-0 my-0"><span class="font-weight-bold">Temperature: </span><span><?=$_SESSION['desData']["temp"]?></span>&#8451;</p>
              </div>
              <div class="col-sm-12 row px-0 mx-0">
                <div class="col-sm-3 bg-sky-blue text-white px-0 border border-white">
                  <p class="pt-1 my-0 text-center"><span><?=$_SESSION['desData']["fog"]["value"]?></span>%</p>
                  <p class="my-0 small text-center block-titles">Fog</p>
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['desData']['fog']['styleParameters']['sun']['padding-bottom']?>px;opacity: <?=$_SESSION['desData']['fog']['styleParameters']['sun']['opacity']?>;" src="img/sun.png" />
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['desData']['fog']['styleParameters']['fog']['padding-bottom']?>px;opacity: <?=$_SESSION['desData']['fog']['styleParameters']['fog']['opacity']?>;" src="img/fog.png" />
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['desData']['fog']['styleParameters']['cloud']['padding-bottom']?>px;opacity: <?=$_SESSION['desData']['fog']['styleParameters']['cloud']['opacity']?>;" src="img/cloud.png" />

                </div>
                <div class="col-sm-3 bg-sky-blue text-white px-0 border border-white">
                  <p class="pt-1 my-0 text-center"><span><?=$_SESSION['desData']["lowClouds"]["value"]?></span>%</p>
                  <p class="my-0 small text-center block-titles">Low</p>
                  <p class="my-0 small text-center block-titles">Clouds</p>
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['desData']['lowClouds']['styleParameters']['sun']['padding-bottom']?>px;opacity: <?=$_SESSION['desData']['lowClouds']['styleParameters']['sun']['opacity']?>;" src="img/sun.png" />
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['desData']['lowClouds']['styleParameters']['fog']['padding-bottom']?>px;opacity: <?=$_SESSION['desData']['lowClouds']['styleParameters']['fog']['opacity']?>;" src="img/fog.png" />
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['desData']['lowClouds']['styleParameters']['cloud']['padding-bottom']?>px;opacity: <?=$_SESSION['desData']['lowClouds']['styleParameters']['cloud']['opacity']?>;" src="img/cloud.png" />
                </div>
                <div class="col-sm-3 bg-sky-blue text-white px-0 border border-white">
                  <p class="pt-1 my-0 text-center"><span><?=$_SESSION['desData']["mediumClouds"]["value"]?></span>%</p>
                  <p class="my-0 small text-center block-titles">Medium</p>
                  <p class="my-0 small text-center block-titles">Clouds</p>
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['desData']['mediumClouds']['styleParameters']['sun']['padding-bottom']?>px;opacity: <?=$_SESSION['desData']['mediumClouds']['styleParameters']['sun']['opacity']?>;" src="img/sun.png" />
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['desData']['mediumClouds']['styleParameters']['fog']['padding-bottom']?>px;opacity: <?=$_SESSION['desData']['mediumClouds']['styleParameters']['fog']['opacity']?>;" src="img/fog.png" />
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['desData']['mediumClouds']['styleParameters']['cloud']['padding-bottom']?>px;opacity: <?=$_SESSION['desData']['mediumClouds']['styleParameters']['cloud']['opacity']?>;" src="img/cloud.png" />
                </div>
                <div class="col-sm-3 bg-sky-blue text-white px-0 border border-white">
                  <p class="pt-1 my-0 text-center"><span><?=$_SESSION['desData']["highClouds"]["value"]?></span>%</p>
                  <p class="my-0 small text-center block-titles">High</p>
                  <p class="my-0 small text-center block-titles">Clouds</p>
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['desData']['highClouds']['styleParameters']['sun']['padding-bottom']?>px;opacity: <?=$_SESSION['desData']['highClouds']['styleParameters']['sun']['opacity']?>;" src="img/sun.png" />
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['desData']['highClouds']['styleParameters']['fog']['padding-bottom']?>px;opacity: <?=$_SESSION['desData']['highClouds']['styleParameters']['fog']['opacity']?>;" src="img/fog.png" />
                  <img class="block-img" style="padding-bottom: <?=$_SESSION['desData']['highClouds']['styleParameters']['cloud']['padding-bottom']?>px;opacity: <?=$_SESSION['desData']['highClouds']['styleParameters']['cloud']['opacity']?>;" src="img/cloud.png" />
                </div>
              </div>
            <?php
              }
            ?>
        </div>
        <div class="col-lg-5 col-sm-12 p-2 m-1">
        </div>
      </div>
    </div>
	</div>
    <script>
      $( document ).ready(function() {
          var dep_selected = $("input[name=use_dep]:checked").val();
          updateDepForm(dep_selected);

          var des_selected = $("input[name=use_des]:checked").val();
          updateDesForm(des_selected);

          $("input[name=use_dep]").change(function(){
              var dep_selected = $("input[name=use_dep]:checked").val();
              updateDepForm(dep_selected);

          });

          $("input[name=use_des]").change(function(){
              var des_selected = $("input[name=use_des]:checked").val();
              updateDesForm(des_selected);

          });
      });
      function updateDepForm(value){
          if(value == 1){
              $(".address-dep").attr("disabled", "disabled");
              $(".address-dep").val("");
              $(".geoloc-dep").removeAttr("disabled");
          }else{
              $(".address-dep").removeAttr("disabled");
              $(".geoloc-dep").attr("disabled", "disabled");
              $(".geoloc-dep").val("");
          }
      }
      function updateDesForm(value){
          if(value == 1){
              $(".address-des").attr("disabled", "disabled");
              $(".address-des").val("");
              $(".geoloc-des").removeAttr("disabled");
          }else{
              $(".address-des").removeAttr("disabled");
              $(".geoloc-des").attr("disabled", "disabled");
              $(".geoloc-des").val("");
          }
      }
    </script>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>
