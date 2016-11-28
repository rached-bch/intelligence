<?php

namespace IT\PsWebsiteGeneratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;


class DefaultController extends Controller
{   
    public function indexAction(Request $request)
    {
        $form = $this->get('form.factory')->createBuilder(FormType::class/*, $website*/)
        ->add('domain', TextType::class, array(
            'constraints' => new Assert\Regex(array( 'pattern' => '/^([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,}$/' )),
        ))
        ->add('port', TextType::class, array(
            'constraints' => new Assert\Regex(array( 'pattern' => '/[0-9]+$/' )),
        ))
        ->add('prestashop_version', TextType::class, array(
            'constraints' => new Assert\Regex(array( 'pattern' => '/[0-9]+(\.[0-9]+)+$/' )),
        ))
        ->add('database',    TextType::class)
        ->add('local_path_installation',     TextType::class)
        ->add('local_path_repository_prestashop_versions',     TextType::class)
        ->add('local_path_windows_host_file',     TextType::class)
        ->add('local_vhost_file',     TextType::class)
        ->add('save',      SubmitType::class)
        ->getForm() ;

        // Si la requête est en POST
        if ($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
            $form->handleRequest($request);
            
            // On vérifie que les valeurs entrées sont correctes
            // (Nous verrons la validation des objets en détail dans le prochain chapitre)
            if ($form->isValid()) {

                $data_domain = $form->getData();
                //printf('<hr><pre>%s</pre><hr>' , print_r($data_domain , true));//betadev

                $result_create_domaine = $this->createNewLocalPrestashopDomain($data_domain) ;  
                if(isset($result_create_domaine['result']) && $result_create_domaine['result'] === true) {
                    $request->getSession()->getFlashBag()->add('notice', sprintf('Le site web %s a bien été créé.', $result_create_domaine['domain']));            
                }  else {
                   $request->getSession()->getFlashBag()->add('error', 'Problème lors de la création du site web.'); 
                   foreach($result_create_domaine['errors'] as $one_error) {
                        $request->getSession()->getFlashBag()->add('error', $one_error); 
                    } 
                }

                // On redirige vers la page de visualisation de l'annonce nouvellement créée
                //return $this->redirectToRoute('st_platform_homepage');
            }
        }

        // À ce stade, le formulaire n'est pas valide car :
        // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
        // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
        return $this->render('ITPsWebsiteGeneratorBundle:Default:index.html.twig', array(
        'form' => $form->createView(),
        ));
    }

    public function createNewLocalPrestashopDomain($data_domain) {
        $result = false ;
        $errors = array();
        $res_configure_windows_domain = $this->configureLocalDomainWindowsHost($data_domain);
        if($res_configure_windows_domain === true) {
            $res_configure_xampp_domain = $this->configureLocalDomain($data_domain);
            if($res_configure_xampp_domain === true) {
                $res_download_prestashop = $this->downloadPrestashop($data_domain);
                if($res_download_prestashop === true) {
                    $res_configure_database = $this->configureLocalDatabase($data_domain);
                    if($res_configure_database === true) {
                        $result = true ;                    
                    } else {
                        $errors[] = "Erreur lors de la configurationd de la base de données" ;
                    }            
                } else {
                    $errors[] = "Erreur lors du téléchargement de Prestashop" ;
                }                     
            } else {
                $errors[] = "Erreur lors de la configuration du domaine en local" ;
            } 
        } else {
            $errors[] = "Erreur lors de la configuration du domaine en local" ;
        }
       return array("result" => $result, "errors" => $errors) ;
    }

    public function configureLocalDomainWindowsHost($data_domain) {
        $result = true ;
        $create = true ;

        $path_host_file = $data_domain['local_path_windows_host_file'] ;
        $fs = new Filesystem();
        if($fs->exists($path_host_file)) {
            $handle = fopen ($path_host_file, "ra");
            if ($handle) {
                $ip_founded = false ;
                $domain_founded = false ;
                while (!feof($handle)) {
                    $buffer = fgets($handl);
                    if($buffer !== false) {
                        $tab_data = explode(" ", $buffer) ;
                        foreach($tab_data as $one_string) {
                            $one_string = trim($one_string) ;
                            if($one_string == "127.0.0.1" || $one_string == "localhost") {
                                $ip_founded = true ;
                            } elseif($one_string == $data_domain['domain'] || $one_string == $data_domain['domain']) {
                                $domain_founded = true ;
                            }  
                            if($ip_founded == true && $domain_founded == true) {
                                $create = false ;
                                break ;
                            }    
                        }
                        if($ip_founded == true && $domain_founded == true) 
                            break ;
                    }
                }

                if($create === true) {
                    $backup_file = $path_host_file.time().uniqid();
                    $fs->copy($path_host_file, $backup_file);
                    /*fwrite($handle , "\n\r");
                    if(fwrite($handle , "127.0.0.1       ".$data_domain['domain']) === false)
                        $result = false ;
                    fwrite($handle , "\n\r");*/
                }
                fclose($handle);
            }
        } else {
            $result = false ;
        }
        return $result ;
    } 

    public function configureLocalDomain($data_domain) {
        $result = true ;

        $path_vhost_file = $data_domain['local_vhost_file'] ;
        $fs = new Filesystem();
        if($fs->exists($path_vhost_file)) {
            $handle = fopen ($path_vhost_file, "ra");
            if ($handle) {
            
                $backup_file = $path_vhost_file.time().uniqid();
                $fs->copy($path_vhost_file, $backup_file);
                /*fwrite($handle , "\n\r");
                $domain_config = '<VirtualHost *:'.$data_domain['port'].'81>
                    ServerAdmin adresse1@domainevirtuel.com
                    DocumentRoot "E:\xampp5\htdocs\sitti\prodhair-old-prod"
                    ServerName  '.$data_domain['domain'].'
                    ServerAlias  '.$data_domain['domain'].'
                </VirtualHost>' ;
                if(fwrite($handle , $domain_config) === false)
                    $result = false ;
                fwrite($handle , "\n\r");*/
                
                fclose($handle);

            }
        } else {
            $result = false ;
        } 
        return $result ;
    } 

    public function downloadPrestashop($data_domain) {
        $result = false ;

        $path_local_check = $data_domain['local_path_repository_prestashop_versions'] ;
        $path_local_install = $data_domain['local_path_installation'] ;
        $path_external_check = 'https://download.prestashop.com/download/old/' ;

        $fs = new Filesystem();

        if(!$fs->exists($path_local_install)) {
            try {
                $fs->mkdir($path_local_install);
                $path_source_file =   $path_external_check."prestashop_".$data_domain['prestashop_version'].".zip" ;
                if($fs->exists($path_local_check."prestashop_".$data_domain['prestashop_version'].".zip"))
                    $path_source_file =   $path_local_check."prestashop_".$data_domain['prestashop_version'].".zip" ;

                $path_local_install_file =  $path_local_install."prestashop_".$data_domain['prestashop_version'].".zip" ;

                if(file_put_contents($path_local_install_file, file_get_contents($path_source_file)) !== false) {
                    $archive = new PclZip($path_local_install_file);
                    if ($v_result_list = $archive->extract() != 0) {
                        $result = true ;  
                    }        
                }
            } catch (Exception $e) {}
        }    
        

        
        return $result ;
    }

    public function configureLocalDatabase($data_domain) {
        $result = false ;


         
        

        
        return $result ;
    }
}
