<?php

/**
 *  Test Phalcone tree
 */

/**
 *
 * @package controllers
 *
 * @author avtorpc <avtorpc@gmail.com>
 *
 * v.1.0( 2016 )
 *
 * @copyright   Copyright (c) 2017, Denis Ivanov
 */
class Node extends Tree
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $level;

    /**
     * @var integer
     */
    private $l_key;

    /**
     * @var integer
     */
    private $r_key;

    /**
     * @var array
     */
    private $a = [];

    /**
     * Node constructor.
     * @param $name string
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * @param $level
     * @return $this
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param $r_key
     * @return $this
     */
    public function setRkey($r_key)
    {
        $this->r_key = $r_key;
        return $this;
    }

    /**
     * @return int
     */
    public function getRkey()
    {
        return $this->r_key;
    }

    /**
     * @param $l_key
     * @return $this
     */
    public function setLkey($l_key)
    {
        $this->l_key = $l_key;
        return $this;
    }

    /**
     * @return int
     */
    public function getLkey()
    {
        return $this->l_key;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * Создание нового узла в дереве
     *
     * @param Node $node
     * @param null $parentNode
     * @return Node
     */
    public function createNode(Node $node, $parentNode = NULL)
    {

        if (is_null($parentNode)) {
            $this->setID(1)->setLevel(1)->setLkey(1)->setRkey(2);
            self::$tree[$this->name]['name'] = $this->name;
            self::$tree[$this->name]['id'] = 1;
            self::$tree[$this->name]['level'] = 1;
            self::$tree[$this->name]['l_key'] = 1;
            self::$tree[$this->name]['r_key'] = 2;
        } else {
            $parentNode = $this->getNode($parentNode);
            $node->setID($parentNode->getID() + 1)
                ->setLevel($parentNode->getLevel() + 1)
                ->setLkey($parentNode->getRkey())
                ->setRkey($parentNode->getRkey() + 1);

            foreach (self::$tree as $key => $val) {
                if ($val['r_key'] >= $parentNode->getRkey()) {

                    self::$tree[$key]['r_key'] = $val['r_key'] + 2;

                    if ($val['l_key'] > $node->getLkey()) {
                        self::$tree[$key]['l_key'] = $val['l_key'] + 2;

                    }
                } else {

                }
            }
            self::$tree[$node->getName()]['name'] = $node->getName();
            self::$tree[$node->getName()]['id'] = $node->getID();
            self::$tree[$node->getName()]['level'] = $node->getLevel();
            self::$tree[$node->getName()]['l_key'] = $node->getLkey();
            self::$tree[$node->getName()]['r_key'] = $node->getRkey();

            return $node;
        }
    }

    /**
     *
     * Добавление узла в созданный узел
     *
     * @param Node $node
     * @param Node $parent
     */
    public function attachNode(Node $node, Node $parent)
    {
        $node = $this->getNode($node);
        $bb = [];
        foreach (self::$tree as $k_b => $v_b) {//Список всех участников ветки
            if ($v_b['l_key'] >= $node->getLkey() AND $v_b['r_key'] <= $node->getRkey() AND $node->getLevel() <= $v_b['level']) {
                $bb[$k_b] = $v_b;
            }
        }
        $this->deleteNode($node);
        $this->createNode($node, $parent);
    }

    /**
     *
     * Получение узла с потомками
     *
     * @param Node $node
     * @return Node
     */
    public function getNode(Node $node)
    {
        $iter = new ArrayIterator(self::$tree);
        foreach ($iter as $key => $value) {
            if ($value['name'] == $node->getName()) {
                $node->setID($value['id'])
                    ->setLevel($value['level'])
                    ->setLkey($value['l_key'])
                    ->setRkey($value['r_key']);
            }
        }
        return $node;
    }

    /**
     *
     * Вывод узла дерева как иерархической струтуры
     *
     * @param Node $node
     * @return array
     */
    public function export(Node $node)
    {
        $node = $this->getNode($node);
        $bb = [];
        foreach (self::$tree as $k_b => $v_b) {//Список всех участников ветки
            if ($v_b['l_key'] >= $node->getLkey() AND $v_b['r_key'] <= $node->getRkey()) {
                $bb[$k_b] = $v_b;
            }
        }
        $a = [];
        foreach ($bb as $key => $value) {
            $twig = [];
            if ($value['r_key'] - $value['l_key'] == 1) {//выбираем конечный листок
                foreach (self::$tree as $k => $v) {
                    if ($value['l_key'] >= $v['l_key'] AND $value['r_key'] <= $v['r_key'] AND $node->getLevel() <= $v['level']) {
                        $twig[$v['level']] = $v['name'];
                    }
                }
            }
            if (count($twig) > 0) {
                $a = array_merge_recursive($a, $this->recur($twig));
            }
        }
        return ($a);
    }

    /**
     *
     * Удаление узла
     *
     * @param Node $node
     */
    public function deleteNode(Node $node)
    {
        $node = $this->getNode($node);
        foreach (self::$tree as $k_b => $v_b) {
            if ($v_b['l_key'] >= $node->getLkey() AND $v_b['r_key'] <= $node->getRkey() AND $v_b['level'] >= $node->getLevel()) {
                unset(self::$tree[$k_b]);
            }
        }
        foreach (self::$tree as $k_b => $v_b) {
            if ($v_b['r_key'] > $node->getRkey() AND $v_b['l_key'] < $node->getLkey()) {
                self::$tree[$k_b]['r_key'] = self::$tree[$k_b]['r_key'] - ($node->getRkey() - $node->getLkey() + 1);
            }
            if ($v_b['l_key'] > $node->getLkey()) {
                self::$tree[$k_b]['l_key'] = self::$tree[$k_b]['l_key'] - ($node->getRkey() - $node->getLkey() + 1);
                self::$tree[$k_b]['r_key'] = self::$tree[$k_b]['r_key'] - ($node->getRkey() - $node->getLkey() + 1);
            }
        }
    }

    /**
     *
     * Получаем все деревр целиком
     *
     * @return array
     */
    public function getTree()
    {
        return self::$tree;
    }


    private function recur($arr)
    {
        if (count($arr) == 0) {
            return [];
        } else {
            return [array_shift($arr) => $this->recur($arr)];
        }
    }


}
