<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("IPL_CSORT_COMPONENT_NAME"),
	"DESCRIPTION" => GetMessage("IPL_CSORT_COMPONENT_DESCRIPTION"),
	"ICON" => "/images/cat_detail.gif",
	"CACHE_PATH" => "Y",
	"PATH" => array (
		"ID" => "iplogic",
		"NAME" => "iPloGic",
		'SORT'	=> 10,
	),
);

?>