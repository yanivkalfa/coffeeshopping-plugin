<div id="toptemplatecont" class="center">
    <?php if(!is_user_logged_in()) { ?>
        <span>
        <a href="<?php echo site_url(); ?>/login/">Login</a> |
        <a href="<?php echo site_url(); ?>/register/">Register</a>
    </span>
    <?php } else {
        $user = wp_get_current_user();
        $logout = wp_logout_url( site_url() );
        ?>
        <span> Hey <?php echo $user->user_nicename ?> <a href="<?php echo $logout; ?>">Logout</a></span>

    <?php }?>
     |
    <span> סניפים: אלנבי 83, תל אביב (סניף ראשי) | נורדאו 1, חיפה | שד' בן-גוריון 84, קרית מוצקין (רבמ"ד) | ז'בוטינסקי 1 (פינת הגעתון), נהריה | שפרעם</span>
</div>
