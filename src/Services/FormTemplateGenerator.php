<?php


namespace App\Services;


use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FormTemplateGenerator
{

    public function getForm($elements, $formBuilder, $buttonName = null, $buttonType = "primary"){

        //sort by position
        $tempArray = [];
        foreach ($elements as $item){
            array_push($tempArray, $item);
        }
        usort($tempArray, fn($a, $b) => strcmp($a->getPosition(), $b->getPosition()));
        $elements = $tempArray;

        foreach ($elements as $element){
            switch ($element->getType()){
                case "string":
                    $formBuilder = $this->addStringType($formBuilder, $element);
                    break;
                case "email":
                    $formBuilder = $this->addEmailType($formBuilder, $element);
                    break;
                case "int":
                    $formBuilder = $this->addIntType($formBuilder, $element);
                    break;
                case "radio":
                    $formBuilder = $this->addRadioType($formBuilder, $element);
                    break;
            }
        }

        if($buttonName != null){
            $formBuilder
                ->add('save', SubmitType::class, array(
                    'label' => $buttonName,
                    'attr' => array('class' => 'btn btn-'. $buttonType .' mt-3')
                ));
        }

        return $formBuilder;
    }

    private function addStringType($formBuilder, $element){
        $label = $this->createLabel($element);
        $name = $this->createValidName($element->getLabel());

        if($element->getMultiline() == 1){

            if($element->getRequired()) {

                $formBuilder
                    ->add($name, CKEditorType::class, array(
                        'config' =>[
                            'uiColor' => '#e2e2e2',
                            'toolbar' => 'basic',
                            'required' => true
                        ],
                        'required' => true,
                        'label' => $label,
                        'attr' => array('class' => 'form-control', 'rows' => '10')));

            } else {
                $formBuilder
                    ->add($name, CKEditorType::class, array(
                        'config' =>[
                            'uiColor' => '#e2e2e2',
                            'toolbar' => 'basic',
                            'required' => false
                        ],
                        'required' => false,
                        'label' => $label,
                        'attr' => array('class' => 'form-control', 'rows' => '10')));
            }

        }else{

            if($element->getRequired()){
                $formBuilder
                    ->add($name, TextType::class,
                        array('attr' => array('class' => 'form-control'), 'label' => $label));
            } else {
                $formBuilder
                    ->add($name, TextType::class,
                        array(
                            'required' => false,
                            'attr' => array('class' => 'form-control'), 'label' => $label
                        ));
            }

        }

        return $formBuilder;
    }

    private function addEmailType($formBuilder, $element){
        $label = $this->createLabel($element);
        $name = $this->createValidName($element->getLabel());

        if($element->getRequired()){
            $formBuilder
                ->add($name, EmailType::class,
                    array('attr' => array('class' => 'form-control'), 'label' => $label));
        } else {
            $formBuilder
                ->add($name, EmailType::class,
                    array(
                        'required' => false,
                        'attr' => array('class' => 'form-control'), 'label' => $label
                    ));

        }

        return $formBuilder;

    }

    private function addIntType($formBuilder, $element){
        $label = $this->createLabel($element);
        $name = $this->createValidName($element->getLabel());

        if($element->getRequired()){
            $formBuilder
                ->add($name, IntegerType::class,
                    array('attr' => array('class' => 'form-control'), 'label' => $label));
        } else {
            $formBuilder
                ->add($name, IntegerType::class,
                    array(
                        'required' => false,
                        'attr' => array('class' => 'form-control'), 'label' => $label
                    ));

        }

        return $formBuilder;
    }

    private function addRadioType($formBuilder, $element){
        $label = $this->createLabel($element);
        $name = $this->createValidName($element->getLabel());
        $choices = $this->mapChoices($element->getChoices());

        if($element->getRequired()){
            $formBuilder
                ->add($name, ChoiceType::class, [
                    'attr' => array('class' => 'form-control'),
                    'choices'  => $choices,
                    'label' => $label
                ]);
        } else {
            $formBuilder
                ->add($name, ChoiceType::class, [
                    'attr' => array('class' => 'form-control'),
                    'choices'  => $choices,
                    'required' => false,
                    'label' => $label,
                    'empty_data'  => null,
                    'empty_value' => "Geen"
                ]);

        }

        return $formBuilder;
    }

    private function createLabel($element){
        $label = $element->getLabel();

        if($element->getPrice() != null){
            $label .= ": €" . $element->getPrice();
        }
        if($element->getComment() != null){
            $label .= " (" . $element->getComment() . ")";
        }

        return $label;
    }

    private function createValidName($string){
        $stringParts = explode(' ', $string);
        $name = "";
        foreach ($stringParts as $part){
            $name .= "_".$part;
        }
        return $name;
    }

    private function mapChoices($unmappedChoices){
        return array_combine($unmappedChoices, $unmappedChoices);
    }

}