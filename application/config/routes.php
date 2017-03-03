<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

#$route['default_controller'] = "statements";
#$route['(:any)'] = 'statements/$1';
/*$route['elenco/(:any)'] = 'elenco/index/$1';
$route['comune/(:any)'] = 'statements/comune/$1';
$route['lists/(:any)'] = 'statements/lists/$1';
$route['nuova'] = 'statements/nuova';
$route['lists'] = 'statements/lists';
$route['default_controller'] = "statements";
$route['404_override'] = '';*/


$route['default_controller'] = 'domanda';
$route['comune/([^/]+)'] = 'domanda/comune/$1';

$route['admin/mailtemplate/([^/]+)/([^/]+)/([^/]+)'] = 'admin/mailtemplate/$1/$2/$3';

$route['report_familiari/([^/]+)'] = 'domanda/report_familiari/$1';
#$route['(:any)'] = 'pages/view/$1';


/* End of file routes.php */
/* Location: ./application/config/routes.php */
