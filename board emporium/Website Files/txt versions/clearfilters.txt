
<?php
    # unsets all filter form get variables
    unset($_GET[sort]);
    unset($_GET[filterCategory]);
    unset($_GET[filterSize]);
    unset($_GET[filterSwitch]);
    unset($_GET[filterColor]);
    unset($_GET[filterBacklight]);

    //Redirects user to the login page
    header("Location: shop.php");
?>
