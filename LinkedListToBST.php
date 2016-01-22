<?php

class Node
{
    private $previous;
    private $next;
    private $value;

    public function __construct($value, $previous = null, $next = null)
    {
        $this->value = $value;
        $this->previous = $previous;
        $this->next = $next;
    }

    public function value()
    {
        return $this->value;
    }

    public function previous()
    {
        return $this->previous;
    }

    public function next()
    {
        return $this->next;
    }

    public function valueIs($value)
    {
        $this->value = $value;
    }

    public function hasNext()
    {
        return (!is_null($this->next()));
    }

    public function hasPrevious()
    {
        return (!is_null($this->previous()));
    }

    public function previousIs($node)
    {
        $this->unlockFromPrevious();
        $this->previous = $node;
    }

    public function nextIs($node)
    {
        $this->unlockFromNext();
        $this->next = $node;
    }

    private function unlockFromNext()
    {
        if (!is_null($this->next())) {
            $this->next->previous = null;
        }
    }

    private function unlockFromPrevious()
    {
        if (!is_null($this->previous())) {
            $this->previous->next = null;
        }
    }
}


class Lista
{
    private $head = null;
    private $tail = null;
    private $count = 0;

    private $cicli = 0;

    public function add(Node $node)
    {
        if ($this->isEmpty()) {
            $this->head = $node;
        }
        if (!$this->isEmpty()) {
            $this->tail->nextIs($node);
            $node->previousIs($this->tail);
        }
        $this->tail = $node;
        $this->count++;
    }

    public function count()
    {
        return $this->count;
    }

    public function isEmpty()
    {
        return ($this->count() == 0);
    }

    public function valueOfHead()
    {
        if (!is_null($this->head)) {
            return $this->head->value();
        }
    }

    public function toBST()
    {
        if ($this->head->hasNext()) {
            $root = $this->centerAtRightOf($this->head);
            $this->BSTizeNode($root);
            $this->head = $root;
        }
        $this->tail = null;
    }

    private function BSTizeNode($node)
    {
        if (is_null($node)) {
            return;
        }
        $right = $this->centerAtRightOf($node);
        $left = $this->centerAtLeftOf($node);

        $leftValue = (!is_null($left)) ? $left->value() : "NULL";
        $nodeValue = (!is_null($node)) ? $node->value() : "NULL";
        $rightValue = (!is_null($right)) ? $right->value() : "NULL";
        //echo PHP_EOL . $leftValue . " <> " . $nodeValue . " <> " . $rightValue;

        $node->nextIs($right);
        $node->previousIs($left);
        $this->BSTizeNode($right);
        $this->BSTizeNode($left);
    }

    // 1 <--> 2 <--> 3 <--> 4 <--> 5 <--> 6 <--> 7
    private function centerAtRightOf($node)
    {
        if (!$node->hasNext()) {
            return null;
        }
        $sideNode = $node;
        $runner = $node;

        while(!is_null($runner)) {
            if ($runner->hasNext()) {
                $sideNode = $sideNode->next();
                $runner = $runner->next()->next();
            } else {
                $runner = null;
            }
        }
        return $sideNode;
    }

    private function centerAtLeftOf($node)
    {
        if (!$node->hasPrevious()) {
            return null;
        }
        $sideNode = $node;
        $runner = $node;

        while(!is_null($runner)) {
            if ($runner->hasPrevious()) {
                $sideNode = $sideNode->previous();
                $runner = $runner->previous()->previous();
            } else {
                $runner = null;
            }
        }
        return $sideNode;
    }

    private function centerAtSideOf($node, $side)
    {
    }

    public function traceAsList()
    {
        $out = '';
        $current = $this->head;
        while(!is_null($current)) {
            $out .= $current->value();
            if ($current->next() != null) {
                $out .= " <--> ";
            }
            $current = $current->next();
        }
        return $out;
    }

    // TODO: pretty print of tree
    public function traceAsTree()
    {
        return $this->traceSubTreeFrom($this->head);
    }

    private function traceSubTreeFrom($node)
    {
        if (is_null($node)) {
            return "";
        }

        $out = "[";
        if ($node->hasPrevious()) {
            $out .= $this->traceSubTreeFrom($node->previous()) . '/';
        }
        $out .= $node->value();
        if ($node->hasNext()) {
            $out .='\\' . $this->traceSubTreeFrom($node->next());
        }
        $out .= ']';

        return $out;
    }
}




$list = new Lista();
for($value = 1; $value <= 13; $value ++) {
    $list->add((new Node($value)));
}

echo PHP_EOL . $list->traceAsList() . PHP_EOL;

$list->toBST();
echo PHP_EOL . "ROOT IS " . $list->valueOfHead() . PHP_EOL;
echo PHP_EOL . $list->traceAsTree() . PHP_EOL;



