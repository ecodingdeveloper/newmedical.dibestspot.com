<?php
$this->load->view('web/includes/header');/** @phpstan-ignore-line */
$this->load->view('web/includes/sidebar');/** @phpstan-ignore-line */
$this->load->view($theme . '/modules/' . $module . '/' . $page);/** @phpstan-ignore-line */
$this->load->view('web/includes/footer');/** @phpstan-ignore-line */