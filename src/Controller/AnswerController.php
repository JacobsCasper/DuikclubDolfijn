<?php


namespace App\Controller;


use App\Entity\Answer;
use App\Entity\Page;
use App\Services\AddGlobalsService;
use App\Services\publishedPageFilter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{
    /**
     * @Route("/answers/{id}", name="answers")
     * @IsGranted("ROLE_ADMIN")
     */
    public function getAnswers($id, Request $request, PaginatorInterface $paginator){
        $this->getGlobalVars();

        $answers = $this->getDoctrine()->getRepository(Answer::class)->findBy(['parent_id'=> $id]);
        $topics = [];
        if($answers != null){
            foreach ($answers[0]->getAnswers() as $key => $value){
                array_push($topics, $key);
            }
        }
        $result = $paginator->paginate(
            $answers,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('AdminSpecificPages/answers.html.twig',
            array(
                'answers' => $result,
                'topics' => $topics,
                'formId' => $id
            ));
    }

    /**
     * @Route("/export/{id}",  name="export")
     * @IsGranted("ROLE_ADMIN")
     */
    public function export($id, Request $request, PaginatorInterface $paginator)
    {
        $this->getGlobalVars();
        $answers = $this->getDoctrine()->getRepository(Answer::class)->findBy(['parent_id'=> $id]);
        $list = [];
        $topics = [];
        if($answers != null){
            foreach ($answers[0]->getAnswers() as $key => $value){
                array_push($topics, $key);
            }
            //set answers into a list
            foreach ($answers as $answer){
                $values = [];
                foreach ($answer->getAnswers() as $val){
                    array_push($values, $val);
                }
                array_push($list, $values);
            }
        }
        //export start
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Answer');

        $co = 'A';
        foreach ($topics as $topic){
            $sheet->getCell($co . 1)->setValue($topic);
            $co++;
        }

        // Increase row cursor after header write
        $sheet->fromArray($list,null, 'A2', true);


        $writer = new Xlsx($spreadsheet);

        $writer->save('exports/answers.xlsx');
        //export end

        $result = $paginator->paginate(
            $answers,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('AdminSpecificPages/answers.html.twig',
            array(
                'answers' => $result,
                'topics' => $topics,
                'formId' => $id,
                'exportedFile' => "exports/answers.xlsx"
            ));
    }

    /**
     * @Route("/remove/answers/{id}",  name="removeAllAnswers")
     * @IsGranted("ROLE_ADMIN")
     */
    public function removeAllAnswers($id, Request $request, PaginatorInterface $paginator)
    {
        $this->getGlobalVars();
        $answers = $this->getDoctrine()->getRepository(Answer::class)->findBy(['parent_id'=> $id]);

        $entityManager = $this->getDoctrine()->getManager();
        foreach ($answers as $answer){
            $entityManager->remove($answer);
        }
        $entityManager->flush();

        return $this->render('AdminSpecificPages/answers.html.twig',
            array(
                'formId' => $id
            ));
    }

    private function getGlobalVars(){
        AddGlobalsService::addGlobals($this->get('twig'), publishedPageFilter::filter($this->getDoctrine()->getRepository(Page::class)->findAll()));
    }
}