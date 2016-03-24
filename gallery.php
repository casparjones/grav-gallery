<?php
namespace Grav\Plugin;

use \Grav\Common\Plugin;
use \Grav\Common\Grav;
use \Grav\Common\Page\Page;

class GalleryPlugin extends Plugin
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize configuration
     */
    public function onPluginsInitialized()
    {
        if ($this->isAdmin()) {
            $this->active = false;
            return;
        }

        $this->enable([
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0]
        ]);
    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    /**
     * Set needed variables to display cart.
     */
    public function onTwigSiteVariables()
    {
        if ($this->config->get('plugins.gallery.built_in_css')) {
            $this->grav['assets']
                ->addCss('plugin://gallery/css/gallery.css')
                ->addCss('plugin://gallery/css/gallery-custom.css');
        }

        $this->grav['assets']
            ->add('jquery', 101)
            ->addJs('plugin://gallery/js/gallery.min.js');

        $init = "$(document).ready(function() {
                        $('a[rel=\"lightbox\"]').featherlight({
                            openSpeed: '250',
                            closeSpeed: '250',
                            closeOnClick: '250',
                            root: 'body'
                        });
                     });";
        $this->grav['assets']
            ->addCss('plugin://featherlight/css/featherlight.min.css')
            ->add('jquery', 101)
            ->addJs('plugin://featherlight/js/featherlight.min.js')
            ->addInlineJs($init);
    }
}
