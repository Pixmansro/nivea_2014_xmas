<?php
/**
 * This file is part of the nette-vanocni_soutez
 * Copyright (c) 2014
 *
 * @created 7.11.14
 * @file    QuizTwoFormFactory.php
 * @author  Pavel Paulík <pavel.paulik1@gmail.com>
 */

namespace AppModule\Forms;

use Nette\Forms\Form;

interface IQuizTwoFormFactory
{

    /** @return QuizTwoFormFactory */
    function create();
}

class QuizTwoFormFactory extends BasicForm implements IQuizTwoFormFactory
{
    public function __construct()
    {
        parent::__construct();
    }


    /** @return QuizTwoFormFactory */
    function create()
    {
        $this->addRadioList('quizTwo', null, array(10 => 'a)', 15 => 'b)', 20 => 'c)'))
            ->addRule(Form::FILLED, 'Prosím, vyplňte jednu možnost.');

        $this->addSubmit('send', 'Pokračovat')->setAttribute('class', 'btn next');

        $this->onSuccess[] = array($this, 'formSubmitted');
        $this->getElementPrototype()->class = 'quiz-form';


    }

    /**
     * @param BasicForm $form
     */
    public function formSubmitted(BasicForm $form)
    {
        $presenter = $this->getPresenter();
        $values = $form->getValues();
        if (!$presenter->getUser()->isLoggedIn()) {
            $section = $presenter->getSession($this->section);
            $section->quizOne = $values['quizTwo'];

        } else {
            $em = $this->getEntityMapper()->getEntityManager();
            $em->persist($form->entity);
            $em->flush();
        }

        $message = 'Thanks';
        $form->getPresenter()->flashMessage($presenter->translator->translate($message));
        $form->getPresenter()->redirect($form->getRedirect());
    }

} 