</head>

<nav class="bgcolor_primary color_on_primary">
    <a class="navbar_left navbar_nopadding" href="./"><img class="navbar_logo" src="./<?php echo $folder_media; ?>/logosmall.png" alt="Logo di <?php echo $service_name; ?>"/></a>
    <a class="navbar_left <?php if($pagename == "create") echo "active"; ?>" href="./create.php">➕ Crea contenuto</a>
    <a class="navbar_left <?php if ($pagename == "ideagenerator") echo "active"; ?>" href="./ideagenerator.php">🔮 Generatore di idee</a>
    <a class="navbar_left <?php if ($pagename == "about") echo "active"; ?>" href="./about.php">📕 Manuale utente</a>


    <?php if (isLogged()) { ?>
        <a class="navbar_right" href="#" onClick="logout();">🚪 Logout</a>
        <a class="navbar_right navbar_nopadding <?php if ($pagename == "profile") echo "active"; ?>" title="<?php echo $id; ?>" href="profile.php"><img class="avatar navbar_avatar" src="<?php echo "./" . $folder_avatars . "/" . $avataruri; ?>"  alt="Immagine avatar"/></a>
    <?php } else { ?>
        <a class="navbar_right <?php if ($pagename == "auth") echo "active"; ?>" href="auth.php">🚪 Login</a>
    <?php } ?>
    <span class="navbar_right">🕒 <span id="navbar_datetime">Ora</span></span>
</nav>