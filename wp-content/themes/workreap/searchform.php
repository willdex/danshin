<?php
/**
 *
 * Theme Search form
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */
$theme_check = workreap_new_theme_active();
if(!$theme_check){ ?>
    <form class="wt-formtheme wt-formsearch" method="get" role="search" action="<?php echo esc_url(home_url('/')); ?>">
        <fieldset>
            <div class="form-group">
                <input type="search" name="s" value="<?php echo get_search_query(); ?>" class="form-control" placeholder="<?php esc_attr_e('Searching Might Help', 'workreap') ?>">
                <button type="submit" class="wt-searchgbtn"><i class="fa fa-search"></i></button>
            </div>
        </fieldset>
    </form>
<?php }else{ ?>
    <form class="tu-formtheme tu-formsearch" method="get" role="search" action="<?php echo esc_url(home_url('/')); ?>">
        <fieldset>
            <div class="form-group">
                <input type="search" name="s" value="<?php echo get_search_query(); ?>" class="form-control" placeholder="<?php esc_attr_e('Search with keyword', 'workreap') ?>">
                <button type="submit" class="tu-searchgbtn"><i class="fa fa-search"></i><span><?php esc_html_e('Search now', 'workreap') ?></span></button>
            </div>
        </fieldset>
    </form>
<?php } ?>