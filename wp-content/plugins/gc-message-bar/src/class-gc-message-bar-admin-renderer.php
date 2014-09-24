<?php
if(!class_exists("Gc_Message_Bar_Admin_Renderer")){
	class Gc_Message_Bar_Admin_Renderer extends Gc_MessageBar_Abstract_Renderer{
        protected $options;
        protected $themes_repo;
        protected $event_manager = null;

        public function __construct($namespace, $controller,$options){
            parent::__construct($namespace,$controller);
            $this->options = $options;
            $this->metrix_code = $this->get_option_value('metrix_code');
            $this->themes_repo = Gc_MessageBar_CF::create("Theme_Repository_Factory")->get_instance();
            $this->event_manager = Gc_MessageBar_Service_Locator::get(GC_MESSAGE_BAR_SL_EVENT_MANAGER);
            $this->initialize();
    	}
        public function initialize(){
            $this->event_manager->listen(GC_MESSAGE_BAR_NAME.".render_section",array($this,"on_render_section"));
        }

    	public function render($gci) {
            global $GC_Message_Bar_Config;
?>
    <div class="clear"></div>
    <section class="GCPLUGIN">
        <div class="wrapper820">
            <div class="adminpanel">
                <header>
                    <div class="headertop">
                        <div class="logo"></div>
                        <?php if( empty($this->metrix_code) ) :?>
                        <div class="connectblock">
                            <?php /* <div class="whatismygc"></div> */ ?>
                            <a href="<?php echo $GC_Message_Bar_Config['MYGC']; ?>/signup/plugin" target="_blank"><b>Sign Up</b> for FREE</a>
                            <div class="indicator">Not connected to MY.GetConversion</div>
                            <div class="clear"></div>
                        </div>
                        <?php else: ?>
                        <div class="connectblock">
                            <a href="<?php echo $GC_Message_Bar_Config['MYGC']; ?>/signin" target="_blank">Sign in</a>
                            <div class="indicator indicator_blue"><b>Connected to MY.GetConversion</b></div>
                            <a href="javascript:void(0);" class="disconnect" title="Disconnect" name="Disconnect">Disconnect</a>
                            <div class="clear"></div>
                            <script type="text/javascript">
                                jQuery(document).ready(function(){
                                    jQuery('.disconnect').on('click',function(){
                                        Gc.Disconnect_Button_Click('<?php echo $this->metrix_code; ?>');
                                    });
                                });
                            </script>
                        </div>
                    <?php endif; ?>
                    </div>
                    <nav>
                        <ul class="menu1">
                            <li class="first"><a href="<?php echo $GC_Message_Bar_Config['GCFORUM']; ?>" target="_blank"><b>Forum</b></a></li>
                            <li><a href="<?php echo $GC_Message_Bar_Config['GCIDEA']; ?>" target="_blank">Suggest an <b>Idea</b></a></li>
                            <li><a href="<?php echo $GC_Message_Bar_Config['GCBUG']; ?>" target="_blank">Report a <b>Bug</b></a></li>
                            <div class="clear"></div>
                        </ul>
                        <?php if( !empty($this->metrix_code) ) :?>
                        <ul class="menu2">
                            <li class="first"><a href="<?php echo $GC_Message_Bar_Config['WPORGURL']; ?>" target="_blank">Support us by giving &#9733;&#9733;&#9733;&#9733;&#9733; rating on wordpress.org</a></li>
                            <div class="clear"></div>
                        </ul>
                        <?php endif; ?>
                        <div class="clear"></div>
                    </nav>
                </header>

                <?php if( empty($this->metrix_code) ) :?>
                <section class="newprodmsg">
                    <iframe src="<?php echo $GC_Message_Bar_Config['GCSERVICES']; ?>/gc-message-bar/buy-or-connect" width="818" height="280"></iframe>
                </section>
                <?php else : ?>
                <section class="newprodmsg">
                    <iframe src="<?php echo $GC_Message_Bar_Config['GCSERVICES']; ?>/gc-message-bar/new-product" width="818" height="280"></iframe>
                </section>
                <?php endif; ?>

                <form method="post" id="updateSettings">
                <?php
                global $gc_message_bar_admin_layout;

                foreach($gc_message_bar_admin_layout as $container){
                    $container['namespace'] = $this->namespace;
                    $this->event_manager->dispatch(GC_MESSAGE_BAR_NAME.".render_section",new Gc_MessageBar_Event($container));
                }
                ?>
                </form>

                <footer>
                    <nav>
                        <ul class="menu1">
                            <li class="first"><a href="<?php echo $GC_Message_Bar_Config['GCFORUM']; ?>" target="_blank">Ask on <b>Forum</b></a></li>
                            <li><a href="<?php echo $GC_Message_Bar_Config['GCROADMAP']; ?>" target="_blank">Vote for <b>Roadmap</b></a></li>
                            <li><a href="<?php echo $GC_Message_Bar_Config['GCIDEA']; ?>" target="_blank">Suggest an <b>Idea</b></a></li>
                            <li><a href="<?php echo $GC_Message_Bar_Config['GCBUG']; ?>" target="_blank">Report a <b>Bug</b></a></li>
                            <div class="clear"></div>
                        </ul>
                        <ul class="menu2">
                            <li><a href="<?php echo $GC_Message_Bar_Config['WPORGURL']; ?>" target="_blank">Support us by giving &#9733;&#9733;&#9733;&#9733;&#9733; rating on wordpress.org</a></li>
                            <div class="clear"></div>
                        </ul>
                        <div class="clear"></div>
                    </nav>
                    <div class="metainfo">
                        <div class="metablock">
                            <div class="gcpversion">Version: <?php echo Gc_MessageBar_Util::get_version() ?> <?php if($GC_Message_Bar_Config['GCTYPE'] == 'DEV'): ?>DEVELOPMENT<?php endif; ?></div>
                            <div class="gcpengine">
                                <a href="#" id="engine_switcher">Show/Hide Engine Settings</a>
                            </div>
                        </div>
                        <div class="copyblock">
                            <div class="copytxt"><a href="<?php echo $GC_Message_Bar_Config['GCPLUGINHOME']; ?>" target="_blank">GC Message Bar</a> by</div>
                            <div class="copylogo"><a href="<?php echo $GC_Message_Bar_Config['GCHOME']; ?>" target="_blank" class="gclogo">GetConversion</a></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </footer>
            </div>
            <form method="post">
                <?php
                    $renderer_name = "Gc_MessageBar_Options_Engine_Group_Container_Renderer";
                    $container =  array(
                        "title" => "Engine",
                        "id"    => "engine_settings",
                        "option_group" => "internal_engine",
                    );
                    $cnt = new $renderer_name(Gc_MessageBar_CF::create_and_init("Option_Group",$container),$this->options->filter_options_by_group($container["option_group"]),$this->namespace);
                    $cnt->set_event_manager($this->event_manager);
                    $cnt->set_event_prefix(GC_MESSAGE_BAR_NAME);
                    $cnt->init_event_handler();
                    $cnt->render(array());
                ?>
            <input type="hidden" name="<?php echo $this->namespace.'engine';?>" value="true"/>
            </form>

        </div>
    </section>
    <div style="clear:both;"></div>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery("#engine_switcher").click(function() {
                enginepanel_close();
                return false;
            });
            
            jQuery("#engine_settings_a").click(function() {
                enginepanel_close();
                return false;
            });
        }); 

        function enginepanel_close() {
            jQuery('.enginepanel').toggle();
            Gc.Option_Group_On_Click(this,'engine_settings');
        }
    </script>

        <?php
    	}
        public function on_render_section($event){
            $container = $event->get_params();
            if(isset($container["renderer"])){
                $renderer_name = $container["renderer"];
            }else{
                $renderer_name = "Gc_MessageBar_Options_Group_Container_Renderer";
            }
            $cnt = new $renderer_name(Gc_MessageBar_CF::create_and_init("Option_Group",$container),$this->options->filter_options_by_group($container["option_group"]),$this->namespace);
            $cnt->set_event_manager($this->event_manager);
            $cnt->set_event_prefix(GC_MESSAGE_BAR_NAME);
            $cnt->init_event_handler();
            $cnt->render(array());
        }
        public function render_pageswitcher($state, $val, $id, $permalink, $filter) {
              $states = array("on" => 1,"off" => 2);
              $tid = $states[$state];
              $text = array(1=> '<span>ON</span><b></b><div class="clear"></div>',2=>'<b></b><span>OFF</span><div class="clear"></div>');

            ?>
            <div class="onoff" id="gc_message_bar_pageswitcher_cnt">
                <div class="edit">
                    <a href="#" class="<?php echo $state; ?>" onclick="return Gc.Onoff_Button_On_Click(this,'gc_message_bar_pageswitcher');" id="gc_message_bar_pageswitcher_a"><?php echo $text[$tid]; ?></a>
                    <input type="hidden" name="gc_message_bar_pageswitcher" value="<?php echo $id; ?>" id="gc_message_bar_pageswitcher_input"/> 
                </div>
                <div class="label">
                    <label>GC Message Bar appears on this page</label>
                </div>
                <div class="clear"></div>
            </div>
            <script type="text/javascript">
                jQuery(document).ready(function() {
                    jQuery('#gc_message_bar_pageswitcher_input').on('change', function() {
                        if ((jQuery(this).val() == "1" && "<?php echo $filter; ?>" == "displayed_pages_allow") || (jQuery(this).val() == "2" && "<?php echo $filter; ?>" == "displayed_pages_deny")) {
                            jQuery.ajax({
                               url:ajaxurl,
                               data:{
                                  'action':'gc-message-bar-add-page',
                                  'data':'<?php echo $permalink; ?>'
                               },
                               type:'POST',
                               dataType:'json'
                            }).done(function(response){
                                if (!response.success) {
                                    alert('Something went wrong.');
                                }
                            }).fail(function(response){
                                alert('Something went wrong. Please try again later.');
                            });
                        } else {
                            jQuery.ajax({
                               url:ajaxurl, 
                               data:{
                                  'action':'gc-message-bar-remove-page',
                                  'data':'<?php echo $permalink; ?>'
                               },
                               type:'POST',
                               dataType:'json'
                            }).done(function(response){
                                if (!response.success) {
                                    alert('Something went wrong. Please try again later.');
                                }
                            }).fail(function(response){
                                alert('Something went wrong. Please try again later.');
                            });
                        }
                    });
                });
            </script>
            <?php
        }   


	}
}