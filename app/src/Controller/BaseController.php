<?php

namespace App\Controller;

use App\Entity\Card;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\DateTime;

class BaseController extends Controller
{
    /**
     * @Route("/", name="base")
     */
    public function index(Request $request)
    {
        $card = new Card();

        $form = $this->createFormBuilder($card)
            ->add('card', NumberType::class, [
                'label' => 'Номер карты',
            ])
            ->add('name', TextType::class, [
                'label' => 'Имя держателя'
            ])
            ->add('mm', NumberType::class, [
                'label' => 'Месяц'
            ])
            ->add('yy', NumberType::class, [
                'label' => 'Год'
            ])
            ->add('cvv', NumberType::class, [
                'label' => 'CVV'
            ])
            ->add('save', SubmitType::class, array('label' => 'Оплатить'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $this->validateForm($form)) {
            try {
                $card = $form->getData();
                $this->saveCard($card);
                $this->addFlash(
                    'success',
                    'Карта успешно сохранена'
                );
            } catch (\Exception $e) {
                $this->addFlash(
                    'danger',
                    'Не удалось сохранить карту !'
                );
            }
            return $this->redirectToRoute('base');
        }

        return $this->render('base/index.html.twig', [
            'controller_name' => 'BaseController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @param FormInterface $form
     * @return bool
     */
    protected function validateForm(FormInterface $form): bool
    {
        /** @var  $card Card */
        $card = $form->getData();
        if (!$this->checkLuhn($card->getCard())) {
            $form->get('card')->addError(new FormError('Неверный номер карты'));
        }
        $cvv = $card->getCvv();
        if (!is_int($cvv) || $cvv <= 0 || $cvv > 999) {
            $form->get('cvv')->addError(new FormError('Неверный cvv'));
        }
        $mm = $card->getMm();
        if (!is_int($mm) || $mm <= 0 || $mm > 12) {
            $form->get('mm')->addError(new FormError('Неверный месяц'));
        }
        $yy = $card->getYy();
        if (!is_int($yy) || $yy < intval(date("Y")) || $yy > 2100) {
            $form->get('yy')->addError(new FormError('Неверный год'));
        }
        if (strlen($card->getName()) > 255) {
            $form->get('yy')->addError(new FormError('Длинное имя'));
        }
        return $form->isValid();
    }

    /**
     * @param $number
     * @return bool
     */
    protected function checkLuhn($number)
    {
        $sum = 0;
        $numDigits = strlen($number) - 1;
        $parity = $numDigits % 2;
        for ($i = $numDigits; $i >= 0; $i--) {
            $digit = substr($number, $i, 1);
            if (!$parity == ($i % 2)) {
                $digit <<= 1;
            }
            $digit = ($digit > 9) ? ($digit - 9) : $digit;
            $sum += $digit;
        }
        return (0 == ($sum % 10));
    }

    /**
     * @param Card $card
     * @return array
     */
    protected function saveCard(Card $card)
    {
        /**@var $qb \Doctrine\DBAL\Query\QueryBuilder * */
        $qb = $this->getDoctrine()->getConnection()->createQueryBuilder();
        $qb->insert('card')
            ->values([
                'card' => '?',
                'mm' => '?',
                'yy' => '?',
                'cvv' => '?',
                'name' => '?'
            ])
            ->setParameter(0, $card->getCard())
            ->setParameter(1, $card->getMm())
            ->setParameter(2, $card->getYy())
            ->setParameter(3, $card->getCvv())
            ->setParameter(4, $card->getName());
        return $qb->execute();
    }
}
