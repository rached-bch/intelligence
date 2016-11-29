<?php

namespace IT\PsModuleGeneratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Validator\Constraints\Regex;
use IT\PlatformBundle\Validator\Constraints\ContainsAlphanumeric;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->get('form.factory')->createBuilder(FormType::class)
        ->add('name',     TextType::class, array(
            'label'    => $this->get('translator')->trans('Name'),
            'required' => true,
            'constraints' => new Regex(array( 'pattern' => '/^[_.a-z0-9]+$/i' )),
        ))
        ->add('tab',     TextType::class, array(
            'label'    => $this->get('translator')->trans('Name'),
            'required' => true,
            'constraints' => new Regex(array( 'pattern' => '/^[_.a-z0-9]+$/i' )),
        ))
        ->add('version',     TextType::class, array(
            'label'    => $this->get('translator')->trans('Version'),
            'required' => true,
            'constraints' => new Regex("/([0-9]*[.])?[0-9]+/"),
        ))
        ->add('author',    TextType::class, array(
            'label'    => $this->get('translator')->trans('Author'),
            'required' => true,
        ))
        ->add('need_instance',    CheckboxType::class, array(
            'label'    => $this->get('translator')->trans('Need instance'),
            'required' => false,
        ))
        ->add('bootstrap',    CheckboxType::class, array(
            'label'    => $this->get('translator')->trans('Bootstrap'),
            'required' => false,
        ))
        ->add('display_name',    TextType::class, array(
            'label'    => $this->get('translator')->trans('Display name'),
            'required' => true,
        ))
        ->add('description',    TextType::class, array(
            'label'    => $this->get('translator')->trans('Description'),
            'required' => true,
        ))
        ->add('compatibility_min_ps_version',     TextType::class, array(
            'label'    => $this->get('translator')->trans('Min compatibility prestashop version'),
            'required' => true,
            'constraints' => new Regex("/([0-9]*[.])?[0-9]+/"),    
        ))
        ->add('compatibility_max_ps_version',     TextType::class, array(
            'label'    => $this->get('translator')->trans('Max compatibility prestashop version'),
            'required' => true,
            'constraints' => new Regex("/([0-9]*[.])?[0-9]+/"),
        ))
        ->add('override',    CheckboxType::class, array(
            'label'    => $this->get('translator')->trans('Override'),
            'required' => false,
        ))
        ->add('config_enabled',     CheckboxType::class, array(
            'label'    => 'Configuration enabled',
            'required' => false,
        ))
        ->add('config_names', CollectionType::class, array(
            'entry_type'   => TextType::class,
            'allow_add'    => true,
            'allow_delete' => true, 
            'required' => false, 
            'constraints' => new ContainsAlphanumeric(),
        ))
        ->add('config_types', CollectionType::class, array(
            'entry_type'   => ChoiceType::class,
            'entry_options'  => array(
                'choices'  => array(
                    'Bolean' => 1,
                    'Int' => 2,
                    'Float' => 3,
                    'Text' => 4,
                ),
            ),
            'allow_add'    => true,
            'allow_delete' => true, 
            'required' => false
        ))
        ->add('controller_enabled',     CheckboxType::class, array(
            'label'    => 'Controller enabled',
            'required' => false,
        ))
        ->add('controller_names', CollectionType::class, array(
            'entry_type'   => TextType::class,
            'allow_add'    => true,
            'allow_delete' => true, 
            'required' => false, 
            'constraints' => new ContainsAlphanumeric(),
        ))
        ->add('controller_configs', CollectionType::class, array(
            'entry_type'   => TextType::class,
            'allow_add'    => true,
            'allow_delete' => true, 
            'required' => false
        ))

        ->add('icon',     FileType::class, array(
            'label'    => $this->get('translator')->trans('Icon'),
            'required' => false,
        ))
        ->add('save',      SubmitType::class, array(
            'label'    => $this->get('translator')->trans('Save'),
        ))
        ->getForm() ;
        
         #####################

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $form_data = $form->getData();
            print sprintf('<pre>%s</pre>', print_r($form_data, true)) ;die();    
            /*$request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

            return $this->redirectToRoute('it_platform_view', array('id' => $advert->getId()));*/
        }   

         #####################   

        return $this->render('ITPsModuleGeneratorBundle:Default:index.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
