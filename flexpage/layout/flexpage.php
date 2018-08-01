<?php
/**
 * Flexpage Theme
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see http://opensource.org/licenses/gpl-3.0.html.
 *
 * @copyright Copyright (c) 2009 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @package theme_flexpage
 * @author Mark Nielsen
 */

/**
 * Flexpage Layout File
 *
 * @author Mark Nielsen
 * @package theme_flexpage
 */

/**
 * Flexpage local library
 * @see format_flexpage_default_width_styles
 */
require_once($CFG->dirroot.'/course/format/flexpage/locallib.php');

$hasheading = ($PAGE->heading);
$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$hasfooter = (empty($PAGE->layout_options['nofooter']));
$hassidetop = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('side-top', $OUTPUT));
$hassidepre = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('side-pre', $OUTPUT));
$hassidepost = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('side-post', $OUTPUT));
$haslogininfo = (empty($PAGE->layout_options['nologininfo']));

$showsidepre = ($hassidepre && !$PAGE->blocks->region_completely_docked('side-pre', $OUTPUT));
$showsidepost = ($hassidepost && !$PAGE->blocks->region_completely_docked('side-post', $OUTPUT));

// Always show block regions when editing so blocks can
// be dragged into empty block regions.
if ($PAGE->user_is_editing()) {
    if ($PAGE->blocks->is_known_region('side-pre')) {
        $showsidepre = true;
        $hassidepre  = true;
    }
    if ($PAGE->blocks->is_known_region('side-post')) {
        $showsidepost = true;
        $hassidepost  = true;
    }
    if ($PAGE->blocks->is_known_region('side-top')) {
        $hassidetop = true;
    }
}

$custommenu = $OUTPUT->custom_menu();
$hascustommenu = (empty($PAGE->layout_options['nocustommenu']) && !empty($custommenu));

$courseheader = $coursecontentheader = $coursecontentfooter = $coursefooter = '';
if (empty($PAGE->layout_options['nocourseheaderfooter'])) {
    $courseheader = $OUTPUT->course_header();
    $coursecontentheader = $OUTPUT->course_content_header();
    if (empty($PAGE->layout_options['nocoursefooter'])) {
        $coursecontentfooter = $OUTPUT->course_content_footer();
        $coursefooter = $OUTPUT->course_footer();
    }
}

$bodyclasses = array();
if ($showsidepre && !$showsidepost) {
    if (!right_to_left()) {
        $bodyclasses[] = 'side-pre-only';
    } else {
        $bodyclasses[] = 'side-post-only';
    }
} else if ($showsidepost && !$showsidepre) {
    if (!right_to_left()) {
        $bodyclasses[] = 'side-post-only';
    } else {
        $bodyclasses[] = 'side-pre-only';
    }
} else if (!$showsidepost && !$showsidepre) {
    $bodyclasses[] = 'content-only';
}
if ($hascustommenu) {
    $bodyclasses[] = 'has_custom_menu';
}

//Generar la URL del curso
$courseurl = $CFG->wwwroot."/course/view.php?id=".$PAGE->course->id;

//Generar el Breadcrumb
$breadcrumb = '<a class="navbar-link" href="'. $courseurl . '">'. $PAGE->course->fullname .'</a>'; //Nombre del curso, va siempre
if($sectionname) {$breadcrumb .= ' <span class="separador">|</span> <a class="navbar-link" href="'. $sectionurl . '">'.$sectionname .'</a>';};  //Agregamos nombre de sección, si está disponible
if($PAGE->cm->name) {$breadcrumb .= ' <span class="separador">|</span> '.$PAGE->cm->name;}; //Agregamos nombre del módulo (Página/Foro/Carpeta/Etc), si está disponible.


echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes() ?>>
<head>
    <!-- Head -->
    <?php require('head.php'); ?>
    <!-- /Head -->
</head>
<body id="<?php p($PAGE->bodyid) ?>" <?php echo $OUTPUT->body_attributes(); ?>>
<?php echo $OUTPUT->standard_top_of_body_html() ?>
<?php if($hassidepre) { ?>
    <div class="navmenu navmenu-default navmenu-fixed-left navmenu-inverse"> <!-- Navbar Lateral -->
        <?php if($PAGE->course->fullname) { ?>
            <div class="bloque-lateral panel panel-default coursename block"> <!-- Nombre del curso. -->
                <div class="panel-body nopadding"><h1 class="text-center nomargin"><?php echo $PAGE->course->fullname; ?></h1></div>
            </div>
        <?php }; ?>
        <div class="bloque-lateral panel panel-default breadcrumb block visible-sm visible-xs"> <!-- Breadcrumb, solo SM y XS -->
            <div class="panel-body">
                <?php echo $breadcrumb; ?>
            </div>
        </div>
        <?php echo $OUTPUT->blocks('side-pre'); ?>
    </div> <!-- ./navbar lateral -->
<?php }; ?>
<div class="canvas <?php if($PAGE->cm->section) {echo "section-" . $PAGE->cm->section;}; if($PAGE->cm->sectionname) {echo "sectionname-" . $PAGE->cm->sectionname;}; ?>">
    <!-- Navbar superior -->
    <nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
        <div class="container-fluid">
            <?php if($hassidepre) { ?>
                <button type="button" class="navbar-toggle" data-toggle="offcanvas" data-recalc="false" data-target=".navmenu" data-canvas=".canvas">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <p class="navbar-brand hidden-sm hidden-xs"><?php echo $breadcrumb; ?></p>
            <?php } else { ?>
                <p class="navbar-brand"><?php echo '<a class="navbar-link" href="'. $courseurl . '">'. $PAGE->course->fullname .'</a>'; ?></p>
            <?php }; ?>
            <div class="navbar-right hidden-sm hidden-xs"><?php echo $OUTPUT->user_menu(); ?></div>
        </div>
    </nav> <!-- ./navbar superior -->

<!-- END OF HEADER -->


<!-- START CONTENT BODY -->
    <div class="container-fluid main-container" id="page-content">
        <?php echo $OUTPUT->main_content() ?>
        <?php if ($hassidetop) { ?>
        <div id="region-top" class="block-region col-md-12">
            <div class="region-content col-md-12">
                <?php echo $OUTPUT->blocks('side-top') ?>
            </div>
        </div>
        <?php } ?>
        <?php if (format_flexpage_has_next_or_previous()) { ?>
        <div class="flexpage_prev_next">
            <?php
            echo format_flexpage_previous_button();
            echo format_flexpage_next_button();
            ?>
        </div>
        <?php } ?>
        <div id="region-main-box"  class="col-md-12" >
            <div id="region-post-box"  class="col-md-12">

                <div id="region-main-wrap" class="col-md-6">
                    <div id="region-main" class="block-region">
                        <div class="region-content" >
                            <?php echo $OUTPUT->blocks('main') ?>
                        </div>
                    </div>
                </div>

                <?php if ($hassidepost OR (right_to_left() AND $hassidepre)) { ?>
                <div id="region-post" class="block-region col-md-6">
                    <div class="region-content">
                        <?php
                            echo $OUTPUT->blocks('side-post');
                         ?>
                    </div>
                </div>
                <?php } ?>

            </div>
        </div>
    </div>

</div>
<?php require('end_of_html.php'); ?>
<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>