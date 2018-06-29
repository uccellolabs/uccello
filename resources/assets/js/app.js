// Bootstrap
require('./bootstrap');

// Slimscroll
require('adminbsb-materialdesign/plugins/jquery-slimscroll/jquery.slimscroll.js');

// Admin BSB
require('adminbsb-materialdesign');

// Admin BSB Plugins
require('adminbsb-materialdesign/plugins/bootstrap-select/js/bootstrap-select.js');

// Node waves (for Admin BSB)
require('node-waves');

// Autosize
var autosize = require('adminbsb-materialdesign/plugins/autosize/autosize.js');
autosize($('textarea.auto-growth'));