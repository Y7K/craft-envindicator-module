/**
 * Environment Indicator module for Craft CMS
 *
 * Environment Indicator JS
 *
 * @author    Y7K
 * @copyright Copyright (c) 2018 Y7K
 * @link      y7k.com
 * @package   EnvironmentIndicatorModule
 * @since     1.0.0
 */

document.addEventListener('DOMContentLoaded', (event) => {

    // Create the div element
    const div = document.createElement('div');
    div.className = `envindicator envindicator-${window.appEnv}`;
    div.innerHTML = window.envText;

    // Append it to the sidebar,
    // or on the loginscreen, replace the craft logo with it
    const sidebar = document.getElementById('global-sidebar');
    if (sidebar) {
        sidebar.appendChild(div);
    } else {
        const poweredby = document.getElementById('poweredby');
        poweredby.parentNode.replaceChild(div, poweredby);
    }

});
