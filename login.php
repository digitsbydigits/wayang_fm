<!DOCTYPE html>
<html lang="en">
    <head>
        <title>wayang.fm : Log-in</title>
        <link rel="stylesheet" href="styles/css/base.css">
        <link rel="stylesheet" href="styles/css/registration.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">
    </head>

    <body onload="PreloadPage()">
    <div class="loader">
        <img src="styles/img/popcorn.gif" alt="">
        <p id="loadingtext">Making some popcorns...</p>
    </div> 
    
    <script>
        var loaderVar;
        var loader = document.getElementsByClassName("loader")[0];

        function fadeOutLoad() {
            document.body.style.visibility = "visible";
            loader.style.opacity = "0";
            loader.style.left = "-200%";
        }

        function showPage() {
            loader.style.visibility = "hidden";
            loader.style.display = "none";
            document.body.classList.remove('preventscrolling');
        }

        function PreloadPage() {
            document.body.classList.add('preventscrolling');
            const loadtext = ["Making some popcorns...", "Pouring some caramel...", "Microwaving the kernels..."];
            const randomloadText = loadtext[Math.floor(Math.random() * loadtext.length)];
            document.getElementById('loadingtext').innerText = randomloadText;

            loaderVar = setTimeout(fadeOutLoad, 1900);
            loaderVar = setTimeout(showPage, 2900);
        }

    </script>


        <div class="header">
            <a href="welcome.php" class="custom-tooltip" data-tooltip="* Takes you back to the welcome page. :D">
            <div class="headercontainer">
                <div class="hlogo" style="margin-left: 1vw;"><img src="styles/img/icons/wayanglogo.png" alt="wayang.fm"></div>
                <h1>wayang.fm</h1>
                <div class="spacer"> </div>
            </div>
            </a>
        </div>
        
        <div class="main">
            <form class="sign-up" action="loginprocess.php" method="POST">
                <div class="registertext">
                    <h1>Log-in</h1>
                </div>

                <div class="input-contain">
                    <label for="email">Email</label>
                    <input type="email" name="email" placeholder="digitsbydigits@wayang.fm" required>
                </div>

                <div class="input-contain">
                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="Must include special characters!" required>
                </div>

                <div class="registerbtn">
                    <button type="submit"><p>Continue</p>
                        <span class="btn_tooltip"><img src="styles/css/areyousure.jpg" alt="" style="max-width: auto; max-height: 2dvh;"><br>are you sure?</span>
                    </button>
                </div>

                <div class="link-contain">
                    <p>Don't have an account? <a href="sign-up.html"class="custom-tooltip" data-tooltip="* Join us! :D" style="display : inline-block;">Sign-up instead.</a></p>
                </div>

            </form>
        </div>
        

        <footer>
            <div class="footercontain">
                <div class="footerlogo">
                    <p class="bgdisclaimer">&copy; Site by @digitsbydigits / B12STM25F022.</p>
                    <a href="welcome.php"><h1><img src="styles/img/icons/wayanglogo.png" alt="wayang.fm">wayang.fm</h1></a>
                </div>
                <div class="spacer"> </div>
                <div>
                    <a href="aboutus.html"><h1>About Us</h1></a>
                </div>
                <div class="spacer"> </div>
                <div class="socmed">
                    <h3>Our Socials</h3>
                    <a href="https://www.youtube.com/@digitsbydigits" target="_blank"><img src="styles/img/icons/youtubelogo.png" alt=""> </a>
                    <a href="https://www.facebook.com/digitsbydigits/" target="_blank"><img src="styles/img/icons/facebooklogo.png" alt=""> </a>
                    <a href="https://www.instagram.com/digitsbydigits" target="_blank"><img src="styles/img/icons/instagramlogo.png" alt=""> </a>
                </div>
            </div>
        </footer>
    </body>


</html>