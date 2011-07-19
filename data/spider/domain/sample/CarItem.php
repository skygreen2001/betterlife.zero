<?php
/**
 * 汽车配件
 *
 * @author zhouyuepu
 */
class CarItem extends DataObject {
    public $name;
    private $imgUrl;
    private $detailUrl;
    public  function setImgUrl($value) {
        $this->imgUrl=$value;
    }
    public function getImgUrl() {
        return $this->imgUrl;
    }
    public  function setDetailUrl($value) {
        $this->detailUrl=$value;
    }
    public function getDetailUrl() {
        return $this->detailUrl;
    }
    public function setName($name) {
        $this->name=$name;
    }
    public function getName() {
        return $this->name;
    }
}
?>
