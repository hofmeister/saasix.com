<?
session_set_cookie_params(60*60*24*300);
session_name('cloudix');
session_start();
require_once 'bootstrap.php';

UI::pageTitle('CloudIx');
UI::pageDescription('CloudIx gives you the much needed overview of available cloud applications. '
                    .'We welcome all contributions and hope you will join us in building a complete app index.');



$data = $ctrl->$methodName();
if (!is_string($data)) {
    $output = UI::render("$controllerName/$methodName", $data);
} else {
    $output = $data;
}

echo UI::render('layout/site',array(
                            'content'=>$output,
                            'title'=>UI::pageTitle(),
                            'description'=>UI::pageDescription()));
session_commit();