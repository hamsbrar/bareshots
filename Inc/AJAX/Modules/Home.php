<?php

namespace Inc\AJAX\Modules;

final class Home
{
    public static $alias;

    public static $carousel_settings;

    public static $config;

    public static $folder;

    public static $folder_url;

    public static function carousel()
    {
        if ( ! self::$carousel_settings || empty(self::$carousel_settings))
        {
            return '';
        }

        $content = '';

        foreach (self::$carousel_settings as $filename => $data)
        {
            $image_title = (isset($data['title']) && \trim($data['title'])) ? \trim($data['title']) : '';

            $image_description = (isset($data['description']) && \trim($data['description'])) ? \trim($data['description']) : '';

            $image_load_page = (isset($data['load_page']) && \trim($data['load_page'])) ? \trim($data['load_page']) : '';

            $image_load_sub_page = (isset($data['load_sub_page']) && \trim($data['load_sub_page'])) ? \trim($data['load_sub_page']) : '';

            if (\file_exists(self::$folder . '/.files/' . $filename))
            {
                $content .= '<div class="carousel-cell carousel-item" style="background:url(' . self::$folder_url . '/.files/' . $filename . ');background-repeat: repeat;background-size: auto;background-repeat: no-repeat;background-size: auto;background-' . (self::$config['is_widescreen_slider'] == 'true' && self::$config['widescreen_full_image'] == 'true' ? 'size:cover;' : 'position:center;') . '"" >
                                <div class="display-carousel-container" style="' . (isset($first) ? 'display:none;' : '') . '" onclick="load_page(\'' . $image_load_page . '\', \'' . $image_load_sub_page . '\')">
                                    <div class="carousel-title h5 b1">
                                        ' . $image_title . '
                                    </div>
                                    <div class="carousel-description h2 b1">
                                        ' . $image_description . '
                                    </div>
                                </div>
                            </div>';
                $first = true;
            }
        }

        return '<div class="carousel">' . $content . '</div>';
    }

    public static function carouselDots()
    {
        if (isset(self::$config['carousel_dots_to_bar']) && self::$config['carousel_dots_to_bar'] == 'true')
        {
            return '.flickity-page-dots {
                bottom: -22px;
            }
            .flickity-page-dots .dot {
                height: 4px;
                width: 40px;
                margin: 0;
                border-radius: 0;
            }';
        }

        return '';
    }

    public static function init($home_alias)
    {
        global $MASTER;

        self::$alias = $home_alias;

        self::$config = PAGE_DATA[$home_alias]['page_settings'] ?? false;

        self::$folder = __DIR__ . '/../../../profiles/' . $MASTER['profile_json']['active_profile'] . '/menu/' . $home_alias;

        self::$folder_url = $MASTER['profile_json']['active_profile_url'] . '/menu/' . $home_alias;

        if (\file_exists(self::$folder . '/carousel.json'))
        {
            self::$carousel_settings = \json_decode(\file_get_contents(self::$folder . '/carousel.json'), true);
        }
        else {
            self::$carousel_settings = false;
        }
    }
}
