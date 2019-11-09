<?php

namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class RedcircleShortcode extends Shortcode
{
    const REDCIRCLE_REGEX = '(?:https?:\/\/api\.podcache\.net\/embedded-player\/sh\/(?<showId>[a-zA-Z0-9-]+)\/ep\/(?<episodeId>[a-zA-Z0-9-]+))';

    public function init()
    {
        $this->shortcode->getHandlers()->add('redcircle', function(ShortcodeInterface $sc) {

            // Get shortcode content and parameters
            $url = $sc->getContent();
            $params = $sc->getParameters();

            if ($url) {
                preg_match($this::REDCIRCLE_REGEX, $url, $matches);
                $search = $matches[0];

                // double check to make sure we found a valid RedCircle show id
                if (!isset($matches['showId'])) {
                    return $search;
                }

                /** @var Twig $twig */
                $twig = $this->grav['twig'];

                // build the replacement embed HTML string
                $replace = $twig->processTemplate('partials/redcircle.html.twig', array(
                    'player_parameters' => $params,
                    'show_id' => $matches['showId'],
                    'episode_id' => $matches['episodeId'],
                ));

                // do the replacement
                return str_replace($search, $replace, $search);
            }


        });
    }
}
