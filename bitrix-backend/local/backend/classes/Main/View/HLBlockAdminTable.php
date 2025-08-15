<?php

namespace Main\View;

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Entity\Query;
use CAdminFilter;
use CAdminList;
use CAdminResult;
use CAdminSorting;
use CDBResult;
use Main\DI\Containerable;
use function TG\Main\View\GetMessage;
use function TG\Main\View\htmlspecialcharsbx;
use function TG\Main\View\IncludeModuleLangFile;
use function TG\Main\View\ShowFilterLogicHelp;
use const TG\Main\View\LANG;
use const TG\Main\View\LANGUAGE_ID;

class AdminTable
{
    use Containerable;

    public $entityInfo;
    public $entityId;
    public $queryParams;
    public $filter = [];
    public $menu = [];

    public function __construct($entityUid, $params = [])
    {
        $this->entityInfo = $this->container->getHlBlockService()->getEntityInfo($entityUid);
        $this->entityId = $this->entityInfo['ID'];
        $this->filter = $params['filter'] ?: [];
        $this->queryParams = $params['queryParams'] ?: [];
        $this->menu = $params['menu'] ?: [];
    }

    function output()
    {
        global $APPLICATION, $USER, $USER_FIELD_MANAGER;

        IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/highloadblock/admin/highloadblock_row_edit.php");
        IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/highloadblock/admin/highloadblock_row_list.php");

        $GLOBALS["lang"] = 'ru';

        $ENTITY_ID = $this->entityId;
        $hblockName = '';
        $hlblock = null;
        $queryString = $this->getQueryString();

        if ($ENTITY_ID > 0) {
            $hlblock = HL\HighloadBlockTable::getById($ENTITY_ID)->fetch();

            if (!empty($hlblock)) {
                //localization
                $lng = HL\HighloadBlockLangTable::getList(array(
                        'filter' => array('ID' => $hlblock['ID'], '=LID' => LANG))
                )->fetch();
                if ($lng) {
                    $hblockName = $lng['NAME'];
                } else {
                    $hblockName = $hlblock['NAME'];
                }
                //check rights
                if ($USER->isAdmin()) {
                    $canEdit = $canDelete = true;
                } else {
                    $operations = HL\HighloadBlockRightsTable::getOperationsName($ENTITY_ID);
                    if (empty($operations)) {
                        $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
                    } else {
                        $canEdit = in_array('hl_element_write', $operations);
                        $canDelete = in_array('hl_element_delete', $operations);
                    }
                }
            }
        }

        if (empty($hlblock)) {
            die;
        }

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);

        /** @var HL\DataManager $entity_data_class */
        $entity_data_class = $entity->getDataClass();
        $entity_table_name = $hlblock['TABLE_NAME'];

        $sTableID = 'tbl_embed_' . $entity_table_name;
        $oSort = new CAdminSorting($sTableID, "ID", "asc");
        $lAdmin = new CAdminList($sTableID, $oSort);

        $arHeaders = array(array(
            'id' => 'ID',
            'content' => 'ID',
            'sort' => 'ID',
            'default' => true
        ));

        $ufEntityId = 'HLBLOCK_' . $hlblock['ID'];

        $USER_FIELD_MANAGER->AdminListAddHeaders($ufEntityId, $arHeaders);


        // show all columns by default
        foreach ($arHeaders as &$arHeader) {
            $arHeader['default'] = true;
        }
        unset($arHeader);

        $lAdmin->AddHeaders($arHeaders);

        if (!in_array($by, $lAdmin->GetVisibleHeaderColumns(), true)) {
            $by = 'ID';
        }

        // add filter
        $filter = null;

        $filterFields = array('find_id');
        $filterValues = array();
        $filterTitles = array('ID');

        $USER_FIELD_MANAGER->AdminListAddFilterFields($ufEntityId, $filterFields);

        $filter = $lAdmin->InitFilter($filterFields);

        if (!empty($_REQUEST['find_id'])) {
            $filterValues['ID'] = $_REQUEST['find_id'];
        }

        $filterValues = $this->filter + $filterValues;

        $USER_FIELD_MANAGER->AdminListAddFilter($ufEntityId, $filterValues);
        $USER_FIELD_MANAGER->AddFindFields($ufEntityId, $filterTitles);

        $filter = new CAdminFilter(
            $sTableID . "_filter_id",
            $filterTitles
        );


        // group actions
        if ($lAdmin->EditAction() && $canEdit) {
            foreach ($FIELDS as $ID => $arFields) {
                $ID = (int)$ID;
                if ($ID <= 0)
                    continue;

                if (!$lAdmin->IsUpdated($ID))
                    continue;

                $entity_data_class::update($ID, $arFields);
            }
        }

        if ($arID = $lAdmin->GroupAction()) {
            if ($_REQUEST['action_target'] == 'selected') {
                $arID = array();

                $rsData = $entity_data_class::getList(array(
                    "select" => array('ID'),
                    "filter" => $filterValues
                ));

                while ($arRes = $rsData->Fetch())
                    $arID[] = $arRes['ID'];
            }

            foreach ($arID as $ID) {
                $ID = (int)$ID;

                if (!$ID) {
                    continue;
                }

                switch ($_REQUEST['action']) {
                    case "delete":
                        if ($canDelete) {
                            $entity_data_class::delete($ID);
                        }
                        break;
                }
            }
        }

        $arr = $canDelete ? array('delete' => true) : array();

        $lAdmin->AddGroupActionTable($arr);

        // select data
        /** @var string $order */
        $order = strtoupper($_REQUEST['order'] ?: 'DESC');

        $usePageNavigation = true;
        if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'excel') {
            $usePageNavigation = false;
        } else {
            $navyParams = CDBResult::GetNavParams(CAdminResult::GetNavSize(
                $sTableID,
                array('nPageSize' => 20, 'sNavID' => $APPLICATION->GetCurPage() . '?ENTITY_ID=' . $ENTITY_ID)
            ));
            if ($navyParams['SHOW_ALL']) {
                $usePageNavigation = false;
            } else {
                $navyParams['PAGEN'] = (int)$navyParams['PAGEN'];
                $navyParams['SIZEN'] = (int)$navyParams['SIZEN'];
            }
        }
        $selectFields = $lAdmin->GetVisibleHeaderColumns();
        if (!in_array('ID', $selectFields))
            $selectFields[] = 'ID';
        $getListParams = array(
            'select' => $selectFields,
            'filter' => $filterValues,
            'order' => array($by => $order)
        );
        unset($filterValues, $selectFields);
        if ($usePageNavigation) {
            $getListParams['limit'] = $navyParams['SIZEN'];
            $getListParams['offset'] = $navyParams['SIZEN'] * ($navyParams['PAGEN'] - 1);
        }

        if ($usePageNavigation) {
            $countQuery = new Query($entity_data_class::getEntity());
            $countQuery->addSelect(new ExpressionField('CNT', 'COUNT(1)'));
            $countQuery->setFilter($getListParams['filter']);
            $totalCount = $countQuery->setLimit(null)->setOffset(null)->exec()->fetch();
            unset($countQuery);
            $totalCount = (int)$totalCount['CNT'];
            if ($totalCount > 0) {
                $totalPages = ceil($totalCount / $navyParams['SIZEN']);
                if ($navyParams['PAGEN'] > $totalPages)
                    $navyParams['PAGEN'] = $totalPages;
                $getListParams['limit'] = $navyParams['SIZEN'];
                $getListParams['offset'] = $navyParams['SIZEN'] * ($navyParams['PAGEN'] - 1);
            } else {
                $navyParams['PAGEN'] = 1;
                $getListParams['limit'] = $navyParams['SIZEN'];
                $getListParams['offset'] = 0;
            }
        }
        $rsData = new CAdminResult($entity_data_class::getList($getListParams), $sTableID);
        if ($usePageNavigation) {
            $rsData->NavStart($getListParams['limit'], $navyParams['SHOW_ALL'], $navyParams['PAGEN']);
            $rsData->NavRecordCount = $totalCount;
            $rsData->NavPageCount = $totalPages;
            $rsData->NavPageNomer = $navyParams['PAGEN'];
        } else {
            $rsData->NavStart();
        }


        // build list
        $lAdmin->NavText($rsData->GetNavPrint(GetMessage("PAGES")));
        while ($arRes = $rsData->NavNext(true, "f_")) {
            $f_ID = $arRes['ID'];

            $row = $lAdmin->AddRow($f_ID, $arRes);

            $row->AddViewField('ID', '<a href="' . 'highloadblock_row_edit.php?ENTITY_ID=' . $hlblock['ID'] . '&ID=' . $f_ID . '&lang=' . LANGUAGE_ID . '">' . $f_ID . '</a>');

            if ($canEdit) {
                $USER_FIELD_MANAGER->AddUserFields('HLBLOCK_' . $hlblock['ID'], $arRes, $row);
            }

            $arActions = array();

            $arActions[] = array(
                'ICON' => 'edit',
                'TEXT' => GetMessage($canEdit ? 'MAIN_ADMIN_MENU_EDIT' : 'MAIN_ADMIN_MENU_VIEW'),
                'ACTION' => $lAdmin->ActionRedirect('highloadblock_row_edit.php?' . $queryString . '&ID=' . $f_ID . '&lang=' . LANGUAGE_ID),
                'DEFAULT' => true
            );

            if ($canEdit) {
                $arActions[] = array(
                    'ICON' => 'copy',
                    'TEXT' => GetMessage('MAIN_ADMIN_MENU_COPY'),
                    'ACTION' => $lAdmin->ActionRedirect('highloadblock_row_edit.php?' . $queryString . '&ID=' . $f_ID . '&lang=' . LANGUAGE_ID . '&action=copy')
                );
            }


            $row->AddActions($arActions);
        }


        // view
        $menu = $this->menu;
        if ($canEdit && false) {
            $menu[] = array(
                'TEXT' => 'Добавить',
                'TITLE' => 'Добавить',
                'LINK' => 'highloadblock_row_edit.php?ENTITY_ID=' . $ENTITY_ID . '&amp;lang=' . LANGUAGE_ID,
                'ICON' => 'btn_new'
            );
        }

        $lAdmin->AddAdminContextMenu($menu);

        if ($_REQUEST["mode"] == 'list' || $_REQUEST["mode"] == 'frame') {
            ob_clean();
        }

        $lAdmin->CheckListMode();

        //require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

        ?>
        <form name="find_form" method="GET" action="<? echo $APPLICATION->GetCurPage() ?>?<?= $queryString ?>">
            <? $filter->Begin(); ?>
            <tr>
                <td>ID</td>
                <td>
                    <input
                            type="text"
                            name="find_id"
                            size="47"
                            value="<? echo htmlspecialcharsbx($_REQUEST['find_id']) ?>"
                    >
                    <?= ShowFilterLogicHelp() ?>
                </td>
            </tr>
            <?
            $USER_FIELD_MANAGER->AdminListShowFilter($ufEntityId);
            $filter->Buttons(array(
                "table_id" => $sTableID,
                "url" => $APPLICATION->GetCurPage() . '?' . $queryString,
                "form" => "find_form"
            ));
            $filter->End();
            ?>
        </form>
        <?

        $lAdmin->DisplayList();
    }

    function getQueryString()
    {
        return http_build_query($this->getQueryParams());
    }

    function getQueryParams()
    {
        return [
                'ENTITY_ID' => $this->entityId,
            ] + $this->queryParams;
    }
}

