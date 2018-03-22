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


abstract class Tree
{

    protected static $tree = [];

// создает узел (если $parentNode == NULL - корень)
    abstract protected function createNode(Node $node, $parentNode = NULL);

// удаляет узел и все дочерние узлы
    abstract protected function deleteNode(Node $node);

// делает один узел дочерним по отношению к другому
    abstract protected function attachNode(Node $node, Node $parent);

// получает узел по названию
    abstract protected function getNode(Node $node);

// преобразует дерево со всеми элементами в ассоциативный массив
    abstract protected function export(Node $node);
}
