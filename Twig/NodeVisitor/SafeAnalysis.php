<?php

class Twig_NodeVisitor_SafeAnalysis implements Twig_NodeVisitorInterface
{
    protected $data = [];
    protected $safeVars = [];

    public function setSafeVars($safeVars)
    {
        $this->safeVars = $safeVars;
    }

    public function getSafe(Twig_NodeInterface $node)
    {
        $hash = spl_object_hash($node);
        if (!isset($this->data[$hash])) {
            return;
        }

        foreach ($this->data[$hash] as $bucket) {
            if ($bucket['key'] !== $node) {
                continue;
            }

            if (in_array('html_attr', $bucket['value'])) {
                $bucket['value'][] = 'html';
            }

            return $bucket['value'];
        }
    }

    protected function setSafe(Twig_NodeInterface $node, array $safe)
    {
        $hash = spl_object_hash($node);
        if (isset($this->data[$hash])) {
            foreach ($this->data[$hash] as &$bucket) {
                if ($bucket['key'] === $node) {
                    $bucket['value'] = $safe;

                    return;
                }
            }
        }
        $this->data[$hash][] = [
            'key'   => $node,
            'value' => $safe,
        ];
    }

    public function enterNode(Twig_NodeInterface $node, Twig_Environment $env)
    {
        return $node;
    }

    public function leaveNode(Twig_NodeInterface $node, Twig_Environment $env)
    {
        if ($node instanceof Twig_Node_Expression_Constant) {
            // constants are marked safe for all
            $this->setSafe($node, ['all']);
        } elseif ($node instanceof Twig_Node_Expression_BlockReference) {
            // blocks are safe by definition
            $this->setSafe($node, ['all']);
        } elseif ($node instanceof Twig_Node_Expression_Parent) {
            // parent block is safe by definition
            $this->setSafe($node, ['all']);
        } elseif ($node instanceof Twig_Node_Expression_Conditional) {
            // intersect safeness of both operands
            $safe = $this->intersectSafe($this->getSafe($node->getNode('expr2')), $this->getSafe($node->getNode('expr3')));
            $this->setSafe($node, $safe);
        } elseif ($node instanceof Twig_Node_Expression_Filter) {
            // filter expression is safe when the filter is safe
            $name = $node->getNode('filter')->getAttribute('value');
            $args = $node->getNode('arguments');
            if (false !== $filter = $env->getFilter($name)) {
                $safe = $filter->getSafe($args);
                if (null === $safe) {
                    $safe = $this->intersectSafe($this->getSafe($node->getNode('node')), $filter->getPreservesSafety());
                }
                $this->setSafe($node, $safe);
            } else {
                $this->setSafe($node, []);
            }
        } elseif ($node instanceof Twig_Node_Expression_Function) {
            // function expression is safe when the function is safe
            $name = $node->getAttribute('name');
            $args = $node->getNode('arguments');
            $function = $env->getFunction($name);
            if (false !== $function) {
                $this->setSafe($node, $function->getSafe($args));
            } else {
                $this->setSafe($node, []);
            }
        } elseif ($node instanceof Twig_Node_Expression_MethodCall) {
            if ($node->getAttribute('safe')) {
                $this->setSafe($node, ['all']);
            } else {
                $this->setSafe($node, []);
            }
        } elseif ($node instanceof Twig_Node_Expression_GetAttr && $node->getNode('node') instanceof Twig_Node_Expression_Name) {
            $name = $node->getNode('node')->getAttribute('name');
            // attributes on template instances are safe
            if ('_self' == $name || in_array($name, $this->safeVars)) {
                $this->setSafe($node, ['all']);
            } else {
                $this->setSafe($node, []);
            }
        } else {
            $this->setSafe($node, []);
        }

        return $node;
    }

    protected function intersectSafe(array $a = null, array $b = null)
    {
        if (null === $a || null === $b) {
            return [];
        }

        if (in_array('all', $a)) {
            return $b;
        }

        if (in_array('all', $b)) {
            return $a;
        }

        return array_intersect($a, $b);
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return 0;
    }
}
