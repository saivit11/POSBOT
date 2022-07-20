<!DOCTYPE html>


<?php 

  require 'config.php';
  $str="";
  $error_message="";
  require 'index_handler.php';

?>


<html lang="en" style="scroll-behavior: smooth">
  <head>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="bootstrap.css">

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pose-Bot | Correct it!</title>
    <!-- <link rel="stylesheet" href="styles.css" /> -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
      integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2"
      crossorigin="anonymous"
    />
    <script
      src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
      integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
      integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"
      integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s"
      crossorigin="anonymous"
    ></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@1.3.1/dist/tf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@teachablemachine/pose@0.8/dist/teachablemachine-pose.min.js"></script>
    <script type="text/javascript">
      // More API functions here:
      // https://github.com/googlecreativelab/teachablemachine-community/tree/master/libraries/pose
      console.log = () => {};
      // the link to your model provided by Teachable Machine export panel
      const URL = "./TestFolder/TestTeachableModel/my_model/";
      let model, webcam, ctx, labelContainer, maxPredictions;

      let group = [];
      let toggle = false;

      async function init() {
        toggle = true;
        const modelURL = URL + "model.json";
        const metadataURL = URL + "metadata.json";

        // load the model and metadata
        // Refer to tmImage.loadFromFiles() in the API to support files from a file picker
        // Note: the pose library adds a tmPose object to your window (window.tmPose)

        model = await tmPose.load(modelURL, metadataURL);
        // you need to create File objects, like with file input elements (<input type="file" ...>)
        // const uploadModel = document.getElementById('upload-model');
        // const uploadWeights = document.getElementById('upload-weights');
        // const uploadMetadata = document.getElementById('upload-metadata');
        // model = await tmPose.loadFromFiles(uploadModel.files[0], uploadWeights.files[0], uploadMetadata.files[0])
        maxPredictions = model.getTotalClasses();

        // Convenience function to setup a webcam
        let mql = window.matchMedia("(max-width: 570px)");
        const size = !mql ? window.innerWidth * 0.6 : window.innerHeight * 0.48;
        const flip = true; // whether to flip the webcam
        webcam = new tmPose.Webcam(size, size, flip); // width, height, flip
        await webcam.setup(); // request access to the webcam
        await webcam.play();
        window.requestAnimationFrame(loop);

        // append/get elements to the DOM
        const canvas = document.getElementById("canvas");
        canvas.width = !mql
          ? window.innerWidth * 0.5
          : window.innerHeight * 0.45;
        canvas.height = !mql
          ? window.innerWidth * 0.5
          : window.innerHeight * 0.45;
        ctx = canvas.getContext("2d");
        labelContainer = document.getElementById("label-container");
        for (let i = 0; i < maxPredictions; i++) {
          // and class labels
          labelContainer.appendChild(document.createElement("div"));
        }
      }

      async function loop(timestamp) {
        console.log(timestamp);
        webcam.update(); // update the webcam frame

        await predict();
        if (toggle) {
          window.requestAnimationFrame(loop);
        }
      }

      async function predict() {
        // Prediction #1: run input through posenet
        // estimatePose can take in an image, video or canvas html element
        const { pose, posenetOutput } = await model.estimatePose(webcam.canvas);
        // Prediction 2: run input through teachable machine classification model
        const prediction = await model.predict(posenetOutput);
        // console.log(group)
        if (group.length < 100) {
          group.push(prediction);
        } else {
          group.shift();
          group.push(prediction);
        }
        checkPosture(group);
        // console.log(group)
        for (let i = 0; i < maxPredictions; i++) {
          const classPrediction =
            prediction[i].className +
            ": " +
            prediction[i].probability.toFixed(2);
          // labelContainer.childNodes[i].innerHTML = classPrediction;
        }

        // finally draw the poses
        drawPose(pose);
      }
      function notifyMe(message) {
        // Let's check if the browser supports notifications
        const startover = function () {
          // console.log('NOTIFICATION CLICKed')
          toggle = true;
          // console.log(toggle)
          window.requestAnimationFrame(loop);
          group = [];
        };
        if (!("Notification" in window)) {
          alert("This browser does not support desktop notification");
        }

        // Let's check whether notification permissions have already been granted
        else if (Notification.permission === "granted") {
          // If it's okay let's create a notification
          var title = "Posture";
          var icon =
            "https://homepages.cae.wisc.edu/~ece533/images/airplane.png";
          var body =
            message + " and click this notification";
          var tag = "pose-bot";
          var notification = new Notification(title, { body, icon, tag });
        }

        // Otherwise, we need to ask the user for permission
        else if (Notification.permission !== "denied") {
          Notification.requestPermission().then(function (permission) {
            // If the user accepts, let's create a notification
            if (permission === "granted") {
              var notification = new Notification(title, { body, icon, tag });
            }
          });
        }

        notification.onclick = startover;
        notification.onclose = startover;

        // At last, if the user has denied notifications, and you
        // want to be respectful there is no need to bother them any more.
      }
      function checkPosture(posturegroup) {
        //group
        console.log(group);
        goodposture = (
          posturegroup.reduce((sum, data) => {
            return sum + data[0].probability;
          }, 0) / 100
        ).toFixed(3);
        badposture = (
          posturegroup.reduce((sum, data) => {
            return sum + data[1].probability;
          }, 0) / 100
        ).toFixed(3);
        nearscreen = (
          posturegroup.reduce((sum, data) => {
            return sum + data[2].probability;
          }, 0) / 100
        ).toFixed(3);

        // console.log('good: ',goodposture,' bad: ', badposture)
        // document.getElementById('good').innerHTML = 'Good: ' + goodposture
        // document.getElementById('bad').innerHTML = 'Bad: ' + badposture

        console.log({ goodposture, badposture, nearscreen });

        if (badposture >= 0.8) {
          // console.log('bad posture')
          toggle = false;
          group = [];
          notifyMe("Your back is slouching. Please correct your posture");
        }

        else if (nearscreen >= 0.8) {
          // console.log('bad posture')
          toggle = false;
          group = [];
          notifyMe("You are too near too the screen. Please move away");
        }
      }

      function drawPose(pose) {
        if (webcam.canvas) {
          ctx.drawImage(webcam.canvas, 0, 0);
          // draw the keypoints and skeleton
          if (pose) {
            const minPartConfidence = 0.5;
            tmPose.drawKeypoints(pose.keypoints, minPartConfidence, ctx);
            tmPose.drawSkeleton(pose.keypoints, minPartConfidence, ctx);
          }
        }
      }
    </script>
  </head>
  <style>
    @font-face{
      font-family: 'Bellota-LightItalic';
      src:url('Bellota-LightItalic.otf');
    }
    @font-face{
      font-family: 'Bellota-BoldItalic';
      src:url('Bellota-BoldItalic.otf');
    }

    * {
      /* border: 1px dotted green; */
      /*font-family: Verdana, Geneva, Tahoma, sans-serif; */
    }


    main > img {
      height: 300px;
    }
    /* width */
    ::-webkit-scrollbar {
      width: 10px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
      background: #3ba2c2;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
      background: #286274;
    }

    .search_prac_header h3{
      font-weight: bolder;
    }

    .show_matches h3{
      font-weight: bolder;
    }

    .search_prac {
      font-family: 'Bellota-LightItalic',sans-serif;
      color: black;
      position: relative;
      margin-right: auto;
      margin-left: auto;
      top: 7%;
      width: 50%;
      background-color: #b3b3b3;
      border: 1px solid #000;
      border-radius: 7px;
      padding: 5px;
      opacity: 90%;
      text-align: center;
    }

    .show_matches {
      font-family: 'Bellota-LightItalic',sans-serif;
      color: black;
      position: relative;
      margin-right: auto;  
      margin-left: auto;
      top: 7%;
      width: 40%;
      background-color: #B9B8B3;
      border: 1px solid #EDEDED;
      border-radius: 7px;
      padding: 5px;
      opacity: 90%;
      float: center;
      text-align: center;
    }

    .search_prac label {
      font-size: 120%;
      font-weight: bold;
    }

    .btn-primary {
      background-color: #3b5fc2;
    }
    .btn-primary:hover {
      background-color: #4569ce;
    }
    .reveal_name_form {
      opacity: 0;
      max-height: 0;
      overflow: hidden;
      transform: scale(0.8);
      transition: 0.5s;
      input[type="radio"]:checked ~ &,
      input[type="checkbox"]:checked ~ & {
        opacity: 1;
        max-height: 100px;
        overflow: visible;
        padding: 10px 20px;
        transform: scale(1);
      }
    }
  </style>
  <body class="" style="background-color: #232128">
    <script SameSite="None; Secure" src="https://static.landbot.io/landbot-3/landbot-3.0.0.js"></script>
    <script>
      var myLandbot = new Landbot.Livechat({
        configUrl: 'https://chats.landbot.io/v3/H-889796-6MP674PMAKCYKO6L/index.json',
      });
    </script>
    <div class="landing text-light">
      <!-- Header with logo position: sticky/fixed -->
      <header class="text-left">
        <div class="logo-div">
          <img src="./assets//img/logo.jpeg" alt="Logo" class="img img-fluid" />
        </div>
      </header>

      <!-- Main screen -->
      <main>
        <div class="text-center">
          <div class="d-flex flex-direction-row">
            <div
              id="demo"
              class="carousel d-flex align-items-center"
              data-ride="carousel"
              style="min-width: 700px; min-height: 600px"
            >
              <ul class="carousel-indicators">
                <li data-target="#demo" data-slide-to="0" class="active"></li>
                <li data-target="#demo" data-slide-to="1"></li>
                <li data-target="#demo" data-slide-to="2"></li>
              </ul>
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <img src="./assets/img/1.png" alt="Los Angeles" />
                  <div class="carousel-caption">
                    <!-- <h3 class="text-dark">Back pain?</h3>
                    <p class="text-dark">
                      The reason could be bad sitting posture
                    </p> -->
                  </div>
                </div>
                <div class="carousel-item">
                  <img
                    width="600"
                    style="margin: 100px 0"
                    src="https://buffer.com/resources/content/images/resources/wp-content/uploads/2013/11/Screen-Shot-2013-11-11-at-8.09.23-AM.png"
                    alt="Chicago"
                  />
                  <div class="carousel-caption">
                    <!-- <h3>Chicago</h3>
                    <p>Thank you, Chicago!</p> -->
                  </div>
                </div>
                <div class="carousel-item">
                  <img
                    width="600"
                    src="https://scitechdaily.com/images/Human-Spinal-Cord.jpg"
                    width="80%"
                    alt="New York"
                  />
                  <div class="carousel-caption">
                    <!-- <h3>New York</h3>
                    <p>We love the Big Apple!</p> -->
                  </div>
                </div>
                <div class="carousel-item">
                  <img
                    src="https://images.squarespace-cdn.com/content/v1/5601a1b2e4b0eaa138babe0c/1460050490690-ZYDR6C3LGLX0T3P06ZMU/ke17ZwdGBToddI8pDm48kN_oxOEgSHwyifItmiijwgFZw-zPPgdn4jUwVcJE1ZvWEtT5uBSRWt4vQZAgTJucoTqqXjS3CfNDSuuf31e0tVFCGwsdcQRbmn_Ph9Y1L2ib5ngnVgMZdvrTksiDaHAyK6EcAfnVBrEqrgp1UxUHGkY/bad+posture.png?format=1500w"
                    alt="Chicago"
                  />
                  <div class="carousel-caption">
                    <!-- <h3>Chicago</h3>
                    <p>Thank you, Chicago!</p> -->
                  </div>
                </div>
              </div>
              <a class="carousel-control-prev" href="#demo" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
              </a>
              <a class="carousel-control-next" href="#demo" data-slide="next">
                <span class="carousel-control-next-icon"></span>
              </a>
            </div>

            <div
              class="d-flex flex-column justify-content-center align-items-center p-5"
            >
              <p class="display-1">Our Vision</p>
              <h4 style="margin-top: 2em">
                We created this application to help users maintain a good
                posture and save from early signs of postural imbalance and
                protect your vision, this application uses a image classifier
                from Google API. It notifies the user when he/she is sitting in
                a bad position or is too close to the screen. We have also
                included EchoAR models to educate the children about the harms
                of sitting in a bad position and importance of healthy eyes 👀.
              </h4>
              <a href="#monitor">
                <button
                  class="btn btn-primary m-2 font-weight-bold px-5"
                  style="border-radius: 9999px"
                >
                  Monitor Me!
                </button>
              </a>
              <a href="#search">
                <button
                  class="btn btn-primary m-2 font-weight-bold px-5"
                  style="border-radius: 9999px"
                >
                  Find Practitioner
                </button>
              </a>
              <a href="#models">
                <button
                  type="button"
                  class="btn btn-primary m-2 font-weight-bold px-5"
                  style="border-radius: 999px"
                >
                  Explore 3D AR Models
                </button>
              </a>
            </div>
          </div>

          <div
            id="monitor"
            class="text-center d-flex flex-column justify-content-center align-items-center"
          >
            <canvas
              style="
                height: 50vh;
                width: 50vh;
                background-color: #3ba2c2;
                border-radius: 50px;
                margin-top: 15em;
              "
              id="canvas"
            ></canvas>
            <div style="margin-bottom: 10em">
              <button
                class="font-weight-bold btn btn-primary px-5 text-light m-2"
                style="border-radius: 9999px"
                onclick="init()"
              >
                Monitor me.
              </button>
              <!-- Button trigger modal -->
            </div>
          </div>
        </div>
      </main>

      <div class="search_prac" id="search">
        <div class="search_prac_header">
          <h3>Search Practitioner</h3>
        </div>

        <div>
          <select>
            <option>Choose Search by Option</option>
            <option value="form1">Search by Name</option>
            <option value="form2">Search by Specialization</option>
            <option value="form3">Search by Location</option>
          </select>
        </div>
        <br><br>
        <div id="check" class="form1 box">
          <form action="index.php" method="post">
            <label for="prac_name">Practitioner Name:</label>
            <input type="text" name="prac_name" required>
            <br>
            <label for="prac_city">City Name:</label>
            <input type="text" name="prac_city" required>
            <br>
            <input type="submit" name="search_button1" value="Search">
            <br>
          </form>
        </div>
        <div id="check" class="form2 box">
          <form action="index.php" method="post">
            <label for="prac_type">Practitioner Specialization:</label>
            <input type="text" name="prac_type" required>
            <br>
            <label for="prac_city">City Name:</label>
            <input type="text" name="prac_city" required>
            <br>
            <input type="submit" name="search_button2" value="Search">
            <br>
          </form>
        </div>

        <div id="check" class="form3 box">
          <form action="index.php" method="post">
            <label for="prac_ctry">Country Name:</label>
            <input type="text" name="prac_ctry" required>
            <br>
            <label for="prac_state">State Name:</label>
            <input type="text" name="prac_state" required>
            <br>
            <label for="prac_city">City Name:</label>
            <input type="text" name="prac_city" required>
            <br>
            <label for="prac_street">Street Name:</label>
            <input type="text" name="prac_street" required>
            <br>
            <input type="submit" name="search_button3" value="Search">
            <br>
          </form>
        </div>
        <!-- <p><?php echo $error_message; ?></p> -->

        <script>
          $(document).ready(function() {
            $("select").change(function() {
              $(this).find("option:selected").each(function() {
                var optionValue = $(this).attr("value");
                if (optionValue) {
                  $(".box").not("." + optionValue).hide();
                  $("." + optionValue).show();
                } else {
                  $(".box").hide();
                }
              });
            }).change();
          });
        </script>

        <!-- <form action="index.php" method="POST">
          <label for="prac_name_name">Practitioner Name:</label>
          <input type="text" name="prac_name" required>
          <br>
          <label for="prac_type">Practitioner Specialization:</label>
          <input type="text" name="prac_type" required>
          <br>
          <label for="prac_ctry">Country Name:</label>
          <input type="text" name="prac_ctry" required>
          <br>
          <label for="prac_state">State Name:</label>
          <input type="text" name="prac_state" required>
          <br>
          <label for="prac_city">City Name:</label>
          <input type="text" name="prac_city" required>
          <br>
          <label for="prac_street">Street Name:</label>
          <input type="text" name="prac_street" required>
          <br>
          <input type="submit" name="search_button" value="Search">
          <br>
        </form> -->
      
      </div>
      <br><br>

      <div class='show_matches'>
        <h3>Top Matches</h3>
        <hr>
        <?php  
          echo $str;
        ?>
      </div>

      <div id="models" class="d-flex flex-column p-5" style="margin: 10em 0">
        <div>
          <h2 class="text-center display-2">EchoAR Models</h2>
          <div
            class="d-flex flex-row justify-content-center align-items-center"
          >
            <div
              class="d-flex flex-column mx-5 m-3 text-center align-items-center justify-content-center"
            >
              <img height="300" src="./assets/img/skeleton.jpeg" alt="" />
              <h4>skeleton</h4>
              <p><a href="https://go.echoar.xyz/3Rit">View Model</a></p>
            </div>
            <div
              class="d-flex flex-column mx-5 m-3 text-center align-items-center justify-content-center"
            >
              <img height="300" src="./assets/img/heart.jpeg" alt="" />
              <h4>heart</h4>
              <p><a href="https://go.echoar.xyz/Mu5V">View Model</a></p>
            </div>
          </div>
        </div>
        <div class="d-flex flex-row">
          <iframe
            class="m-2"
            width="100%"
            height="480px"
            src="https://poly.google.com/view/dBzk5erdQcg/embed?chrome=min"
            frameborder="0"
            style="border: none"
            allowvr="yes"
            allow="vr; xr; accelerometer; magnetometer; gyroscope; autoplay;"
            allowfullscreen
            mozallowfullscreen="true"
            webkitallowfullscreen="true"
            onmousewheel=""
          ></iframe>
        </div>
      </div>

      <div
        class="d-flex flex-column p-5 justify-content-center align-items-center"
        style="margin: 15em 0"
      >
<!--         <h1 class="display-2">The Team</h1>
        <div class="d-flex flex-row text-center">
          <div>
            <img
              class="img thumbnail mx-4"
              src="Nadish.jpeg"
              width="400"
              height="400"
            />
            <h3 class="my-3">Mohammed Nadish</h3>
          </div>
          <div>
            <img
              class="img thumbnail mx-4"
              src="https://avatars.githubusercontent.com/u/61933732?v=4"
              width="400"
              height="400"
            />
            <h3 class="my-3">Sai Chaitanya</h3>
          </div>
          <div>
            <img
              class="img thumbnail mx-4"
              src="Soham.jpeg"
              width="400"
              height="400"
            />
            <h3 class="my-3">Soham Sengupta</h3>
          </div>
        </div> -->
      </div>
    </div>
    <!-- Canvas with video  -->
    <!-- Posture Output  -->
  </body>
</html>













