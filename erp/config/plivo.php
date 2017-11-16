<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Config for the Plivo library
 */
 
 $this->_ci = & get_instance();
 $this->_ci->load->model('settings_model');
$sms_setting = $this->_ci->settings_model->getSettings();
 
$config['AUTH_ID'] = $sms_setting->sms_auth_id;

$config['AUTH_TOKEN'] = $sms_setting->sms_auth_taken;

$config['API_VERSION'] = 'v1';

$config['END_POINT'] = 'https://api.plivo.com';
