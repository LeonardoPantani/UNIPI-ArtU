</head>
<nav>
    <div class="navbar_list bgcolor_primary color_on_primary">
        <div class="">
            <div class="navbar_left">
                <a id="navbar_index" href="./" class="<?php if ($pagename == "index") echo "active"; ?>">🌍 Esplora</a>
                <a id="navbar_create" href="./create.php" class="<?php if($pagename == "create") echo "active"; ?>">➕ Crea contenuto</a>
                <a id="navbar_idea" href="./ideagenerator.php" class="<?php if ($pagename == "ideagenerator") echo "active"; ?>">🔮 Generatore di idee</a>
            </div>

            <div class="navbar_right">
                <div id="navbar_version">versione <?php echo $service_version; ?></div>
                <div id="navbar_datetime"></div>

                <?php if (isLogged()) { ?>
                    <a title="<?php echo $id; ?>" id="navbar_avatar" href="settings.php" class="<?php if ($pagename == "settings") echo "active"; ?>"><img class="avatar avatar_small" src="<?php echo "./" . $folder_avatars . "/" . $avataruri; ?>"  alt="Immagine avatar"/></a>
                    <a href="#logout" onClick="logout();">🚪 Logout</a>
                <?php } else { ?>
                    <a href="auth.php" class="<?php if ($pagename == "auth") echo "active"; ?>">🚪 Login</a>
                <?php } ?>
            </div>
        </div>
    </div>
</nav>