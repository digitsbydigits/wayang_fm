<?php include 'userauth_check.php'; 
     
    date_default_timezone_set('Asia/Kuala_Lumpur');

    $hour = (int)date('H');
    $curr_time = date('H:i');
    $eepi = false;

    if ($hour < 6) {
        $eepi = true;
    }

    $eepitext = ($eepi) ? "It's ZZZ time..." : "";

    $roomcode = strtoupper(bin2hex(random_bytes(3)));

    $display_name = $_SESSION['username'] ?? 'User';

    $user = $_SESSION['username'];
    $res = $conn->query("SELECT current_accessory FROM accounts WHERE username = '$user'");
    $row = $res->fetch_assoc();
    $current_acc = $row['current_accessory'] ?? ''; 
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>wayang.fm</title>
        <link rel="stylesheet" href="styles/css/base.css">
        <link rel="stylesheet" href="styles/css/dashboard.css">
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
            const loadtext = <?php echo ($eepi) ? '["Dreaming of popcorns...", "Remember to tuck in your popcorn..."]' : '["Making some popcorns...", "Pouring some caramel...", "Microwaving the kernels..."]';?>;
            const randomloadText = loadtext[Math.floor(Math.random() * loadtext.length)];
            document.getElementById('loadingtext').innerText = randomloadText;

            loaderVar = setTimeout(fadeOutLoad, 1900);
            loaderVar = setTimeout(showPage, 2900);
        }

        document.addEventListener('change', (e) => {
            if (e.target.name === 'useraccessories') {
                const accessory = e.target.value;
                const overlay = document.getElementById('avatar-overlay');

                // 1. Update the Avatar Image instantly
                overlay.src = `styles/img/icons/${accessory}.png`;
                overlay.style.display = 'block';

                // 2. Send to PHP to save in Database
                const formData = new FormData();
                formData.append('accessory', accessory);

                fetch('save_accessory.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const container = document.getElementById('notification-container');
                        const toast = document.createElement('div');
                        toast.className = 'equip-toast';
                        toast.innerText = `✨ ${accessory} equipped & saved!`;
                        container.appendChild(toast);
                        setTimeout(() => toast.remove(), 2500);
                    }
                });
            }
        });

        function generateNewCode() {
            const display = document.getElementById('roomCodeDisplay');
            fetch('generate_roomcode.php')
                .then(response => response.text()) 
                .then(newCode => {
                    display.innerText = newCode;
                    const container = document.getElementById('notification-container');
                    const toast = document.createElement('div');
                    toast.className = 'equip-toast';
                    toast.innerText = "🍿 New Room Code Generated!";
                    container.appendChild(toast);
                    setTimeout(() => toast.remove(), 2000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    display.innerText = "Error!";
                });
        }

        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            
            const timeString = `${hours}:${minutes}`;
            
            document.getElementById('liveClock').innerText = timeString;
        }

        setInterval(updateClock, 1000);

    </script>

        <div class="header">
            <a href="welcome.php" class="custom-tooltip" data-tooltip="* Takes you back to the welcome page. :D">
            <div class="headercontainer">
                <div class="hlogo" style="margin-left: 1vw;"><img src="styles/img/icons/wayanglogo.png" alt="wayang.fm"></div>
                <h1>Dashboard</h1>
                <div class="spacer"> </div>
            </div>
            </a>
        </div>
        
        <div class="main">
            <div class="maincontain">

                <div class="avatarmain">
                    <div class="avatarsection">
                        <div class="avatardisplay">
                            <div class="avatarwindow">
                            <img id="avatar-base" src="styles/img/icons/user.png" alt="">
                            <img id="avatar-overlay" src="<?= !empty($current_acc) ? "styles/img/icons/$current_acc.png" : "" ?>"<?= empty($current_acc) ? 'display:none;' : '' ?>">
                            </div>
                            <h3><?= htmlspecialchars($display_name) ?></h3>
                        </div>
                        <div class="info">
                            <p>Current time : </p><br>
                            <h2 id="liveClock" style="color: wheat; font-family: 'Press-Start'"><?= $curr_time ?></h2>
                            <p style="color : red;"><?= $eepitext ?></p>
                        </div>
                    </div>

                    <div class="avataraccessories">
                        <h3>Accessories</h3>

                        <div class="selection">
                            <div class="choice">
                                <label for="tophat">tophat</label>
                                <img src="styles/img/icons/tophat.png" alt="">
                                <input type="radio" id="tophat" name="useraccessories" value="tophat" <?= ($current_acc == 'tophat') ? 'checked' : '' ?>>
                            </div>

                            <div class="choice">
                                <label for="sunflower">sunflower</label>
                                <img src="styles/img/icons/sunflower.png" alt="">
                                <input type="radio" id="sunflower" name="useraccessories" value="sunflower" <?= ($current_acc == 'sunflower') ? 'checked' : '' ?>>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="spacer"></div>

                <div class="useractivity">
                    <div class="activitysection">
                        <form id="join" action="joinroom.php" method="POST">
                            <input type="text" name="join_code" placeholder="Room Code"><br><br>
                            <button type="submit"><p>Join a room</p>
                                <span class="btn_tooltip">Make sure the code is correct!</span>
                            </button>
                        </form> 
                    </div>

                    <div class="spacer"></div>

                    <div class="activitysection">
                        <form id="createroom" action="#">
                            <p>Your room code :</p><br><p id="roomCodeDisplay" style="color: wheat; font-size: 2vmax; font-family: 'Press-Start'">------</p><br>
                            <button type="button" onclick="generateNewCode()"><p>Create a room</p>
                                <span class="btn_tooltip">Host your own room.</span>
                            </button>
                        </form> 

                    </div>
                </div>
            </div>

            <div id="notification-container"></div>
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