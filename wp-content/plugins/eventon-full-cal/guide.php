<?php
echo "
<p>Use this shortcode to add the calendar with full grid: <b>[add_eventon_fc]</b><br/><br/>
You can also use the variable <b>month_incre</b> to show a different focus month just like the eventon calendar.<br/>
eg. <b>[add_eventon_fc month_incre=+3]</b> - this will show events a month with same date that is  3 months in advance.
</p>

<p>
Other variables that work with this shortcode are <b>event_type, event_type_2, and event_count</b>. Also note you will see easy shortcode buttons for daily view in EventON shortcode popup in WYSIWYG text editor on pages.
</p>
<br/>
<p>
<b>Php template tags</b><br/>
&lt;?php<br/> if(function_exists(add_eventon_fc)){<br/>
add_eventon_fc(&#36;args);</br>
}?&gt;
</p>

";
?>