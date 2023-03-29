<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Web\Json;

global $APPLICATION;
global $IPL_SORT;


class iplogicCatalogSort extends CBitrixComponent
{
	protected $session;
	protected $request;

	function __construct($component = null) {
		parent::__construct($component);
		$this->session = \Bitrix\Main\Application::getInstance()->getSession();
		$this->request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
	}

	function onPrepareComponentParams($arParams) {
		if($arParams["SAVE_IN"] != "C" && $arParams["SAVE_IN"] != "S") {
			$arParams["SAVE_IN"] = "S";
		}
		return $arParams;
	}

	function executeComponent() {

		global $APPLICATION;
		global $IPL_SORT;

		$sorting = htmlspecialchars_decode(stripslashes($this->arParams["SORTING"]));
		try {
			$this->arResult['FIELDS'] = Json::decode($sorting);
		} catch (Exception $e) {
			$this->arResult['FIELDS'] = [];
		}
		$this->arResult["PRE_INSTALLED"] = [
			"DEFAULT" => [
				"SORT" => "SORT",
				"ORDER" => "DESC"
			],
			"POPULARITY" => [
				"SORT" => "shows",
				"ORDER" => "DESC"
			],
			"DATE" => [
				"SORT" => "active_from",
				"ORDER" => "DESC"
			],
			"NAME_ASC" => [
				"SORT" => "NAME",
				"ORDER" => "ASC"
			],
			"NAME_DESC" => [
				"SORT" => "NAME",
				"ORDER" => "DESC"
			],
			"PRICE_ASC" => [
				"SORT" => "CATALOG_PRICE_SCALE_1",
				"ORDER" => "ASC"
			],
			"PRICE_DESC" => [
				"SORT" => "CATALOG_PRICE_SCALE_1",
				"ORDER" => "DESC"
			],
		];

		$name = "IPL_SORT_" . $this->arParams["SUFFIX"];

		$order = "DESC";

		if ($this->request->get('sortby')) {
			if ($this->request->get('sortorder')) {
				$order = $this->request->get('sortorder');
			}
			if($this->arParams["SAVE_IN"] == "C") {
				setcookie($name . "_SORT", $this->request->get('sortby'), time() + (86400 * $this->arParams["COOKIE_EXPIRES"]));
				setcookie($name . "_ORDER", $order, time() + (86400 * $this->arParams["COOKIE_EXPIRES"]));
			}
			else {
				$this->session->set($name . "_SORT", $this->request->get('sortby'));
				$this->session->set($name . "_ORDER", $order);
			}
			$sort = $this->request->get('sortby');
		}
		else {
			if($this->arParams["SAVE_IN"] == "C") {
				if (!isset($_COOKIE[$name . "_SORT"])) {
					setcookie($name . "_SORT", $this->arParams["ELEMENT_SORT_FIELD"], time() + (86400 * $this->arParams["COOKIE_EXPIRES"]));
					$sort = $this->arParams["ELEMENT_SORT_FIELD"];
				}
				else {
					$sort = $_COOKIE[$name . "_SORT"];
				}
				if (!isset($_COOKIE[$name . "_ORDER"])) {
					setcookie($name . "_ORDER", $this->arParams["ELEMENT_SORT_ORDER"], time() + (86400 * $this->arParams["COOKIE_EXPIRES"]));
					$order = $this->arParams["ELEMENT_SORT_ORDER"];
				}
				else {
					$order = $_COOKIE[$name . "_ORDER"];
				}
			}
			else {
				if (!$this->session->has($name . "_SORT")) {
					$this->session->set($name . "_SORT", $this->arParams["ELEMENT_SORT_FIELD"]);
					$sort = $this->arParams["ELEMENT_SORT_FIELD"];
				}
				else {
					$sort = $this->session->get($name . "_SORT");
				}
				if (!$this->session->has($name . "_ORDER")) {
					$this->session->set($name . "_ORDER", $this->arParams["ELEMENT_SORT_ORDER"]);
					$order = $this->arParams["ELEMENT_SORT_ORDER"];
				}
				else {
					$order = $this->session->get($name . "_ORDER");
				}
			}
		}

		$IPL_SORT[$name . "_SORT"] = $sort;
		$IPL_SORT[$name . "_ORDER"] = $order;

		foreach($this->arResult['FIELDS'] as $key => $arItem) {
			if($arItem['TYPE'] != "CUSTOM") {
				$arItem['FIELD'] = $this->arResult["PRE_INSTALLED"][$arItem['TYPE']]["SORT"];
				$arItem['DIR'] = $this->arResult["PRE_INSTALLED"][$arItem['TYPE']]["ORDER"];
			}
			if($arItem['FIELD'] == $sort && $arItem['DIR'] == $order) {
				$arItem['SELECTED'] = "Y";
			}
			else {
				$arItem['SELECTED'] = "N";
			}
			$this->arResult['FIELDS'][$key] = $arItem;
		}


		$uri = explode("?",$APPLICATION->GetCurPageParam("", ["sortby", "sortorder"], false));
		$this->arResult['REQUEST_URI'] = $uri[0];
		$this->arResult['GET'] = [];
		$arGetPairs = explode("&",$uri[1]);
		foreach($arGetPairs as $key => $var) {
			$pair = explode("=",$var);
			$this->arResult['GET'][$pair[0]] = $pair[1];
		}

		$this->includeComponentTemplate();
	}

}