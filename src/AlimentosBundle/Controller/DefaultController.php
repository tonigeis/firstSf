<?php

namespace AlimentosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AlimentosBundle\Model\Model;
use AlimentosBundle\Config\Config;

class DefaultController extends Controller
{
    public function indexAction(){
        $params = array(
             'mensaje' => 'Bienvenido al curso de Symfony2',
             'fecha' => date('d-m-yy'),
             'nombre' => 'Toni',
         );

        return $this->render('AlimentosBundle:Default:index.html.twig', $params);
    }

    public function listarAction(){
        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario,
			Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        $params = array(
			'alimentos' => $m->dameAlimentos(),
        );

        return
			$this->render('AlimentosBundle:Default:mostrarAlimentos.html.twig', $params);

    }

    public function verAction($id){
        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario,
                     Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        $alimento = $m->dameAlimento($id);

        if(!$alimento)
        {
          throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
        }

        $params = $alimento;

        return $this->render('AlimentosBundle:Default:verAlimento.html.twig', $params);

    }

    public function insertarAction()
    {
        $params = array(
        'nombre' => '',
        'energia' => '',
        'proteina' => '',
        'hc' => '',
        'fibra' => '',
        'grasa' => '',
        );

        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario,
            Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // comprobar campos formulario
            if ($m->insertarAlimento($_POST['nombre'], $_POST['energia'],$_POST['proteina'], $_POST['hc'], $_POST['fibra'], $_POST['grasa'])) {
                $params['mensaje'] = 'Alimento insertado correctamente';
                $params['alimentos'] = $m->buscarAlimentosPorNombre($_POST['nombre']);
            } else {
                $params = array(
                'nombre' => $_POST['nombre'],
                'energia' => $_POST['energia'],
                'proteina' => $_POST['proteina'],
                'hc' => $_POST['hc'],
                'fibra' => $_POST['fibra'],
                'grasa' => $_POST['grasa'],
                );

                $params['mensaje'] = 'No se ha podido insertar el alimento. Revisa el formulario';
            }
        }

        return
            $this->render('AlimentosBundle:Default:formInsertar.html.twig', $params);

    }

    public function buscarPorNombreAction()
    {
        $params = array(
        'nombre' => '',
        'resultado' => array(),
        );

        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario,
                         Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $params['nombre'] = $_POST['nombre'];
            $params['resultado'] = $m->buscarAlimentosPorNombre($_POST['nombre']);
        }

        return
            $this->render('AlimentosBundle:Default:buscarPorNombre.html.twig',
            $params);

    }
}
