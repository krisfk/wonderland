<?php

//  Define a list of config variables for the project.
$config                     = new stdClass();

$config->plugin_title       = 'fiberconnect_gateway';
$config->project_title      = 'fiberconnect-payment-woocommerce';
$config->debug_logging      = true;

//  A list of variables for SonarQube code check.
$str                = new stdClass();

$str->title         = 'title';
$str->description   = 'description';
$str->desc_tip      = 'desc_tip';
$str->default		= 'default';

$str->ignore		= 'ignore_errors';
$str->header		= 'header';
$str->method		= 'method';
$str->api_key		= 'api_key';
$str->x_oah_key     = 'X-OpenAPIHub-Key:';

$str->pending       = 'pending';
$str->gateway_url   = 'gw_url'

?>