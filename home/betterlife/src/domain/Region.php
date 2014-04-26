<?php
/**
 +---------------------------------------<br/>
 * 地区<br/>
 +---------------------------------------
 * @category betterlife
 * @package 
 * @author skygreen skygreen2001@gmail.com
 */
class Region extends DataObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 标识
     * @var string
     * @access public
     */
    public $ID;
    /**
     * 父地区标识
     * @var string
     * @access public
     */
    public $Parent_ID;
    /**
     * 地区名称
     * @var string
     * @access public
     */
    public $Region_Name;
    /**
     * 地区类型<br/>
     * 0:国家-country<br/>
     * 1:省-province<br/>
     * 2:市-city<br/>
     * 3:区-region<br/>
     * 
     * @var enum
     * @access public
     */
    public $Region_Type;
    //</editor-fold>

    /** 
     * 显示地区类型<br/>
     * 0:国家-country<br/>
     * 1:省-province<br/>
     * 2:市-city<br/>
     * 3:区-region<br/>
     * <br/>
     */
    public function getRegion_TypeShow()
    {
        return self::Region_TypeShow($this->Region_Type);
    }

    /** 
     * 显示地区类型<br/>
     * 0:国家-country<br/>
     * 1:省-province<br/>
     * 2:市-city<br/>
     * 3:区-region<br/>
     * <br/>
     */
    public static function Region_TypeShow($Region_Type)
    {
        return EnumRegionType::Region_TypeShow($Region_Type);
    }

}
?>