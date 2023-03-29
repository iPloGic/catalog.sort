<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\Json;

	$arComponentParameters = [
		"GROUPS" => [
			"SORT_FIELDS" => [ "NAME" => Loc::getMessage("GROUP_SORT_FIELDS"), "SORT" => 150],
		],
		"PARAMETERS" => [

			"SAVE_IN"  => [
				"PARENT" => "BASE",
				"NAME" => Loc::getMessage("PARAMETER_SAVE_IN"),
				"TYPE" => "LIST",
				"VALUES" => [
					"C" => Loc::getMessage("PARAMETER_SAVE_IN_COOKIE"),
					"S" => Loc::getMessage("PARAMETER_SAVE_IN_SESSION")
				],
				"DEFAULT" => "C",
				"ADDITIONAL_VALUES" => "N",
				"REFRESH" => "Y"
			],

			"SUFFIX"  => [
				"PARENT" => "BASE",
				"NAME" => Loc::getMessage("PARAMETER_COOKIE_SUFFIX"),
				"TYPE" => "STRING",
				"DEFAULT" => 'jp6Yh5F48',
			],

			"ELEMENT_SORT_FIELD"  => [
				"PARENT" => "BASE",
				"NAME" => Loc::getMessage("PARAMETER_COOKIE_ELEMENT_SORT_FIELD"),
				"TYPE" => "STRING",
				"DEFAULT" => 'SORT',
			],

			"ELEMENT_SORT_ORDER"  => [
				"PARENT" => "BASE",
				"NAME" => Loc::getMessage("PARAMETER_ELEMENT_SORT_ORDER"),
				"TYPE" => "LIST",
				"VALUES" => [
					"ASC" => Loc::getMessage("PARAMETER_SORTING_ASC"),
					"DESC" => Loc::getMessage("PARAMETER_SORTING_DESC")
				],
				"DEFAULT" => "DESC",
				"ADDITIONAL_VALUES" => "N",
				"REFRESH" => "N"
			],

			"SORTING"  => [
				"PARENT" => "SORT_FIELDS",
				"NAME" => Loc::getMessage("PARAMETER_SORTING"),
				'TYPE' => 'CUSTOM',
				'JS_FILE' => str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__) . '/settings/sorting.js',
				'JS_EVENT' => 'initListSortingControl',
				'JS_MESSAGES' => Json::encode([]),
				'JS_DATA' => Json::encode([
					"componentPath" => __DIR__
				]),
				'DEFAULT' => Loc::getMessage("PARAMETER_SORTING_DEFAULT")
			],

			"CACHE_TIME" => [
				"DEFAULT" => 36000,
				"PARENT" => "CACHE_SETTINGS",
			],
			"CACHE_TYPE"  => [
				"PARENT" => "CACHE_SETTINGS",
				"NAME" => Loc::getMessage("COMP_PROP_CACHE_TYPE"),
				"TYPE" => "LIST",
				"VALUES" => [
					"A" => Loc::getMessage("COMP_PROP_CACHE_TYPE_AUTO")." ".Loc::getMessage("COMP_PARAM_CACHE_MAN"), 
					"Y" => Loc::getMessage("COMP_PROP_CACHE_TYPE_YES"), 
					"N" => Loc::getMessage("COMP_PROP_CACHE_TYPE_NO")],
				"DEFAULT" => "N",
				"ADDITIONAL_VALUES" => "N",
				"REFRESH" => "Y"
			],
		],
	];

	if ( $arCurrentValues["SAVE_IN"] == "C" ) {
		$arComponentParameters["PARAMETERS"]["COOKIE_EXPIRES"]  = [
			"PARENT" => "BASE",
			"NAME" => Loc::getMessage("PARAMETER_COOKIE_EXPIRES"),
			"TYPE" => "STRING",
			"DEFAULT" => '7',
		];
	}


?>
