<?php
// bit array - 使用メモリが1バイト(ファイルにも1バイトで保存できる。)
use chdemko\BitArray\BitArray;

class State {
    private $STATELIST = array(
        '通常', '死亡',  '毒', '眠り', '石化', '混乱', '麻痺'
    );

    public function getStateNum(string $input) {
        $num = array_search($input, $this->STATELIST);
        if ($num === false) {
            throw new Exception($input . " は定義されていないステータスです。");
            return;
        }
		return $num;
    }

	public function getCount() {
		return count($this->STATELIST);
	}

	public function getValue($index) {
		return $this->STATELIST[$index];
	}
}

class Player {
    private $state = 0;
    private $state_instance;

    public function __construct() {
        $this->state_instance = new State();
        $this->state = BitArray::fromDecimal($this->state_instance->getCount(), 0);
    } 

    public function setState($value)
    {
        $this->state->offsetSet($this->state_instance->getStateNum($value), true);
    }

    public function delState($value)
    {
        $this->state->offsetSet($this->state_instance->getStateNum($value), false);
    }

    public function output()
    {
        for ($i = 0; $i <= $this->state->size()-1; $i++) {
            if ($this->state[$i]){
                echo PHP_EOL . $this->state_instance->getValue($i);
            }
        }
    }
}

$p = new Player();

try {
    $p->setState("通常");
    $p->setState("石化");
    $p->setState("死亡");
    $p->delState("死亡");
    $p->setState("なし");
} catch (Exception $e) {
    echo $e->getMessage() . ": " . $e->getLine() . "行目" . PHP_EOL;
}

echo PHP_EOL . "現在のステータス----------------------------------" . PHP_EOL;
$p->output();
