<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

use \Bitrix\Main\Localization\Loc;

?>
<form name="elements_sort_form" method="get" action="<?=$arResult['REQUEST_URI'];?>">
<?

if ( count($arResult['GET']) ) {
	foreach($arResult['GET'] as $key => $value) {
		echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
	}
}
echo '<input type="hidden" name="sortby" value="'.$GLOBALS['IPL_SORT']["IPL_SORT_" . $arParams["SUFFIX"] . "_SORT"].'">';
echo '<input type="hidden" name="sortorder" value="'.$GLOBALS['IPL_SORT']["IPL_SORT_" . $arParams["SUFFIX"] . "_ORDER"].'">';
?>
	<?=Loc::getMessage('IPL_CS_SORT')?>:&nbsp;
	<select class="catalog_sort_select"> <?
		foreach($arResult["FIELDS"] as $key => $arItem) {?>
			<option value="<?=$key?>"<? if($arItem["SELECTED"] == 'Y'){?> selected="selected"<?}?>><?=$arItem["NAME"]?></option>
		<?}?>
	</select>
</form><?
$arJsParams = [
	"fields" => $arResult["FIELDS"],
	"componentPath" => $componentPath,
];
?>
<script type="text/javascript">
	let obJCCatalogSortingComponent = new JCCatalogSortingComponent(<?=CUtil::PhpToJSObject($arJsParams)?>);
</script>