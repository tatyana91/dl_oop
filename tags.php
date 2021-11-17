<?php
class Tag {
    protected $name;
    protected $attrs;

    public function __construct($name){
        $this->name = $name;
        $this->attrs = array();
        return $this;
    }

    public function attr($name, $value){
        $this->attrs[$name] = $value;
        return $this;
    }

    protected function renderAttrs(){
        return implode(' ', array_map(function ($name, $value){
            return " $name=\"$value\" ";
        }, array_keys($this->attrs), array_values($this->attrs)));
    }

    public function render(){
        return "";
    }
}

class SingleTag extends Tag {
    public function render(){
        return "<{$this->name} {$this->renderAttrs()}>";
    }
}

class PairTag extends Tag {
    private $children = array();

    public function appendChild($child){
        array_push($this->children, $child);
        return $this;
    }

    private function renderChildren(){
        return implode('', array_map(function (Tag $child){
            return $child->render();
        }, $this->children));
    }

    public function render(){
        $res = "<{$this->name} {$this->renderAttrs()}>";
        $res .= $this->renderChildren();
        $res .= "</$this->name>";
        return $res;
    }
}

function forTest() {
    $img = (new SingleTag('img'))
        ->attr('src', 'f1.jpg')
        ->attr('alt', 'f1 not found');
    $input = (new SingleTag('input'))
        ->attr('type', 'text')
        ->attr('name', 'f1');
    $label1 = (new PairTag('label'))
        ->appendChild($img)
        ->appendChild($input);

    $img = (new SingleTag('img'))
        ->attr('src', 'f2.jpg')
        ->attr('alt', 'f2 not found');
    $input = (new SingleTag('input'))
        ->attr('type', 'password')
        ->attr('name', 'f2');
    $label2 = (new PairTag('label'))
        ->appendChild($img)
        ->appendChild($input);

    return (new PairTag('form'))
        ->appendChild($label1)
        ->appendChild($label2);
}

echo forTest()->render();