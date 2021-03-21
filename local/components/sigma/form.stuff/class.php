<?
use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
class StuffForm extends CBitrixComponent{

    private $_request;

    /**
     * Проверяет наличие модуля для работы с инфоблоками
     * @return bool
     * @throws Exception
     */
    private function _checkModules() {
        if (   !Loader::includeModule('iblock') )
        {
            throw new \Exception('Не загружен модуль для работы с инфоблоками');
        }
        return true;
    }

    /**
     * Подготовка параметров компонента (заготовка)
     * @param $arParams
     * @return mixed
     */
//    public function onPrepareComponentParams($arParams) {
//        return $arParams;
//    }

    /**
     * Получение свойств инфоблока
     * @param $arParams
     * @return array
     */
    public function getIblockProps($arParams) {
        $props = array();
        $res_props = CIBlock::GetProperties($arParams['IBLOCK_ID']);
        while($res_prop = $res_props->Fetch()){
            if($res_prop['PROPERTY_TYPE'] == 'L'){
                $listProps = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"),
                                                            Array("IBLOCK_ID"=>$arParams['IBLOCK_ID']));
                while($listProp = $listProps->GetNext())
                {
                    $res_prop['LIST_ITEMS'][] = $listProp;
                }
            }
            $props[] = $res_prop;
        }
        return $props;
    }


    /**
     * Обезопасить поля
     * @param string $str
     * @return string $str
     */

    static public function escape($str) {
        return htmlentities($str);
    }

    /**
     * Проверяет заполненность поля
     * @param string $str
     * @return mixed
     */
    static public function checkIfRequired($str) {
        $str = trim($str);
        if(!empty($str)){
            return $str;
        }else{
            return false;
        }
    }

    /**
     * Проверка заполненных полей
     * @param $props
     * @return mixed $message, $props
     */

    static public function checkProps($props){

        $message = '';

        foreach($props as $key=>&$prop){
            $prop = self::escape($prop);
            $tmp = self::checkIfRequired($prop);
            if($tmp){
                $prop = $tmp;
            }else{
                $message .= "Ошибка: Поле " .$key. " не заполнено. </br>";
            }
        }

        if(!empty($message)){
            return $message;
        }else{
            return $props;
        }
    }

    /**
     * Запись сотрудника в инфоблок
     * @param $props
     * @return string $message
     */

    public function addElement($props){
        $message = '';
        $element = new CIBlockElement;
        $elementName = $props['NAME'] ." ". $props['SURNAME'];

        GLOBAL $USER;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(),
            "IBLOCK_ID"      => $this->arParams['IBLOCK_ID'],
            "PROPERTY_VALUES"=> $props,
            "NAME"           => $elementName,
            "ACTIVE"         => "Y"
        );

        if($ELEMENT_ID = $element->Add($arLoadProductArray))
            $message = 'Сотрудник <br/><br/><b>' . $elementName . '</b> <br/><br/>добавлен';
        else
            $message = 'Ошибка: '  .$element->LAST_ERROR. '. Cотрудник ' . $elementName . ' не добавлен';

        return $message;

    }

    public function executeComponent() {
        $this->_checkModules();

        $this->_request = Application::getInstance()->getContext()->getRequest();
        print_r($this->_post);
        if(!empty($this->_request->getPostList()->toArray())){
            GLOBAL $APPLICATION;
            $APPLICATION->RestartBuffer();
            $props = self::checkProps($this->_request->getPostList()->toArray());
            if(is_string($props)){
                echo $props;
            }else{
                echo $this->addElement($props);
            }

        }else{
            $this->arResult['IBLOCK_ID'] = $this->arParams['IBLOCK_ID'];
            $this->arResult['PROPS'] = $this->getIblockProps($this->arParams);
            $this->IncludeComponentTemplate();
        }

    }
}?>
