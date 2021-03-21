<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
    $props = $arResult['PROPS'];
?>

<form id="stuff_form" >
    <div class="stuff_form_error"></div>
    <fieldset>
        <legend>Данные сотрудника</legend>

            <?foreach($props as $prop){
                $type='';
                switch($prop['PROPERTY_TYPE']) {
                    case 'S':
                        $type = 'text';
                        break;
                    case 'N':
                        $type = 'number';
                        break;
                    case 'L' :
                        $type = 'list';
                        break;
                }

                if($type != 'list'){?>
                <div class="form-row">
                    <label><?= $prop['NAME']?>
                        <input type="<?= $type ?>" name="<?= $prop['CODE'] ?>" required/>
                    </label>
                </div>
                <?}else{?>
                <div class="form-row">
                    <label><?= $prop['NAME']?>

                        <select name="<?= $prop['CODE']?>" size="1">

                            <?foreach($prop['LIST_ITEMS'] as $item){?>
                                <option value="<?=$item['ID']?>"><?=$item['VALUE']?></option>
                            <?}?>
                        </select>
                    </label>
                </div>
                <?}
                }?>
    </fieldset>
    <div class="form-row btns">
        <input class="form_btn" type="submit" value="Отправить" />
        <input class="form_btn" type="reset" value="Сбросить" />
    </div>
</form>




    <div class="popup stuff_form_popup"></div>
