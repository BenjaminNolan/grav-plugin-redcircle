<?php
/**
 * RedCircle
 *
 * This plugin embeds RedCircle video from markdown URLs
 *
 * Based on the YouTube plugin.
 *
 * Licensed under MIT, see LICENSE.
 */

namespace Grav\Plugin;

use Grav\Common\Data\Data;
use Grav\Common\Plugin;
use Grav\Common\Page\Page;
use Grav\Common\Twig\Twig;
use Grav\Plugin\Redcircle\Twig\RedcircleTwigExtension;
use RocketTheme\Toolbox\Event\Event;

class RedcirclePlugin extends Plugin
{
    const REDCIRCLE_REGEX = '(?:https?:\/\/api\.podcache\.net\/embedded-player\/sh\/(?<showId>[a-zA-Z0-9-]+)\/ep\/(?<episodeId>[a-zA-Z0-9-]+))';

    /**
     * Return a list of subscribed events.
     *
     * @return array    The list of events of the plugin of the form
     *                      'name' => ['method_name', priority].
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
        ];
    }

    /**
     * Initialize configuration.
     */
    public function onPluginsInitialized()
    {
        if ($this->isAdmin()) {
            $this->enable([
                'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
            ]);
            return;
        }

        $this->enable([
            'onPageContentRaw' => ['onPageContentRaw', 0],
            'onTwigExtensions' => ['onTwigExtensions', 0],
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onShortcodeHandlers' => ['onShortcodeHandlers', 0],
        ]);
    }

    /**
     * Add content after page content was read into the system.
     *
     * @param  Event  $event An event object, when `onPageContentRaw` is fired.
     */
    public function onPageContentRaw(Event $event)
    {
        /** @var Page $page */
        $page = $event['page'];
        /** @var Twig $twig */
        $twig = $this->grav['twig'];
        /** @var Data $config */
        $config = $this->mergeConfig($page, TRUE);

        if ($config->get('enabled')) {
            // Get raw content and substitute all formulas by a unique token
            $raw = $page->getRawContent();

            // build an anonymous function to pass to `parseLinks()`
            $function = function ($matches) use ($twig, $config) {
                $search = $matches[0];

                // double check to make sure we found a valid RedCircle show id
                if (!isset($matches['showId'])) {
                    return $search;
                }

                // build the replacement embed HTML string
                $replace = $twig->processTemplate('partials/redcircle.html.twig', array(
                    'player_parameters' => $config->get('player_parameters'),
                    'show_id' => $matches['showId'],
                    'episode_id' => $matches['episodeId'],
                ));

                // do the replacement
                return str_replace($search, $replace, $search);
            };

            // set the parsed content back into as raw content
            $page->setRawContent($this->parseLinks($raw, $function, $this::REDCIRCLE_REGEX));
        }
    }

    /**
     * Add Twig Extensions.
     */
    public function onTwigExtensions()
    {
        require_once __DIR__ . '/classes/Twig/RedcircleTwigExtension.php';
        $this->grav['twig']->twig->addExtension(new RedcircleTwigExtension());
    }

    /**
     * Set needed variables to display breadcrumbs.
     */
    public function onTwigSiteVariables()
    {
        if (!$this->isAdmin() && $this->config->get('plugins.redcircle.built_in_css')) {
            $this->grav['assets']->add('plugin://redcircle/css/redcircle.css');
        }

        if ($this->isAdmin() && $this->config->get('plugins.redcircle.add_editor_button')) {
            $this->grav['assets']->add('plugin://redcircle/admin/editor-button/js/button.js');
        }
    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    /**
     * Initialize shortcodes
     */
    public function onShortcodeHandlers()
    {
        $this->grav['shortcode']->registerAllShortcodes(__DIR__.'/shortcodes');
    }
}
