</head>
<nav>
    <div class="navbar_list bgcolor_primary color_on_primary">
        <div class="">
            <div class="navbar_left">
                <a href="./" class="<?php if ($pagename == "index") echo "active"; ?>">Home</a>
                <a href="./explore.php" class="<?php if ($pagename == "explore") echo "active"; ?>">Esplora</a>
                <a href="./ideagenerator.php" class="<?php if ($pagename == "ideagenerator") echo "active"; ?>">Idea?</a>
            </div>

            <div class="navbar_right">
                <div id="navbar_datetime"></div>

                <?php if (isLogged()) { ?>
                    <a id="navbar_avatar" href="settings.php" class="<?php if ($pagename == "settings") echo "active"; ?>"><img class="avatar avatar_small" src="<?php echo "./" . $folder_avatars . "/" . $avataruri; ?>"  alt="Immagine avatar"/></a>
                    <a href="#logout" onClick="logout();">Logout</a>
                <?php } else { ?>
                    <a href="auth.php" class="<?php if ($pagename == "auth") echo "active"; ?>">Login</a>
                <?php } ?>
            </div>
        </div>
    </div>
</nav>