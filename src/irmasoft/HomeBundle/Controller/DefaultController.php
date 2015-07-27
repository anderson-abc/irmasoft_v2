<?php

namespace irmasoft\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use irmasoft\HomeBundle\Entity\Contact;
use irmasoft\HomeBundle\Form\ContactType;

use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('irmasoftHomeBundle:Default:index.html.twig');
    }
    public function aboutAction()
    {
        return $this->render('irmasoftHomeBundle:Default:about.html.twig');
    }    
    public function servicesAction()
    {
        return $this->render('irmasoftHomeBundle:Default:services.html.twig');
    }
    public function contactAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
    	$Contact = new Contact();
        $form = $this->createForm(new ContactType(), $Contact);
        $request = $this->getRequest();
        
        if ($request->isMethod('POST')){
            $form->bind($request);
            
            if($form->isValid()){
                
                $name = $request->request->get('name'); 
                $phone = $request->request->get('phone'); 
                $email = $request->request->get('email'); 
                $message = $request->request->get('message'); 
                $int = $request->request->get('int'); 
                
                
//                $Contact = $form->getData();
//                if($int == 10){
                    $Contact->setName($name);
                    $Contact->setPhone($phone);
                    $Contact->setEmail($email);
                    $Contact->setMessage($message);

                    $em->persist($Contact);
                    $em->flush();

                    // send mail now !
                    $my_mail = \Swift_Message::newInstance()
                        ->setSubject('Contact Irmasoft')
                        ->setFrom($email)
                        ->setTo('contact@irmasoft.com')
                        ->setBody('<p>'.$message.'</p>','text/html')
                    ;
                    $this->get('mailer')->send($my_mail);


                    $this->get('session')->getFlashBag()->add(
                        'success',
                        'Votre mail à été bien envoyer, nous vous contacterons rapidement.'
                    );
//                }
                return $this->redirect($this->generateUrl('irmasoft_home_contact'));
            }
        }
        return $this->render('irmasoftHomeBundle:Default:contact.html.twig',
            array('form' => $form->createView()));
        }
        
        
//        return $this->render('irmasoftHomeBundle:Default:contact.html.twig');
//    }
}
