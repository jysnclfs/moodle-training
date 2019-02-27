<?php

class theme_photo_core_renderer extends theme_boost\output\core_renderer  {

    // public function favicon() {
    //     $context = context_system::instance();
    //     $fs = get_file_storage();
    //     $files = $fs->get_area_files($context->id, 'theme_photo', 'favicon');
    //     foreach($files as $file){
    //     if($file->get_filename() != ".") {
    //     $faviconUrl = moodle_url::make_pluginfile_url(
    //     $file->get_contextid(),
    //     $file->get_component(),
    //     $file->get_filearea(),
    //     $file->get_itemid(),
    //     $file->get_filepath(),
    //     $file->get_filename()
    //     );
    //     }
    //     }
    //     $public_display_config = get_config('local_public_display');
    //     return $public_display_config->displaypublicretailpage && !empty($faviconUrl) ? $faviconUrl : $this->image_url('favicon', 'theme');
    //     }
    // print_object(s)

    // public function heading($text, $level = 2, $classes = 'main', $id = null) {
    //     $content  = html_writer::start_tag('div', array('class'=>'headingcontainer'));
    //     $content .= html_writer::empty_tag('img', array('src'=>$this->image_url('logo', 'theme_photo'), 'alt'=>'', 'class'=>'headingimage'));
    //     $content .= parent::heading($text, $level, $classes, $id);
    //     $content .= html_writer::end_tag('div');
    //     return $content;
    // }
    

    public function page_title() {
        $data = get_config('theme_photo', 'pagetitle') . ' : ' . $this->page->title;
        return $data;
    }

    // public function render_index_page($page) {                                                                                      
    //     $data = $page->export_for_template($this);                                                                                  
    //     return parent::render_from_template('theme_photo/index_page', $data);                                                         
    // }  
    
    // public function navbar_home($returnlink = true) {
    //     global $CFG;

    //     // global $CFG;
    //     // $logo = get_config('core_admin', 'logocompact');
    //     // if (empty($logo)) {
    //     //     return false;
    //     // }

    //     // die();

    //     $imageurl = get_config('logo', 'theme_photo');
    //     if ($this->should_render_logo() || empty($imageurl)) {
    //         // If there is no small logo we always show the site name.
    //         return $this->get_home_ref($returnlink);
    //     }
    //     $image = html_writer::img($imageurl, get_string('sitelogo', 'theme_' . $this->page->theme->name),
    //         array('class' => 'small-logo'));

    //     // if ($returnlink) {
    //     //     $logocontainer = html_writer::link(new moodle_url('/'), $image,
    //     //         array('class' => 'small-logo-container', 'title' => get_string('home')));
    //     // } else {
    //         $logocontainer = html_writer::tag('span', $image, array('class' => 'small-logo-container'));
    //     // }

    //     // Sitename setting defaults to true.
    //     // if (!isset($this->page->theme->settings->sitename) || !empty($this->page->theme->settings->sitename)) {
    //     //     return $logocontainer . $this->get_home_ref($returnlink);
    //     // }

    //     return $logocontainer;
    // }
    public function test() {
        $imageurl = $this->image_url('logo', 'theme_photo');
        $image = html_writer::img($imageurl, get_string('pluginname', 'theme_' . $this->page->theme->name), array('class' => 'small-logo'));

        $logocontainer = html_writer::tag('span', $image, array('class' => 'small-logo-container'));

        return $logocontainer;
    }
}