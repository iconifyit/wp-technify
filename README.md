# WP Technify

Plugin of various WP utilities

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `technify.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php do_action('plugin_name_hook'); ?>` in your templates

https://developers.google.com/speed/pagespeed/insights/?url=https%3A%2F%2Fvectoricons.net%2F&hl

https://developers.google.com/speed/docs/insights/OptimizeCSSDelivery

<html>
  <head>
    <style>
      .blue{color:blue;}
    </style>
    </head>
  <body>
    <div class="blue">
      Hello, world!
    </div>
    <noscript id="deferred-styles">
      <link rel="stylesheet" type="text/css" href="small.css"/>
    </noscript>
    <script>
      var loadDeferredStyles = function() {
        var addStylesNode = document.getElementById("deferred-styles");
        var replacement = document.createElement("div");
        replacement.innerHTML = addStylesNode.textContent;
        document.body.appendChild(replacement)
        addStylesNode.parentElement.removeChild(addStylesNode);
      };
      var raf = requestAnimationFrame || mozRequestAnimationFrame ||
          webkitRequestAnimationFrame || msRequestAnimationFrame;
      if (raf) raf(function() { window.setTimeout(loadDeferredStyles, 0); });
      else window.addEventListener('load', loadDeferredStyles);
    </script>
  </body>
</html>

https://github.com/addyosmani/critical