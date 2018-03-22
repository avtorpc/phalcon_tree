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

use Phalcon\Mvc\Controller;

/**
 * Class IndexController
 *
 * Создание объектного дерева на PHP
 * без использования шаблонизатора .volt
 */
class IndexController extends Controller
{
    /**
     *
     * Дефолтный метод
     * Вывод массивов после выполнения операций удаления-добавления
     *
     * return void
     */
    public function indexAction()
    {
        echo "<pre>";

        $tree_country = new Node('Country');
        $tree_country->createNode($tree_country);

        $RU = $tree_country->createNode(new Node('Rossia'), $tree_country);

        $Mos = $tree_country->createNode(new Node('moscow'), $RU);

        $Kreml = $tree_country->createNode(new Node('kreml'), $Mos);

        $GB = $tree_country->createNode(new Node('England'), $tree_country);


        $NL = $tree_country->createNode(new Node('Niderlandy'), $tree_country);

        print_r($tree_country->export( $Kreml ));

        $FR = $tree_country->createNode(new Node('France'), $tree_country);

        $Ams = $tree_country->createNode(new Node('amsterdam'), $NL);

        $Lon = $tree_country->createNode(new Node('london'), $GB);

        $Tayer = $tree_country->createNode(new Node('tayer'), $Lon);

        $Pari = $tree_country->createNode(new Node('paris'), $FR);
        $Lion = $tree_country->createNode(new Node('lion'), $FR);

        $tree_country->deleteNode( $Mos );

        $Mos = $tree_country->createNode(new Node('moscow'), $RU);

        $tree_country->attachNode($FR, $RU);

        print_r($tree_country->export($tree_country));

     //   print_r($tree_country->getTree());

        die;
    }
}