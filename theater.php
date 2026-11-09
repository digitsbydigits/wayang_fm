<?php
    include 'userauth_check.php';
    $room_to_join = $_GET['room'] ?? 'None';

    $check = $conn->prepare("SELECT room_id FROM rooms WHERE room_code = ?");
    $check->bind_param("s", $room_to_join);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows === 0) {
        header("Location: dashboard.php?error=room_closed");
        exit;
    }

    $display_name = $_SESSION['username'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= htmlspecialchars($display_name) ?>'s Room</title>
        <link rel="stylesheet" href="styles/css/base.css">
        <link rel="stylesheet" href="styles/css/theater.css">
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

        let localStream = null;

        async function startCapture() {
            try {
                localStream = await navigator.mediaDevices.getDisplayMedia({
                    video: { cursor: "always" },
                    audio: true
                });

                const videoElement = document.getElementById('shared-screen');
                videoElement.srcObject = localStream;

                localStream.getVideoTracks()[0].onended = () => {
                    stopCapture();
                };
            } catch (err) {
                console.error("Error: " + err);
            }
        }

        function stopCapture() {
            if (localStream) {
                localStream.getTracks().forEach(track => track.stop());
                document.getElementById('shared-screen').srcObject = null;
            }
        }

        function leaveRoom() {
            if (typeof stopCapture === "function") {
                stopCapture();
            }
            window.location.assign("leave_room.php?room=<?= htmlspecialchars($room_to_join) ?>");
        }

        function fScreen() {
            document.addEventListener('DOMContentLoaded', () => {
                const btn = document.getElementById('fullscreenbtn');
                const videoContainer = document.getElementById('video-box');

                if (btn) {
                    btn.addEventListener('click', () => {
                        if (!document.fullscreenElement) {
                            videoContainer.requestFullscreen().catch(err => {
                                console.error(`Error: ${err.message}`);
                            });
                        } else {
                            document.exitFullscreen();
                        }
                    });
                }
            });
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
            <div>
                <p style="color: wheat; font-size: clamp(2vmax, 2vmax, 5vmax); text-align: center;">Room code : <?= htmlspecialchars($room_to_join) ?></p>
            </div>

            <div class="screencontainer">
                <iframe id="video-box" allowfullscreen="true">
                    <video id="screen-viewer" autoplay playsinline></video>
                </iframe>

                <div id="standardbutton">
                    <button type="button" onclick="leaveRoom()">
                        <p>X</p>
                    </button>
                
                    <button type="button" onclick="startCapture()">
                        <p>Start/Stop Screenshare</p>
                    </button>

                    <button type="button" id="fullscreenbtn" onclick="fullScreen()">
                        <p>&#x1F5D6;</p>
                    </button>
                </div>

            </div>
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