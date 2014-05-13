<?php
/**
 * @package    plg_sys_lightbox4net
 * @author     Design4Net (Sergey Kupletsky)
 * @copyright  Copyright by Design4Net (C) 2013-2014. All rights reserved.
 * @license    GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version	   1.0.0RC
**/

// no direct access
defined('_JEXEC') or die;


class plgSystemLightbox4Net extends JPlugin {

    /**
     * Get params from plugin
     *
     */
    function plgLightbox4Net(&$subject, $config)
    {
        parent::__construct($subject, $config);
        $this->_plugin = JPluginHelper::getPlugin( 'system', 'Lightbox4Net' );
        $this->_params = new JParameter( $this->_plugin->params );
    }

    /**
     * Function that adding lightbox js and css files to site
     *
     */
    public function onBeforeCompileHead()
    {
        $document = JFactory::getDocument();

        // Add JavaScript
        JHtml::script('plg_system_lightbox4net/js/blueimp-gallery.min.js', false, true);
        if ($this->params->get('fullscreen') === '1') {
            JHtml::script('plg_system_lightbox4net/js/blueimp-gallery-fullscreen.min.js', false, true);
        }
        if ($this->params->get('show-indicator') === '1') {
            JHtml::script('plg_system_lightbox4net/js/blueimp-gallery-indicator.js', false, true);
        }
        JHtml::script('plg_system_lightbox4net/js/jquery.blueimp-gallery.min.js', false, true);

        // Add CSS
        JHtml::stylesheet('plg_system_lightbox4net/css/blueimp-gallery.min.css', false, true, false);
        if ($this->params->get('show-indicator') === '1') {
            JHtml::stylesheet('plg_system_lightbox4net/css/blueimp-gallery-indicator.css', false, true, false);
        }
    }

	/**
	 * Function that adding lightbox html code to site before body
	 *
	 */
  	public function onAfterRender()
    {
        // Get application params
        $app = JFactory::getApplication();

        // Disable in admin panel
        if($app->isAdmin()) {
            return;
        }

        // Initialise variables
        $controls =  ($this->params->get('show-controls') === '1') ? 'blueimp-gallery-controls' : '';
        $title =     ($this->params->get('show-title') === '1') ? '<h3 class="title"></h3>' : '';
        $autoplay =  ($this->params->get('show-autoplay') === '1') ? '<a class="play-pause"></a>' : '';
        $indicator = ($this->params->get('show-indicator') === '1') ? '<ol class="indicator"></ol>' : '';

        // Get body code and storing as buffer
        $buffer = JResponse::getBody();

        // If 'data-gallery' is in text
        if (strpos('data-gallery', $buffer)) {

            // Embed blueimp lightbox code
            $blueimp = '<div id="blueimp-gallery" class="blueimp-gallery '. $controls .'" >';
            $blueimp .= '<div class="slides"></div>';
            $blueimp .= $title;
            $blueimp .= '<a class="prev">‹</a>';
            $blueimp .= '<a class="next">›</a>';
            $blueimp .= '<a class="close">×</a>';
            $blueimp .= $autoplay;
            $blueimp .= $indicator;
            $blueimp .= '</div>';

            $buffer = preg_replace("/<\/body>/", $blueimp."\n\n</body>", $buffer);

            // Output the buffer
            JResponse::setBody($buffer);
        }
	}
}
?>