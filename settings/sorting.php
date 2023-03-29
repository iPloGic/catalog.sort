<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_js.php');

use \Bitrix\Main\Web\Json;

if (!check_bitrix_sessid()) {
	return;
}

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

__IncludeLang($request->get('component_path').'/lang/'.LANGUAGE_ID.'/.parameters.php');

function typeField($key, $val = "") {
	$result = "
<select id='SORTING_parameter_type_".$key."' class='ipl-catalog-sort-type' data-group='".$key."' size='1'>
	<option value='DEFAULT'".($val=="DEFAULT" ? " selected" : "").">".GetMessage("PARAMETER_SORTING_DEFAULT")."</option>
	<option value='POPULARITY'".($val=="POPULARITY" ? " selected" : "").">".GetMessage("PARAMETER_SORTING_POPULARITY")."</option>
	<option value='DATE'".($val=="DATE" ? " selected" : "").">".GetMessage("PARAMETER_SORTING_DATE")."</option>
	<option value='NAME_ASC'".($val=="NAME_ASC" ? " selected" : "").">".GetMessage("PARAMETER_SORTING_NAME_ASC")."</option>
	<option value='NAME_DESC'".($val=="NAME_DESC" ? " selected" : "").">".GetMessage("PARAMETER_SORTING_NAME_DESC")."</option>
	<option value='PRICE_ASC'".($val=="PRICE_ASC" ? " selected" : "").">".GetMessage("PARAMETER_SORTING_PRICE_ASC")."</option>
	<option value='PRICE_DESC'".($val=="PRICE_DESC" ? " selected" : "").">".GetMessage("PARAMETER_SORTING_PRICE_DESC")."</option>
	<option value='CUSTOM'".($val=="CUSTOM" ? " selected" : "").">".GetMessage("PARAMETER_SORTING_CUSTOM")."</option>
</select>
	";
	return $result;
}

function buildForm($arFields) {
	$result = '';
	if(is_array($arFields) && count($arFields)) {
		foreach($arFields as $key => $arField) {
			$result .= '<div class="ipl-catalog-sort-group" style="margin-bottom: 25px;">';
			$result .= GetMessage("PARAMETER_SORTING_TYPE") . '<br>';
			$result .= typeField($key, $arField["TYPE"]) . '<br>';
			$result .= GetMessage("PARAMETER_SORTING_NAME") . '<br>';
			$result .= '<input type="text" size="20" class="ipl-catalog-sort-field" data-name="NAME" data-group="'.$key.'" id="SORTING_parameter_name_'.$key.'" value="'.$arField["NAME"].'"><br>';
			if($arField["TYPE"] == "CUSTOM") {
				$result .= GetMessage("PARAMETER_SORTING_FIELD") . '<br>';
				$result .= '<input type="text" size="20" class="ipl-catalog-sort-field" data-name="FIELD" data-group="'.$key.'"  id="SORTING_parameter_field_'.$key.'" value="'.$arField["FIELD"].'"><br>';
				$result .= GetMessage("PARAMETER_SORTING_DIR") . '<br>';
				$result .= "
<select id='SORTING_parameter_dir_".$key."' size='1' class='ipl-catalog-sort-field' data-name='DIR' data-group='".$key."'>
	<option value='ASC'".($arField["FIELD"]=="ASC" ? " selected" : "").">".GetMessage("PARAMETER_SORTING_ASC")."</option>
	<option value='DESC'".($arField["FIELD"]=="DESC" ? " selected" : "").">".GetMessage("PARAMETER_SORTING_DESC")."</option>
</select><br>
				";
			}
			$result .= '<a href="javascript:void(0);" class="ipl-catalog-sort-delete-group" data-group="'.$key.'">'.GetMessage("PARAMETER_SORTING_DELETE").'</a>';
			$result .= '</div>';
		}
	}
	$result .= '<a href="javascript:void(0);" class="ipl-catalog-sort-add-group">'.GetMessage("PARAMETER_SORTING_ADD").'</a>';
	return $result;
}

$response = [];
$count = 1;

if($request->get('fields') == "") {
	$arFields = [];
}
else {
	try {
		$arFields = Json::decode($request->get('fields'));
	} catch (Exception $e) {
		$arFields = [];
	}

}

switch($request->get('action')) {
	case "show":
		if(!count($arFields)) {
			$arFields = [
				0 => [
					"TYPE" => 'DEFAULT',
					"NAME" => GetMessage("PARAMETER_SORTING_DEFAULT")
				]
			];
		}
		break;
	case "change_type":
		$arFields[$request->get('group')] = [
			"TYPE" => $request->get('type'),
			"NAME" => GetMessage("PARAMETER_SORTING_" . $request->get('type')),
		];
		if($request->get('type') == "CUSTOM") {
			$arFields[$request->get('group')]["FIELD"] = "";
			$arFields[$request->get('group')]["DIR"] = "ASC";
		}
		break;
	case "add":
		$arFields[] = [
			"TYPE" => 'DEFAULT',
			"NAME" => GetMessage("PARAMETER_SORTING_DEFAULT")
		];
		break;
	case "delete":
		unset($arFields[$request->get('group')]);
		$arFields = array_values($arFields);
		break;
}


$response['html'] = buildForm($arFields);
$response['count'] = count($arFields);
$response['fields'] = $arFields;

echo Json::encode($response);








