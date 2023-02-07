<?php

class NaiveBayes {
    protected $data = [];
    protected $attr = [];
    protected $prob = [];

    public function __construct(array $data, array $attr)
    {
        $this->data = $data;
        $this->attr = $attr;
    }

    protected function getTargetValues()
    {
        $targetValues = [];
        foreach ($this->data as $item){
            $targetValues[] = $item[count($this->attr)];
        }        
        return $targetValues;
    }

    public function getLabelClass()
    {
        return array_unique($this->getTargetValues());
    }

    protected function _hitung()
    {
        $statClass = array_count_values($this->getTargetValues());
        $probClass = [];
        foreach($statClass as $class => $stat){
            $probClass[$class]['prob'] = $stat / count($this->data);
        }

        foreach ($this->attr as $idxAttr => $attrib){
            $classAttr = [];
            foreach ($this->getLabelClass() as $labelClass){
                $p = $this->getDataByAttrAndClassLabel($idxAttr, $labelClass);
                // echo json_encode($p);
                $statCaseByAttr = array_count_values($p);                
                foreach ($statCaseByAttr as $cases => $val){
                    $ratio = $val / count($p);                    
                    $probClass[$labelClass][$attrib][$cases] = $ratio;
                }
            }
        }
        $this->prob = $probClass;        
    }

    protected function getDataByAttrAndClassLabel(int $idxAttr, string $labelClass)
    {
        $data = [];

        foreach ($this->data as $item){
            if ($item[count($this->attr)] == $labelClass){
                $data[] = $item[$idxAttr];
            }
        }
        return $data;
    }

    public function run()
    {
        $this->_hitung();
        return $this;
    }

    public function predict(array $data)
    {
        $prediction = [];
        // echo json_encode($data) .'<br>';
        foreach ($this->getLabelClass() as $labelClass) {            
            echo $labelClass;
            $probabilistik = $this->prob[$labelClass]['prob'];
            foreach ($data as $idxAttr => $av){
                $probabilistik = $probabilistik * @$this->prob[$labelClass][$this->attr[$idxAttr]][$av];
            }
            $prediction[$labelClass] = $probabilistik;
        }
        arsort($prediction);       

        // echo json_encode($data) .'<br>';
        // print_r($prediction);
        return $prediction;
    }
}
?>