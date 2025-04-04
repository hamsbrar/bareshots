<?php

namespace Inc\AJAX;

use Inc\System\Renderer;
use Inc\System\AJAXResponder;
use Inc\Common\Procedures;
use Inc\AJAX\Modules\Bio;
use Inc\AJAX\Modules\Shop;
use Inc\AJAX\Modules\Home;
use Inc\AJAX\Modules\Contact;
use Inc\AJAX\Modules\Portfolio;

final class SystemActions
{
    public static function systemPageBio($arguments)
    {
        global $MASTER;

        Bio::init($arguments['route_1']);

        if ( ! Bio::$config)
        {
            return AJAXResponder::setError($MASTER['_bio_configuration_not_found']);
        }

        $image = Bio::image();

        if ( ! $image)
        {
            return AJAXResponder::setError($MASTER['_bio_image_not_found']);
        }

        Renderer::insert('image_src', $image);

        Renderer::insert('image_direction', Bio::$config['image_direction']);

        Renderer::insert('html_content', Bio::$config['html_content']);

        Renderer::insert('page_header', Procedures::getPageTitle(Bio::$config));

        Renderer::insert('page_footer', Procedures::getPageQuote(Bio::$config));

        Renderer::insert('page_animate_scroll_to_content', Bio::$config['page_animate_scroll_to_content']);

        Renderer::insert('content', Renderer::openParse('pages/partials/bio'));

        AJAXResponder::setData(
            Renderer::openParse('pages/wrapper')
        );
    }

    public static function systemPageContact($arguments)
    {
        global $MASTER;

        Contact::init($arguments['route_1']);

        if ( ! Contact::$config)
        {
            return AJAXResponder::setError($MASTER['_contact_configuration_not_found']);
        }

        Renderer::insert('contact_form_title', Contact::$config['contact_form_title']);
        Renderer::insert('contact_form_description', Contact::$config['contact_form_description']);
        Renderer::insert('contact_form_title_mobile', Contact::$config['contact_form_title_mobile']);

        Renderer::insert('page_header', Procedures::getPageTitle(Contact::$config));

        Renderer::insert('page_footer', Procedures::getPageQuote(Contact::$config));

        Renderer::insert('page_animate_scroll_to_content', Contact::$config['page_animate_scroll_to_content']);

        require_once __DIR__ . '/../../lib/simple-php-captcha/loader.php';

        $_SESSION['captcha'] = \simple_php_captcha();

        Renderer::insert('captcha', '<div class="input-container js-captcha-image js-is-hidden"><img src="' . $_SESSION['captcha']['image_src'] . '"></div><br><div class="input-container" ><input type="text" placeholder="' . $MASTER['_contact_enter_text_shown_in_above_image'] . '" class="js-captcha input h4 full b1"></div><br>');

        Renderer::insert('content', Renderer::openParse('pages/partials/contact'));

        AJAXResponder::setData(
            Renderer::openParse('pages/wrapper')
        );
    }

    public static function systemPageHome($arguments)
    {
        global $MASTER;

        Home::init($arguments['route_1']);

        if ( ! Home::$config)
        {
            return AJAXResponder::setError($MASTER['_home_configuration_not_found']);
        }

        Renderer::insert('carousel', Home::carousel());

        Renderer::insert('carousel_dots_settings', Home::carouselDots());
        Renderer::insert('autoPlay', Home::$config['carousel_autoPlay']);
        Renderer::insert('fullscreen', Home::$config['carousel_fullscreen']);
        Renderer::insert('wrapAround', Home::$config['carousel_wrapAround']);

        Renderer::insert('html_content', Home::$config['html_content']);

        Renderer::insert('page_header', Procedures::getPageTitle(Home::$config));

        Renderer::insert('page_footer', Procedures::getPageQuote(Home::$config));

        Renderer::insert('page_animate_scroll_to_content', Home::$config['page_animate_scroll_to_content']);

        Renderer::insert('content', Renderer::openParse('pages/partials/home'));

        AJAXResponder::setData(
            Renderer::openParse('pages/partials/' . (Home::$config['is_widescreen_slider'] == 'true' ? 'home_fullscreen' : 'home'))
        );
    }

    public static function systemPagePortfolio($arguments)
    {
        global $MASTER;

        Portfolio::init($arguments['route_1'], $arguments['route_2']);

        if ( ! Portfolio::$config)
        {
            return AJAXResponder::setError($MASTER['_portfolio_configuration_not_found']);
        }

        if (Portfolio::$set_default_sub_alias)
        {
            return AJAXResponder::setData('<script>load_page("' . $arguments['route_1'] . '", "' . Portfolio::$set_default_sub_alias . '")</script>');
        }

        $images = Portfolio::images();

        if ( ! $images || empty($images))
        {
            return AJAXResponder::setError($MASTER['_portfolio_no_images']);
        }

        Renderer::insert('images', \json_encode($images));

        Renderer::insert('load_per_page', Portfolio::$config['load_per_page']);

        Renderer::insert('page_header', Procedures::getPageTitle(Portfolio::$config));

        Renderer::insert('page_navigation', Procedures::getPageNav(Portfolio::$alias, Portfolio::$sub_alias));

        Renderer::insert('page_footer', Procedures::getPageQuote(Portfolio::$config));

        Renderer::insert('page_animate_scroll_to_content', Portfolio::$config['page_animate_scroll_to_content']);

        Renderer::insert('content', Renderer::openParse('pages/partials/portfolio'));

        AJAXResponder::setData(
            Renderer::openParse('pages/wrapper')
        );
    }

    public static function systemPageShop($arguments)
    {
        global $MASTER;

        Shop::init($arguments['route_1']);

        if ( ! Shop::$config)
        {
            return AJAXResponder::setError($MASTER['_shop_configuration_not_found']);
        }

        $files = Shop::items();

        if ( ! $files)
        {
            return AJAXResponder::setMessage($MASTER['_shop_no_items']);
        }

        $items = '';

        Renderer::insert('href', Shop::$config['contact_menu_name']);

        foreach ($files as $item)
        {
            Renderer::insert('title', $item['title']);

            Renderer::insert('price', $item['price']);

            Renderer::insert('description', $item['description']);

            Renderer::insert('image_src', $item['image']);

            $items .= Renderer::openParse('partials/shop/' . Shop::$config['item_display_type']);
        }

        Renderer::insert('items', $items);

        Renderer::insert('page_header', Procedures::getPageTitle(Shop::$config));

        Renderer::insert('page_footer', Procedures::getPageQuote(Shop::$config));

        Renderer::insert('page_animate_scroll_to_content', Shop::$config['page_animate_scroll_to_content']);

        Renderer::insert('content', Renderer::openParse('pages/partials/shop'));

        AJAXResponder::setData(
            Renderer::openParse('pages/wrapper')
        );
    }
}
