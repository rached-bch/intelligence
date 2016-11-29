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

use PclZip;

class DefaultController extends Controller
{   
    public function indexAction(Request $request)
    {
        $form = $this->get('form.factory')->createBuilder(FormType::class/*, $website*/)
        ->add('domain', TextType::class, array(
            'constraints' => new Assert\Regex(array( 'pattern' => '/^[_.a-z0-9]+$/i' )),
        ))
        ->add('port', TextType::class, array(
            'constraints' => new Assert\Regex(array( 'pattern' => '/^[0-9]+$/' )),
        ))
        ->add('prestashop_version', TextType::class, array(
            'constraints' => new Assert\Regex(array( 'pattern' => '/^[0-9]+(\.[0-9]+)+$/' )),
        ))
        ->add('database',    TextType::class, array(
            'constraints' => new Assert\Regex(array( 'pattern' => '/^[_.a-z0-9]+$/i' )),
        ))
        ->add('database_user',    TextType::class)
        ->add('database_password',    TextType::class, array('required' => false))
        ->add('database_host',    TextType::class)
        ->add('database_port',    TextType::class, array(
            'constraints' => new Assert\Regex(array( 'pattern' => '/^[0-9]+$/' )),
        ))
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

                if(substr($data_domain['local_path_installation'], -1, 1) != "/" && substr($data_domain['local_path_installation'], -1, 1) != "\\")
                    $data_domain['local_path_installation'] .= "/" ;

                if(substr($data_domain['local_path_repository_prestashop_versions'], -1, 1) != "/" && substr($data_domain['local_path_repository_prestashop_versions'], -1, 1) != "\\")
                    $data_domain['local_path_repository_prestashop_versions'] .= "/" ;    

                $result_create_domaine = $this->createNewLocalPrestashopDomain($data_domain) ;  
                if(isset($result_create_domaine['result']) && $result_create_domaine['result'] === true) {
                    $request->getSession()->getFlashBag()->add('success', sprintf('Le site web http://%s'.($data_domain['port'] != 80 ? ":".$data_domain['port'] : "").' a bien été créé.', $data_domain['domain']));            
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
                        $errors[] = "Erreur lors de la configuration de la base de données" ;
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
            $handle = fopen ($path_host_file, "a+b");
            if ($handle) {
                $ip_founded = false ;
                $domain_founded = false ;
                while (!feof($handle)) {
                    $buffer = fgets($handle);
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

                    //$fs->copy($path_host_file, $backup_file);
                    
                    fwrite($handle , PHP_EOL);
                    $res_write = fwrite($handle , "127.0.0.1       ".$data_domain['domain']) ;
                    if($res_write === false || $res_write === 0)
                        $result = false ;
                    fwrite($handle , PHP_EOL);
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
            $handle = fopen ($path_vhost_file, "a+");
            if ($handle) {
            
                $backup_file = $path_vhost_file.time().uniqid();
                $fs->copy($path_vhost_file, $backup_file);
                fwrite($handle , PHP_EOL);
                $domain_config = '<VirtualHost *:'.$data_domain['port'].'>'.PHP_EOL
                .'ServerAdmin adresse1@domainevirtuel.com'.PHP_EOL
                .'DocumentRoot "'.substr($data_domain['local_path_installation'], 0, strlen($data_domain['local_path_installation'])-1).'"'.PHP_EOL
                .'ServerName  '.$data_domain['domain'].''.PHP_EOL
                .'ServerAlias  '.$data_domain['domain'].''.PHP_EOL
                .'</VirtualHost>' ;
                $res_write = fwrite($handle , $domain_config) ;
                if($res_write === false || $res_write === 0)
                    $result = false ;
                fwrite($handle , PHP_EOL);
                
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
                $path_local_check_file = $path_local_check."prestashop_".$data_domain['prestashop_version'].".zip" ;
                $path_local_install_file =  $path_local_install."prestashop_".$data_domain['prestashop_version'].".zip" ;
                if($fs->exists($path_local_check_file)) {
                    $path_source_file = $path_local_check_file ;

                    if(file_put_contents($path_local_install_file, file_get_contents($path_source_file)) !== false) {
                        $archive = new PclZip($path_local_install_file);
                        if ($v_result_list = $archive->extract(PCLZIP_OPT_PATH, $path_local_install) != 0) {
                            $result = true ;  
                            $fs->remove($path_local_install_file) ;
                        }        
                    }
                } else {
                   if(file_put_contents($path_local_check_file, file_get_contents($path_source_file)) !== false) {
                        $fs->copy($path_local_check_file, $path_local_install_file, true) ;
                        if($fs->exists($path_local_install_file)) {
                            $archive = new PclZip($path_local_install_file);
                            if ($v_result_list = $archive->extract(PCLZIP_OPT_PATH, $path_local_install) != 0) {
                                $result = true ;  
                                $fs->remove($path_local_install_file) ;
                            }  
                        }       
                    } 
                } 
            } catch (Exception $e) {}
        }    
        

        
        return $result ;
    }

    public function configureLocalDatabase($data_domain) {
        $result = false ;

        // connect to host 
        $config = new \Doctrine\DBAL\Configuration();
        //..
        $connectionParams = array(
            //'dbname' => 'test',
            'user' => $data_domain['database_user'],
            'password' => $data_domain['database_password'],
            'host' => $data_domain['database_host'],
            'port' => $data_domain['database_port'],
            'driver' => 'pdo_mysql',
        );
        $connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

        // check to create database     
        $sql = "
            SELECT SCHEMA_NAME  
            FROM INFORMATION_SCHEMA.SCHEMATA 
            WHERE SCHEMA_NAME = '". addslashes($data_domain['database']) ."'
        ";
        $result_database_check = $connection->executeQuery($sql)->rowCount() ;
        if($result_database_check == 0) {
            
            $sql = " CREATE DATABASE `". $data_domain['database']."` CHARACTER SET utf8 COLLATE utf8_general_ci ";
            $result_database_check = $connection->prepare($sql)->execute() ;
            if($result_database_check == true) {
                $result = true ;
            }
        }

        return $result ;
    }
}
