</head>
<nav>
    <div class="navbar_list bgcolor_primary color_on_primary">
        <div class="navbar_left">
            <a href="./"><img src="./<?php echo $folder_media; ?>/logosmall.png" alt="Logo di <?php echo $service_name; ?>"/></a>
            <a id="navbar_create" href="./create.php" class="<?php if($pagename == "create") echo "active"; ?>">➕ Crea contenuto</a>
            <a id="navbar_idea" href="./ideagenerator.php" class="<?php if ($pagename == "ideagenerator") echo "active"; ?>">🔮 Generatore di idee</a>
            <a id="navbar_about" href="./about.php" class="<?php if ($pagename == "about") echo "active"; ?>">📕 Manuale utente</a>
        </div>

        <div class="navbar_right">
            <div id="navbar_datetime"></div>

            <?php if (isLogged()) { ?>
                <a title="<?php echo $id; ?>" id="navbar_avatar" href="profile.php" class="<?php if ($pagename == "profile") echo "active"; ?>"><img class="avatar avatar_small" src="<?php echo "./" . $folder_avatars . "/" . $avataruri; ?>"  alt="Immagine avatar"/></a>
                <a href="#logout" onClick="logout();">🚪 Logout</a>
            <?php } else { ?>
                <a href="auth.php" class="<?php if ($pagename == "auth") echo "active"; ?>">🚪 Login</a>
            <?php } ?>
        </div>
    </div>
</nav>