<?php

namespace Grav\Plugin\Redcircle\Twig;

use Grav\Common\Grav;

class RedcircleTwigExtension extends \Twig_Extension
{
    /**
     * Returns extension name.
     *
     * @return string
     */
    public function getName()
    {
        return 'RedcircleTwigExtension';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('redcircle_embed_url', [$this, 'embedUrl']),
        ];
    }

    /**
     * Builds RedCircle video embed URL.
     *
     * @param  string  $show_id
     * @param  string  $episode_id
     * @param  array   $player_parameters
     * @return string
     */
    public function embedUrl($show_id, $episode_id, array $player_parameters = array())
    {
        $grav = Grav::instance();

        // build base video embed URL (while respecting privacy enhanced mode setting)
	$url = "https://api.podcache.net/embedded-player/sh/{$show_id}/ep/{$episode_id}";

        // filter player parameters to only those not matching RedCircle defaults
        $filtered_player_parameters = array();
        foreach ($player_parameters as $key => $value) {
            $parameter_blueprint = $grav['config']->blueprints()->get('plugins.redcircle.player_parameters.' . $key);

            // configured value matches RedCircle default -> skip parameter
            if (isset($parameter_blueprint['default']) && $parameter_blueprint['default'] == $value) {
                continue;
            }

            $filtered_player_parameters[$key] = $value;
        }

        // append query string (if any)
        if (!empty($filtered_player_parameters) && ($query_string = http_build_query($filtered_player_parameters))) {
            $url .= '?' . $query_string;
        }

        return $url;
    }
}

